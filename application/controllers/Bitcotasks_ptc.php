<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bitcotasks_ptc extends Member_Controller {

    public function __construct() {
        parent::__construct();
    }

	    // API kérések kezelése
    protected function requestWithCurl($url, $token) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token"
        ]);
        $response = curl_exec($ch);
        if(curl_errno($ch)) {
            $response = false;
        }
        curl_close($ch);
        return $response;
    }

    // PTC kampányadatok lekérése
    protected function get_ptc_campaigns($apiKey, $userId, $userIp, $bearerToken) {
        $url = "https://bitcotasks.com/api/$apiKey/$userId/$userIp";
        $response = $this->requestWithCurl($url, $bearerToken);
        if ($response) {
            return json_decode($response, true);
        }
        return ['status' => 500, 'message' => 'Failed to connect to API.'];
    }

public function index() {

    // A PTC API kulcsok és Bearer token
    $apiKey = $this->settings['bitcotasks_api']; // Cseréld ki a saját API kulcsodra
    $bearerToken = $this->settings['bitcotasks_bearer_token']; // Cseréld ki a saját Bearer tokenedre
    $userIp = $_SERVER['REMOTE_ADDR']; // Felhasználó IP címének lekérése
    $userId = $this->currentUser['id'];

    // Lekérjük a kampányokat az API-tól
    $campaignsData = $this->get_ptc_campaigns($apiKey, $userId, $userIp, $bearerToken);

    // Ellenőrizzük, hogy kaptunk-e választ
    if (isset($campaignsData['status']) && $campaignsData['status'] == '200') {
        $errormessage = $campaignsData['message'] ?? 'Hiba történt az adatok lekérésekor.';
        $campaigns = $campaignsData['data'];
        $totalCampaigns = count($campaigns);
        $totalRewards = array_sum(array_column($campaigns, 'reward'));       
			
			$data = [
            'campaigns' => $campaigns,
            'totalCampaigns' => $totalCampaigns,
            'totalRewards' => $totalRewards
        ];    
    }
    
    
        $data['pageTitle'] = 'Bitcotasks PTC';

        $this->_load_view('bitcotasks_ptc', $data); // A kampányok megjelenítése
}

}

