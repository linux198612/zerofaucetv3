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

public function updateUserBalance($userId, $reward) {
    // Settings lekérdezése az adatbázisból
    $this->db->select('value');
    $this->db->where('name', 'currency_value');
    $query = $this->db->get('settings');
    $result = $query->row_array();

    if (!$result) {
        log_message('error', 'Currency value not found in settings.');
        return false; // Ha nem található az érték, visszatérünk
    }

    $zeroRate = (float) $result['value']; // Lekérdezett árfolyam konvertálása float típusra

    // Konverzió kiszámítása (USD -> Zero)
    $usdValue = $reward / 1000 * 0.01; // Példa: 1000 credit = 0.01 USD
    $zeroValue = $usdValue / $zeroRate; // USD -> Zero átváltás

    // Adatbázis frissítése (balance mező növelése)
    $this->db->set('balance', 'balance + ' . $zeroValue, false);
    $this->db->where('id', $userId);
    $this->db->update('users');
    return true; // Sikeres frissítés esetén visszatérünk
}


    // Felhasználó egyenlegének csökkentése
    public function reduceUserBalance($userId, $reward) {
        $this->db->set('credits', 'credits - ' . $reward, false);
        $this->db->where('id', $userId);
        $this->db->update('users');
    }
}
