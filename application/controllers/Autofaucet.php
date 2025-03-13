<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autofaucet extends Member_Controller {

    public function __construct() {
        parent::__construct();
    }

    private function generateToken($userId) {
        $newToken = bin2hex(random_bytes(16));
        $_SESSION['auto_token'] = $newToken;
        $this->db->set('auto_token', $newToken)->where('id', $userId)->update('users');
        return $newToken;
    }

    public function index() {
        $userId = $this->currentUser['id'];

        // Referrer ellenőrzés
        $valid_referrer = parse_url(base_url(), PHP_URL_HOST);
        $referrer_host = parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_HOST);

        if ($referrer_host !== $valid_referrer) {
            show_error('Invalid referrer. Request not allowed.');
        }

        // Felhasználó adatok lekérdezése
        $user = $this->db->where('id', $userId)->get('users')->row_array();

        // Beállítások közvetlen elérése a settings tömbből
        $zerosEarned = $this->settings['autofaucet_reward'] ?? 0.00001000;
        $timeAuto = $this->settings['autofaucet_interval'] ?? 30;
        $energyReward = $this->settings['rewardEnergy'] ?? 1;
        $refReward = ($this->settings['referral_percent'] ?? 10) / 100;
        $focusAuto = $this->settings['autofocus'] ?? "no";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'collect') {
            // Token ellenőrzése
            if ($_SESSION['auto_token'] !== $user['auto_token']) {
                $this->session->set_flashdata('error', "Invalid token. Autofaucet will restart.");
                $this->generateToken($userId);
                redirect('autofaucet');
            }

            // Időintervallum ellenőrzése
            $query = $this->db->query("
                SELECT IFNULL(TIMESTAMPDIFF(SECOND, last_autofaucet, NOW()), ?) AS seconds_since_last
                FROM users WHERE id = ?", [$timeAuto + 1, $userId]);

            $result = $query->row();
            if ($result && $result->seconds_since_last < $timeAuto) {
                $remainingTime = $timeAuto - $result->seconds_since_last;
                $this->session->set_flashdata('error', "Our anti-cheat system detected unusual activity. Follow the rules to use the autofaucet fairly.");
                redirect('autofaucet');
            }

            // Egyenleg és energia frissítése
            $newBalance = $user['balance'] + $zerosEarned;
            $newEnergyReward = $user['energy'] + $energyReward;

            // Referral bónusz kezelése
            if ($user['referred_by'] && $user['referred_by'] != 0) {
                $referralBonus = $zerosEarned * $refReward;
                $this->db->set('balance', 'balance + ' . $referralBonus, false)
                         ->set('referral_earnings', 'referral_earnings + ' . $referralBonus, false)
                         ->where('id', $user['referred_by'])
                         ->update('users');
            }

            // Felhasználó frissítése
            $this->db->set([
                'balance' => $newBalance,
                'energy' => $newEnergyReward,
                'last_autofaucet' => date('Y-m-d H:i:s')
            ])->where('id', $userId)->update('users');

            $this->session->set_flashdata('points_earned', number_format($zerosEarned, 8, '.', ''));
            redirect('autofaucet');
        }

        // Token generálása, ha nem létezik
        if (!isset($_SESSION['auto_token'])) {
            $this->generateToken($userId);
        }

        $data = [
            'user' => $user,
            'balance' => $user['balance'],
            'timeAuto' => $timeAuto,
            'zerosEarnedFormatted' => number_format($zerosEarned, 8, '.', ''),
            'focusAuto' => $focusAuto,
            'energyReward' => $energyReward,
            'pageTitle' => 'Autofaucet'
        ];

        $this->_load_view('autofaucet', $data);
    }
}
