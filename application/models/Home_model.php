<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    public function handleUserLogin($zerocoin_address, $referral_id = null) {
        // Keresés a felhasználó címére az adatbázisban
        $this->db->where('address', $zerocoin_address);
        $query = $this->db->get('users');
        $user = $query->row();

        if ($user) {
            // Ha létezik a felhasználó, visszaadjuk az ID-ját
            return $user->id;
        } else {
            // Új felhasználó hozzáadása
            $data = [
                'address' => $zerocoin_address,
                'referral_id' => $referral_id,
                'balance' => 0,
                'total_withdrawals' => 0
            ];
            $this->db->insert('users', $data);
            return $this->db->insert_id(); // Az új felhasználó ID-ja
        }
    }

    public function getTotalUsers() {
        $this->db->select('COUNT(*) AS total');
        $query = $this->db->get('users');
        return $query->row()->total;
    }

    public function getTotalCollected() {
        $this->db->select_sum('balance');
        $query = $this->db->get('users');
        return $query->row()->balance;
    }

    public function getTotalWithdrawals() {
        $this->db->select_sum('total_withdrawals');
        $query = $this->db->get('users');
        return $query->row()->total_withdrawals;
    }

	public function getLastWithdrawals() {
	    // Az adatok lekérése a withdrawals és users táblából
	    $this->db->select('w.amount, w.requested_at, w.status, u.address'); // Az address mezőt is lekérjük a users táblából
	    $this->db->from('withdrawals w');
	    $this->db->join('users u', 'w.user_id = u.id'); // JOIN a users táblával
	    $this->db->order_by('w.requested_at', 'DESC');
	    $this->db->limit(10);
	    return $this->db->get()->result(); // Az eredményt visszaadjuk
	}

}
