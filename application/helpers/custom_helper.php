<?php
function timeElapsedString($datetime, $full = false) {
    // Ha a $datetime nem érvényes, akkor térj vissza egy alapértelmezett szöveggel
    if (!$datetime) {
        return "Invalid Date";
    }

    // Jelenlegi dátum és a kért dátum közötti különbség kiszámítása
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Ha nincs különbség
    if ($diff->y >= 1) {
        return $diff->y . ' year(s) ago';
    } elseif ($diff->m >= 1) {
        return $diff->m . ' month(s) ago';
    } elseif ($diff->d >= 1) {
        return $diff->d . ' day(s) ago';
    } elseif ($diff->h >= 1) {
        return $diff->h . ' hour(s) ago';
    } elseif ($diff->i >= 1) {
        return $diff->i . ' minute(s) ago';
    } else {
        return $diff->s . ' second(s) ago';
    }
}

    function get_banner_settings() {
        // CI szuperglobális hozzáférés az adatbázishoz
        $CI =& get_instance();
        $CI->load->database();

        // Lekérjük a beállításokat a settings táblából
        $banners = $CI->db->select('name, value')
                          ->where_in('name', ['banner_top', 'banner_bottom', 'banner_left', 'banner_right'])
                          ->get('settings')
                          ->result_array();

        // Az eredményeket asszociatív tömbbe helyezzük
        $bannerSettings = [];
        foreach ($banners as $banner) {
            $bannerSettings[$banner['name']] = $banner['value'];
        }

        // Visszaadjuk a beállításokat
        return $bannerSettings;
    }

    function render_footer() {
        $CI =& get_instance();
        $CI->load->model('Settings_model');
        $settings = $CI->Settings_model->get_settings();
    
        // Footer HTML generálása
        $footerHtml = '<footer class="footer">
            <div class="container">
                <p>&copy; ' . date('Y') . ' ' . htmlspecialchars($settings['faucet_name']) . '. All rights reserved.</p>
                <p>Powered by <a href="http://coolscript.hu" target="_blank" rel="noopener noreferrer">CoolScript ZeroFaucet 0.96.0</a></p>
            </div>
        </footer>';
    
        return $footerHtml;
    }

