<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offerwalls_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Transaction lekérése az új táblából
    public function getTransaction($transactionId, $offerwallName) {
        return $this->db->get_where('offerwall_history', [
            'trans_id' => $transactionId,
            'offerwall' => $offerwallName
        ])->row_array();
    }

    // Új tranzakció beszúrása
    public function insertTransaction($userId, $offerwall, $userIp, $reward, $transactionId, $status, $availableAt) {
        $data = [
            'user_id' => $userId,
            'offerwall' => $offerwall,
            'ip_address' => $userIp,
            'amount' => $reward,
            'trans_id' => $transactionId,
            'status' => $status,
            'available_at' => $availableAt,
            'claim_time' => time()
        ];
        $this->db->insert('offerwall_history', $data);
        return $this->db->insert_id();
    }

    // Felhasználó egyenlegének frissítése
    public function updateUserBalance($userId, $reward) {
        $this->db->set('credits', 'credits + ' . $reward, false);
        $this->db->where('id', $userId);
        $this->db->update('users');
    }

    // Felhasználó egyenlegének csökkentése
    public function reduceUserBalance($userId, $reward) {
        $this->db->set('credits', 'credits - ' . $reward, false);
        $this->db->where('id', $userId);
        $this->db->update('users');
    }
}
