<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rule extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_admin();
        $this->load->model('M_Rule');
        $this->load->model('M_Gejala');
        $this->load->model('M_Penyakit');
        $this->load->model('M_Inference');
    }

    public function index() {
        $data['user'] = $this->current_user();
        $data['title'] = 'Basis Pengetahuan (Rule Base)';
        $data['rules'] = $this->M_Rule->get_all();
        $data['penyakit'] = $this->M_Penyakit->get_all();
        $data['gejala'] = $this->M_Gejala->get_all();
        $data['next_rule_id'] = $this->M_Rule->get_next_id();

        $this->load->view('layouts/header', $data);
        $this->load->view('rule/index', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function tambah() {
        $id_rule = strtoupper(trim($this->input->post('id_rule', TRUE))) ?: $this->M_Rule->get_next_id();
        $kode_penyakit = $this->input->post('kode_penyakit', TRUE);
        $gejala_codes = $this->input->post('gejala');

        if (empty($id_rule) || empty($kode_penyakit)) {
            $this->session->set_flashdata('error', 'Kode rule dan penyakit wajib dipilih.');
            redirect('rule');
        }

        if (empty($gejala_codes) || !is_array($gejala_codes)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu gejala untuk menyusun premis kondisi IF rule.');
            redirect('rule');
        }

        $existing = $this->M_Rule->get_by_id($id_rule);
        if ($existing) {
            $this->session->set_flashdata('error', 'Kode rule ' . $id_rule . ' sudah ada di basis pengetahuan.');
            redirect('rule');
        }

        $this->M_Rule->insert($id_rule, $kode_penyakit, $gejala_codes);
        $this->session->set_flashdata('success', 'Rule ' . $id_rule . ' berhasil ditambahkan ke basis pengetahuan.');
        redirect('rule');
    }

    public function edit($id_rule) {
        $kode_penyakit = $this->input->post('kode_penyakit', TRUE);
        $gejala_codes = $this->input->post('gejala');

        if (empty($kode_penyakit) || empty($gejala_codes) || !is_array($gejala_codes)) {
            $this->session->set_flashdata('error', 'Penyakit dan daftar gejala premis tidak boleh kosong.');
            redirect('rule');
        }

        $this->M_Rule->update($id_rule, $kode_penyakit, $gejala_codes);
        $this->session->set_flashdata('success', 'Rule ' . $id_rule . ' berhasil diperbarui.');
        redirect('rule');
    }

    public function hapus($id_rule) {
        $this->M_Rule->delete($id_rule);
        $this->session->set_flashdata('success', 'Rule ' . $id_rule . ' berhasil dihapus dari sistem.');
        redirect('rule');
    }

    public function matriks() {
        $data['user'] = $this->current_user();
        $data['title'] = 'Matriks Rule-Gejala & Tabel Keputusan';
        $data['matrix_data'] = $this->M_Rule->get_matrix_data();

        $this->load->view('layouts/header', $data);
        $this->load->view('rule/matriks', $data);
        $this->load->view('layouts/footer', $data);
    }

    public function simulasi() {
        $data['user'] = $this->current_user();
        $data['title'] = 'Simulasi Forward Chaining (Uji Pakar)';
        $data['gejala'] = $this->M_Gejala->get_all();
        $data['rules'] = $this->M_Rule->get_all();

        $selected = $this->input->post('gejala');
        $data['selected_gejala'] = is_array($selected) ? $selected : [];
        $data['inference'] = null;

        if ($this->input->method() === 'post') {
            $data['inference'] = $this->M_Inference->forward_chaining($data['selected_gejala']);
        }

        $this->load->view('layouts/header', $data);
        $this->load->view('rule/simulasi', $data);
        $this->load->view('layouts/footer', $data);
    }
}
