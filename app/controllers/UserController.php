<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: UserController
 * 
 * Automatically generated via CLI.
 */
class UserController extends Controller
{
   public function __construct() {
        parent::__construct();
        $this->call->model('UserModel');
        $this->call->model('TrainingCenterModel');
        $this->call->model('CenterApplicationModel');
   }

   public function showUsers() {
        $centerId = $this->is_center_admin()
            ? (int) $this->session->userdata('training_center_id')
            : null;
        $data['users'] = $this->UserModel->all($centerId);
        $data['notification'] = $this->session->flashdata('notification');
        $data['current_user_id'] = (int) $this->session->userdata('id');
        $data['is_center_admin'] = $this->is_center_admin();
        $this->call->view('users/index', $data);
    }

   public function create() {
        $data = [
            'errors' => [],
            'account' => [],
            'training_centers' => $this->form_centers(),
            'is_center_admin' => $this->is_center_admin()
        ];
        if ($this->form_validation->submitted()) {
            $account = $this->account_input();
            $account = $this->apply_center_scope($account);
            $data['account'] = $account;
            $data['errors'] = $this->validate_account($account, null, true);
            if (!$data['errors']) {
                $this->UserModel->create_account(
                    $account['username'],
                    $account['email'],
                    $account['password'],
                    $account['role'],
                    $account['full_name'],
                    $account['is_active'],
                    $account['training_center_id']
                );
                $this->session->set_flashdata('notification', 'Account created successfully.');
                header('Location: ' . site_url('/show-users'));
                exit;
            }
        }
        $data['page_title'] = 'Create account';
        $data['form_action'] = site_url('/admin/users/create');
        $this->call->view('users/form', $data);
   }

   public function edit($id) {
        $user = $this->UserModel->find((int) $id);
        if (!$user || !$this->can_manage_user($user)) {
            show_404();
            return;
        }

        $data = [
            'errors' => [],
            'account' => $user,
            'training_centers' => $this->form_centers(),
            'is_center_admin' => $this->is_center_admin()
        ];
        if ($this->form_validation->submitted()) {
            $account = $this->account_input();
            $account = $this->apply_center_scope($account);
            $data['account'] = array_merge($user, $account);
            $data['errors'] = $this->validate_account($account, (int) $id, false);
            if (!$data['errors']) {
                $this->UserModel->update_account(
                    (int) $id,
                    $account['username'],
                    $account['email'],
                    $account['role'],
                    $account['full_name'],
                    $account['is_active'],
                    $account['training_center_id'],
                    $account['password']
                );
                $this->session->set_flashdata('notification', 'Account updated successfully.');
                header('Location: ' . site_url('/show-users'));
                exit;
            }
        }
        $data['page_title'] = 'Edit account';
        $data['form_action'] = site_url('/admin/users/edit/' . (int) $id);
        $this->call->view('users/form', $data);
   }

   public function delete($id) {
        $user = $this->UserModel->find((int) $id);
        if (!$user || !$this->can_manage_user($user)) {
            show_404();
            return;
        }
        if ((int) $id === (int) $this->session->userdata('id')) {
            $this->session->set_flashdata('notification', 'You cannot delete the account you are using.');
        } else {
            $this->UserModel->delete_account((int) $id);
            $this->session->set_flashdata('notification', 'Account deleted successfully.');
        }
        header('Location: ' . site_url('/show-users'));
        exit;
   }

   private function account_input() {
        return [
            'username' => trim((string) $this->io->post('username')),
            'email' => trim((string) $this->io->post('email')),
            'full_name' => trim((string) $this->io->post('full_name')),
            'role' => trim((string) $this->io->post('role')),
            'password' => (string) $this->io->post('password'),
            'is_active' => $this->io->post('is_active') === '1' ? 1 : 0,
            'training_center_id' => (int) $this->io->post('training_center_id') ?: null
        ];
   }

   private function validate_account($account, $excludeId, $passwordRequired) {
        $errors = [];
        if (!preg_match('/^[A-Za-z0-9_.-]{3,100}$/', $account['username'])) {
            $errors[] = 'Username must be 3 to 100 characters and use letters, numbers, dots, underscores, or hyphens.';
        }
        if (!filter_var($account['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }
        if ($account['full_name'] === '' || strlen($account['full_name']) > 150) {
            $errors[] = 'Full name is required and must be at most 150 characters.';
        }
        $allowedRoles = $this->is_center_admin()
            ? ['instructor', 'student']
            : ['admin', 'center_admin', 'instructor', 'student'];
        if (!in_array($account['role'], $allowedRoles, true)) {
            $errors[] = 'Select a valid account role.';
        }
        if ($account['role'] !== 'admin' && $account['training_center_id'] === null) {
            $errors[] = 'Select a training center for center organizer, instructor, and student accounts.';
        } elseif ($account['training_center_id'] !== null) {
            $center = $this->db->raw(
                'SELECT id FROM training_centers WHERE id = ? AND is_active = 1 LIMIT 1',
                [$account['training_center_id']]
            )->fetch(PDO::FETCH_ASSOC);
            if (!$center) {
                $errors[] = 'Select an active training center.';
            }
        }
        if ($passwordRequired && $account['password'] === '') {
            $errors[] = 'A password is required for new accounts.';
        }
        if ($account['password'] !== '' && strlen($account['password']) < 4) {
            $errors[] = 'Password must contain at least 4 characters.';
        }
        $sql = 'SELECT COUNT(*) AS total FROM users WHERE (email = ? OR username = ?)';
        $args = [$account['email'], $account['username']];
        if ($excludeId !== null) {
            $sql .= ' AND id <> ?';
            $args[] = $excludeId;
        }
        $existing = $this->db->raw($sql, $args)->fetch(PDO::FETCH_ASSOC);
        if ((int) ($existing['total'] ?? 0) > 0) {
            $errors[] = 'That email address or username is already in use.';
        }
        return $errors;
   }

   private function is_center_admin() {
        return $this->session->userdata('user_role') === 'center_admin';
   }

   private function form_centers() {
        if (!$this->is_center_admin()) {
            return $this->TrainingCenterModel->all_active();
        }
        $center = $this->TrainingCenterModel->find_active(
            (int) $this->session->userdata('training_center_id')
        );
        return $center ? [$center] : [];
   }

   private function apply_center_scope($account) {
        if ($this->is_center_admin()) {
            $account['training_center_id'] = (int) $this->session->userdata('training_center_id');
        }
        return $account;
   }

   private function can_manage_user($user) {
        if (in_array(
            $this->CenterApplicationModel->status_for_user((int) $user['id']),
            ['pending', 'rejected'],
            true
        )) {
            return false;
        }
        if (!$this->is_center_admin()) {
            return true;
        }
        return (int) ($user['training_center_id'] ?? 0) === (int) $this->session->userdata('training_center_id')
            && in_array($user['role'] ?? '', ['student', 'instructor'], true);
   }
}