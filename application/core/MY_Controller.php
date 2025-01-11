<?php
class My_Controller extends CI_Controller {
    protected $settings = [];
    protected $currentUser = null;

    public function __construct() {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->settings = $this->Settings_model->get_settings();

        // Beállítjuk az aktuális felhasználót, ha van bejelentkezve
        $userId = $this->session->userdata('user_id');
        if ($userId) {
            $this->load->database();
            $this->currentUser = $this->db->get_where('users', ['id' => $userId])->row_array();
        }
    }

    protected function render($view, $data = []) {

        $data['settings'] = $this->settings;
        $data['user'] = $this->currentUser; // Elérhetővé tesszük a nézetek számára
        $data['content'] = $this->load->view($view, $data, TRUE);
        $this->load->view('template', $data);
    }

    protected function is_logged_in() {
        return $this->currentUser !== null; // Ellenőrizzük, hogy van-e bejelentkezett felhasználó
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
        return null;
    }
    
    
}
