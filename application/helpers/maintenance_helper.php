<?php

if (!function_exists('check_maintenance')) {
    function check_maintenance() {
        $CI =& get_instance();  // CI globális objektumot hívunk
        $maintenance = $CI->db->get_where('settings', ['name' => 'maintenance'])->row_array();

        if (isset($maintenance['value']) && $maintenance['value'] === 'on') {
            return true;
        } else {
            return false;
        }
    }
}
