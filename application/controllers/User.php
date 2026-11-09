<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_admin();
        $this->load->model('M_Auth');
    }

    public function index() {
        $data['user'] = $this->current_user();
        $data['title'] = 'Kelola Data Pengguna Sistem';
        $data['users'] = $this->M_Auth->get_all();

        $this->load->view('layouts/header', $data);
        $this->load->view('user/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function tambah() {
        $nama = trim($this->input->post('nama', TRUE));
        $username = trim($this->input->post('username', TRUE));
        $password = $this->input->post('password');
        $role = $this->input->post('role', TRUE);

        if (empty($nama) || empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Semua kolom wajib diisi.');
            redirect('user');
        }

        if ($this->M_Auth->username_exists($username)) {
            $this->session->set_flashdata('error', 'Username sudah dipakai oleh pengguna lain.');
            redirect('user');
        }

        $this->M_Auth->insert([
            'nama' => $nama,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => in_array($role, ['admin', 'user']) ? $role : 'user'
        ]);

        $this->session->set_flashdata('success', 'Pengguna ' . html_escape($nama) . ' berhasil ditambahkan.');
        redirect('user');
    }

    public function edit($id_user) {
        $nama = trim($this->input->post('nama', TRUE));
        $username = trim($this->input->post('username', TRUE));
        $password = $this->input->post('password');
        $role = $this->input->post('role', TRUE);

        if (empty($nama) || empty($username)) {
            $this->session->set_flashdata('error', 'Nama dan username tidak boleh kosong.');
            redirect('user');
        }

        if ($this->M_Auth->username_exists($username, $id_user)) {
            $this->session->set_flashdata('error', 'Username sudah dipakai oleh pengguna lain.');
            redirect('user');
        }

        $updateData = [
            'nama' => $nama,
            'username' => $username,
            'role' => in_array($role, ['admin', 'user']) ? $role : 'user'
        ];

        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->M_Auth->update($id_user, $updateData);

        // If current logged in user changed their own name
        if ($id_user == $this->session->userdata('id_user')) {
            $this->session->set_userdata('nama', $nama);
            $this->session->set_userdata('username', $username);
            $this->session->set_userdata('role', $updateData['role']);
        }

        $this->session->set_flashdata('success', 'Data pengguna berhasil diperbarui.');
        redirect('user');
    }

    public function hapus($id_user) {
        if ($id_user == $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
            redirect('user');
        }

        $this->M_Auth->delete($id_user);
        $this->session->set_flashdata('success', 'Pengguna berhasil dihapus.');
        redirect('user');
    }
}
