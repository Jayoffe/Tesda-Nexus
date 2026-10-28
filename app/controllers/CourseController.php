<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CourseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('CourseModel');
        $this->call->model('TrainingCenterModel');
        $this->call->model('EnrollmentModel');
        $this->call->model('CourseMaterialModel');
    }

    public function index()
    {
        $this->require_staff_admin();
        $centerId = $this->is_center_admin()
            ? (int) $this->session->userdata('training_center_id')
            : null;
        $this->call->view('courses/index', [
            'courses' => $this->CourseModel->all($centerId),
            'is_center_admin' => $this->is_center_admin(),
            'notification' => $this->session->flashdata('notification')
        ]);
    }

    public function detail($id)
    {
        $userId = (int) $this->session->userdata('id');
        $role = $this->session->userdata('user_role');
        $centerId = (int) $this->session->userdata('training_center_id');
        $course = in_array($role, ['admin', 'student'], true)
            ? $this->CourseModel->find((int) $id)
            : $this->CourseModel->for_center((int) $id, $centerId);

        if (!$course) {
            show_404();
            return;
        }

        $enrollment = null;
        if ($role === 'student') {
            if (!$this->EnrollmentModel->student_is_enrolled($userId, (int) $id)) {
                show_error('403 Forbidden', 'You must be enrolled to view this course.', 'error_general', 403);
                return;
            }
            foreach ($this->EnrollmentModel->for_student($userId) as $studentCourse) {
                if ((int) $studentCourse['course_id'] === (int) $id) {
                    $enrollment = $studentCourse;
                    break;
                }
            }
        } elseif (!in_array($role, ['admin', 'center_admin', 'instructor'], true)) {
            show_error('403 Forbidden', 'This page is not available for your account role.', 'error_general', 403);
            return;
        }

        $this->call->view('courses/detail', [
            'course' => $course,
            'role' => $role,
            'enrollment' => $enrollment,
            'roster' => $role === 'student'
                ? []
                : $this->EnrollmentModel->roster_for_course((int) $id),
            'materials' => $this->CourseMaterialModel->for_course((int) $id),
            'notification' => $this->session->flashdata('notification')
        ]);
    }

    public function add_material($id)
    {
        $this->require_instructor();
        $centerId = (int) $this->session->userdata('training_center_id');
        $course = $this->CourseModel->for_center((int) $id, $centerId);
        if (!$course) {
            show_404();
            return;
        }

        $title = trim((string) $this->io->post('title'));
        $description = trim((string) $this->io->post('description'));
        $url = trim((string) $this->io->post('resource_url'));
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        if (
            $title === ''
            || strlen($title) > 150
            || strlen($description) > 500
            || strlen($url) > 2048
            || !filter_var($url, FILTER_VALIDATE_URL)
            || !in_array($scheme, ['http', 'https'], true)
        ) {
            $this->session->set_flashdata(
                'notification',
                'Enter a title, an optional description, and a valid http or https material link.'
            );
            header('Location: ' . site_url('/course/' . (int) $id));
            exit;
        }

        $this->CourseMaterialModel->add(
            (int) $id,
            (int) $this->session->userdata('id'),
            $title,
            $description,
            $url
        );
        $this->session->set_flashdata('notification', 'Course material shared with enrolled students.');
        header('Location: ' . site_url('/course/' . (int) $id));
        exit;
    }

    public function update_delivery_details($id)
    {
        $this->require_instructor();
        $course = $this->CourseModel->for_center(
            (int) $id,
            (int) $this->session->userdata('training_center_id')
        );
        if (!$course) {
            show_404();
            return;
        }

        $classDays = trim((string) $this->io->post('class_days'));
        $classSchedule = trim((string) $this->io->post('class_schedule'));
        $courseOutline = trim((string) $this->io->post('course_outline'));
        if (strlen($classDays) > 150 || strlen($classSchedule) > 10000 || strlen($courseOutline) > 20000) {
            $this->session->set_flashdata('notification', 'Days, schedule, or outline exceed the allowed length.');
        } else {
            $this->CourseModel->update_delivery_details(
                (int) $id,
                $classDays,
                $classSchedule,
                $courseOutline
            );
            $this->session->set_flashdata('notification', 'Course schedule and outline updated.');
        }

        header('Location: ' . site_url('/course/' . (int) $id));
        exit;
    }

    public function create()
    {
        $this->form(null);
    }

    public function edit($id)
    {
        $this->require_staff_admin();
        $course = $this->find_manageable_course((int) $id);
        if (!$course) {
            show_404();
            return;
        }
        $this->form((int) $id, $course);
    }

    public function delete($id)
    {
        $this->require_staff_admin();
        if (!$this->find_manageable_course((int) $id)) {
            show_404();
            return;
        }
        $this->CourseModel->delete((int) $id);
        $this->session->set_flashdata('notification', 'Course deleted successfully.');
        header('Location: ' . site_url('/admin/courses'));
        exit;
    }

    private function form($id, $course = [])
    {
        $this->require_staff_admin();
        $centerId = (int) $this->session->userdata('training_center_id');
        $centers = $this->is_center_admin()
            ? array_filter([$this->TrainingCenterModel->find_active($centerId)])
            : $this->TrainingCenterModel->all_active();
        $data = [
            'course' => $course ?: [],
            'training_centers' => $centers,
            'is_center_admin' => $this->is_center_admin(),
            'errors' => [],
            'form_action' => site_url($id === null
                ? '/admin/courses/create'
                : '/admin/courses/edit/' . $id)
        ];

        if ($this->form_validation->submitted()) {
            $course = [
                'course_code' => strtoupper(trim((string) $this->io->post('course_code'))),
                'course_name' => trim((string) $this->io->post('course_name')),
                'qualification' => strtoupper(trim((string) $this->io->post('qualification'))),
                'description' => trim((string) $this->io->post('description')),
                'capacity' => (int) $this->io->post('capacity'),
                'status' => trim((string) $this->io->post('status')),
                'training_center_id' => (int) $this->io->post('training_center_id'),
                'class_days' => trim((string) $this->io->post('class_days')),
                'class_schedule' => trim((string) $this->io->post('class_schedule')),
                'course_outline' => trim((string) $this->io->post('course_outline'))
            ];
            if ($this->is_center_admin()) {
                $course['training_center_id'] = $centerId;
            }
            $data['course'] = $course;
            $data['errors'] = $this->validate_course($course, $id);
            if (!$data['errors']) {
                $this->CourseModel->save(
                    $course['course_code'],
                    $course['course_name'],
                    $course['qualification'],
                    $course['description'],
                    $course['capacity'],
                    $course['status'],
                    $course['training_center_id'],
                    $course['class_days'],
                    $course['class_schedule'],
                    $course['course_outline'],
                    $id
                );
                $this->session->set_flashdata('notification', 'Course saved successfully.');
                header('Location: ' . site_url('/admin/courses'));
                exit;
            }
        }

        $data['page_title'] = $id === null ? 'Add course' : 'Edit course';
        $this->call->view('courses/form', $data);
    }

    private function validate_course($course, $excludeId)
    {
        $errors = [];
        if (!preg_match('/^[A-Z0-9-]{2,40}$/', $course['course_code'])) {
            $errors[] = 'Course code must use 2 to 40 letters, numbers, or hyphens.';
        }
        if ($course['course_name'] === '' || strlen($course['course_name']) > 150) {
            $errors[] = 'Course name is required and must be at most 150 characters.';
        }
        if (!preg_match('/^[A-Z0-9 -]{2,30}$/', $course['qualification'])) {
            $errors[] = 'Enter a valid qualification such as NC II.';
        }
        if ($course['capacity'] < 1 || $course['capacity'] > 10000) {
            $errors[] = 'Capacity must be between 1 and 10,000.';
        }
        if (strlen($course['description']) > 10000 || strlen($course['class_days']) > 150) {
            $errors[] = 'Description or class days exceed the allowed length.';
        }
        if (strlen($course['class_schedule']) > 10000 || strlen($course['course_outline']) > 20000) {
            $errors[] = 'Schedule or course outline exceeds the allowed length.';
        }
        if (!in_array($course['status'], ['open', 'closed'], true)) {
            $errors[] = 'Select a valid course status.';
        }
        $center = $this->db->raw(
            'SELECT id FROM training_centers WHERE id = ? AND is_active = 1 LIMIT 1',
            [$course['training_center_id']]
        )->fetch(PDO::FETCH_ASSOC);
        if (!$center) {
            $errors[] = 'Select an active training center.';
        }
        if ($this->is_center_admin()
            && (int) $course['training_center_id'] !== (int) $this->session->userdata('training_center_id')) {
            $errors[] = 'You can only manage courses for your assigned training center.';
        }
        $sql = 'SELECT COUNT(*) AS total FROM courses WHERE course_code = ? AND training_center_id = ?';
        $args = [$course['course_code'], $course['training_center_id']];
        if ($excludeId !== null) {
            $sql .= ' AND id <> ?';
            $args[] = $excludeId;
        }
        $existing = $this->db->raw($sql, $args)->fetch(PDO::FETCH_ASSOC);
        if ((int) ($existing['total'] ?? 0) > 0) {
            $errors[] = 'That course code is already in use.';
        }
        return $errors;
    }

    private function require_staff_admin()
    {
        if (!in_array($this->session->userdata('user_role'), ['admin', 'center_admin'], true)) {
            show_error('403 Forbidden', 'Training center management access is required.', 'error_general', 403);
            exit;
        }
    }

    private function is_center_admin()
    {
        return $this->session->userdata('user_role') === 'center_admin';
    }

    private function find_manageable_course($id)
    {
        if ($this->is_center_admin()) {
            return $this->CourseModel->for_center(
                $id,
                (int) $this->session->userdata('training_center_id')
            );
        }
        return $this->CourseModel->find($id);
    }

    private function require_instructor()
    {
        if ($this->session->userdata('user_role') !== 'instructor') {
            show_error('403 Forbidden', 'Instructor access is required.', 'error_general', 403);
            exit;
        }
    }
}
