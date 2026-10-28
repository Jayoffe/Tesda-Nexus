<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class TrainingCenterController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('TrainingCenterModel');
    }

    public function index()
    {
        $this->require_admin();
        $errors = [];

        if ($this->form_validation->submitted()) {
            $center = [
                'center_code' => strtoupper(trim((string) $this->io->post('center_code'))),
                'name' => trim((string) $this->io->post('name')),
                'region' => trim((string) $this->io->post('region')),
                'province' => trim((string) $this->io->post('province')),
                'city' => trim((string) $this->io->post('city')),
                'address' => trim((string) $this->io->post('address'))
            ];

            $errors = $this->validate_center($center);
            if (!$errors) {
                $this->TrainingCenterModel->create(
                    $center['center_code'],
                    $center['name'],
                    $center['region'],
                    $center['province'],
                    $center['city'],
                    $center['address']
                );
                $this->session->set_flashdata('notification', 'Training center added.');
                header('Location: ' . site_url('/admin/centers'));
                exit;
            }
        } else {
            $center = [];
        }

        $this->call->view('centers/index', [
            'centers' => $this->TrainingCenterModel->all_with_counts(),
            'center' => $center,
            'errors' => $errors,
            'notification' => $this->session->flashdata('notification')
        ]);
    }

    private function validate_center($center)
    {
        $errors = [];
        if (!preg_match('/^[A-Z0-9-]{2,40}$/', $center['center_code'])) {
            $errors[] = 'Center code must use 2 to 40 letters, numbers, or hyphens.';
        }
        if ($center['name'] === '' || strlen($center['name']) > 150) {
            $errors[] = 'Center name is required and must be at most 150 characters.';
        }
        if (strlen($center['region']) > 100 || strlen($center['province']) > 100 || strlen($center['city']) > 100) {
            $errors[] = 'Region, province, and city must each be at most 100 characters.';
        }
        if (strlen($center['address']) > 255) {
            $errors[] = 'Address must be at most 255 characters.';
        }

        $existing = $this->db->raw(
            'SELECT id FROM training_centers WHERE center_code = ? LIMIT 1',
            [$center['center_code']]
        )->fetch(PDO::FETCH_ASSOC);
        if ($existing) {
            $errors[] = 'That training center code is already in use.';
        }
        return $errors;
    }

    private function require_admin()
    {
        if ($this->session->userdata('user_role') !== 'admin') {
            show_error('403 Forbidden', 'Administrator access is required.', 'error_general', 403);
            exit;
        }
    }
}
