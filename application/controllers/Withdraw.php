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
        if (check_maintenance()) {
            redirect('page/maintenance');
            exit();
        }

        $valid_referrer = parse_url(base_url(), PHP_URL_HOST);
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $referrer_host = parse_url($referrer, PHP_URL_HOST);

        if ($referrer_host !== $valid_referrer) {
            show_error('Invalid referrer. Request not allowed.');
        }

        if (!$this->session->userdata('user_id')) {
            redirect('home');
        }

        $userId = $this->session->userdata('user_id');
        $query = $this->db->get_where('users', ['id' => $userId]);
        $user = $query->row_array();

        $balance = $user['balance'];
        $address = $user['address'];

        $minWithdraw = $this->db->get_where('settings', ['name' => 'min_withdraw'])->row_array();
        $minZero = floatval($minWithdraw['value']);

        if ($this->input->post('withdraw')) {
            if ($balance >= $minZero) {
                if (isset($_SESSION['withdraw_lock']) && $_SESSION['withdraw_lock'] === true) {
                    $this->session->set_flashdata('error', 'Please wait before trying again.');
                    redirect('withdraw');
                }

                $_SESSION['withdraw_lock'] = true;

                $manualWithdraw = isset($this->settings['manual_withdraw']) ? $this->settings['manual_withdraw'] : 'off';

                if ($manualWithdraw === 'on') {
                    $this->db->insert('withdrawals', [
                        'user_id' => $userId,
                        'amount' => $balance,
                        'txid' => '',
                        'status' => 'Pending',
                        'requested_at' => date('Y-m-d H:i:s')
                    ]);
                    $this->db->set('balance', 0)->where('id', $userId)->update('users');
                    $this->session->set_flashdata('message', 'Withdrawal request submitted. Please wait for admin approval.');
                } else {
                    $zcApi = $this->db->get_where('settings', ['name' => 'zerochain_api'])->row_array();
                    $zcPrivateKey = $this->db->get_where('settings', ['name' => 'zerochain_privatekey'])->row_array();

                    $result = @file_get_contents("https://zerochain.info/api/rawtxbuild/{$zcPrivateKey['value']}/{$address}/{$balance}/0/1/{$zcApi['value']}");

                    if ($result === false) {
                        $this->log_withdraw_error($userId, $balance, $address, 'Error in external API request.');
                        $this->session->set_flashdata('error', 'Error with external API request.');
                        redirect('withdraw');
                    }

                    $data = json_decode($result, true);
                    if (isset($data['txid'])) {
                        $TxID = $data['txid'];
                    } else {
                        $TxID = "";
                    }

                    if ($TxID !== "") {
                        $this->db->set('total_withdrawals', 'total_withdrawals + ' . $balance, FALSE);
                        $this->db->set('balance', 0);
                        $this->db->where('id', $userId);
                        $this->db->update('users');

                        $data = [
                            'user_id' => $userId,
                            'amount' => $balance,
                            'txid' => $TxID,
                            'status' => 'Paid',
                            'requested_at' => date('Y-m-d H:i:s'),
                        ];
                        $this->db->insert('withdrawals', $data);

                        $this->session->set_flashdata('message', "Successful payment: {$balance} ZER, TxID: {$TxID}");
                    } else {
                        $this->log_withdraw_error($userId, $balance, $address, 'Failed to generate TxID.');
                        $this->session->set_flashdata('message', "Payment failed. Please try again later.");
                    }
                }

                unset($_SESSION['withdraw_lock']);

            } else {
                $this->session->set_flashdata('message', "Insufficient balance. Minimum withdrawal is {$minZero} ZER.");
            }

            redirect('withdraw');
        }

        $this->db->where('user_id', $userId);
        $this->db->order_by('requested_at', 'DESC');
        $this->db->limit(10);
        $query = $this->db->get('withdrawals');
        $withdrawals = $query->result_array();

        $data['balance'] = $balance;
        $data['minZero'] = $minZero;
        $data['withdrawals'] = $withdrawals;

        $data['pageTitle'] = 'Withdraw';
        $data['content'] = $this->load->view('withdraw', $data, TRUE);
        $this->load->view('template', $data);
    }

    private function log_withdraw_error($userId, $amount, $address, $errorMessage) {
        $this->db->insert('withdraw_log', [
            'user_id' => $userId,
            'amount' => $amount,
            'address' => $address,
            'error_message' => $errorMessage,
            'logged_at' => date('Y-m-d H:i:s')
        ]);
    }
}
