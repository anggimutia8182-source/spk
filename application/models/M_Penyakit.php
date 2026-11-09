<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Penyakit extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->order_by('kode_penyakit', 'ASC')->get('tbl_penyakit')->result();
    }

    public function get_by_kode($kode_penyakit) {
        return $this->db->get_where('tbl_penyakit', ['kode_penyakit' => $kode_penyakit])->row();
    }

    public function insert($data) {
        return $this->db->insert('tbl_penyakit', $data);
    }

    public function update($kode_penyakit, $data) {
        return $this->db->where('kode_penyakit', $kode_penyakit)->update('tbl_penyakit', $data);
    }

    public function delete($kode_penyakit) {
        return $this->db->where('kode_penyakit', $kode_penyakit)->delete('tbl_penyakit');
    }

    public function count() {
        return $this->db->count_all('tbl_penyakit');
    }

    public function exists($kode_penyakit) {
        return $this->db->where('kode_penyakit', $kode_penyakit)->count_all_results('tbl_penyakit') > 0;
    }
}
