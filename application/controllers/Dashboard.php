<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_login();
        $this->load->model('M_Gejala');
        $this->load->model('M_Penyakit');
        $this->load->model('M_Rule');
        $this->load->model('M_Konsultasi');
        $this->load->model('M_Auth');
    }

    public function index() {
        $user = $this->current_user();
        $data['user'] = $user;

        if ($user->role === 'admin') {
            $data['title'] = 'Dashboard Pakar - SPK Gangguan Tidur';
            $data['total_gejala'] = $this->M_Gejala->count();
            $data['total_penyakit'] = $this->M_Penyakit->count();
            $data['total_rule'] = $this->M_Rule->count();
            $data['total_user'] = count($this->M_Auth->get_all());
            $data['stats'] = $this->M_Konsultasi->get_statistics();
            $data['recent_konsultasi'] = array_slice($this->M_Konsultasi->get_all(), 0, 5);

            $this->load->view('layouts/header', $data);
            $this->load->view('dashboard/admin', $data);
            $this->load->view('layouts/footer', $data);
        } else {
            $data['title'] = 'Dashboard Pasien - SPK Gangguan Tidur';
            $data['recent_konsultasi'] = array_slice($this->M_Konsultasi->get_by_user($user->id_user), 0, 5);
            $data['total_konsultasi'] = count($this->M_Konsultasi->get_by_user($user->id_user));
            $data['penyakit_list'] = $this->M_Penyakit->get_all();

            $this->load->view('layouts/header', $data);
            $this->load->view('dashboard/user', $data);
            $this->load->view('layouts/footer', $data);
        }
    }
}
