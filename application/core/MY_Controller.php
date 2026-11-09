<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('M_Auth');
    }

    protected function require_login() {
        if (!$this->session->userdata('is_logged_in')) {
            // Save the requested URL so user can be redirected back after login
            $current_segment = $this->uri->segment(1);
            if (!in_array($current_segment, ['login', 'register', 'logout', 'auth'])) {
                $this->session->set_userdata('redirect_url', current_url());
            }
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses fitur sistem.');
            redirect('login');
        }
    }

    protected function require_admin() {
        $this->require_login();
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Akses ditolak! Halaman ini dikhususkan untuk Administrator (Pakar).');
            redirect('dashboard');
        }
    }

    protected function current_user() {
        if (!$this->session->userdata('is_logged_in')) {
            return null;
        }
        return (object)[
            'id_user' => $this->session->userdata('id_user'),
            'nama' => $this->session->userdata('nama'),
            'username' => $this->session->userdata('username'),
            'role' => $this->session->userdata('role')
        ];
    }
}
