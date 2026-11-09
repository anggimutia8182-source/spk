<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Rule extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        $this->db->select('r.id_rule, r.kode_penyakit, p.nama_penyakit, p.saran_penanganan');
        $this->db->from('tbl_rule r');
        $this->db->join('tbl_penyakit p', 'p.kode_penyakit = r.kode_penyakit');
        $this->db->order_by('r.id_rule', 'ASC');
        $rules = $this->db->get()->result();

        foreach ($rules as $rule) {
            $this->db->select('rg.kode_gejala, g.nama_gejala, g.keterangan');
            $this->db->from('tbl_rule_gejala rg');
            $this->db->join('tbl_gejala g', 'g.kode_gejala = rg.kode_gejala');
            $this->db->where('rg.id_rule', $rule->id_rule);
            $this->db->order_by('rg.kode_gejala', 'ASC');
            $rule->gejala = $this->db->get()->result();
            $rule->gejala_codes = array_map(function($item) {
                return $item->kode_gejala;
            }, $rule->gejala);
        }

        return $rules;
    }

    public function get_by_id($id_rule) {
        $this->db->select('r.id_rule, r.kode_penyakit, p.nama_penyakit, p.saran_penanganan');
        $this->db->from('tbl_rule r');
        $this->db->join('tbl_penyakit p', 'p.kode_penyakit = r.kode_penyakit');
        $this->db->where('r.id_rule', $id_rule);
        $rule = $this->db->get()->row();

        if ($rule) {
            $this->db->select('rg.kode_gejala, g.nama_gejala, g.keterangan');
            $this->db->from('tbl_rule_gejala rg');
            $this->db->join('tbl_gejala g', 'g.kode_gejala = rg.kode_gejala');
            $this->db->where('rg.id_rule', $id_rule);
            $this->db->order_by('rg.kode_gejala', 'ASC');
            $rule->gejala = $this->db->get()->result();
            $rule->gejala_codes = array_map(function($item) {
                return $item->kode_gejala;
            }, $rule->gejala);
        }

        return $rule;
    }

    public function insert($id_rule, $kode_penyakit, $gejala_codes = []) {
        $this->db->trans_start();

        $this->db->insert('tbl_rule', [
            'id_rule' => $id_rule,
            'kode_penyakit' => $kode_penyakit
        ]);

        if (!empty($gejala_codes)) {
            foreach ($gejala_codes as $kg) {
                $this->db->insert('tbl_rule_gejala', [
                    'id_rule' => $id_rule,
                    'kode_gejala' => trim($kg)
                ]);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update($id_rule, $kode_penyakit, $gejala_codes = []) {
        $this->db->trans_start();

        $this->db->where('id_rule', $id_rule)->update('tbl_rule', [
            'kode_penyakit' => $kode_penyakit
        ]);

        $this->db->where('id_rule', $id_rule)->delete('tbl_rule_gejala');

        if (!empty($gejala_codes)) {
            foreach ($gejala_codes as $kg) {
                $this->db->insert('tbl_rule_gejala', [
                    'id_rule' => $id_rule,
                    'kode_gejala' => trim($kg)
                ]);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete($id_rule) {
        $this->db->trans_start();
        $this->db->where('id_rule', $id_rule)->delete('tbl_rule_gejala');
        $this->db->where('id_rule', $id_rule)->delete('tbl_rule');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function count() {
        return $this->db->count_all('tbl_rule');
    }

    public function get_next_id() {
        $query = $this->db->query("SELECT id_rule FROM tbl_rule ORDER BY CAST(SUBSTRING(id_rule, 2) AS UNSIGNED) DESC LIMIT 1");
        $row = $query->row();
        if ($row) {
            $num = (int)substr($row->id_rule, 1);
            return 'R' . ($num + 1);
        }
        return 'R1';
    }

    public function get_matrix_data() {
        $gejala = $this->db->order_by('kode_gejala', 'ASC')->get('tbl_gejala')->result();
        $penyakit = $this->db->order_by('kode_penyakit', 'ASC')->get('tbl_penyakit')->result();
        $rules = $this->get_all();

        // Map rule -> gejala
        $rule_gejala_map = [];
        // Map penyakit -> gejala
        $penyakit_gejala_map = [];

        foreach ($rules as $r) {
            $rule_gejala_map[$r->id_rule] = $r->gejala_codes;
            if (!isset($penyakit_gejala_map[$r->kode_penyakit])) {
                $penyakit_gejala_map[$r->kode_penyakit] = [];
            }
            $penyakit_gejala_map[$r->kode_penyakit] = array_unique(array_merge($penyakit_gejala_map[$r->kode_penyakit], $r->gejala_codes));
        }

        return [
            'gejala' => $gejala,
            'penyakit' => $penyakit,
            'rules' => $rules,
            'rule_gejala_map' => $rule_gejala_map,
            'penyakit_gejala_map' => $penyakit_gejala_map
        ];
    }
}
