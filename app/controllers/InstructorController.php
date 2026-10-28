<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class InstructorController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('UserModel');
        $this->call->model('CourseModel');
        $this->call->model('EnrollmentModel');
    }

    public function index()
    {
        $this->require_role('instructor');
        $trainingCenterId = (int) $this->session->userdata('training_center_id');
        if ($trainingCenterId < 1) {
            show_error('Training center required', 'Ask an administrator to assign your account to a training center.', 'error_general', 403);
            return;
        }
        $this->call->view('instructor/home', [
            'username' => $this->session->userdata('username'),
            'full_name' => $this->session->userdata('full_name'),
            'training_center_name' => $this->session->userdata('training_center_name'),
            'students' => $this->UserModel->students($trainingCenterId),
            'courses' => $this->CourseModel->open($trainingCenterId),
            'enrollments' => $this->EnrollmentModel->for_instructor(
                (int) $this->session->userdata('id'),
                $trainingCenterId
            ),
            'notification' => $this->session->flashdata('notification'),
            'notification_success' => $this->session->flashdata('notification_success') === '1'
        ]);
    }

    public function enroll()
    {
        $this->require_role('instructor');
        $studentId = (int) $this->io->post('student_id');
        $courseId = (int) $this->io->post('course_id');
        $student = $this->UserModel->find($studentId);
        $course = $this->CourseModel->find($courseId);
        $success = false;
        $trainingCenterId = (int) $this->session->userdata('training_center_id');

        if (!$student || $student['role'] !== 'student' || (int) $student['is_active'] !== 1
            || (int) $student['training_center_id'] !== $trainingCenterId) {
            $message = 'Select an active student account.';
        } elseif (!$course || $course['status'] !== 'open'
            || (int) $course['training_center_id'] !== $trainingCenterId) {
            $message = 'Select an open course.';
        } elseif ($this->EnrollmentModel->exists($studentId, $courseId)) {
            $message = 'This student is already enrolled in that course.';
        } elseif ($this->EnrollmentModel->course_enrollment_count($courseId) >= (int) $course['capacity']) {
            $message = 'This course has reached its enrollment capacity.';
        } else {
            $this->EnrollmentModel->add(
                $studentId,
                $courseId,
                (int) $this->session->userdata('id')
            );
            $message = 'Student enrolled successfully.';
            $success = true;
        }

        $this->session->set_flashdata('notification', $message);
        $this->session->set_flashdata('notification_success', $success ? '1' : '0');
        header('Location: ' . site_url('/instructor'));
        exit;
    }

    private function require_role($role)
    {
        if ($this->session->userdata('user_role') !== $role) {
            show_error('403 Forbidden', 'This page is not available for your account role.', 'error_general', 403);
            exit;
        }
    }
}
