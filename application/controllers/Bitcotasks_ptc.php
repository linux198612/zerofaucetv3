<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bitcotasks_ptc extends My_Controller {

    public function __construct() {
        parent::__construct();
    }

public function index() {
    // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
    if (!$this->is_logged_in()) {
        show_error("A felhasználó nincs bejelentkezve.");
    }

    // A PTC API kulcsok és Bearer token
    $apiKey = $this->settings['bitcotasks_api']; // Cseréld ki a saját API kulcsodra
    $bearerToken = $this->settings['bitcotasks_bearer_token']; // Cseréld ki a saját Bearer tokenedre
    $userIp = $_SERVER['REMOTE_ADDR']; // Felhasználó IP címének lekérése
    $userId = $this->currentUser['id']; // A bejelentkezett felhasználó ID-ja

    // Lekérjük a kampányokat az API-tól
    $campaignsData = $this->get_ptc_campaigns($apiKey, $userId, $userIp, $bearerToken);

    // Ellenőrizzük, hogy kaptunk-e választ
    if ($campaignsData && $campaignsData['status'] == '200') {
        $campaigns = $campaignsData['data'];
        $totalCampaigns = count($campaigns);
        $totalRewards = array_sum(array_column($campaigns, 'reward'));

        $data = [
            'campaigns' => $campaigns,
            'totalCampaigns' => $totalCampaigns,
            'totalRewards' => $totalRewards
        ];
        $data['pageTitle'] = 'Bitcotasks PTC';

        $this->render('bitcotasks_ptc', $data); // A kampányok megjelenítése
    } else {
        show_error("Hiba történt a PTC kampányok lekérésekor.");
    }
}

}
