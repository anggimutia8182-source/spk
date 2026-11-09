<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Auth extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_by_username($username) {
        return $this->db->get_where('tbl_user', ['username' => $username])->row();
    }

    public function get_by_id($id_user) {
        return $this->db->get_where('tbl_user', ['id_user' => $id_user])->row();
    }

    public function get_all() {
        return $this->db->order_by('id_user', 'ASC')->get('tbl_user')->result();
    }

    public function insert($data) {
        return $this->db->insert('tbl_user', $data);
    }

    public function update($id_user, $data) {
        return $this->db->where('id_user', $id_user)->update('tbl_user', $data);
    }

    public function delete($id_user) {
        return $this->db->where('id_user', $id_user)->delete('tbl_user');
    }

    public function username_exists($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('id_user !=', $exclude_id);
        }
        return $this->db->count_all_results('tbl_user') > 0;
    }
}
