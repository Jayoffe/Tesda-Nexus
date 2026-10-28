<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthMiddleware
{
    public function handle($next)
    {
        $session = load_class('session', 'libraries');

        if (
            !$session->has_userdata('id')
            || !$session->has_userdata('user_id')
            || !$session->has_userdata('username')
            || !$session->has_userdata('user_role')
        ) {
            header('Location: ' . site_url('/login'));
            exit;
        }

        return $next();
    }
}