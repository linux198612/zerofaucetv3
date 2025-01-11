<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offerwalls extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Offerwalls_model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function bitcotasks() {
        // API kulcs és iframe generálása
        $api_key = $this->settings['bitcotasks_api']; // UPDATE YOUR API KEY HERE
		  $data['iframe'] = '<iframe style="width:100%;height:800px;border:0;padding:0;margin:0;" scrolling="yes" frameborder="0" src="https://bitcotasks.com/offerwall/' . $api_key . '/' . $this->currentUser['id'] . '"></iframe>';
        $data['pageTitle'] = 'Bitcotasks Offerwalls';

        // Rendereljük a nézetet
        $this->render('offerwalls', $data);
    }
}
