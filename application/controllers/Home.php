<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Home_model');
        // Karbantartás állapotának ellenőrzése
    }

    public function index() {

        if (check_maintenance()) {  // Itt meghívjuk a helper függvényt
            redirect('page/maintenance');  // Ha karbantartás van, átirányítunk
            exit();  // Leállítjuk a további kód végrehajtását
        }		
		
        $bannerHeaderHome = $this->db->get_where('settings', ['name' => 'banner_header_home'])->row_array();
        $data['bannerHeaderHome'] = isset($bannerHeaderHome['value']) ? $bannerHeaderHome['value'] : ''; 
        $bannerFooterHome = $this->db->get_where('settings', ['name' => 'banner_footer_home'])->row_array();
        $data['bannerFooterHome'] = isset($bannerFooterHome['value']) ? $bannerFooterHome['value'] : ''; 
   
        // Ha a felhasználó már be van jelentkezve, irányítsuk át a dashboard oldalra
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');  // Nem szükséges base_url() itt
        }

        // Referral ID lekérése
        $referral_id = $this->input->get('ref') ? intval($this->input->get('ref')) : 0; // Null helyett 0 alapértelmezett

        // Bejelentkezés kezelése
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Szóközök eltávolítása a Zerocoin cím elejéről és végéről
            $zerocoin_address = trim($this->input->post('address'));

            // Ellenőrzés, hogy a Zerocoin cím érvényes-e
            if (strpos($zerocoin_address, 't1') !== 0 || filter_var($zerocoin_address, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = "Invalid Zerocoin address, please register a Zerocoin address at <a style='color:black;' target='_blank' href='https://zerochain.info'>https://zerochain.info</a>.";
            } else {
                // Szóközök levágása után ellenőrizzük az adatbázisban
                $user_id = $this->Auth_model->handleUserLogin($zerocoin_address, $referral_id);
                if ($user_id) {
                    // Ellenőrizd, hogy az adatbázisban lévő cím tartalmaz-e szóközt
                    $user = $this->db->get_where('users', ['id' => $user_id])->row_array();
                    if ($user && trim($user['address']) !== $user['address']) {
                        // Frissítsük az adatbázisban lévő címet a szóközök eltávolításával
                        $this->db->where('id', $user_id);
                        $this->db->update('users', ['address' => trim($user['address'])]);
                    }

                    // Sessionbe tároljuk a felhasználó ID-jét
                    $this->session->set_userdata('user_id', $user_id);
                    redirect('dashboard'); // Irányítás a Dashboard oldalra
                } else {
                    $data['error'] = "Login failed. Please check your Zerocoin address.";
                }
            }
        }

        // Adatok az oldalhoz
        $data['totalUsers'] = $this->Home_model->getTotalUsers();
        $data['totalCollected'] = $this->Home_model->getTotalCollected();
        $data['totalWithdrawals'] = $this->Home_model->getTotalWithdrawals();
        $data['withdrawals'] = $this->Home_model->getLastWithdrawals();
        
        $faucetName = $this->db->get_where('settings', ['name' => 'faucet_name'])->row_array();
        $data['faucetName'] = isset($faucetName['value']) ? $faucetName['value'] : ''; 

        $data['pageTitle'] = 'Home';
        
        $this->load->view('home', $data);
    }

    public function faq() {
        $faucetName = $this->db->get_where('settings', ['name' => 'faucet_name'])->row_array();
        $data['faucetName'] = isset($faucetName['value']) ? $faucetName['value'] : ''; 
        $data['pageTitle'] = 'FAQ';
        
        $this->load->view('faq', $data);
    }
}


