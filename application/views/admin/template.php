<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
                <div class="pt-3">
                    <h5 class="text-white text-center">Admin Menu</h5>
                    <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                    <a href="<?= base_url('admin/home_settings') ?>">Home</a>
                    <a href="<?= base_url('admin/autofaucet') ?>">Autofaucet</a>
                    <a href="<?= base_url('admin/faucet') ?>">Faucet</a>
                    <?php if (isset($settings['manual_withdraw']) && $settings['manual_withdraw'] == 'on'): ?>
                    <a href="<?= base_url('admin/pending_withdraw') ?>">Pending Withdraw</a>
                    <?php endif; ?>
                    <a href="<?= site_url('admin/energy_shop') ?>">Energy Shop manager</a>
                    <a href="<?= site_url('admin/withdraw_settings') ?>">Withdraw settings</a>
                    <a href="<?= base_url('admin/change-password') ?>">Change Password</a>
                    <a href="<?= base_url('admin/logout') ?>">Logout</a>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1><?= $title ?? 'Admin Panel' ?></h1>
                </div>

                <!-- Dynamic content -->
                <?= $content ?>
            </main>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
