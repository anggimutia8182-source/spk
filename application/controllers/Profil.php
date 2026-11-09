<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_login();
        $this->load->model('M_Auth');
    }

    public function index() {
        $user = $this->current_user();
        $data['user'] = $this->M_Auth->get_by_id($user->id_user);
        $data['title'] = 'Profil Pengguna';

        $this->load->view('layouts/header', $data);
        $this->load->view('profil/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function update() {
        $user = $this->current_user();
        $nama = trim($this->input->post('nama', TRUE));
        $old_pass = $this->input->post('password_lama');
        $new_pass = $this->input->post('password_baru');
        $conf_pass = $this->input->post('password_konfirmasi');

        if (empty($nama)) {
            $this->session->set_flashdata('error', 'Nama lengkap wajib diisi.');
            redirect('profil');
        }

        $dbUser = $this->M_Auth->get_by_id($user->id_user);
        $updateData = ['nama' => $nama];

        if (!empty($new_pass)) {
            $is_valid_old = password_verify($old_pass, $dbUser->password) || (md5($old_pass) === $dbUser->password);
            if (!$is_valid_old) {
                $this->session->set_flashdata('error', 'Password saat ini salah.');
                redirect('profil');
            }

            if (strlen($new_pass) < 5) {
                $this->session->set_flashdata('error', 'Password baru minimal 5 karakter.');
                redirect('profil');
            }

            if ($new_pass !== $conf_pass) {
                $this->session->set_flashdata('error', 'Konfirmasi password baru tidak cocok.');
                redirect('profil');
            }

            $updateData['password'] = password_hash($new_pass, PASSWORD_DEFAULT);
        }

        $this->M_Auth->update($user->id_user, $updateData);
        $this->session->set_userdata('nama', $nama);

        $this->session->set_flashdata('success', 'Profil dan informasi akun berhasil diperbarui.');
        redirect('profil');
    }
}
