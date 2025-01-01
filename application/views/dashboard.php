<h1 class="mb-4 page-title">Dashboard</h1>
<div class="text-center">
<?= $settings['banner_header_dashboard']; ?>
</div>
<!-- Fő információk -->
<div class="row g-4">
    <div class="col-md-4">
        <div class="card dashboard-card text-center p-4">
            <h5 class="card-title">Address</h5>
            <p class="masked-address"><?= substr($user['address'], 0, 5) . "..." . substr($user['address'], -5) ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card text-center p-4">
            <h5 class="card-title">Balance</h5>
            <p class="highlight"><?= number_format($user['balance'], 8) ?> ZER</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card text-center p-4">
            <h5 class="card-title">Total Withdrawals</h5>
            <p class="highlight"><?= number_format($user['total_withdrawals'], 8) ?> ZER</p>
        </div>
    </div>
</div>

<!-- További információk -->
<div class="row g-4 mt-4">
    <div class="col-md-4">
        <div class="card dashboard-card text-center p-4">
            <h5 class="card-title">Energy</h5>
            <p class="highlight"><?= $user['energy'] ?></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card text-center p-4">
            <h5 class="card-title">Referral Earnings</h5>
            <p class="highlight"><?= number_format($totalReferralEarnings, 8, '.', '') ?> ZER</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card text-center p-4">
            <h5 class="card-title">Referrals</h5>
            <p class="highlight"><?= $referralCount ?> User(s)</p>
       </div>
    </div>
</div>
<br>
<div class="text-center">
<?= $settings['banner_footer_dashboard']; ?>
</div>