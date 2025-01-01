<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autofaucet extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->load->database();
    }

    public function index() {
    	$data['settings'] = $this->settings;
        if (check_maintenance()) {
            redirect('page/maintenance');
            exit();
        }

        // Referrer ellenőrzés
        $valid_referrer = parse_url(base_url(), PHP_URL_HOST);
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $referrer_host = parse_url($referrer, PHP_URL_HOST);

        if ($referrer_host !== $valid_referrer) {
            show_error('Invalid referrer. Request not allowed.');
        }

        if (!$this->session->userdata('user_id')) {
            redirect('home');
        }

        $userId = $this->session->userdata('user_id');

        // Felhasználó adatok lekérdezése
        $this->db->where('id', $userId);
        $query = $this->db->get('users');
        $user = $query->row_array();

        // Beállítások lekérdezése az adatbázisból
        $zerosEarned = $this->db->where('name', 'autofaucet_reward')->get('settings')->row('value') ?? 0.00001000;
        $timeAuto = $this->db->where('name', 'autofaucet_interval')->get('settings')->row('value') ?? 30;
        $energyReward = $this->db->where('name', 'rewardEnergy')->get('settings')->row('value') ?? 1;
        $refReward = ($this->db->where('name', 'referral_percent')->get('settings')->row('value') ?? 10) / 100;
        $focusAuto = $this->db->where('name', 'autofocus')->get('settings')->row('value') ?? "no";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'collect') {
    // Token ellenőrzése
    if (!isset($_SESSION['auto_token']) || $_SESSION['auto_token'] !== $user['auto_token']) {
        $this->session->set_flashdata('error', "Invalid token. Autofaucet will restart.");
        $newToken = bin2hex(random_bytes(16));
        $_SESSION['auto_token'] = $newToken;

        $this->db->set('auto_token', $newToken);
        $this->db->where('id', $userId);
        $this->db->update('users');

        redirect('autofaucet');
    }

// Időintervallum ellenőrzése közvetlenül az adatbázisban
$timeAuto = (int)$this->db->where('name', 'autofaucet_interval')->get('settings')->row('value') ?? 30;

$query = $this->db->query("
    SELECT 
        IFNULL(TIMESTAMPDIFF(SECOND, last_autofaucet, NOW()), $timeAuto + 1) AS seconds_since_last
    FROM users
    WHERE id = ?", [$userId]);

$result = $query->row();
if ($result && $result->seconds_since_last < $timeAuto) {
    $remainingTime = $timeAuto - $result->seconds_since_last;
    $this->session->set_flashdata('error', "Our anti-cheat system detected unusual activity. Follow the rules to use the autofaucet fairly.");
    redirect('autofaucet');
}

    $newBalance = $user['balance'] + $zerosEarned;
    $newEnergyReward = $user['energy'] + $energyReward;

    // Referral bónusz kezelése
    $referralBonus = $zerosEarned * $refReward;
    if ($user['referred_by'] && $user['referred_by'] != 0) {
        $this->db->set('balance', 'balance + ' . $referralBonus, false);
        $this->db->where('id', $user['referred_by']);
        $this->db->update('users');

        $this->db->set('referral_earnings', 'referral_earnings + ' . $referralBonus, false);
        $this->db->where('id', $userId);
        $this->db->update('users');
    }

    // Egyenleg és energy frissítése
    $this->db->set('balance', $newBalance);
    $this->db->set('energy', $newEnergyReward);
    $this->db->set('last_autofaucet', date('Y-m-d H:i:s'));
    $this->db->where('id', $userId);
    $this->db->update('users');

    $this->session->set_flashdata('points_earned', number_format($zerosEarned, 8, '.', ''));

    redirect('autofaucet');
}



        $newToken = bin2hex(random_bytes(16));
        $_SESSION['auto_token'] = $newToken;

        $this->db->set('auto_token', $newToken);
        $this->db->where('id', $userId);
        $this->db->update('users');

        $data['user'] = $user;
        $data['balance'] = $user['balance'];
        $data['timeAuto'] = $timeAuto;
        $data['zerosEarnedFormatted'] = number_format($zerosEarned, 8, '.', '');
        $data['focusAuto'] = $focusAuto;
        $data['energyReward'] = $energyReward;

        $data['pageTitle'] = 'Autofaucet';
        $data['content'] = $this->load->view('autofaucet', $data, TRUE);  
        $this->load->view('template', $data); 
    }
}