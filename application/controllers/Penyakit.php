<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyakit extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_admin();
        $this->load->model('M_Penyakit');
    }

    public function index() {
        $data['user'] = $this->current_user();
        $data['title'] = 'Kelola Data Penyakit / Gangguan Tidur';
        $data['penyakit'] = $this->M_Penyakit->get_all();

        $this->load->view('layouts/header', $data);
        $this->load->view('penyakit/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function tambah() {
        $kode_penyakit = strtoupper(trim($this->input->post('kode_penyakit', TRUE)));
        $nama_penyakit = trim($this->input->post('nama_penyakit', TRUE));
        $saran_penanganan = trim($this->input->post('saran_penanganan', TRUE));

        if (empty($kode_penyakit) || empty($nama_penyakit)) {
            $this->session->set_flashdata('error', 'Kode dan nama penyakit wajib diisi.');
            redirect('penyakit');
        }

        if ($this->M_Penyakit->exists($kode_penyakit)) {
            $this->session->set_flashdata('error', 'Kode penyakit ' . $kode_penyakit . ' sudah terdaftar.');
            redirect('penyakit');
        }

        $this->M_Penyakit->insert([
            'kode_penyakit' => $kode_penyakit,
            'nama_penyakit' => $nama_penyakit,
            'saran_penanganan' => $saran_penanganan
        ]);

        $this->session->set_flashdata('success', 'Data gangguan tidur (' . $kode_penyakit . ') berhasil ditambahkan.');
        redirect('penyakit');
    }

    public function edit($kode_penyakit) {
        $nama_penyakit = trim($this->input->post('nama_penyakit', TRUE));
        $saran_penanganan = trim($this->input->post('saran_penanganan', TRUE));

        if (empty($nama_penyakit)) {
            $this->session->set_flashdata('error', 'Nama penyakit wajib diisi.');
            redirect('penyakit');
        }

        $this->M_Penyakit->update($kode_penyakit, [
            'nama_penyakit' => $nama_penyakit,
            'saran_penanganan' => $saran_penanganan
        ]);

        $this->session->set_flashdata('success', 'Data penyakit ' . $kode_penyakit . ' berhasil diperbarui.');
        redirect('penyakit');
    }

    public function hapus($kode_penyakit) {
        $deleted = $this->M_Penyakit->delete($kode_penyakit);
        if ($deleted) {
            $this->session->set_flashdata('success', 'Penyakit ' . $kode_penyakit . ' berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus penyakit.');
        }
        redirect('penyakit');
    }
}
