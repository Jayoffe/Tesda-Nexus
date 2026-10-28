<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: UserModel
 * 
 * Automatically generated via CLI.
 */
class UserModel extends Model {
    protected $table = 'users';
    protected $primary_key = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];

    public function __construct()
    {
        parent::__construct();
    }

    public function find_by_username($username)
    {
        return $this->db->table('users')
            ->where('username', $username)
            ->get();
    }

    public function find_by_email($email)
    {
        return $this->db->table('users')
            ->where('email', $email)
            ->get();
    }

    public function find_by_identity($identity)
    {
        return $this->find_by_email($identity) ?: $this->find_by_username($identity);
    }

    public function all($trainingCenterId = null)
    {
        $sql =
            'SELECT u.id, u.username, u.email, u.full_name, u.role, u.is_active,
                    u.created_at, tc.name AS training_center_name, a.status AS application_status
             FROM users u
             LEFT JOIN training_centers tc ON tc.id = u.training_center_id
             LEFT JOIN center_applications a ON a.user_id = u.id';
        $args = [];
        if ($trainingCenterId !== null) {
            $sql .= ' WHERE u.training_center_id = ?';
            $args[] = (int) $trainingCenterId;
        }
        $sql .= ' ORDER BY u.role ASC, u.username ASC';
        return $this->db->raw($sql, $args)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function students($trainingCenterId)
    {
        return $this->db->table('users')
            ->select('id, username, email, full_name')
            ->where('role', 'student')
            ->where('is_active', 1)
            ->where('training_center_id', $trainingCenterId)
            ->order_by('full_name', 'ASC')
            ->get_all();
    }

    public function count_by_role($role, $trainingCenterId = null)
    {
        $sql = 'SELECT COUNT(*) AS total FROM users WHERE role = ? AND is_active = 1';
        $args = [$role];
        if ($trainingCenterId !== null) {
            $sql .= ' AND training_center_id = ?';
            $args[] = (int) $trainingCenterId;
        }
        $result = $this->db->raw($sql, $args)->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    public function find($id)
    {
        return $this->db->table('users')->where('id', $id)->get();
    }

    public function create_account($username, $email, $password, $role, $fullName, $active, $trainingCenterId)
    {
        return $this->db->table('users')->insert([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'full_name' => $fullName,
            'is_active' => $active,
            'training_center_id' => $trainingCenterId
        ]);
    }

    public function update_account($id, $username, $email, $role, $fullName, $active, $trainingCenterId, $password = '')
    {
        $data = [
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'full_name' => $fullName,
            'is_active' => $active,
            'training_center_id' => $trainingCenterId
        ];
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        return $this->db->table('users')->where('id', $id)->update($data);
    }

    public function update_student_profile($id, $fullName, $email)
    {
        return $this->db->table('users')->where('id', $id)->update([
            'full_name' => $fullName,
            'email' => $email
        ]);
    }

    public function delete_account($id)
    {
        return $this->db->table('users')->where('id', $id)->delete();
    }

}