<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_Gejala');
        $this->load->model('M_Penyakit');
        $this->load->model('M_Rule');
    }

    public function index() {
        $data['penyakit'] = $this->M_Penyakit->get_all();
        $data['total_gejala'] = $this->M_Gejala->count();
        $data['total_penyakit'] = $this->M_Penyakit->count();
        $data['total_rule'] = $this->M_Rule->count();
        $data['user'] = $this->current_user();
        $data['title'] = 'Sistem Pakar Identifikasi Gangguan Tidur - Forward Chaining';

        $this->load->view('layouts/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('layouts/footer', $data);
    }
}
