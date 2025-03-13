<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challenge_model extends CI_Model {

public function get_user_progress($userId, $challengeType) {
    $today = strtotime('today');  // A mai nap kezdete

    if ($challengeType === 'faucet') {
        // A tranzakciók számát kell lekérdezni a 'Faucet' típusú tranzakciókhoz, nem az amount-ot
        $this->db->where('userid', $userId);
        $this->db->where('type', 'Faucet');
        $this->db->where('timestamp >=', $today);  // Csak a mai nap tranzakcióit számoljuk
        $this->db->from('transactions');
        $faucetCount = $this->db->count_all_results();  // A tranzakciók száma
        return $faucetCount;  // Visszaadja az elvégzett faucetek számát
    } elseif ($challengeType === 'offerwall') {
        $this->db->select_sum('amount');
        $this->db->where('user_id', $userId);
        $this->db->where('status', 'Paid');  // Csak a kifizetett krediteket számolja
        $this->db->where('claim_time >=', $today);  // Csak a mai nap offerwall rekordjait számolja
        $query = $this->db->get('offerwall_history');
        return $query->row()->amount ?? 0;  // Ha nincs tranzakció, akkor 0
    }
    return 0;
}

    
    // Aktív kihívások lekérése
    public function get_active_challenges($userId) {
        $this->db->select('*');
        $this->db->from('challenges');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();

        $challenges = $query->result_array();

        // Kihívások teljesítési állapotának hozzáadása
        foreach ($challenges as &$challenge) {
            // Megnézzük a felhasználó aktuális teljesítményét
            $progress = $this->get_user_progress($userId, $challenge['type']);
            $challenge['progress'] = $progress;
        }

        return $challenges;
    }

    public function can_claim_reward($userId, $challengeId) {
        $today = date('Y-m-d');

        // Ellenőrizzük, hogy teljesítette-e a kihívást aznap
        $this->db->from('challenge_rewards');
        $this->db->where('user_id', $userId);
        $this->db->where('challenge_id', $challengeId);
        $this->db->where('completed_at', $today);
        $this->db->where('status', 'pending');
        return $this->db->count_all_results() > 0;
    }

public function claim_reward($userId, $challengeId) {
    $today = date('Y-m-d');

    // Lekérdezzük a kihívás jutalmát
    $this->db->select('reward');
    $this->db->from('challenges');
    $this->db->where('id', $challengeId);
    $reward = $this->db->get()->row_array();

    if (!$reward) {
        log_message('error', 'Reward not found for challenge ID: ' . $challengeId);
        return false;  // Hibás esetben jelezzük a problémát
    }

    // Ellenőrizzük, hogy a reward érvényes szám-e, és float-ra konvertáljuk
    $rewardAmount = floatval($reward['reward']);

    // A felhasználó balance-jának frissítése
    $this->db->set('balance', 'balance + ' . $rewardAmount, FALSE);
    $this->db->where('id', $userId);
    $this->db->update('users');  // A 'users' tábla, ahol a balance tárolódik

    // Jutalom státuszának frissítése
    $this->db->where('user_id', $userId);
    $this->db->where('challenge_id', $challengeId);
    $this->db->where('completed_at', $today);
    $this->db->update('challenge_rewards', ['status' => 'claimed']);
    
    return true;  // Sikeres végrehajtás
}



public function calculate_faucet_progress($userId) {
    // A mai nap kezdete UNIX időbélyegben
    $todayStart = strtotime('today midnight');

    // Faucet tranzakciók számának lekérése az adott napon
    $this->db->select('COUNT(*) as count');
    $this->db->from('transactions');
    $this->db->where('userid', $userId);
    $this->db->where('type', 'Faucet');
    $this->db->where('timestamp >=', $todayStart);
    $result = $this->db->get()->row_array();
    return $result['count'] ?? 0;
}

public function calculate_offerwall_progress($userId) {
    // A mai nap kezdete UNIX időbélyegben
    $todayStart = strtotime('today midnight');

    // Offerwall kreditek összegzése az adott napon
    $this->db->select('SUM(amount) as total');
    $this->db->from('offerwall_history');
    $this->db->where('user_id', $userId);
    $this->db->where('status', 'Paid');
    $this->db->where('claim_time >=', $todayStart);
    $result = $this->db->get()->row_array();
    return $result['total'] ?? 0;
}

public function check_and_log_progress($userId) {
    // Aktuális napi dátum UNIX időbélyeg formájában
    $todayStart = strtotime('today midnight');  // A mai nap kezdete

    // Faucet tranzakciók számának lekérése
    $faucetProgress = $this->calculate_faucet_progress($userId);

    // Offerwall tranzakciók összegének lekérése
    $offerwallProgress = $this->calculate_offerwall_progress($userId);

    return [
        'faucetProgress' => $faucetProgress,
        'offerwallProgress' => $offerwallProgress
    ];
}

    
    public function get_all_challenges() {
    $this->db->order_by('id', 'DESC');
    return $this->db->get('challenges')->result_array();
}

public function get_challenge_by_id($id) {
    return $this->db->get_where('challenges', ['id' => $id])->row_array();
}

public function add_challenge($data) {
    $this->db->insert('challenges', $data);
}

public function update_challenge($id, $data) {
    $this->db->where('id', $id);
    $this->db->update('challenges', $data);
}

public function delete_challenge($id) {
    $this->db->where('id', $id);
    $this->db->delete('challenges');
}

}
