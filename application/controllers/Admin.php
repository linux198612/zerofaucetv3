<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->load->model('Challenge_model');
        
    }

    // Belépési oldal
    public function login() {
        if ($this->session->userdata('admin')) {
            redirect('admin/dashboard');
        }

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $query = $this->db->get_where('settings', ['name' => 'admin_username']);
            $admin_username = $query->row()->value;

            $query = $this->db->get_where('settings', ['name' => 'admin_password']);
            $admin_password = $query->row()->value;

            if ($username === $admin_username && password_verify($password, $admin_password)) {
                $this->session->set_userdata('admin', true);
                redirect('admin/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password.');
            }
        }

        $this->load->view('admin/login');
    }

        public function settings_save() {
            if ($this->input->method() === 'post') {
                // Lekérjük az összes POST adatot
                foreach ($this->input->post() as $name => $value) {
                    // Ellenőrizzük, hogy a beállítás létezik-e a settings táblában
                    $existing_setting = $this->Settings_model->get_setting($name);
        
                    if ($existing_setting === null) {
                        // Ha a beállítás nem létezik, hibaüzenet
                        $this->session->set_flashdata('error', "The setting '$name' does not exist!");
                        // Visszairányítjuk az előző oldalra
                        redirect($this->input->server('HTTP_REFERER') ?? base_url('admin/dashboard'));
                        return; // Kilépünk, hogy ne folytassuk a mentést
                    }
        
                    // Frissítjük a beállítást a modellben
                    $this->Settings_model->update_setting($name, $value);
                }
        
                // Üzenet beállítása a sikeres mentéshez
                $this->session->set_flashdata('success', 'Settings saved successfully!');
            }
        
            // Ellenőrizzük, hogy van-e HTTP_REFERER, és oda irányítjuk a felhasználót
            $redirect_url = $this->input->server('HTTP_REFERER');
            
            // Ha nincs referer, alapértelmezett visszairányítás
            if (!$redirect_url) {
                $redirect_url = base_url('admin/dashbaord');
            }
        
            // Visszairányítjuk a megfelelő oldalra
            redirect($redirect_url);
        }
        

    // Kijelentkezés
    public function logout() {
        $this->session->unset_userdata('admin');
        redirect('admin/login');
    }

    // Dashboard (Főoldal)
    public function dashboard() {

        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből

        // Nézet betöltése a beállításokkal
        $this->_load_view('dashboard', [
            'title' => 'Dashboard',
            'settings' => $settings // Beállítások átadása a nézetnek
        ]);
    }
    
        public function challenge($action = 'list', $id = null) {
        $data = []; // Nézethez használt adatok
        $data['pageTitle'] = 'Manage Challenges';

        if ($action === 'list') {
            // Kihívások listázása
            $data['view'] = 'list';
            $data['challenges'] = $this->Challenge_model->get_all_challenges();
        } elseif ($action === 'add') {
            // Új kihívás hozzáadása
            if ($this->input->post()) {
                $challengeData = [
                    'name' => $this->input->post('name'),
                    'type' => $this->input->post('type'),
                    'target' => $this->input->post('target'),
                    'reward' => $this->input->post('reward'),
                ];
                $this->Challenge_model->add_challenge($challengeData);
                redirect('admin/challenge');
            }
            $data['view'] = 'form';
            $data['pageTitle'] = 'Add Challenge';
        } elseif ($action === 'edit' && $id) {
            // Kihívás szerkesztése
            if ($this->input->post()) {
                $challengeData = [
                    'name' => $this->input->post('name'),
                    'type' => $this->input->post('type'),
                    'target' => $this->input->post('target'),
                    'reward' => $this->input->post('reward'),
                ];
                $this->Challenge_model->update_challenge($id, $challengeData);
                redirect('admin/challenge');
            }
            $data['view'] = 'form';
            $data['pageTitle'] = 'Edit Challenge';
            $data['challenge'] = $this->Challenge_model->get_challenge_by_id($id);
        } elseif ($action === 'delete' && $id) {
            // Kihívás törlése
            $this->Challenge_model->delete_challenge($id);
            redirect('admin/challenge');
        } else {
            show_404();
        }

        // Minden nézet ugyanazon fájlon belül kezelt
        $this->_load_view('challenge', $data);
    }
        // Offerwalls
    public function offerwalls() {

        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből

        // Nézet betöltése a beállításokkal
        $this->_load_view('offerwalls', [
            'title' => 'Offerwalls',
            'settings' => $settings // Beállítások átadása a nézetnek
        ]);
    }
    
            // Offerwalls
    public function zerads() {

        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből

        // Nézet betöltése a beállításokkal
        $this->_load_view('zerads', [
            'title' => 'Zerads Settings',
            'settings' => $settings // Beállítások átadása a nézetnek
        ]);
    }

    public function withdraw_settings() {

        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből

        // Nézet betöltése a beállításokkal
        $this->_load_view('withdraw_settings', [
            'title' => 'Withdraw settings',
            'settings' => $settings // Beállítások átadása a nézetnek
        ]);
    }

    public function faucet() {

        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből

        // Nézet betöltése a beállításokkal
        $this->_load_view('faucet', [
            'title' => 'Faucet',
            'settings' => $settings // Beállítások átadása a nézetnek
        ]);
    }

    // Jelszó módosítás
    public function change_password() {


        if ($this->input->post()) {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');
            $confirm_password = $this->input->post('confirm_password');

            $query = $this->db->get_where('settings', ['name' => 'admin_password']);
            $admin_password = $query->row()->value;

            if (!password_verify($current_password, $admin_password)) {
                $this->session->set_flashdata('error', 'Current password is incorrect.');
            } elseif ($new_password !== $confirm_password) {
                $this->session->set_flashdata('error', 'New passwords do not match.');
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $this->db->where('name', 'admin_password')->update('settings', ['value' => $hashed_password]);

                $this->session->set_flashdata('success', 'Password updated successfully.');
                redirect('admin/change_password');
            }
        }

        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből

        $this->_load_view('change_password', ['title' => 'Change Password', 'settings' => $settings]);
    }

   
    public function home_settings() {
      
        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből
    
        // Nézet betöltése az egységes metódussal
        $this->_load_view('home_settings', [
            'title' => 'Home/Index Page Settings',
            'settings' => $settings,
        ]);
    }

    // API hívás funkció
    private function call_zerochain_api($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Maximum 10 másodperc várakozás
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

public function pending_withdraw() {
    $data['withdrawals'] = $this->db->where('status', 'Pending')->get('withdrawals')->result_array();

    if ($this->input->post('action')) {
        $id = $this->input->post('id');
        $action = $this->input->post('action');

        if ($action === 'approve') {
            $this->db->trans_start(); // Adatbázis tranzakció indítása
            $withdrawal = $this->db->where('id', $id)
                                   ->where('status', 'Pending') // Csak 'Pending' státuszú tételt engedünk
                                   ->get('withdrawals')
                                   ->row_array();

            if ($withdrawal) {
                // A kifizetés státuszának átállítása 'Processing'-re
                $this->db->set('status', 'Processing')
                         ->where('id', $id)
                         ->update('withdrawals');

                $user = $this->db->where('id', $withdrawal['user_id'])->get('users')->row_array();
                $address = $user['address'];
                $amount = $withdrawal['amount'];

                $zcApi = $this->db->get_where('settings', ['name' => 'zerochain_api'])->row_array();
                $zcPrivateKey = $this->db->get_where('settings', ['name' => 'zerochain_privatekey'])->row_array();

                $result = file_get_contents("https://zerochain.info/api/rawtxbuild/{$zcPrivateKey['value']}/{$address}/{$amount}/0/1/{$zcApi['value']}");
                $data = json_decode($result, true);

                if (isset($data['txid'])) {
                    $TxID = $data['txid'];

                    // Frissítjük az adatbázist 'Paid' státuszra
                    $this->db->set('status', 'Paid')
                             ->set('txid', $TxID)
                             ->where('id', $id)
                             ->update('withdrawals');

                    // Felhasználói egyenleg frissítése
                    $this->db->set('total_withdrawals', 'total_withdrawals + ' . $amount, FALSE)
                             ->where('id', $withdrawal['user_id'])
                             ->update('users');

                    $this->session->set_flashdata('message', "Withdrawal ID {$id} approved and processed.");
                } else {
                    // Hiba esetén visszaállítjuk az állapotot 'Pending'-re
                    $this->db->set('status', 'Pending')
                             ->where('id', $id)
                             ->update('withdrawals');

                    $this->session->set_flashdata('error', "API error. Could not process withdrawal ID {$id}.");
                }
            } else {
                $this->session->set_flashdata('error', "Withdrawal ID {$id} not found or already processed.");
            }

            $this->db->trans_complete(); // Adatbázis tranzakció vége
        } elseif ($action === 'reject') {
            $withdrawal = $this->db->where('id', $id)->get('withdrawals')->row_array();
            if ($withdrawal) {
                $amount = $withdrawal['amount'];

                $this->db->set('status', 'Rejected')
                         ->where('id', $id)
                         ->update('withdrawals');

                $this->db->set('balance', 'balance + ' . $amount, FALSE)
                         ->where('id', $withdrawal['user_id'])
                         ->update('users');

                $this->session->set_flashdata('message', "Withdrawal ID {$id} rejected and balance refunded.");
            } else {
                $this->session->set_flashdata('error', "Withdrawal ID {$id} not found.");
            }
        }

        redirect('admin/pending_withdraw');
    }

    $settings = $this->Settings_model->get_settings();

    $this->_load_view('pending_withdraw', [
        'title' => 'Pending Withdrawals',
        'withdrawals' => $data['withdrawals'],
        'settings' => $settings
    ]);
}

	public function autofaucet() {
	
        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből
	
	    // A nézet betöltése a beállításokkal
	    $this->_load_view('autofaucet', ['title' => 'Autofaucet Settings', 'settings' => $settings]);
	}
	
		public function cronjob() {
	
        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből
	
	    // A nézet betöltése a beállításokkal
	    $this->_load_view('cronjob', ['title' => 'Cronjob Settings', 'settings' => $settings]);
	}

    public function energy_shop()
    {
  
    
        if ($this->input->post('action') === 'save_package') {
            // Csomag mentése
            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $energy_cost = $this->input->post('energy_cost');
            $zero_amount = $this->input->post('zero_amount');
    
            $data = [
                'name' => $name,
                'energy_cost' => $energy_cost,
                'zero_amount' => $zero_amount,
            ];
    
            if ($id) {
                $this->db->where('id', $id)->update('energyshop_packages', $data);
                $this->session->set_flashdata('success', 'Package updated successfully.');
            } else {
                $this->db->insert('energyshop_packages', $data);
                $this->session->set_flashdata('success', 'Package added successfully.');
            }
    
            redirect('admin/energy_shop');
        }
    
        // Adatok lekérdezése
        $query = $this->db->get('energyshop_packages');
        $data['packages'] = $query->result_array();
    
        // Beállítások lekérdezése
        $settings = $this->Settings_model->get_settings();

    
        // Nézet betöltése a csomagokkal és a beállításokkal
        $this->_load_view('energy_shop', [
            'title' => 'Energy Shop Settings',
            'settings' => $settings,  // Beállítások átadása
            'data' => $data           // Csomagok és egyéb adatok átadása
        ]);
    }
    
    public function energyshop_delete($id)
    {


        // Csomag törlése
        $this->db->where('id', $id)->delete('energyshop_packages');
        $this->session->set_flashdata('success', 'Package deleted successfully.');

        redirect('admin/energy_shop');
    }

    public function users() {

    
        // Felhasználók lekérése az adatbázisból
        $data['users'] = $this->db->get('users')->result_array();
 		   $settings = $this->Settings_model->get_settings();
        // Nézet betöltése
        $this->_load_view('users', [
            'title' => 'User Management',
            'users' => $data['users'],
            'settings' => $settings
        ]);
    }
    
    public function user_details($user_id)
    {

    
        // Felhasználó adatainak lekérése
        $user = $this->db->where('id', $user_id)->get('users')->row_array();
    
        if (!$user) {
            show_404(); // Ha nincs ilyen felhasználó, 404-es hiba
        }
    
        if ($this->input->method() === 'post') {
            // POST kérelem - adatok frissítése
            $balance = $this->input->post('balance');
            $energy = $this->input->post('energy');
    
            // Kézi validáció
            $errors = [];
            if (!is_numeric($balance)) {
                $errors[] = 'Balance must be a valid number.';
            }
            if (!ctype_digit($energy)) {
                $errors[] = 'Energy must be a valid integer.';
            }
    
            if (empty($errors)) {
                // Ha nincs hiba, frissítjük az adatokat
                $data = [
                    'balance' => $balance,
                    'energy' => $energy
                ];
    
                $this->db->where('id', $user_id)->update('users', $data);
    
                $this->session->set_flashdata('success', 'User data successfully updated.');
                redirect('admin/user_details/' . $user_id);
            } else {
                // Hibák tárolása
                $this->session->set_flashdata('error', implode('<br>', $errors));
            }
        }
    
        // Nézet betöltése
        $this->_load_view('user_details', [
            'title' => 'User Details',
            'user' => $user
        ]);
    }
    
    
    public function withdraw_logs()
{


    // Logok lekérdezése
    $logs = $this->db->order_by('logged_at', 'DESC')->get('withdraw_log')->result_array();
        // Beállítások lekérdezése
        $settings = $this->Settings_model->get_settings();

    $this->_load_view('withdraw_logs', [
        'title' => 'Withdraw Logs',
        'logs' => $logs,
        'settings' => $settings
    ]);
}

public function delete_withdraw_log($id)
{


    // Log törlése az ID alapján
    $this->db->where('id', $id)->delete('withdraw_log');
    $this->session->set_flashdata('success', 'The log entry has been successfully deleted.');
    redirect('admin/withdraw_logs');
}

    
    public function process_withdrawal($user_id) {

    
        $amount = $this->input->post('amount');
        $user = $this->db->where('id', $user_id)->get('users')->row_array();
    
        if ($user && is_numeric($amount) && $amount > 0) {
            // Kifizetési kérelem rögzítése 'Pending' státusszal
            $this->db->insert('withdrawals', [
                'user_id' => $user_id,
                'amount' => $amount,
                'status' => 'Pending',
                'requested_at' => date('Y-m-d H:i:s')  // A helyes oszlopnév
            ]);
    
            // Felhasználó adatai
            $address = $user['address']; // Felhasználó ZeroCoin címe
    
            // ZeroChain API kulcsok lekérése
            $zcApi = $this->db->get_where('settings', ['name' => 'zerochain_api'])->row_array();
            $zcPrivateKey = $this->db->get_where('settings', ['name' => 'zerochain_privatekey'])->row_array();
    
            // API hívás a kifizetéshez
            $result = file_get_contents("https://zerochain.info/api/rawtxbuild/{$zcPrivateKey['value']}/{$address}/{$amount}/0/1/{$zcApi['value']}");
    
            if ($result === false) {
                log_message('error', 'Error in file_get_contents');
                $this->session->set_flashdata('error', 'Error with external API request.');
                redirect('admin/user_details/' . $user_id);
            }
    
            // JSON válasz feldolgozása
            $data = json_decode($result, true);
            if (isset($data['txid'])) {
                $TxID = $data['txid'];
            } else {
                $TxID = "";
            }
    
        // Ha van tranzakciós ID, akkor frissítjük az adatbázist
        if ($TxID !== "") {
            // Kifizetés sikeres, frissítjük az adatbázist
            $this->db->set('status', 'Paid')
                    ->set('txid', $TxID)
                    ->where('user_id', $user_id)
                    ->where('status', 'Pending')
                    ->update('withdrawals');

            // Levonjuk a kifizetett összeget a felhasználó egyenlegéből
            $this->db->set('balance', 'balance - ' . $amount, FALSE);  // Levonás
            $this->db->where('id', $user_id);
            $this->db->update('users');

            // Frissítjük a kifizetett összeg statisztikáját
            $this->db->set('total_withdrawals', 'total_withdrawals + ' . $amount, FALSE);
            $this->db->where('id', $user_id);
            $this->db->update('users');

            // Üzenet küldése a sikeres kifizetésről
            $this->session->set_flashdata('success', 'Withdrawal request approved and processed.');
        } else {
            log_message('error', 'API Error: Could not process withdrawal for user ID ' . $user_id);
            $this->session->set_flashdata('error', 'API error. Could not process withdrawal.');
        }
        } else {
            $this->session->set_flashdata('error', 'Invalid amount or user.');
        }
    
        redirect('admin/user_details/' . $user_id);
    }
       
		public function offerwalls_pending() {
		    // Lekérjük az összes "Pending" státuszú kérelmet az offerwall_history táblából
		    $data['offerwalls'] = $this->db->where('status', 'Pending')->get('offerwall_history')->result_array();
		
		    // Ellenőrizzük, hogy érkezett-e művelet (jóváhagyás vagy elutasítás)
		    if ($this->input->post('action')) {
		        $id = $this->input->post('id'); // A rekord ID-ja
		        $action = $this->input->post('action'); // 'approve' vagy 'reject'
		
		        if ($action === 'approve') {
		            // Jóváhagyás: Frissítjük a státuszt 'Paid'-re
		            $this->db->set('status', 'Paid')->where('id', $id)->update('offerwall_history');
		
		            // Opcionálisan: hozzáadhatjuk az összegét a felhasználó számlájához, ha van ilyen funkció.
		            $offer = $this->db->where('id', $id)->get('offerwall_history')->row_array();
		            if ($offer) {
		                $this->db->set('credits', 'credits + ' . $offer['amount'], false)
		                    ->where('id', $offer['user_id'])
		                    ->update('users');
		            }
		
		            $this->session->set_flashdata('success', "Offerwall ID {$id} approved successfully.");
		        } elseif ($action === 'reject') {
		            // Elutasítás: Frissítjük a státuszt 'Rejected'-re
		            $this->db->set('status', 'Rejected')->where('id', $id)->update('offerwall_history');
		            $this->session->set_flashdata('success', "Offerwall ID {$id} rejected successfully.");
		        } else {
		            $this->session->set_flashdata('error', 'Invalid action specified.');
		        }
		
		        // Visszairányítás az offerwall_pending oldalra
		        redirect('admin/offerwalls_pending');
		    }
    
				$settings = $this->Settings_model->get_settings();
		    // Betöltjük a nézetet
		    $this->_load_view('offerwalls_pending', [
		        'title' => 'Pending Offerwalls',
		        'offerwalls' => $data['offerwalls'],
		        'settings' => $settings
		    ]);
		}

}
