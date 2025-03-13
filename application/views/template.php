<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'Default Title' ?></title>
<!-- Favicon -->
    <link rel="icon" href="<?= site_url('assets/favicon.png') ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">t>
     <style>
        body {
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            margin-top: 20px;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #1e3a5f; /* Sötétkék háttér */
            border-right: 1px solid #e0e0e0;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 10px;
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: transform 0.3s ease-in-out;
            padding-top: 60px;
        }
        .sidebar a {
            text-decoration: none;
            color: #ffffff; /* Fehér szöveg */
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: background-color 0.2s ease;
        }
        .sidebar a:hover {
            background-color: #2d5986; /* Világosabb kék hover */
        }

        .content-wrapper {
            margin-left: 250px;
            flex-grow: 1;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }
        .toggle-btn {
            display: none;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                height: 100%;
                z-index: 1050;
                flex-direction: column;
                padding-top: 60px;
            }
            .sidebar.show {
                left: 0;
            }
            .content-wrapper {
                margin-left: 0;
            }
            .toggle-btn {
                display: block;
                position: absolute;
                top: 15px;
                left: 15px;
                background-color: #f8f9fa;
                border: none;
                color: black;
                padding: 10px;
                font-size: 20px;
                cursor: pointer;
                z-index: 1060;
                border-radius: 5px;
            }
        }
        .masked-address {
            font-family: monospace;
        }
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.1);
            color: white; /* Kékes szöveg */
        }
        .highlight {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2a9d8f;
        }

        .footer {
            border: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.1);
            color: white; /* Kékes szöveg */
        }
        .footer a {
            color: white;
        }
        .page-title {
            color: white;
        }
        .sidebar hr {
    border: 0;
    border-top: 1px solid #ffffff; /* Fehér vonal */
    margin: 0px 0; /* Távolság a környező elemek között */
}
/* Nyíl alapértelmezetten */
.arrow {
    font-size: 10px;
    margin-left: 5px;
    transition: transform 0.3s ease; /* Animáció a nyíl elforgatásához */
}

/* Offerwalls menü alapértelmezett stílus */
.offerwalls-menu a {
    color: #ffffff;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    transition: background-color 0.2s ease;
}

.offerwalls-menu a:hover {
    background-color: #1e3a5f; /* Hover állapot a menüelemeknél */
}

/* Offerwalls menu alapértelmezett állapota */
.offerwalls-menu {
    display: none; /* Alapértelmezetten elrejtve */
    opacity: 0;
    transform: translateY(-20px); /* Kezdő pozíció */
    transition: opacity 0.3s ease, transform 0.3s ease; /* Fokozatos áttűnés és csúszás */
}

.offerwalls-menu a:hover {
     background-color: #2d5986; /* Világosabb kék hover */
}

/* Amikor a menü megjelenik */
.offerwalls-menu.show {
    display: block;
    opacity: 1;
    transform: translateY(0); /* Menü lefelé csúszik */
}

    </style>

</head>
<body>
    
    <!-- Toggle Button for Mobile Sidebar -->
    <button class="toggle-btn" id="sidebarToggle">&#9776;</button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="<?= site_url('dashboard') ?>" class="active">Dashboard</a>
        <a href="<?= site_url('referrals') ?>">Referrals</a>
        <a href="<?= site_url('withdraw') ?>">Withdraw</a>
        <hr> 
        <?php $pages = [
        'challenge' => 'Challenge',
    'autofaucet' => 'Autofaucet',
    'energyshop' => 'Energy Shop',
    'faucet' => 'Faucet',
    'bitcotasks_ptc' => 'PTC',
    'bitcotasks_shortlinks' => 'Shortlinks'
];

foreach ($pages as $key => $label) {
    if (isset($settings["{$key}_status"]) && $settings["{$key}_status"] == 'on') {
        echo '<a href="' . site_url($key) . '">' . $label . '</a>';
    }
} ?>
<?php if (isset($settings['zerads_ptc_status']) && $settings['zerads_ptc_status'] == 'on'): ?>
<a href="https://zerads.com/ptc.php?ref=<?= $settings['zerads_id']; ?>&user=<?= $this->session->userdata('user_id') ?>" target="_blank">PTC (zerads)</a>
<?php endif; ?>
        <!-- Offerwalls Menu -->
        <?php if (isset($settings['offerwalls_status']) && $settings['offerwalls_status'] == 'on'): ?>
            <a href="javascript:void(0)" class="menu-toggle" id="offerwalls-toggle">
                Offerwalls <span id="offerwalls-arrow" class="arrow">&#9660;</span>
            </a>
            <div class="offerwalls-menu" id="offerwalls-menu">
                <?php if (isset($settings['bitcotasks_status']) && $settings['bitcotasks_status'] == 'on'): ?>
                    <a href="<?= site_url('offerwalls/bitcotasks') ?>">Bitcotasks</a>
                <?php endif; ?>
                <?php if (isset($settings['offerwallmedia_status']) && $settings['offerwallmedia_status'] == 'on'): ?>
                    <a href="<?= site_url('offerwalls/offerwallmedia') ?>">OfferwallMedia</a>
                <?php endif; ?>
                <!-- Add more offerwall links here -->
            </div>
        <?php endif; ?>
        <hr> 
        <a href="<?= site_url('dashboard/logout') ?>">Logout</a>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">

    <?= isset($content) ? $content : ''; ?>

    <!-- Footer -->
    <?php echo render_footer(); ?>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle Sidebar Script -->
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });
        document.getElementById('offerwalls-toggle').addEventListener('click', function() {
            const menu = document.getElementById('offerwalls-menu');
            menu.classList.toggle('show'); // Toggle a "show" osztály a menü megjelenítéséhez
        });

    </script>
</body>
</html>


