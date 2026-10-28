<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CourseModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function all($trainingCenterId = null)
    {
        $sql = 'SELECT c.*, tc.name AS training_center_name,
                    tc.region, tc.province, tc.city, tc.address,
                    (SELECT COUNT(*) FROM enrollments e
                     WHERE e.course_id = c.id AND e.status = \'enrolled\') AS enrollment_count
             FROM courses c
             INNER JOIN training_centers tc ON tc.id = c.training_center_id';
        $args = [];
        if ($trainingCenterId !== null) {
            $sql .= ' WHERE c.training_center_id = ?';
            $args[] = (int) $trainingCenterId;
        }
        $sql .= ' ORDER BY tc.name ASC, c.course_name ASC';
        return $this->db->raw($sql, $args)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function open($trainingCenterId)
    {
        return $this->db->raw(
            "SELECT * FROM courses
             WHERE status = 'open' AND training_center_id = ?
             ORDER BY course_name ASC",
            [$trainingCenterId]
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        return $this->db->raw(
            'SELECT c.*, tc.name AS training_center_name, tc.region, tc.province, tc.city, tc.address,
                    (SELECT COUNT(*) FROM enrollments e
                     WHERE e.course_id = c.id AND e.status = \'enrolled\') AS enrollment_count
             FROM courses c
             INNER JOIN training_centers tc ON tc.id = c.training_center_id
             WHERE c.id = ?',
            [$id]
        )->fetch(PDO::FETCH_ASSOC);
    }

    public function count($trainingCenterId = null)
    {
        $sql = 'SELECT COUNT(*) AS total FROM courses';
        $args = [];
        if ($trainingCenterId !== null) {
            $sql .= ' WHERE training_center_id = ?';
            $args[] = (int) $trainingCenterId;
        }
        $result = $this->db->raw($sql, $args)->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    public function save(
        $courseCode,
        $courseName,
        $qualification,
        $description,
        $capacity,
        $status,
        $trainingCenterId,
        $classDays,
        $classSchedule,
        $courseOutline,
        $id = null
    )
    {
        $data = [
            'course_code' => $courseCode,
            'course_name' => $courseName,
            'qualification' => $qualification,
            'description' => $description,
            'capacity' => $capacity,
            'status' => $status,
            'training_center_id' => $trainingCenterId,
            'class_days' => $classDays,
            'class_schedule' => $classSchedule,
            'course_outline' => $courseOutline
        ];

        if ($id === null) {
            return $this->db->table('courses')->insert($data);
        }
        return $this->db->table('courses')->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->db->table('courses')->where('id', $id)->delete();
    }

    public function update_delivery_details($id, $classDays, $classSchedule, $courseOutline)
    {
        return $this->db->table('courses')->where('id', $id)->update([
            'class_days' => $classDays,
            'class_schedule' => $classSchedule,
            'course_outline' => $courseOutline
        ]);
    }

    public function for_center($id, $trainingCenterId)
    {
        return $this->db->raw(
            'SELECT c.*, tc.name AS training_center_name,
                    tc.region, tc.province, tc.city, tc.address,
                    (SELECT COUNT(*) FROM enrollments e
                     WHERE e.course_id = c.id AND e.status = \'enrolled\') AS enrollment_count
             FROM courses c
             INNER JOIN training_centers tc ON tc.id = c.training_center_id
             WHERE c.id = ? AND c.training_center_id = ?',
            [$id, $trainingCenterId]
        )->fetch(PDO::FETCH_ASSOC);
    }
}
