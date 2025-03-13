<h1 class="mb-4 page-title">Dashboard</h1>

<!-- Zero árfolyam megjelenítése -->
<div class="text-center mb-3">
    <h5 class="text-primary">Current Zero Value</h5>
    <p class="highlight font-weight-bold text-lg"><?= $settings['currency_value']; ?> USD per 1 Zero</p>
</div>

<div class="alert alert-info" role="alert">
    <strong>Important Information:</strong> Credits earned from Offerwalls and PTC(ZerAds) are now automatically converted to Zero Coins.  
    There is no need to manually convert your credits anymore!  
</div>

<div class="text-center mb-4">
    <?= $settings['banner_header_dashboard']; ?>
</div>

<!-- Üzenet megjelenítése -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success" id="success-message">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php elseif ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger" id="error-message">
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Fő információk -->
<div class="row g-4">
    <!-- Address Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-lg text-center p-4">
            <div class="icon-container mb-3 text-dark">
                <i class="bi bi-house-door display-4"></i>
            </div>
            <h5 class="card-title text-dark">Address</h5>
            <p class="masked-address text-muted">(ID: <?= $user['id'] ?>) <?= $user['address'] ?></p>
        </div>
    </div>

    <!-- Balance Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-lg text-center p-4">
            <div class="icon-container mb-3 text-success">
                <i class="bi bi-wallet2 display-4"></i>
            </div>
            <h5 class="card-title text-dark">Balance</h5>
            <p class="highlight text-success"><?= number_format($user['balance'], 8) ?> ZER</p>
        </div>
    </div>

    <!-- Total Withdrawals Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-lg text-center p-4">
            <div class="icon-container mb-3 text-danger">
                <i class="bi bi-arrow-down-left-circle display-4"></i>
            </div>
            <h5 class="card-title text-dark">Total Withdrawals</h5>
            <p class="highlight text-danger"><?= number_format($user['total_withdrawals'], 8) ?> ZER</p>
        </div>
    </div>

    <!-- Offerwalls Credits Card 
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-lg text-center p-4">
            <div class="icon-container mb-3 text-info">
                <i class="bi bi-gift display-4"></i>
            </div>
            <h5 class="card-title text-dark">Credits</h5>
            <p class="highlight text-info"><?= number_format($user['credits'], 2) ?> Credits 
            
                ~ <?= number_format((($user['credits'] / 1000) * 0.01) / $settings['currency_value'], 8) ?> ZER
            
            <form method="post" action="<?= base_url('dashboard/convert_credits') ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <button type="submit" class="btn btn-sm btn-primary" <?= $user['credits'] == 0 ? 'disabled' : '' ?>>Convert</button>
            </form>
            </p>
        </div>
    </div>-->

    <!-- Energy Card -->
    <?php if (isset($settings['energyshop_status']) && $settings['energyshop_status'] == 'on'): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-lg text-center p-4">
            <div class="icon-container mb-3 text-warning">
                <i class="bi bi-lightning-charge display-4"></i>
            </div>
            <h5 class="card-title text-dark">Energy</h5>
            <p class="highlight text-warning"><?= $user['energy'] ?> <i class="bi bi-lightning text-warning"></i></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Referral Earnings Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-lg text-center p-4">
            <div class="icon-container mb-3 text-success">
                <i class="bi bi-cash-stack display-4"></i>
            </div>
            <h5 class="card-title text-dark">Referral Earnings</h5>
            <p class="highlight text-success"><?= number_format($totalReferralEarnings, 8, '.', '') ?> ZER</p>
        </div>
    </div>

    <!-- Referrals Card -->
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-lg text-center p-4">
            <div class="icon-container mb-3 text-primary">
                <i class="bi bi-people display-4"></i>
            </div>
            <h5 class="card-title text-dark">Referrals</h5>
            <p class="highlight text-primary"><?= $referralCount ?> User(s)</p>
        </div>
    </div>
</div>

<br>

<div class="text-center mt-5">
    <?= $settings['banner_footer_dashboard']; ?>
</div>

<script type="text/javascript">
    setTimeout(function() {
        // Üzenet eltüntetése, ha van sikeres üzenet
        var successMessage = document.getElementById('success-message');
        var errorMessage = document.getElementById('error-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 5000); // 5000 ms = 5 másodperc
</script>

<style>
    .icon-container {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.05);
    }

    .highlight {
        font-size: 1.2rem;
        font-weight: 700;
    }
</style>

