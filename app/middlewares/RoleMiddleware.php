<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class RoleMiddleware
{
    private $role;

    public function __construct($role)
    {
        $this->role = is_array($role) ? $role : [$role];
    }

    public function handle($next)
    {
        $session = load_class('session', 'libraries');
        if (!$session->has_userdata('id')) {
            header('Location: ' . site_url('/login'));
            exit;
        }

        if (!in_array($session->userdata('user_role'), $this->role, true)) {
            show_error('403 Forbidden', 'This page is not available for your account role.', 'error_general', 403);
            exit;
        }

        return $next();
    }
}
