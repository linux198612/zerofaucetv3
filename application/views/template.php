<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'Default Title' ?></title>
<!-- Favicon -->
    <link rel="icon" href="<?= site_url('assets/favicon.png') ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	 <script src="<?= site_url('assets/js/main.js') ?>"></script>
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
    'autofaucet' => 'Autofaucet',
    'energyshop' => 'Energy Shop',
    'faucet' => 'Faucet'
];

foreach ($pages as $key => $label) {
    if (isset($settings["{$key}_status"]) && $settings["{$key}_status"] == 'on') {
        echo '<a href="' . site_url($key) . '">' . $label . '</a>';
    }
} ?>
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
    </script>
</body>
</html>


