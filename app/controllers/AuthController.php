<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UserModel');
        $this->call->model('TrainingCenterModel');
        $this->call->model('CenterApplicationModel');
    }

    public function login()
    {
        $data = [
            'error' => '',
            'identity' => ''
        ];

        if ($this->form_validation->submitted()) {
            $identity = trim($this->io->post('email') ?: $this->io->post('username'));
            $password = $this->io->post('password');
            $user = $this->UserModel->find_by_identity($identity);
            $data['identity'] = $identity;

            if (!empty($user) && ($user['is_active'] ?? 1) && password_verify($password, $user['password'])) {
                $center = !empty($user['training_center_id'])
                    ? $this->db->raw(
                        'SELECT name FROM training_centers WHERE id = ?',
                        [(int) $user['training_center_id']]
                    )->fetch(PDO::FETCH_ASSOC)
                    : null;
                $this->session->regenerate_on_login();
                $this->session->set_userdata([
                    'id' => $user['id'],
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'full_name' => $user['full_name'] ?? $user['username'],
                    'user_role' => $user['role'],
                    'training_center_id' => $user['training_center_id'] ?? null,
                    'training_center_name' => $center['name'] ?? ''
                ]);
                header('Location: ' . site_url('/dashboard'));
                exit;
            }

            if (!empty($user) && password_verify($password, $user['password']) && !($user['is_active'] ?? 1)) {
                $applicationStatus = $this->CenterApplicationModel->status_for_user((int) $user['id']);
                $data['error'] = $applicationStatus === 'pending'
                    ? 'Your application is awaiting review by your selected training center.'
                    : ($applicationStatus === 'rejected'
                        ? 'Your application was not approved. Contact your selected training center for details.'
                        : 'This account is inactive. Contact your training center administrator.');
            } else {
                $data['error'] = 'The email or password is incorrect.';
            }
        }

        $this->call->view('auth/login/index', $data);
    }

    public function apply()
    {
        $data = [
            'centers' => $this->TrainingCenterModel->all_active(),
            'errors' => [],
            'application' => [],
            'submitted' => false
        ];

        if ($this->form_validation->submitted()) {
            $application = [
                'full_name' => trim((string) $this->io->post('full_name')),
                'username' => trim((string) $this->io->post('username')),
                'email' => strtolower(trim((string) $this->io->post('email'))),
                'password' => (string) $this->io->post('password'),
                'role' => trim((string) $this->io->post('role')),
                'training_center_id' => (int) $this->io->post('training_center_id')
            ];
            $data['application'] = $application;
            $data['errors'] = $this->validate_application($application);

            if (!$data['errors']) {
                $this->CenterApplicationModel->submit(
                    $application['username'],
                    $application['email'],
                    $application['password'],
                    $application['role'],
                    $application['full_name'],
                    $application['training_center_id']
                );
                foreach ($data['centers'] as $center) {
                    if ((int) $center['id'] === $application['training_center_id']) {
                        $data['submitted_center_name'] = $center['name'];
                        break;
                    }
                }
                $data['submitted'] = true;
                $data['application'] = [];
            }
        }

        $this->call->view('auth/apply/index', $data);
    }

    private function validate_application($application)
    {
        $errors = [];
        if ($application['full_name'] === '' || strlen($application['full_name']) > 150) {
            $errors[] = 'Enter your full name, up to 150 characters.';
        }
        if (!preg_match('/^[A-Za-z0-9_.-]{3,100}$/', $application['username'])) {
            $errors[] = 'Username must be 3 to 100 characters and use letters, numbers, dots, underscores, or hyphens.';
        }
        if (!filter_var($application['email'], FILTER_VALIDATE_EMAIL) || strlen($application['email']) > 255) {
            $errors[] = 'Enter a valid email address.';
        }
        if (!in_array($application['role'], ['student', 'instructor'], true)) {
            $errors[] = 'Choose Student or Instructor.';
        }
        if ($application['password'] === '' || strlen($application['password']) < 8) {
            $errors[] = 'Choose a password with at least 8 characters.';
        }
        $center = $this->db->raw(
            'SELECT id FROM training_centers WHERE id = ? AND is_active = 1 LIMIT 1',
            [$application['training_center_id']]
        )->fetch(PDO::FETCH_ASSOC);
        if (!$center) {
            $errors[] = 'Choose an active training center.';
        }
        $existing = $this->db->raw(
            'SELECT COUNT(*) AS total FROM users WHERE email = ? OR username = ?',
            [$application['email'], $application['username']]
        )->fetch(PDO::FETCH_ASSOC);
        if ((int) ($existing['total'] ?? 0) > 0) {
            $errors[] = 'That email address or username is already registered.';
        }
        return $errors;
    }

    public function logout()
    {
        $this->session->sess_destroy();
        header('Location: ' . site_url('/login'));
        exit;
    }

}