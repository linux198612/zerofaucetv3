<?php
class My_Controller extends CI_Controller {
    protected $settings = [];
    protected $currentUser = null;

    public function __construct() {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->settings = $this->Settings_model->get_settings();

        // Beállítjuk az aktuális felhasználót, ha van bejelentkezve
        $userId = $this->session->userdata('user_id');
        if ($userId) {
            $this->load->database();
            $this->currentUser = $this->db->get_where('users', ['id' => $userId])->row_array();
        }
    }

    protected function render($view, $data = []) {

        $data['settings'] = $this->settings;
        $data['user'] = $this->currentUser; // Elérhetővé tesszük a nézetek számára
        $data['content'] = $this->load->view($view, $data, TRUE);
        $this->load->view('template', $data);
    }

    protected function is_logged_in() {
        return $this->currentUser !== null; // Ellenőrizzük, hogy van-e bejelentkezett felhasználó
    }

    
    
}
