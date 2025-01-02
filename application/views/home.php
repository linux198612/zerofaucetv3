<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'Zerocoin Faucet Login' ?></title>
    <!-- Favicon -->
    <link rel="icon" href="<?= site_url('assets/favicon.png') ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            font-family: 'Roboto', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            margin-top: auto;
            margin-bottom: auto;
        }
        .section {
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.3);
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #ff7b00 !important;
        }
        h1, h3 {
            color: #f8f9fa;
        }
        .btn-primary {
            background-color: #ff7b00;
            border: none;
        }
        .btn-primary:hover {
            background-color: #e66a00;
        }
        .footer {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #ddd;
        }
        table td {
            color: #fff !important;
            background: #1e3c72 !important;
            border-radius: 10px;
            margin-top: 1rem;
        }
        table th {
            background-color: #2a5298 !important;
            color: #fff !important;
        }
        .footer a {
            color: white;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('') ?>"><?= $faucetName ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('home') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('home/faq') ?>">FAQ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container text-center">
        <!-- Header -->
        <div class="section mb-4">
            <h1 class="display-5 fw-bold">Welcome to <?= $faucetName ?></h1>
            <p class="lead">Start collecting Zerocoins today!</p>
        </div>
        <div class="text-center">
            <?= $bannerHeaderHome; ?>
        </div>
        
        <!-- Login Section -->
        <div class="section mb-4" style="max-width: 600px; margin: 0 auto;">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"> <?= $error ?> </div>
            <?php endif; ?>

            <h3 class="mb-4">Login with Your Zerocoin Address</h3>

            <form method="POST" action="">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                <div class="mb-3">
                    <label for="zerocoin_address" class="form-label">Zerocoin Address</label>
                    <input type="text" class="form-control" id="zerocoin_address" name="zerocoin_address" placeholder="Enter your Zerocoin address" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>

        <!-- Stats Section -->
        <div class="row text-center">
            <div class="col-md-4">
                <div class="section">
                    <h5>Registered Users</h5>
                    <p class="display-6 fw-bold"> <?= $totalUsers ?> </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="section">
                    <h5>Total Collected</h5>
                    <p class="display-6 fw-bold"> <?= $totalCollected ?> ZER </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="section">
                    <h5>Total Withdrawals</h5>
                    <p class="display-6 fw-bold"> <?= $totalWithdrawals ?> ZER </p>
                </div>
            </div>
        </div>
        <br>
        <div class="text-center">
            <?= $bannerFooterHome; ?>
        </div>

        <!-- Last 10 Withdrawals -->
        <div class="section" style="max-width: 800px; margin: 0 auto;">
            <h4 class="mb-3">Last 10 Withdrawals</h4>
            <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th>Address</th>
                        <th>Amount</th>
                        <th>Time Ago</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdrawals as $row): ?>
                        <?php $addressMasked = substr($row->address, 0, 5) . '...' . substr($row->address, -5); ?>
                        <tr>
                            <td><?= $addressMasked ?></td>
                            <td><?= $row->amount ?> ZER</td>
                            <td><?= timeElapsedString($row->requested_at) ?></td>
                            <td class="<?php 
                                switch ($row->status) {
                                    case 'Paid': echo 'text-success'; break;
                                    case 'Pending': echo 'text-warning'; break;
                                    case 'Rejected': echo 'text-danger'; break;
                                    default: echo 'text-muted'; break;
                                }
                            ?>">
                                <?= htmlspecialchars($row->status) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <?php echo render_footer(); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
