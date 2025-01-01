<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Felhasználó bejelentkezése vagy regisztrálása
    public function handleUserLogin($zerocoin_address, $referral_id = null) {
        // Ellenőrizzük, hogy létezik-e a felhasználó az adatbázisban
        $this->db->where('address', $zerocoin_address);
        $query = $this->db->get('users');

        // IP cím lekérése
        $ip_address = $this->input->ip_address();
        $data = [
            'last_activity' => time(),
            'ip_address' => $ip_address
        ];

        if ($query->num_rows() == 1) {
            // Létező felhasználó: frissítjük az adatokat
            $user = $query->row();
            $this->db->where('id', $user->id);
            $this->db->update('users', $data);

            return $user->id;
        } else {
            // Új felhasználó létrehozása
            // Ha nincs referral_id, állítsuk 0-ra
            $referral_id = $referral_id ?? 0;

            $data['address'] = $zerocoin_address;
            $data['balance'] = 0;
            $data['joined'] = time();
            $data['referred_by'] = $referral_id;

            $this->db->insert('users', $data);
            return $this->db->insert_id();
        }
    }
}

