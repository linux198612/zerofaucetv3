<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bitcotasks_shortlinks extends Member_Controller {

    public function __construct() {
        parent::__construct();

    }

    public function index() {

        // API paraméterek beállítása
  		  $apiKey = $this->settings['bitcotasks_api']; // Cseréld ki a saját API kulcsodra
 	 	  $bearerToken = $this->settings['bitcotasks_bearer_token']; // Cseréld ki a saját Bearer tokenedre
 	     $userIp = $_SERVER['REMOTE_ADDR']; // Felhasználó IP címének lekérése
  	     $userId = $this->currentUser['id']; // A bejelentkezett felhasználó ID-j

    // Shortlink-adatok lekérése az API-ból
    $shortlinks = $this->get_shortlink_campaigns($apiKey, $userId, $userIp, $bearerToken);
    $data['shortlinks'] = $shortlinks;

    if (isset($shortlinks['status']) && $shortlinks['status'] != 200) {
        $data['error'] = $shortlinks['message'] ?? 'Hiba történt az adatok lekérésekor.';
        $data['shortlinks'] = [];
        $data['total_clicks'] = 0;
        $data['total_reward'] = 0;
    } else {
        // Összesítés
        $totalClicks = 0;
        $totalReward = 0;
        if (!empty($shortlinks['data'])) {
            foreach ($shortlinks['data'] as $link) {
                $available = (int) $link['available']; // Lekattintható mennyiség
                $reward = (float) $link['reward']; // Jutalom shortlinkenként
                $totalClicks += $available;
                $totalReward += $available * $reward;
            }
        }
        $data['total_clicks'] = $totalClicks;
        $data['total_reward'] = $totalReward;
    }
		  $data['pageTitle'] = 'Bitcotasks Shortlinks';
        // Megjelenítjük az adatokat
        $this->_load_view('bitcotasks_shortlinks', $data);
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

    private function get_shortlink_campaigns($apiKey, $userId, $userIp, $bearerToken) {
        $url = "https://bitcotasks.com/sl-api/$apiKey/$userId/$userIp";
        $response = $this->requestWithCurl($url, $bearerToken);
        if ($response) {
            return json_decode($response, true);
        }
        return ['status' => 500, 'message' => 'Failed to connect to API.'];
    }
}
