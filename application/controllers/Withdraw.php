<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdraw extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']); // URL és form helper
        $this->load->library('session'); // Session kezelés
        $this->load->database(); // Adatbázis kapcsolat
    }

    public function index() {
    	$data['settings'] = $this->settings;
        if (check_maintenance()) {  // Itt meghívjuk a helper függvényt
            redirect('page/maintenance');  // Ha karbantartás van, átirányítunk
            exit();  // Leállítjuk a további kód végrehajtását
        }

        // Referrer ellenőrzés (alap URL és referrer összehasonlítása)
        $valid_referrer = parse_url(base_url(), PHP_URL_HOST);  // Csak a domain név
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $referrer_host = parse_url($referrer, PHP_URL_HOST);  // A referrer domain neve

        // Ha a referrer nem egyezik az alap domainnel, hibát jelez
        if ($referrer_host !== $valid_referrer) {
            show_error('Invalid referrer. Request not allowed.');
        }	

        // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
        if (!$this->session->userdata('user_id')) {
            redirect('home');
        }

        // Felhasználói adatok lekérdezése
        $userId = $this->session->userdata('user_id');
        $query = $this->db->get_where('users', ['id' => $userId]);
        $user = $query->row_array();

        $balance = $user['balance'];
        $address = $user['address']; // Felhasználó ZeroCoin címe

        // Lekérjük a minimális kifizetési összeget a settings táblából
        $minWithdraw = $this->db->get_where('settings', ['name' => 'min_withdraw'])->row_array();
        $minZero = floatval($minWithdraw['value']); 

        // POST metódus kezelése a kifizetéshez
        if ($this->input->post('withdraw')) {
            if ($balance >= $minZero) {

                // Zárolás, hogy ne lehessen többször kifizetést indítani
                if (isset($_SESSION['withdraw_lock']) && $_SESSION['withdraw_lock'] === true) {
                    $this->session->set_flashdata('error', 'Please wait before trying again.');
                    redirect('withdraw');
                }

                // Lock aktiválása
                $_SESSION['withdraw_lock'] = true;

                // Beállítások lekérése a manual_withdraw értékének
                $manualWithdraw = isset($this->settings['manual_withdraw']) ? $this->settings['manual_withdraw'] : 'off';

                // Ha manual_withdraw 'on', akkor manuális kifizetés (Pending), ha 'off', akkor azonnali
                if ($manualWithdraw === 'on') {
                    // Manuális kifizetés - Pending státusz
                    $this->db->insert('withdrawals', [
                        'user_id' => $userId,
                        'amount' => $balance,
                        'txid' => '', // Manuális kifizetésnél nincs TxID
                        'status' => 'Pending', // Manuális státusz
                        'requested_at' => date('Y-m-d H:i:s')
                    ]);
                    $this->db->set('balance', 0)->where('id', $userId)->update('users');
                    $this->session->set_flashdata('message', 'Withdrawal request submitted. Please wait for admin approval.');
                } else {
                    // Azonnali kifizetés
                    $zcApi = $this->db->get_where('settings', ['name' => 'zerochain_api'])->row_array();
                    $zcPrivateKey = $this->db->get_where('settings', ['name' => 'zerochain_privatekey'])->row_array();
                    
                    // API hívás a kifizetéshez
                    $result = file_get_contents("https://zerochain.info/api/rawtxbuild/{$zcPrivateKey['value']}/{$address}/{$balance}/0/1/{$zcApi['value']}");
                    
                    if ($result === false) {
                        log_message('error', 'Error in file_get_contents');
                        $this->session->set_flashdata('error', 'Error with external API request.');
                        redirect('withdraw');
                    }

                    // JSON válasz feldolgozása
                    $data = json_decode($result, true);
                    if (isset($data['txid'])) {
                        $TxID = $data['txid'];
                    } else {
                        $TxID = "";
                    }

                    if ($TxID !== "") {
                        // Kifizetés sikeres, egyenleg és log frissítése
                        $this->db->set('total_withdrawals', 'total_withdrawals + ' . $balance, FALSE);
                        $this->db->set('balance', 0);
                        $this->db->where('id', $userId);
                        $this->db->update('users');

                        // Logolás
                        $data = [
                            'user_id' => $userId,
                            'amount' => $balance,
                            'txid' => $TxID,
                            'status' => 'Paid',
                            'requested_at' => date('Y-m-d H:i:s'),
                        ];
                        $this->db->insert('withdrawals', $data);

                        // Sikeres üzenet
                        $this->session->set_flashdata('message', "Successful payment: {$balance} ZER, TxID: {$TxID}");
                    } else {
                        $this->session->set_flashdata('message', "Payment failed. Please try again later.");
                    }
                }

                // Lock eltávolítása a kifizetés után
                unset($_SESSION['withdraw_lock']);                
                
            } else {
                $this->session->set_flashdata('message', "Insufficient balance. Minimum withdrawal is {$minZero} ZER.");
            }

            // Frissítés az oldal újratöltéséhez
            redirect('withdraw');
        }

        // Felhasználó legutóbbi kifizetéseinek lekérdezése
        $this->db->where('user_id', $userId);
        $this->db->order_by('requested_at', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get('withdrawals');
        $withdrawals = $query->result_array();

        // Egyéb adatok, mint a balance, minZero, withdrawals
        $data['balance'] = $balance;
        $data['minZero'] = $minZero;
        $data['withdrawals'] = $withdrawals;

        $data['pageTitle'] = 'Withdraw';
        // Nézet renderelése
        $data['content'] = $this->load->view('withdraw', $data, TRUE);
        $this->load->view('template', $data);
    }
}
