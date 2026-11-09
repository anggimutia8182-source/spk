<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Gejala extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->order_by('kode_gejala', 'ASC')->get('tbl_gejala')->result();
    }

    public function get_by_kode($kode_gejala) {
        return $this->db->get_where('tbl_gejala', ['kode_gejala' => $kode_gejala])->row();
    }

    public function get_by_codes($codes = []) {
        if (empty($codes)) {
            return [];
        }
        return $this->db->where_in('kode_gejala', $codes)
                        ->order_by('kode_gejala', 'ASC')
                        ->get('tbl_gejala')
                        ->result();
    }

    public function insert($data) {
        return $this->db->insert('tbl_gejala', $data);
    }

    public function update($kode_gejala, $data) {
        return $this->db->where('kode_gejala', $kode_gejala)->update('tbl_gejala', $data);
    }

    public function delete($kode_gejala) {
        return $this->db->where('kode_gejala', $kode_gejala)->delete('tbl_gejala');
    }

    public function count() {
        return $this->db->count_all('tbl_gejala');
    }

    public function exists($kode_gejala) {
        return $this->db->where('kode_gejala', $kode_gejala)->count_all_results('tbl_gejala') > 0;
    }
}
