<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faucet extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transactions_model');
        $this->load->model('Users_model');
    }

public function index() {
    $data['settings'] = $this->settings;
    $userId = $this->session->userdata('user_id');

    // Felhasználó adatok lekérdezése
    $this->db->where('id', $userId);
    $query = $this->db->get('users');
    $user = $query->row_array();

    // Faucet alapbeállítások
    $timer = $this->settings['faucet_timer'] ?? 0;
    $dailyLimit = $this->settings['faucet_daily_limit'] ?? 0;
    $hCaptchaSecKey = $this->settings['hcaptcha_sec_key'] ?? '';
    $hCaptchaPubKey = $this->settings['hcaptcha_pub_key'] ?? '';
    $claimStatus = $this->settings['faucet_status'] ?? '';

    // Az aktuális idő
    $currentTimestamp = time();

    // **POST kérés kezelése**
    if ($this->input->post()) {
        if (!$userId) {
            $this->session->set_flashdata('alert', 'danger|You must be logged in to claim.');
            redirect('faucet');
        }

        // Az utolsó tranzakció és a napi claim-ek száma
        $lastClaimTimestamp = $this->Transactions_model->get_last_faucet_transaction($userId) ?? 0;
        $claimCountToday = $this->Transactions_model->get_claim_count_today($userId);

        // Timer ellenőrzése
        $timeSinceLastClaim = $currentTimestamp - $lastClaimTimestamp;
        $wait = max(0, $timer - $timeSinceLastClaim);

        if ($wait > 0) {
            $this->session->set_flashdata('alert', 'danger|You must wait ' . $wait . ' seconds before claiming again.');
            redirect('faucet');
        }

        // Napi limit ellenőrzése
        if ($claimCountToday >= $dailyLimit) {
            $this->session->set_flashdata('alert', 'danger|You have reached your daily claim limit.');
            redirect('faucet');
        }

        // hCaptcha ellenőrzés
        $captchaResponse = $this->input->post('h-captcha-response');
        $captchaVerified = $this->_verify_hcaptcha($captchaResponse, $hCaptchaSecKey);

        if (!$captchaVerified) {
            $this->session->set_flashdata('alert', 'danger|Captcha is incorrect. Try again.');
            redirect('faucet');
        }

        // **Sikeres claim feldolgozása**
        $reward = ($this->settings['faucet_reward'] ?? 0);
        $this->Transactions_model->record_transaction($userId, 'Faucet', $reward);
        $this->Users_model->update_balance($userId, $reward);
        $this->_handle_referral($user, $reward);

        // Sikeres claim után új üzenet
        $this->session->set_flashdata('alert', 'success|Successfully claimed ' . $reward . ' ZER.');
        redirect('faucet');
    }

    // **Friss adatok a POST után**
    $claimCountToday = $this->Transactions_model->get_claim_count_today($userId);
    $lastClaimTimestamp = $this->Transactions_model->get_last_faucet_transaction($userId) ?? 0;

    // Számítsuk ki a várakozási időt
    $timeSinceLastClaim = $currentTimestamp - $lastClaimTimestamp;
    $wait = max(0, $timer - $timeSinceLastClaim);


    // Ellenőrizzük a napi limitet
    $limitReached = ($claimCountToday >= $dailyLimit);

    // Adatok a nézethez
    $data['reward'] = ($this->settings['faucet_reward'] ?? 0);
    $data['wait'] = $limitReached ? 0 : $wait; // Ha elérte a limitet, ne legyen visszaszámláló
    $data['dailyLimit'] = $dailyLimit;
    $data['claimCountToday'] = $claimCountToday;
    $data['lastClaim'] = $lastClaimTimestamp;
    $data['currentTimestamp'] = $currentTimestamp;
    $data['hCaptchaPubKey'] = $hCaptchaPubKey;
    $data['limitReached'] = $limitReached;
    
    $data['pageTitle'] = 'Faucet';
    // Nézet megjelenítése
    $this->render('faucet', $data);
}


    private function _verify_hcaptcha($captchaResponse, $secretKey) {
        $url = "https://hcaptcha.com/siteverify";
        $data = [
            'secret' => $secretKey,
            'response' => $captchaResponse
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result, true);

        return $resultJson['success'] ?? false;
    }

    private function _handle_referral($user, $payOutBTC) {
        $referralPercent = $this->settings['referral_percent'] ?? 0;

        if ($referralPercent >= 1 && !empty($user['referred_by'])) {
            $referralCommission = floor(($referralPercent / 100) * $payOutBTC);
            $this->Users_model->update_balance($user['referred_by'], $referralCommission);
            $this->Transactions_model->record_referral_commission($user['referred_by'], $referralCommission);
        }
    }
}


