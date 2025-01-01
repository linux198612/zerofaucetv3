<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_settings() {
        // Lekérjük az összes beállítást az adatbázisból
        $this->db->select('name, value');
        $query = $this->db->get('settings'); // 'settings' tábla lekérése
        $settings = [];

        foreach ($query->result() as $row) {
            $settings[$row->name] = $row->value; // Beállítások tárolása asszociatív tömbben
        }

        return $settings;
    }
    
        public function get_setting($name) {
        $query = $this->db->get_where('settings', ['name' => $name], 1);
        return $query->row_array()['value'] ?? null;
    }

    public function update_setting($name, $value) {
        // Ellenőrizzük, hogy létezik-e a beállítás
        $query = $this->db->get_where('settings', ['name' => $name]);
    
        if ($query->num_rows() > 0) {
            // Ha létezik, akkor frissítjük
            $this->db->update('settings', ['value' => $value], ['name' => $name]);
        } else {
            // Ha nem létezik, új bejegyzést hozunk létre
            $this->db->insert('settings', ['name' => $name, 'value' => $value]);
        }
    }

}



?>