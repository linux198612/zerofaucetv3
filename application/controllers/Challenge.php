<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challenge extends Member_Controller {

    public function __construct() {
        parent::__construct();
    }

    // Napi teljesítések ellenőrzése
    public function check_and_log_progress($userId) {
        $todayStart = strtotime('today midnight');  // A mai nap kezdete UNIX időbélyeg formájában

        // Faucet tranzakciók számának lekérése
        $this->db->select('COUNT(*) as count');
        $this->db->from('transactions');
        $this->db->where('userid', $userId);
        $this->db->where('type', 'Faucet');
        $this->db->where('timestamp >=', $todayStart);
        $faucetProgress = $this->db->get()->row_array()['count'] ?? 0;

        // Offerwall tranzakciók összegének lekérése
        $this->db->select('SUM(amount) as total');
        $this->db->from('offerwall_history');
        $this->db->where('user_id', $userId);
        $this->db->where('status', 'Paid');
        $this->db->where('claim_time >=', $todayStart);
        $offerwallProgress = $this->db->get()->row_array()['total'] ?? 0;

        return [
            'faucetProgress' => $faucetProgress,
            'offerwallProgress' => $offerwallProgress
        ];
    }

    // Napi kihívások lekérése és állapotának hozzáadása
    public function index() {
        $userId = $this->currentUser['id'];

        // Napi teljesítések ellenőrzése
        $progress = $this->check_and_log_progress($userId);

        // Aktív kihívások lekérése
        $this->db->select('*');
        $this->db->from('challenges');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        $challenges = $query->result_array();

// Kihívások teljesítési állapotának hozzáadása
foreach ($challenges as &$challenge) {
    // A progress érték hozzáadása a kihívásokhoz
    if ($challenge['type'] === 'faucet') {
        $challenge['progress'] = $progress['faucetProgress'];
    } elseif ($challenge['type'] === 'offerwall') {
        $challenge['progress'] = $progress['offerwallProgress'];
    }

    // Ellenőrizzük, hogy a felhasználó már begyűjtötte-e a jutalmat
    $this->db->from('challenge_rewards');
    $this->db->where('user_id', $userId);
    $this->db->where('challenge_id', $challenge['id']);
    $this->db->where('completed_at', date('Y-m-d'));
    $this->db->where('status', 'claimed');
    $alreadyClaimed = $this->db->count_all_results() > 0;

    // Hozzáadjuk a 'alreadyClaimed' kulcsot
    $challenge['alreadyClaimed'] = $alreadyClaimed;
}

        // Adatok visszaadása a nézethez
        $data['challenges'] = $challenges;
        $data['pageTitle'] = 'Daily Challenges';
        $this->_load_view('challenge', $data);
    }
    
public function claim_reward($challengeId) {
    $userId = $this->currentUser['id'];  // A felhasználó ID-ja

    // Kihívás adatainak lekérése
    $this->db->select('reward');
    $this->db->from('challenges');
    $this->db->where('id', $challengeId);
    $reward = $this->db->get()->row_array();

    if (!$reward) {
        log_message('error', 'Reward not found for challenge ID: ' . $challengeId);
        return false;
    }

    $rewardAmount = floatval($reward['reward']);

    // Ellenőrizzük, hogy a felhasználó már begyűjtötte-e a jutalmat az adott napra
    $this->db->from('challenge_rewards');
    $this->db->where('user_id', $userId);
    $this->db->where('challenge_id', $challengeId);
    $this->db->where('completed_at', date('Y-m-d'));
    $this->db->where('status', 'claimed');
    if ($this->db->count_all_results() > 0) {
        $this->session->set_flashdata('error', 'Reward already claimed today!');
        redirect('challenge');
    }

    // A felhasználó balance-jának frissítése
    $this->db->set('balance', 'balance + ' . $rewardAmount, FALSE);
    $this->db->where('id', $userId);
    $this->db->update('users');

    // Kihívás állapotának rögzítése a challenge_rewards táblába
    $this->db->from('challenge_rewards');
    $this->db->where('user_id', $userId);
    $this->db->where('challenge_id', $challengeId);
    $this->db->where('completed_at', date('Y-m-d'));
    $existingRecord = $this->db->get()->row_array();

    if ($existingRecord) {
        // Ha már létezik rekord, frissítjük a státuszt
        $this->db->where('id', $existingRecord['id']);
        $this->db->update('challenge_rewards', ['status' => 'claimed']);
    } else {
        // Ha nincs rekord, új rekordot hozunk létre
        $this->db->insert('challenge_rewards', [
            'user_id' => $userId,
            'challenge_id' => $challengeId,
            'completed_at' => date('Y-m-d'),
            'status' => 'claimed'
        ]);
    }

    // Sikeres jutalom igénylés
    $this->session->set_flashdata('success', 'Reward claimed successfully!');
    redirect('challenge');
}


}


