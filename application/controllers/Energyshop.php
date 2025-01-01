<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Energyshop extends My_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['settings'] = $this->settings;

        // Karbantartás ellenőrzés
        if (check_maintenance()) {
            redirect('page/maintenance');
            exit();
        }

        // Felhasználó session ellenőrzés
        if (!$this->session->userdata('user_id')) {
            redirect('home');
        }

        $userId = $this->session->userdata('user_id');

        // Felhasználó adatainak lekérdezése (energy)
        $user = $this->db->select('energy')->where('id', $userId)->get('users')->row();

        // Shop csomagok lekérdezése
        $packages = $this->db->get('energyshop_packages')->result();

        $data['user_energy'] = $user->energy;
        $data['packages'] = $packages;
        $data['pageTitle'] = 'Energy Shop';

        // Nézet renderelése
        $this->render('energyshop', $data);
    }

    public function buy() {
        // Session ellenőrzés
        if (!$this->session->userdata('user_id')) {
            redirect('home');
        }

        // Ellenőrizzük, hogy a kérés POST típusú-e
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $packageId = $this->input->post('packageId');
        if (!$packageId) {
            show_404();
        }

        $userId = $this->session->userdata('user_id');

        // Felhasználó adatainak lekérdezése
        $user = $this->db->select('energy')->where('id', $userId)->get('users')->row();

        // Csomag adatainak lekérdezése
        $package = $this->db->where('id', $packageId)->get('energyshop_packages')->row();

        // Ellenőrzés: elég energy
        if ($user->energy < $package->energy_cost) {
            $this->session->set_flashdata('error', 'Not enough energy!');
            redirect('energyshop');
        }

        // Vásárlás végrehajtása
        $this->db->set('energy', 'energy - ' . $package->energy_cost, FALSE);
        $this->db->set('balance', 'balance + ' . $package->zero_amount, FALSE);
        $this->db->where('id', $userId);
        $this->db->update('users');

        $this->session->set_flashdata('success', 'Purchase successful!');
        redirect('energyshop');
    }
}
