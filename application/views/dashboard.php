<h1 class="mb-4 page-title">Dashboard</h1>

<!-- Zero árfolyam megjelenítése -->
<div class="text-center mb-3">
    <h5 class="text-primary">Current Zero Value</h5>
    <p class="highlight font-weight-bold text-lg"><?= $settings['currency_value']; ?> USD per 1 Zero</p>
</div>

<div class="text-center mb-4">
    <?= $settings['banner_header_dashboard']; ?>
</div>
<?php if ($this->session->flashdata('message')): ?>
    <div class="alert alert-info">
        <?= $this->session->flashdata('message') ?>
    </div>
<?php endif; ?>
<!-- Fő információk -->
<div class="row g-4">
    <!-- Address Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card dashboard-card text-center p-4 shadow-sm rounded border-0">
            <h5 class="card-title text-dark">Address</h5>
            <p class="masked-address text-muted"><?= substr($user['address'], 0, 5) . "..." . substr($user['address'], -5) ?></p>
        </div>
    </div>

    <!-- Balance Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card dashboard-card text-center p-4 shadow-sm rounded border-0">
            <h5 class="card-title text-dark">Balance</h5>
            <p class="highlight text-success"><?= number_format($user['balance'], 8) ?> ZER</p>
        </div>
    </div>

    <!-- Total Withdrawals Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card dashboard-card text-center p-4 shadow-sm rounded border-0">
            <h5 class="card-title text-dark">Total Withdrawals</h5>
            <p class="highlight text-danger"><?= number_format($user['total_withdrawals'], 8) ?> ZER</p>
        </div>
    </div>

    <!-- Offerwalls Credits Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card dashboard-card text-center p-4 shadow-sm rounded border-0">
            <h5 class="card-title text-dark">Credits</h5>
            <p class="highlight text-info"><?= number_format($user['credits'],2) ?> Credits</p>
            <p class="small text-muted">
                ~ <?= number_format((($user['credits'] / 1000) * 0.01) / $settings['currency_value'], 8) ?> ZER
            </p>
            <form method="post" action="<?= base_url('dashboard/convert_credits') ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <button type="submit" class="btn btn-sm btn-primary" <?= $user['credits'] == 0 ? 'disabled' : '' ?>>Convert to Zero</button>
            </form>
        </div>
    </div>

<!-- Energy Card -->
<?php if (isset($settings['energyshop_status']) && $settings['energyshop_status'] == 'on'): ?>
<div class="col-md-6 col-lg-4">
    <div class="card dashboard-card text-center p-4 shadow-sm rounded border-0">
        <h5 class="card-title text-dark">Energy</h5>
        <p class="highlight text-warning"><?= $user['energy'] ?> <i class="bi bi-lightning text-warning"></i></p>
    </div>
</div>
<?php endif; ?>
    <!-- Referral Earnings Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card dashboard-card text-center p-4 shadow-sm rounded border-0">
            <h5 class="card-title text-dark">Referral Earnings</h5>
            <p class="highlight text-success"><?= number_format($totalReferralEarnings, 8, '.', '') ?> ZER</p>
        </div>
    </div>

    <!-- Referrals Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card dashboard-card text-center p-4 shadow-sm rounded border-0">
            <h5 class="card-title text-dark">Referrals</h5>
            <p class="highlight text-primary"><?= $referralCount ?> User(s)</p>
        </div>
    </div>
</div>

<br>

<div class="text-center mt-5">
    <?= $settings['banner_footer_dashboard']; ?>
</div>
