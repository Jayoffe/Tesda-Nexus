<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class TrainingCenterModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function all_active()
    {
        return $this->db->table('training_centers')
            ->where('is_active', 1)
            ->order_by('name', 'ASC')
            ->get_all();
    }

    public function find_active($id)
    {
        return $this->db->table('training_centers')
            ->where('id', (int) $id)
            ->where('is_active', 1)
            ->get();
    }

    public function all_with_counts()
    {
        return $this->db->raw(
            'SELECT tc.*,
                    (SELECT COUNT(*) FROM users u WHERE u.training_center_id = tc.id) AS user_count,
                    (SELECT COUNT(*) FROM courses c WHERE c.training_center_id = tc.id) AS course_count
             FROM training_centers tc
             ORDER BY tc.name ASC'
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($code, $name, $region, $province, $city, $address)
    {
        return $this->db->table('training_centers')->insert([
            'center_code' => $code,
            'name' => $name,
            'region' => $region ?: null,
            'province' => $province ?: null,
            'city' => $city ?: null,
            'address' => $address ?: null,
            'is_active' => 1
        ]);
    }
}
