<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Member_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $userId = $this->currentUser['id'];
        $user = $this->currentUser;

        // Referral earnings és count lekérdezések egyszerűsítése
        $this->db->select_sum('referral_earnings');
        $this->db->where('referred_by', $userId);
        $totalReferralEarnings = $this->db->get('users')->row()->referral_earnings ?? 0;

        $referralCount = $this->db->where('referred_by', $userId)->count_all_results('users');

        $data = [
            'user' => $user,
            'totalReferralEarnings' => $totalReferralEarnings,
            'referralCount' => $referralCount,
            'pageTitle' => 'Dashboard'
        ];

        // A render metódus helyett a _load_view használata
        $this->_load_view('dashboard', $data);
    }

    public function convert_credits() {
        $userId = $this->currentUser['id'];

        if ($this->currentUser['credits'] > 0) {
            // Settings lekérdezés egy metódus segítségével
            $zeroRate = $this->settings['currency_value'];
            $usdValue = $this->currentUser['credits'] / 1000 * 0.01;
            $zeroValue = $usdValue / $zeroRate;

            // Adatbázis frissítése
            $this->db->set('balance', 'balance + ' . $zeroValue, FALSE);
            $this->db->set('credits', 0); // Credit egyenleg nullázása
            $this->db->where('id', $userId);
            $this->db->update('users');

            // Sikeres konvertálás üzenet
            $successMessage = sprintf(
                'Successfully converted %d credits to %.8f Zero!',
                $this->currentUser['credits'],
                $zeroValue
            );
            $this->session->set_flashdata('success', $successMessage);
        } else {
            // Hibaüzenet
            $this->session->set_flashdata('error', 'You do not have enough credits to convert.');
        }

        redirect('dashboard');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('home');
    }

}

