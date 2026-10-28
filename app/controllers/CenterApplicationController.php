<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CenterApplicationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('CenterApplicationModel');
    }

    public function index()
    {
        $centerId = (int) $this->session->userdata('training_center_id');
        $this->call->view('center_admin/applications', [
            'applications' => $this->CenterApplicationModel->pending_for_center($centerId),
            'center_name' => $this->session->userdata('training_center_name'),
            'notification' => $this->session->flashdata('notification')
        ]);
    }

    public function approve($id)
    {
        $this->review($id, 'approved');
    }

    public function reject($id)
    {
        $this->review($id, 'rejected');
    }

    private function review($id, $decision)
    {
        $reviewed = $this->CenterApplicationModel->review(
            (int) $id,
            (int) $this->session->userdata('training_center_id'),
            (int) $this->session->userdata('id'),
            $decision
        );
        $this->session->set_flashdata(
            'notification',
            $reviewed
                ? 'Application ' . $decision . '.'
                : 'That application is no longer pending or does not belong to this center.'
        );
        header('Location: ' . site_url('/center/applications'));
        exit;
    }
}
