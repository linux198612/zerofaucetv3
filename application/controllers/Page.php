<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // Maintenance mode check
        $maintenance = $this->db->get_where('settings', ['name' => 'maintenance'])->row()->value;
        if ($maintenance == 'on') {
            redirect('maintenance');
        }

 
    }

    public function maintenance() {
        // A karbantartás oldalt itt tölthetjük be
        $this->load->view('maintenance');
    }
}
