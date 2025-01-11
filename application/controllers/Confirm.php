<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Confirm extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Offerwalls_model');
        // Session könyvtár betöltése
        $this->load->library('session');
    }

public function bitcotasks() {
    $secret = $this->settings['bitcotasks_secret']; // Update your secret key from BitcoTasks dashboard
    $minHold = 10000; // Minimum amount for manual approval

    $userId = isset($_REQUEST['subId']) ? $this->db->escape_str($_REQUEST['subId']) : null;
    $transactionId = isset($_REQUEST['transId']) ? $this->db->escape_str($_REQUEST['transId']) : null;
    $reward = isset($_REQUEST['reward']) ? $this->db->escape_str($_REQUEST['reward']) : null;
    $userIp = isset($_REQUEST['userIp']) ? $this->db->escape_str($_REQUEST['userIp']) : "0.0.0.0";
    $signature = isset($_REQUEST['signature']) ? $this->db->escape_str($_REQUEST['signature']) : null;

    // Ellenőrzés: Aláírás hitelesítése
    if (md5($userId . $transactionId . $reward . $secret) != $signature) {
        echo "ERROR: Signature doesn't match";
        return;
    }

    // Duplikált tranzakció ellenőrzése
    $trans = $this->Offerwalls_model->getTransaction($transactionId, 'bitcotasks');
    if ($trans) {
        echo "DUP";
        return;
    }

    // Jutalom feldolgozása
    if ($reward >= $minHold) {
        // 10 centnél nagyobb tranzakció manuális jóváhagyásra kerül
        $this->Offerwalls_model->insertTransaction($userId, 'BitcoTasks', $userIp, $reward, $transactionId, 'Pending', time());
        echo "PENDING"; // Jelzi, hogy jóváhagyásra vár
    } else {
        // 10 centnél kisebb tranzakció automatikusan jóváírásra kerül
        $this->Offerwalls_model->insertTransaction($userId, 'BitcoTasks', $userIp, $reward, $transactionId, 'Paid', time());
        $this->Offerwalls_model->updateUserBalance($userId, $reward);
        echo "ok";
    }
}

public function zerads() {
    $password = $this->settings['zerads_password']; // ZerAds API jelszó

    // Paraméterek lekérése
    $pwd = $this->input->get('pwd');
    $user = (int)$this->input->get('user');  // biztosítjuk, hogy szám legyen
    $amount = $this->input->get('amount');

    // Jelszó hitelesítés
    if ($pwd !== $password) {
        echo "ERROR: Invalid password";
        return;
    }

    if (empty($user) || empty($amount)) {
        echo "ERROR: Missing user or amount";
        return;
    }

    // Ellenőrizzük, hogy létezik-e a felhasználó
    $query = $this->db->get_where('users', array('id' => $user));
    if ($query->num_rows() == 0) {
        echo "ERROR: User not found";
        return;
    }

    // Reward kiszámítása (float típusúra konvertáljuk)
    $reward = (float)$amount * $this->settings['zerads_exchange_value'];

    // Felhasználó egyenlegének frissítése
    $this->db->set('credits', 'credits + ' . $reward, FALSE);
    $this->db->where('id', $user);
    $update = $this->db->update('users');

    if ($update) {
        echo "ok"; // Visszajelzés sikeres frissítésről
    } else {
        echo "ERROR: Failed to update user balance";
    }
}



}