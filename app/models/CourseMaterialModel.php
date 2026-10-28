<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CourseMaterialModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function for_course($courseId)
    {
        return $this->db->raw(
            'SELECT id, title, description, resource_url, created_at
             FROM course_materials
             WHERE course_id = ?
             ORDER BY created_at DESC, id DESC',
            [$courseId]
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($courseId, $instructorId, $title, $description, $resourceUrl)
    {
        return $this->db->table('course_materials')->insert([
            'course_id' => $courseId,
            'uploaded_by' => $instructorId,
            'title' => $title,
            'description' => $description ?: null,
            'resource_url' => $resourceUrl
        ]);
    }
}
