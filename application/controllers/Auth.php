<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function login() {
        if ($this->session->userdata('is_logged_in')) {
            redirect('dashboard');
        }

        $data['title'] = 'Login - SPK Gangguan Tidur';
        $this->load->view('auth/login', $data);
    }

    public function login_process() {
        $username = trim($this->input->post('username', TRUE));
        $password = $this->input->post('password');

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username dan password wajib diisi.');
            redirect('login');
        }

        $user = $this->M_Auth->get_by_username($username);

        if (!$user) {
            $this->session->set_flashdata('error', 'Username tidak ditemukan.');
            redirect('login');
        }

        // Check password (supports password_hash, md5 fallback, and plaintext fallback)
        $is_valid_password = password_verify($password, $user->password) 
            || (md5($password) === $user->password)
            || ($password === $user->password);

        if (!$is_valid_password) {
            $this->session->set_flashdata('error', 'Password yang dimasukkan salah.');
            redirect('login');
        }

        // Auto re-hash password to modern bcrypt if currently plaintext or md5
        if (password_needs_rehash($user->password, PASSWORD_DEFAULT) || $password === $user->password || md5($password) === $user->password) {
            $this->M_Auth->update($user->id_user, [
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
        }

        // Set session
        $this->session->set_userdata([
            'id_user' => $user->id_user,
            'nama' => $user->nama,
            'username' => $user->username,
            'role' => $user->role,
            'is_logged_in' => TRUE
        ]);

        $this->session->set_flashdata('success', 'Selamat datang kembali, ' . html_escape($user->nama) . '!');

        // Redirect to intended URL if available, else dashboard
        $redirect_url = $this->session->userdata('redirect_url');
        if ($redirect_url && filter_var($redirect_url, FILTER_VALIDATE_URL)) {
            $this->session->unset_userdata('redirect_url');
            redirect($redirect_url);
        } else {
            redirect('dashboard');
        }
    }

    public function register() {
        if ($this->session->userdata('is_logged_in')) {
            redirect('dashboard');
        }

        $data['title'] = 'Registrasi Pasien - SPK Gangguan Tidur';
        $this->load->view('auth/register', $data);
    }

    public function register_process() {
        $nama = trim($this->input->post('nama', TRUE));
        $username = trim($this->input->post('username', TRUE));
        $password = $this->input->post('password');
        $password_confirm = $this->input->post('password_confirm');

        if (empty($nama) || empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Semua kolom bertanda bintang wajib diisi.');
            redirect('register');
        }

        if (strlen($username) < 3) {
            $this->session->set_flashdata('error', 'Username minimal 3 karakter.');
            redirect('register');
        }

        if (strlen($password) < 5) {
            $this->session->set_flashdata('error', 'Password minimal 5 karakter.');
            redirect('register');
        }

        if ($password !== $password_confirm) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok.');
            redirect('register');
        }

        if ($this->M_Auth->username_exists($username)) {
            $this->session->set_flashdata('error', 'Username sudah digunakan, silakan pilih username lain.');
            redirect('register');
        }

        $userData = [
            'nama' => $nama,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user'
        ];

        $inserted = $this->M_Auth->insert($userData);

        if ($inserted) {
            $new_user = $this->M_Auth->get_by_username($username);
            $this->session->set_userdata([
                'id_user' => $new_user->id_user,
                'nama' => $new_user->nama,
                'username' => $new_user->username,
                'role' => $new_user->role,
                'is_logged_in' => TRUE
            ]);
            $this->session->set_flashdata('success', 'Akun berhasil dibuat! Selamat datang di Sistem Pakar Gangguan Tidur.');
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem saat mendaftar. Silakan coba lagi.');
            redirect('register');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
