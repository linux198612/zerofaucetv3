<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->model('Settings_model');
    }

		private function _load_view($view, $data = []) {
		    $data['content'] = $this->load->view("admin/{$view}", $data, TRUE);
		    $this->load->view('admin/template', $data);
		}

        private function _check_admin() {
            if (!$this->session->userdata('admin_logged_in')) {
                redirect('admin/login');
            }
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
        


    // Belépési oldal
    public function login() {
        if ($this->session->userdata('admin_logged_in')) {
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
                $this->session->set_userdata('admin_logged_in', true);
                redirect('admin/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password.');
            }
        }

        $this->load->view('admin/login');
    }

    // Kijelentkezés
    public function logout() {
        $this->session->unset_userdata('admin_logged_in');
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
        $this->_check_admin();

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

    public function pending_withdraw() {
        // "Pending" státuszú tételek lekérése
        $data['withdrawals'] = $this->db->where('status', 'Pending')->get('withdrawals')->result_array();

        if ($this->input->post('action')) {
            $id = $this->input->post('id');
            $action = $this->input->post('action'); // 'approve' vagy 'reject'

            log_message('debug', "Action: {$action}, Withdrawal ID: {$id}");

            if ($action === 'approve') {
                $withdrawal = $this->db->where('id', $id)->get('withdrawals')->row_array();
                if ($withdrawal) {
                    // API paraméterek ellenőrzése
                    log_message('debug', "Withdrawal Data: " . json_encode($withdrawal));

                    // Felhasználói adatok lekérése
                    $user = $this->db->where('id', $withdrawal['user_id'])->get('users')->row_array();
                    $address = $user['address']; // Felhasználó ZeroCoin címe
                    $amount = $withdrawal['amount'];

                    // Zárolás, hogy ne lehessen többször kifizetést indítani
                    if (isset($_SESSION['withdraw_lock']) && $_SESSION['withdraw_lock'] === true) {
                        $this->session->set_flashdata('error', 'Please wait before trying again.');
                        redirect('admin/pending_withdraw');
                    }

                    // Lock aktiválása
                    $_SESSION['withdraw_lock'] = true;

                    // ZeroChain API adatok lekérdezése
                    $zcApi = $this->db->get_where('settings', ['name' => 'zerochain_api'])->row_array();
                    $zcPrivateKey = $this->db->get_where('settings', ['name' => 'zerochain_privatekey'])->row_array();

                    // API hívás a kifizetéshez
                    $result = file_get_contents("https://zerochain.info/api/rawtxbuild/{$zcPrivateKey['value']}/{$address}/{$amount}/0/1/{$zcApi['value']}");

                    if ($result === false) {
                        log_message('error', 'Error in file_get_contents');
                        $this->session->set_flashdata('error', 'Error with external API request.');
                        unset($_SESSION['withdraw_lock']);
                        redirect('admin/pending_withdraw');
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
                                ->where('id', $id)
                                ->update('withdrawals');

                        // Felhasználó egyenlegének frissítése
                        $this->db->set('total_withdrawals', 'total_withdrawals + ' . $amount, FALSE);
                        $this->db->where('id', $withdrawal['user_id']);
                        $this->db->update('users');

                        log_message('debug', "Withdrawal ID {$id} approved and processed.");
                        $this->session->set_flashdata('message', "Withdrawal ID {$id} approved and processed.");
                    } else {
                        log_message('error', "API Error: Could not process withdrawal ID {$id}.");
                        $this->session->set_flashdata('error', "API error. Could not process withdrawal ID {$id}.");
                    }

                    // Lock eltávolítása
                    unset($_SESSION['withdraw_lock']);
                } else {
                    log_message('error', "Withdrawal ID {$id} not found.");
                    $this->session->set_flashdata('error', "Withdrawal ID {$id} not found.");
                }
            } elseif ($action === 'reject') {
                // Elutasított tétel frissítése
                $this->db->set('status', 'Rejected')->where('id', $id)->update('withdrawals');
                log_message('debug', "Withdrawal ID {$id} rejected.");
                $this->session->set_flashdata('message', "Withdrawal ID {$id} rejected.");
            }

            redirect('admin/pending_withdraw');
        }

        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből

        // Nézet betöltése
        $this->_load_view('pending_withdraw', [
            'title' => 'Pending Withdrawals',
            'withdrawals' => $data['withdrawals'],
            'settings' => $settings
        ]);
    }

	public function autofaucet() {
	    $this->_check_admin();  // Admin jogosultság ellenőrzése
	
        $settings = $this->Settings_model->get_settings(); // Beállítások lekérése a modellből
	
	    // A nézet betöltése a beállításokkal
	    $this->_load_view('autofaucet', ['title' => 'Autofaucet Settings', 'settings' => $settings]);
	}

    public function energy_shop()
    {
        $this->_check_admin(); // Admin ellenőrzés
    
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
        $this->_check_admin(); // Ellenőrzés

        // Csomag törlése
        $this->db->where('id', $id)->delete('energyshop_packages');
        $this->session->set_flashdata('success', 'Package deleted successfully.');

        redirect('admin/energy_shop');
    }

    public function users() {
        $this->_check_admin(); // Ellenőrizzük az admin bejelentkezést
    
        // Felhasználók lekérése az adatbázisból
        $data['users'] = $this->db->get('users')->result_array();
    
        // Nézet betöltése
        $this->_load_view('users', [
            'title' => 'User Management',
            'users' => $data['users']
        ]);
    }
    
    public function user_details($user_id)
    {
        $this->_check_admin();
    
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
    $this->_check_admin();

    // Logok lekérdezése
    $logs = $this->db->order_by('logged_at', 'DESC')->get('withdraw_log')->result_array();

    $this->_load_view('withdraw_logs', [
        'title' => 'Withdraw Logs',
        'logs' => $logs
    ]);
}

public function delete_withdraw_log($id)
{
    $this->_check_admin();

    // Log törlése az ID alapján
    $this->db->where('id', $id)->delete('withdraw_log');
    $this->session->set_flashdata('success', 'The log entry has been successfully deleted.');
    redirect('admin/withdraw_logs');
}

    
public function process_withdrawal($user_id) {
    $this->_check_admin();

    $amount = $this->input->post('amount');
    $user = $this->db->where('id', $user_id)->get('users')->row_array();

    if ($user && is_numeric($amount) && $amount > 0) {
        // Felhasználó ZeroCoin címe
        $address = $user['address'];
        
        // ZeroChain API kulcsok lekérése
        $zcApi = $this->db->get_where('settings', ['name' => 'zerochain_api'])->row_array();
        $zcPrivateKey = $this->db->get_where('settings', ['name' => 'zerochain_privatekey'])->row_array();

        // API hívás a kifizetéshez
        $api_url = "https://zerochain.info/api/rawtxbuild/{$zcPrivateKey['value']}/{$address}/{$amount}/0/1/{$zcApi['value']}";
        $result = file_get_contents($api_url);

        if ($result === false) {
            log_message('error', 'Error in file_get_contents');
            $this->session->set_flashdata('error', 'Error with external API request.');
            redirect('admin/user_details/' . $user_id);
        }

        // JSON válasz feldolgozása
        $data = json_decode($result, true);
        if (isset($data['txid']) && !empty($data['txid'])) {
            $TxID = $data['txid'];

            // Kifizetés sikeres, beszúrjuk a withdrawals táblába
            $this->db->insert('withdrawals', [
                'user_id' => $user_id,
                'amount' => $amount,
                'status' => 'Paid',
                'txid' => $TxID,
                'requested_at' => date('Y-m-d H:i:s')
            ]);

            // Levonjuk az összeget a felhasználó egyenlegéből
            $this->db->set('balance', 'balance - ' . $amount, FALSE);
            $this->db->where('id', $user_id);
            $this->db->update('users');

            // Frissítjük a kifizetett összeg statisztikáját
            $this->db->set('total_withdrawals', 'total_withdrawals + ' . $amount, FALSE);
            $this->db->where('id', $user_id);
            $this->db->update('users');

            $this->session->set_flashdata('success', 'Withdrawal processed successfully.');
        } else {
            log_message('error', 'API Error: Failed to generate TxID for user ID ' . $user_id);
            $this->session->set_flashdata('error', 'API error: Failed to generate TxID. Please check the address or try again later.');
        }
    } else {
        $this->session->set_flashdata('error', 'Invalid amount or user.');
    }

    redirect('admin/user_details/' . $user_id);
}

       
}
