<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_admin();
        $this->load->model('M_Konsultasi');
        $this->load->model('M_Penyakit');
        $this->load->model('M_Gejala');
    }

    public function index() {
        $filters = [
            'start_date' => $this->input->get('start_date', TRUE),
            'end_date' => $this->input->get('end_date', TRUE),
            'kode_penyakit' => $this->input->get('kode_penyakit', TRUE),
            'search' => $this->input->get('search', TRUE)
        ];

        $data['user'] = $this->current_user();
        $data['title'] = 'Laporan Riwayat Konsultasi & Diagnosa';
        $data['filters'] = $filters;
        $data['penyakit'] = $this->M_Penyakit->get_all();
        $data['konsultasi'] = $this->M_Konsultasi->get_all($filters);

        $this->load->view('layouts/header', $data);
        $this->load->view('laporan/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function cetak() {
        $filters = [
            'start_date' => $this->input->get('start_date', TRUE),
            'end_date' => $this->input->get('end_date', TRUE),
            'kode_penyakit' => $this->input->get('kode_penyakit', TRUE),
            'search' => $this->input->get('search', TRUE)
        ];

        $data['user'] = $this->current_user();
        $data['title'] = 'Cetak Laporan Rekapitulasi Konsultasi';
        $data['filters'] = $filters;
        $data['konsultasi'] = $this->M_Konsultasi->get_all($filters);
        $data['stats'] = $this->M_Konsultasi->get_statistics();

        $this->load->view('laporan/cetak', $data);
    }

    public function hapus($id_konsultasi) {
        $this->M_Konsultasi->delete($id_konsultasi);
        $this->session->set_flashdata('success', 'Data konsultasi berhasil dihapus.');
        redirect('laporan');
    }
}
