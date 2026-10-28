<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class EnrollmentModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function count()
    {
        $result = $this->db->raw('SELECT COUNT(*) AS total FROM enrollments')->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    public function for_student($studentId)
    {
        return $this->db->raw(
            'SELECT c.id AS course_id, c.course_code, c.course_name, c.qualification, c.description,
                    c.class_days, c.class_schedule, c.course_outline, c.training_center_id,
                    tc.name AS training_center_name, tc.region, tc.province, tc.city,
                    e.status AS enrollment_status, e.enrolled_at,
                    (SELECT COUNT(*) + 1
                     FROM enrollments earlier
                     WHERE earlier.course_id = e.course_id AND earlier.status = \'enrolled\'
                       AND (earlier.enrolled_at < e.enrolled_at
                            OR (earlier.enrolled_at = e.enrolled_at AND earlier.id < e.id))
                    ) AS course_position,
                    (SELECT COUNT(*) FROM enrollments total
                    WHERE total.course_id = e.course_id AND total.status = \'enrolled\') AS course_student_count
             FROM enrollments e
             INNER JOIN courses c ON c.id = e.course_id
             INNER JOIN training_centers tc ON tc.id = c.training_center_id
             WHERE e.student_id = ? AND e.status = \'enrolled\'
             ORDER BY e.enrolled_at DESC',
            [$studentId]
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function for_instructor($instructorId, $trainingCenterId)
    {
        return $this->db->raw(
            'SELECT e.id, e.status, e.enrolled_at, c.id AS course_id, c.course_code,
                    c.course_name, c.qualification, tc.name AS training_center_name,
                    u.full_name, u.username, u.email
             FROM enrollments e
             INNER JOIN courses c ON c.id = e.course_id
             INNER JOIN training_centers tc ON tc.id = c.training_center_id
             INNER JOIN users u ON u.id = e.student_id
             WHERE c.training_center_id = ? AND e.instructor_id = ? AND e.status = \'enrolled\'
             ORDER BY e.enrolled_at DESC',
            [$trainingCenterId, $instructorId]
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function roster_for_course($courseId)
    {
        return $this->db->raw(
            'SELECT u.full_name, u.username, u.email, e.enrolled_at, e.status
             FROM enrollments e
             INNER JOIN users u ON u.id = e.student_id
             WHERE e.course_id = ? AND e.status = \'enrolled\'
             ORDER BY e.enrolled_at ASC, e.id ASC',
            [$courseId]
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($studentId, $courseId, $instructorId)
    {
        return $this->db->table('enrollments')->insert([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'instructor_id' => $instructorId,
            'status' => 'enrolled'
        ]);
    }

    public function exists($studentId, $courseId)
    {
        return $this->db->table('enrollments')
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->get();
    }

    public function student_is_enrolled($studentId, $courseId)
    {
        return (bool) $this->db->raw(
            "SELECT id FROM enrollments WHERE student_id = ? AND course_id = ? AND status = 'enrolled' LIMIT 1",
            [$studentId, $courseId]
        )->fetch(PDO::FETCH_ASSOC);
    }

    public function course_enrollment_count($courseId)
    {
        $result = $this->db->raw(
            "SELECT COUNT(*) AS total FROM enrollments WHERE course_id = ? AND status = 'enrolled'",
            [$courseId]
        )->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }
}
