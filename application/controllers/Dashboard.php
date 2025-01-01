<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends My_Controller {

    public function __construct() {
        parent::__construct();

        // Automatikusan ellenőrizzük a bejelentkezést
        if (!$this->is_logged_in()) {
            redirect('home'); 
            exit();
        }
    }

    public function index() {
        $data['settings'] = $this->settings;

        if (check_maintenance()) {
            redirect('page/maintenance'); 
            exit();
        }

        $userId = $this->session->userdata('user_id');
        $user = $this->db->get_where('users', ['id' => $userId])->row_array();

        $this->db->select_sum('referral_earnings');
        $this->db->where('referred_by', $userId);
        $totalReferralEarnings = $this->db->get('users')->row()->referral_earnings ?? 0;

        $this->db->where('referred_by', $userId);
        $referralCount = $this->db->count_all_results('users');

        $data['user'] = $user;
        $data['totalReferralEarnings'] = $totalReferralEarnings;
        $data['referralCount'] = $referralCount;
        $data['pageTitle'] = 'Dashboard';

        $this->render('dashboard', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('home');
    }

    // Metódus hozzáférési szintjének módosítása
    public function is_logged_in() {
        return $this->session->userdata('user_id') !== null;
    }
}


