<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions_model extends CI_Model {

    public function get_claim_count_today($userId) {
        if (!$userId) {
            return 0; // Ha nincs userId, nincs lekérés
        }
        $this->db->where('userid', $userId);
        $this->db->where('type', 'Faucet');
        $this->db->where('DATE(FROM_UNIXTIME(timestamp)) = CURDATE()', null, false);
        return $this->db->count_all_results('transactions');
    }

    public function record_transaction($userId, $type, $amount) {
        if (!$userId) {
            return false; // Ha nincs userId, ne próbáljuk beszúrni
        }
        $data = [
            'userid' => $userId,
            'type' => $type,
            'amount' => $amount,
            'timestamp' => time() // UNIX timestamp rögzítése
        ];
        $this->db->insert('transactions', $data);
    }

    public function record_referral_commission($userId, $amount) {
        if (!$userId) {
            return false; // Ha nincs userId, ne próbáljuk beszúrni
        }
        $data = [
            'userid' => $userId,
            'amount' => $amount,
            'timestamp' => time()
        ];
        $this->db->insert('referralearn', $data);
    }
    
    public function get_last_claim_time($userId) {
        $this->db->select('timestamp');
        $this->db->where('userid', $userId);
        $this->db->order_by('timestamp', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('transactions');

        if ($query->num_rows() > 0) {
            return $query->row()->timestamp; // UNIX timestamp közvetlen visszaadása
        } else {
            return null; // Ha nincs rekord, null-t adunk
        }
    }
    
    public function get_last_faucet_transaction($userId) {
        if (!$userId) {
            return null; // Ha nincs userId, ne keressünk
        }

        $this->db->select('timestamp');
        $this->db->where('userid', $userId);
        $this->db->where('type', 'Faucet');
        $this->db->order_by('timestamp', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get('transactions');

        if ($query->num_rows() > 0) {
            return $query->row()->timestamp; // Csak a timestamp mező visszaadása
        } else {
            return null;
        }
    }
}
