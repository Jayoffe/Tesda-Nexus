<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CenterApplicationModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function submit($username, $email, $password, $role, $fullName, $centerId)
    {
        $this->db->raw('START TRANSACTION');
        try {
            $this->db->raw(
                'INSERT INTO users (username, email, password, role, full_name, is_active, training_center_id)
                 VALUES (?, ?, ?, ?, ?, 0, ?)',
                [
                    $username,
                    $email,
                    password_hash($password, PASSWORD_DEFAULT),
                    $role,
                    $fullName,
                    (int) $centerId
                ]
            );
            $userId = $this->db->raw('SELECT LAST_INSERT_ID() AS id')->fetch(PDO::FETCH_ASSOC);
            if (empty($userId['id'])) {
                throw new RuntimeException('The applicant account could not be created.');
            }
            $this->db->raw(
                'INSERT INTO center_applications (user_id, training_center_id, applied_role, status)
                 VALUES (?, ?, ?, \'pending\')',
                [(int) $userId['id'], (int) $centerId, $role]
            );
            $this->db->raw('COMMIT');
            return (int) $userId['id'];
        } catch (Throwable $error) {
            $this->db->raw('ROLLBACK');
            throw $error;
        }
    }

    public function pending_for_center($trainingCenterId)
    {
        return $this->db->raw(
            'SELECT a.id AS application_id, a.applied_role, a.applied_at,
                    u.id AS user_id, u.full_name, u.username, u.email
             FROM center_applications a
             INNER JOIN users u ON u.id = a.user_id
             WHERE a.training_center_id = ? AND a.status = \'pending\'
             ORDER BY a.applied_at ASC',
            [(int) $trainingCenterId]
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function status_for_user($userId)
    {
        $row = $this->db->raw(
            'SELECT status FROM center_applications WHERE user_id = ? ORDER BY id DESC LIMIT 1',
            [(int) $userId]
        )->fetch(PDO::FETCH_ASSOC);
        return $row['status'] ?? null;
    }

    public function pending_count($trainingCenterId)
    {
        $row = $this->db->raw(
            'SELECT COUNT(*) AS total
             FROM center_applications
             WHERE training_center_id = ? AND status = \'pending\'',
            [(int) $trainingCenterId]
        )->fetch(PDO::FETCH_ASSOC);
        return (int) ($row['total'] ?? 0);
    }

    public function review($applicationId, $trainingCenterId, $reviewerId, $decision)
    {
        if (!in_array($decision, ['approved', 'rejected'], true)) {
            throw new InvalidArgumentException('Invalid application decision.');
        }

        $this->db->raw('START TRANSACTION');
        try {
            $application = $this->db->raw(
                'SELECT user_id FROM center_applications
                 WHERE id = ? AND training_center_id = ? AND status = \'pending\'
                 LIMIT 1 FOR UPDATE',
                [(int) $applicationId, (int) $trainingCenterId]
            )->fetch(PDO::FETCH_ASSOC);
            if (!$application) {
                $this->db->raw('ROLLBACK');
                return false;
            }
            $this->db->raw(
                'UPDATE center_applications
                 SET status = ?, reviewed_by = ?, reviewed_at = CURRENT_TIMESTAMP
                 WHERE id = ? AND training_center_id = ? AND status = \'pending\'',
                [$decision, (int) $reviewerId, (int) $applicationId, (int) $trainingCenterId]
            );
            $this->db->raw(
                'UPDATE users SET is_active = ? WHERE id = ? AND training_center_id = ?',
                [$decision === 'approved' ? 1 : 0, (int) $application['user_id'], (int) $trainingCenterId]
            );
            $this->db->raw('COMMIT');
            return true;
        } catch (Throwable $error) {
            $this->db->raw('ROLLBACK');
            throw $error;
        }
    }
}
