<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referrals extends Member_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

		  $userId = $this->currentUser['id'];

        // Referáltak lekérdezése
        $this->db->select('id, address, referral_earnings, joined, last_activity');
        $this->db->from('users');
        $this->db->where('referred_by', $userId);
        $referrals = $this->db->get()->result_array();

        // Referral link generálása
        $referralLink = base_url() . '?ref=' . $userId;

        // Adatok átadása a nézetnek
        $data['referrals'] = $referrals;
        $data['referralLink'] = $referralLink;
        $data['refPercent'] = 10; // Referral százalék
        $data['pageTitle'] = 'Referrals';

        // Nézet renderelése
        $this->_load_view('referrals', $data);
    }
}
