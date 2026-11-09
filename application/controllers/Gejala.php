<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gejala extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_admin();
        $this->load->model('M_Gejala');
    }

    public function index() {
        $data['user'] = $this->current_user();
        $data['title'] = 'Kelola Data Gejala';
        $data['gejala'] = $this->M_Gejala->get_all();

        $this->load->view('layouts/header', $data);
        $this->load->view('gejala/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function tambah() {
        $kode_gejala = strtoupper(trim($this->input->post('kode_gejala', TRUE)));
        $nama_gejala = trim($this->input->post('nama_gejala', TRUE));
        $keterangan = trim($this->input->post('keterangan', TRUE));

        if (empty($kode_gejala) || empty($nama_gejala)) {
            $this->session->set_flashdata('error', 'Kode gejala dan nama gejala wajib diisi.');
            redirect('gejala');
        }

        if ($this->M_Gejala->exists($kode_gejala)) {
            $this->session->set_flashdata('error', 'Kode gejala ' . $kode_gejala . ' sudah ada di sistem.');
            redirect('gejala');
        }

        $this->M_Gejala->insert([
            'kode_gejala' => $kode_gejala,
            'nama_gejala' => $nama_gejala,
            'keterangan' => $keterangan
        ]);

        $this->session->set_flashdata('success', 'Gejala baru (' . $kode_gejala . ') berhasil ditambahkan.');
        redirect('gejala');
    }

    public function edit($kode_gejala) {
        $nama_gejala = trim($this->input->post('nama_gejala', TRUE));
        $keterangan = trim($this->input->post('keterangan', TRUE));

        if (empty($nama_gejala)) {
            $this->session->set_flashdata('error', 'Nama gejala wajib diisi.');
            redirect('gejala');
        }

        $this->M_Gejala->update($kode_gejala, [
            'nama_gejala' => $nama_gejala,
            'keterangan' => $keterangan
        ]);

        $this->session->set_flashdata('success', 'Data gejala ' . $kode_gejala . ' berhasil diperbarui.');
        redirect('gejala');
    }

    public function hapus($kode_gejala) {
        // Delete will cascade or be handled cleanly
        $deleted = $this->M_Gejala->delete($kode_gejala);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Gejala ' . $kode_gejala . ' berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus gejala karena terhubung dengan rule aktif.');
        }
        redirect('gejala');
    }
}
