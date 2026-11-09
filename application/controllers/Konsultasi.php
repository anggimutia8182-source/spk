<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konsultasi extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_login();
        $this->load->model('M_Gejala');
        $this->load->model('M_Penyakit');
        $this->load->model('M_Rule');
        $this->load->model('M_Konsultasi');
        $this->load->model('M_Inference');
    }

    public function index() {
        $user = $this->current_user();
        $data['user'] = $user;
        $data['title'] = 'Konsultasi Diagnosa Gangguan Tidur';
        $data['no_rm'] = $this->M_Konsultasi->generate_no_rm();
        $data['gejala'] = $this->M_Gejala->get_all();

        $this->load->view('layouts/header', $data);
        $this->load->view('konsultasi/form', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function proses() {
        $user = $this->current_user();
        
        $no_rm = $this->input->post('no_rm', TRUE) ?: $this->M_Konsultasi->generate_no_rm();
        $nama_pasien = trim($this->input->post('nama_pasien', TRUE)) ?: $user->nama;
        $usia = (int)$this->input->post('usia');
        $jenis_kelamin = $this->input->post('jenis_kelamin', TRUE);
        $keluhan = trim($this->input->post('keluhan', TRUE));
        $selected_gejala = $this->input->post('gejala'); // array of codes

        if (empty($selected_gejala) || !is_array($selected_gejala)) {
            $this->session->set_flashdata('error', 'Silakan pilih minimal 1 gejala yang sedang Anda alami.');
            redirect('konsultasi');
        }

        // Run Forward Chaining inference
        $result = $this->M_Inference->forward_chaining($selected_gejala);

        // Resulting disease code or NULL
        $hasil_diagnosa = null;
        if (!empty($result['primary_diagnosa'])) {
            $hasil_diagnosa = $result['primary_diagnosa']->kode_penyakit;
        }

        // Insert consultation record
        $consultData = [
            'id_user' => $user->id_user,
            'no_rm' => $no_rm,
            'nama_pasien' => $nama_pasien,
            'usia' => $usia ?: null,
            'jenis_kelamin' => $jenis_kelamin ?: null,
            'keluhan' => $keluhan ?: null,
            'tanggal' => date('Y-m-d H:i:s'),
            'hasil_diagnosa' => $hasil_diagnosa
        ];

        $id_konsultasi = $this->M_Konsultasi->insert($consultData, $selected_gejala);

        if ($id_konsultasi) {
            $this->session->set_flashdata('success', 'Konsultasi berhasil diproses dengan metode Forward Chaining.');
            redirect('konsultasi/hasil/' . $id_konsultasi);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan hasil konsultasi. Silakan ulangi.');
            redirect('konsultasi');
        }
    }

    public function hasil($id_konsultasi) {
        $user = $this->current_user();
        $konsultasi = $this->M_Konsultasi->get_by_id($id_konsultasi);

        if (!$konsultasi) {
            show_404();
        }

        // Security check: only the owner or an admin can view
        if ($user->role !== 'admin' && $konsultasi->id_user != $user->id_user) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke konsultasi ini.');
            redirect('dashboard');
        }

        // Run inference on the recorded symptoms for complete trace display
        $inference = $this->M_Inference->forward_chaining($konsultasi->gejala_codes);

        $data['user'] = $user;
        $data['title'] = 'Hasil Diagnosa - ' . $konsultasi->no_rm;
        $data['konsultasi'] = $konsultasi;
        $data['inference'] = $inference;

        $this->load->view('layouts/header', $data);
        $this->load->view('konsultasi/hasil', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function riwayat() {
        $user = $this->current_user();
        $data['user'] = $user;
        $data['title'] = 'Riwayat Konsultasi Pasien';
        $data['konsultasi'] = $this->M_Konsultasi->get_by_user($user->id_user);

        $this->load->view('layouts/header', $data);
        $this->load->view('konsultasi/riwayat', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function cetak($id_konsultasi) {
        $user = $this->current_user();
        $konsultasi = $this->M_Konsultasi->get_by_id($id_konsultasi);

        if (!$konsultasi) {
            show_404();
        }

        if ($user->role !== 'admin' && $konsultasi->id_user != $user->id_user) {
            $this->session->set_flashdata('error', 'Akses ditolak.');
            redirect('dashboard');
        }

        $inference = $this->M_Inference->forward_chaining($konsultasi->gejala_codes);

        $data['user'] = $user;
        $data['title'] = 'Cetak Hasil Diagnosa - ' . $konsultasi->no_rm;
        $data['konsultasi'] = $konsultasi;
        $data['inference'] = $inference;

        $this->load->view('konsultasi/cetak', $data);
    }
}
