<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Settings_model'); // Model betöltése a beállításokhoz
    }

    public function index() {
        // Lekérjük az admin beállításokat
        $settings = $this->Settings_model->get_settings();
        
        // Ellenőrizzük, hogy az XP Shop engedélyezve van-e
        $data['show_xp_shop'] = isset($settings['xpshop']) && $settings['xpshop'] == 'on';
        
        // Az oldalt betöltjük a megfelelő adatokkal
        $this->load->view('template', $data);
    }
}
