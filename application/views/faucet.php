<style>

    .card {
        border: 0px;
    }
    .card-header {
        background:  #1e3a5f; /* Sötétkék háttér */
        color: #fff;
    }

    .card-body {
        background: #2a5298;
        color: #fff;
    }

</style>

<script src="https://hcaptcha.com/1/api.js" async defer></script>
<div class="container-fluid">
            <!-- Középső banner (row előtt) -->
            <div class="text-center banner">
                <?= $settings['banner_header_faucet']; ?>
            </div>

            <!-- Tartalom és oldalsávok -->
            <div class="row">
                <!-- Bal oldalsáv -->
                <div class="col-12 col-lg-3 banner banner-left">
							<?= $settings['banner_left_faucet']; ?>
                </div>

                <!-- Középső tartalom -->
                <div class="col-12 col-lg-6 text-center">

                    

<?php if ($this->session->flashdata('alert')): ?>
    <div class="alert alert-<?php echo explode('|', $this->session->flashdata('alert'))[0]; ?>">
        <?php echo explode('|', $this->session->flashdata('alert'))[1]; ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-header">Timer</div>
            <div class="card-body">
						<?php if ($limitReached): ?>
						    
						<?php elseif ($wait > 0): ?>
						    <p class="card-text"><span id="countdown"><?php echo $wait; ?></span></p>
						<?php else: ?>
						    <p class="card-text">You can now claim!</p>
						<?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-header">Reward</div>
            <div class="card-body">
                <p class="card-text"><?php echo number_format($reward, 8); ?> ZER</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-header">Daily Limit</div>
            <div class="card-body">
                <p class="card-text"><?php echo ($dailyLimit - $claimCountToday) . " / $dailyLimit"; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <?php if ($wait <= 0 && !$limitReached): ?>
            <form action="faucet" method="post">
                <div class="form-group">
                    <div class="h-captcha" data-sitekey="<?php echo $hCaptchaPubKey; ?>"></div>
                </div>

                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                
                <button type="submit" class="btn btn-primary" id="claimButton">
                    Claim 
                </button>
            </form>
        <?php endif; ?>
						<?php if ($limitReached): ?>
						    <p class="alert alert-danger">You have reached your daily claim limit.</p>
						<?php endif; ?>
 
    </div>
</div>
                    </div>

                <!-- Jobb oldalsáv -->
                <div class="col-12 col-lg-3 banner banner-right">
                    <?= $settings['banner_right_faucet']; ?>
                </div>
            </div>

            <!-- Alsó banner (row után) -->
            <div class="text-center banner">
                <?= $settings['banner_footer_faucet']; ?>
            </div>
        </div>

        <script>
    // Visszaszámlálás kezelése
    <?php if ($wait > 0): ?>
        var remainingTime = <?php echo $wait; ?>; // A PHP változó értékének átadása JavaScript-ben
        var countdownElement = document.getElementById('countdown');

        // Visszaszámláló frissítése másodpercenként
        var countdownInterval = setInterval(function() {
            if (remainingTime > 0) {
                remainingTime--;
                countdownElement.textContent = remainingTime;
            } else {
                clearInterval(countdownInterval);

                // Oldal újratöltése, amikor a visszaszámláló elérte a nullát
                location.reload(); 
            }
        }, 1000);
    <?php endif; ?>
</script>
