<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offerwalls extends Member_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Offerwalls_model');
        
    }

    public function bitcotasks() {
        // API kulcs és iframe generálása
        $userId = $this->currentUser['id'];
        $api_key = $this->settings['bitcotasks_api']; // UPDATE YOUR API KEY HERE
		  $data['iframe'] = '<iframe style="width:100%;height:800px;border:0;padding:0;margin:0;" scrolling="yes" frameborder="0" src="https://bitcotasks.com/offerwall/' . $api_key . '/' . $userId . '"></iframe>';
        $data['pageTitle'] = 'Bitcotasks Offerwalls';

        // Rendereljük a nézetet
        $this->_load_view('offerwalls', $data);
    }

        public function offerwallmedia() {
        // API kulcs és iframe generálása
        $userId = $this->currentUser['id'];
        $api_key = $this->settings['offerwallmedia_api']; // UPDATE YOUR API KEY HERE
		  $data['iframe'] = '<iframe style="width:100%;height:800px;border:0;padding:0;margin:0;" scrolling="yes" frameborder="0" src="https://offerwallmedia.com/offerwall/' . $api_key . '/' . $userId . '"></iframe>';
        $data['pageTitle'] = 'OfferwallMedia Offerwalls';

        // Rendereljük a nézetet
        $this->_load_view('offerwalls', $data);
    }    
    
}
