<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends Member_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
 
    }

    public function maintenance() {
        // A karbantartás oldalt itt tölthetjük be
        $$this->_load_view('maintenance');
    }
}
