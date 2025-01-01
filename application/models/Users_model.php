<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function update_balance($userId, $amount) {
        if (!$userId) {
            return false; // Ha nincs userId, ne próbáljuk frissíteni
        }
        $this->db->set('balance', 'balance + ' . $amount, FALSE);
        $this->db->where('id', $userId);
        $this->db->update('users');
    }

		public function update_last_claim($userId, $datetime) {
    $this->db->where('id', $userId);
    return $this->db->update('users', ['last_claim' => $datetime]);
}
}