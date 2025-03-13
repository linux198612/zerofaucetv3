<?php
class My_Controller extends CI_Controller {
    protected $settings = [];
    protected $currentUser = null;

    public function __construct() {
        parent::__construct();
        $this->load->model('Settings_model');
        $this->settings = $this->Settings_model->get_settings();

    }


    
}

class Admin_Controller extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		

	 $current_uri = uri_string(); // Csak az aktuális URI-t vesszük
    if ($current_uri != 'admin' && $current_uri != 'admin/login') {
			if($this->session->userdata('admin') == NULL){
			 return redirect(site_url('admin'));
			}
		} elseif ($this->session->userdata('admin') != NULL) {
			return redirect(site_url('admin/dashboard'));
		}
	}

	protected function _load_view($view, $data = []) {
		    $data['content'] = $this->load->view("admin/{$view}", $data, TRUE);
		    $this->load->view('admin/template', $data);
		}
}

class Member_Controller extends MY_Controller
{
    function __construct()
    {
        parent::__construct();

        // Karbantartási mód ellenőrzése
        if (check_maintenance()) {
            redirect('page/maintenance');
            exit();
        }
        
        // Ellenőrizzük a bejelentkezést
        $userId = $this->session->userdata('user_id');
        if (!$userId || !is_numeric($userId)) {
            $this->session->sess_destroy();
            redirect(site_url());
            exit();
        }

        // Betöltjük az aktuális felhasználót
        $this->currentUser = $this->db->get_where('users', ['id' => $userId])->row_array();
        if (!$this->currentUser) {
            $this->session->sess_destroy();
            redirect(site_url());
            exit();
        }

        // Automatikusan átadjuk a felhasználói adatokat a nézetekhez
        $this->load->vars(['user' => $this->currentUser]);
    }

    protected function _load_view($view, $data = []) {
        // A beállításokat automatikusan hozzáadjuk az adatokhoz
        $data['settings'] = $this->settings;

        // Betöltjük a tartalmat a nézethez
        $data['content'] = $this->load->view("{$view}", $data, TRUE);

        // Betöltjük a sablont
        $this->load->view('template', $data);
    }
}

