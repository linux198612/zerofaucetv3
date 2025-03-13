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

    function render_footer() {
        $CI =& get_instance();
        $CI->load->model('Settings_model');
        $settings = $CI->Settings_model->get_settings();
    
        // Footer HTML generálása
        $footerHtml = '<footer class="footer">
            <div class="container">
                <p>&copy; ' . date('Y') . ' ' . htmlspecialchars($settings['faucet_name']) . '. All rights reserved.</p>
                <p>Powered by <a href="http://coolscript.hu" target="_blank" rel="noopener noreferrer">CoolScript ZeroFaucet 0.97.0</a></p>
            </div>
        </footer>';
    
        return $footerHtml;
    }

