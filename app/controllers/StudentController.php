<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('EnrollmentModel');
        $this->call->model('UserModel');
    }

    public function index()
    {
        $this->call->view('student/home', [
            'enrollments' => $this->EnrollmentModel->for_student((int) $this->session->userdata('id')),
            'full_name' => $this->session->userdata('full_name'),
            'username' => $this->session->userdata('username'),
            'training_center_name' => $this->session->userdata('training_center_name')
        ]);
    }

    public function profile()
    {
        $userId = (int) $this->session->userdata('id');
        $user = $this->UserModel->find($userId);
        if (!$user || $user['role'] !== 'student') {
            show_error('403 Forbidden', 'This profile is not available.', 'error_general', 403);
            return;
        }

        $errors = [];
        $notification = $this->session->flashdata('notification');
        if ($this->form_validation->submitted()) {
            $name = trim((string) $this->io->post('full_name'));
            $email = trim((string) $this->io->post('email'));

            if ($name === '' || strlen($name) > 150) {
                $errors[] = 'Name is required and must be at most 150 characters.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
                $errors[] = 'Enter a valid email address.';
            } else {
                $duplicate = $this->db->raw(
                    'SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1',
                    [$email, $userId]
                )->fetch(PDO::FETCH_ASSOC);
                if ($duplicate) {
                    $errors[] = 'That email address is already in use.';
                }
            }

            if (!$errors) {
                $this->UserModel->update_student_profile($userId, $name, $email);
                $this->session->set_userdata('full_name', $name);
                $this->session->set_flashdata('notification', 'Your profile was updated.');
                header('Location: ' . site_url('/student-profile'));
                exit;
            }
            $user['full_name'] = $name;
            $user['email'] = $email;
        }

        $data = [
            'name' => $user['full_name'] ?: $user['username'],
            'email' => $user['email'],
            'training_center_name' => $this->session->userdata('training_center_name'),
            'enrollments' => $this->EnrollmentModel->for_student($userId),
            'errors' => $errors,
            'notification' => $notification
        ];

        $this->call->view('student/profile', $data);
    }

    public function courses()
    {
        $this->call->view('student/courses', [
            'enrollments' => $this->EnrollmentModel->for_student((int) $this->session->userdata('id'))
        ]);
    }
}