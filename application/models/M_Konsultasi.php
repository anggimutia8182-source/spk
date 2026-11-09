<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Konsultasi extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insert($data, $gejala_codes = []) {
        $this->db->trans_start();

        $this->db->insert('tbl_konsultasi', $data);
        $id_konsultasi = $this->db->insert_id();

        if (!empty($gejala_codes)) {
            foreach ($gejala_codes as $kg) {
                $this->db->insert('tbl_konsultasi_gejala', [
                    'id_konsultasi' => $id_konsultasi,
                    'kode_gejala' => trim($kg)
                ]);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status() ? $id_konsultasi : false;
    }

    public function get_by_id($id_konsultasi) {
        $this->db->select('k.*, u.nama as user_nama, u.username, p.nama_penyakit, p.saran_penanganan');
        $this->db->from('tbl_konsultasi k');
        $this->db->join('tbl_user u', 'u.id_user = k.id_user', 'left');
        $this->db->join('tbl_penyakit p', 'p.kode_penyakit = k.hasil_diagnosa', 'left');
        $this->db->where('k.id_konsultasi', $id_konsultasi);
        $konsultasi = $this->db->get()->row();

        if ($konsultasi) {
            $this->db->select('kg.kode_gejala, g.nama_gejala, g.keterangan');
            $this->db->from('tbl_konsultasi_gejala kg');
            $this->db->join('tbl_gejala g', 'g.kode_gejala = kg.kode_gejala');
            $this->db->where('kg.id_konsultasi', $id_konsultasi);
            $this->db->order_by('kg.kode_gejala', 'ASC');
            $konsultasi->gejala = $this->db->get()->result();
            $konsultasi->gejala_codes = array_map(function($item) {
                return $item->kode_gejala;
            }, $konsultasi->gejala);
        }

        return $konsultasi;
    }

    public function get_by_user($id_user) {
        $this->db->select('k.*, p.nama_penyakit, p.saran_penanganan');
        $this->db->from('tbl_konsultasi k');
        $this->db->join('tbl_penyakit p', 'p.kode_penyakit = k.hasil_diagnosa', 'left');
        $this->db->where('k.id_user', $id_user);
        $this->db->order_by('k.tanggal', 'DESC');
        $list = $this->db->get()->result();

        foreach ($list as $item) {
            $this->db->select('kg.kode_gejala, g.nama_gejala');
            $this->db->from('tbl_konsultasi_gejala kg');
            $this->db->join('tbl_gejala g', 'g.kode_gejala = kg.kode_gejala');
            $this->db->where('kg.id_konsultasi', $item->id_konsultasi);
            $this->db->order_by('kg.kode_gejala', 'ASC');
            $item->gejala = $this->db->get()->result();
        }

        return $list;
    }

    public function get_all($filters = []) {
        $this->db->select('k.*, u.nama as user_nama, u.username, p.nama_penyakit');
        $this->db->from('tbl_konsultasi k');
        $this->db->join('tbl_user u', 'u.id_user = k.id_user', 'left');
        $this->db->join('tbl_penyakit p', 'p.kode_penyakit = k.hasil_diagnosa', 'left');

        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(k.tanggal) >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(k.tanggal) <=', $filters['end_date']);
        }
        if (!empty($filters['kode_penyakit'])) {
            if ($filters['kode_penyakit'] === 'NULL') {
                $this->db->where('k.hasil_diagnosa IS NULL', null, false);
            } else {
                $this->db->where('k.hasil_diagnosa', $filters['kode_penyakit']);
            }
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('k.nama_pasien', $filters['search']);
            $this->db->or_like('k.no_rm', $filters['search']);
            $this->db->or_like('u.nama', $filters['search']);
            $this->db->group_end();
        }

        $this->db->order_by('k.tanggal', 'DESC');
        $list = $this->db->get()->result();

        foreach ($list as $item) {
            $this->db->select('kg.kode_gejala, g.nama_gejala');
            $this->db->from('tbl_konsultasi_gejala kg');
            $this->db->join('tbl_gejala g', 'g.kode_gejala = kg.kode_gejala');
            $this->db->where('kg.id_konsultasi', $item->id_konsultasi);
            $this->db->order_by('kg.kode_gejala', 'ASC');
            $item->gejala = $this->db->get()->result();
        }

        return $list;
    }

    public function delete($id_konsultasi) {
        $this->db->trans_start();
        $this->db->where('id_konsultasi', $id_konsultasi)->delete('tbl_konsultasi_gejala');
        $this->db->where('id_konsultasi', $id_konsultasi)->delete('tbl_konsultasi');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function generate_no_rm() {
        $query = $this->db->query("SELECT no_rm FROM tbl_konsultasi WHERE no_rm LIKE 'RM-%' ORDER BY id_konsultasi DESC LIMIT 1");
        $row = $query->row();
        if ($row && preg_match('/RM-(\d+)/', $row->no_rm, $m)) {
            $next = ((int)$m[1]) + 1;
            return 'RM-' . str_pad($next, 4, '0', STR_PAD_LEFT);
        }
        return 'RM-0001';
    }

    public function get_statistics() {
        $total_konsultasi = $this->db->count_all('tbl_konsultasi');
        
        $terdiagnosa = $this->db->where('hasil_diagnosa IS NOT NULL', null, false)
                                ->count_all_results('tbl_konsultasi');
        
        $tidak_terindikasi = $this->db->where('hasil_diagnosa IS NULL', null, false)
                                      ->count_all_results('tbl_konsultasi');

        // Distribution by disease
        $query = $this->db->query("
            SELECT p.kode_penyakit, p.nama_penyakit, COUNT(k.id_konsultasi) as total
            FROM tbl_penyakit p
            LEFT JOIN tbl_konsultasi k ON k.hasil_diagnosa = p.kode_penyakit
            GROUP BY p.kode_penyakit, p.nama_penyakit
            ORDER BY p.kode_penyakit ASC
        ");
        $penyakit_stats = $query->result();

        return [
            'total' => $total_konsultasi,
            'terdiagnosa' => $terdiagnosa,
            'tidak_terindikasi' => $tidak_terindikasi,
            'penyakit_stats' => $penyakit_stats
        ];
    }
}
