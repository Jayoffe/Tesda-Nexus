<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Welcome extends Controller {
	public function index() {
		$this->call->view('landing/index');
	}

	public function dashboard() {
		$role = $this->session->userdata('user_role');
		$data = [
			'username' => $this->session->userdata('username'),
			'full_name' => $this->session->userdata('full_name'),
			'training_center_name' => $this->session->userdata('training_center_name'),
			'user_role' => $role
		];

		if ($role === 'admin') {
			$this->call->model('CourseModel');
			$this->call->model('EnrollmentModel');
			$this->call->model('UserModel');
			$this->call->model('ProductModel');
			$data['analytics'] = [
				'students' => $this->UserModel->count_by_role('student'),
				'instructors' => $this->UserModel->count_by_role('instructor'),
				'courses' => $this->CourseModel->count(),
				'enrollments' => $this->EnrollmentModel->count(),
				'products' => count($this->ProductModel->read())
			];
			$this->call->view('dashboard/admin', $data);
			return;
		}

		if ($role === 'center_admin') {
			$centerId = (int) $this->session->userdata('training_center_id');
			$this->call->model('CourseModel');
			$this->call->model('UserModel');
			$this->call->model('CenterApplicationModel');
			$data['center_name'] = $this->session->userdata('training_center_name');
			$data['analytics'] = [
				'students' => $this->UserModel->count_by_role('student', $centerId),
				'instructors' => $this->UserModel->count_by_role('instructor', $centerId),
				'courses' => $this->CourseModel->count($centerId),
				'applications' => $this->CenterApplicationModel->pending_count($centerId)
			];
			$this->call->view('dashboard/center_admin', $data);
			return;
		}

		if ($role === 'instructor') {
			header('Location: ' . site_url('/instructor'));
			exit;
		}

		$this->call->model('EnrollmentModel');
		$data['enrollments'] = $this->EnrollmentModel->for_student(
			(int) $this->session->userdata('id')
		);
		$this->call->view('student/home', $data);
	}
}
?>