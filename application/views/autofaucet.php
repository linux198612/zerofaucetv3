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
<div class="container">
            <!-- Középső banner (row előtt) -->
            <div class="text-center banner">
                <?= $settings['banner_header_autofaucet']; ?>
            </div>

            <!-- Tartalom és oldalsávok -->
            <div class="row">
                <!-- Bal oldalsáv -->
                <div class="col-12 col-lg-3 banner banner-left">
							<?= $settings['banner_left_autofaucet']; ?>
                </div>

                <!-- Középső tartalom -->
                <div class="col-12 col-lg-6 text-center">

<div id="message-box" class="alert alert-danger" style="<?= $this->session->flashdata('error') ? '' : 'display: none;' ?>">
   <?= $this->session->flashdata('error'); ?>
</div>

<div id="message-box" class="alert alert-success" style="<?= $this->session->flashdata('points_earned') ? '' : 'display: none;' ?>">
    <?= $this->session->flashdata('points_earned') ? $this->session->flashdata('points_earned') . ' ZER and ' . $energyReward . ' Energy earned!' : '' ?>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card bg-light">
            <div class="card-header">Reward per <?= $timeAuto ?> seconds:</div>
            <div class="card-body">
                <p class="card-text"><?= $zerosEarnedFormatted ?> ZER</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-light">
            <div class="card-header">Energy Reward</div>
            <div class="card-body">
                <p class="card-text"><?= $settings['rewardEnergy']; ?> energy per claim</p>
            </div>
        </div>
    </div>
</div>

<div class="progress mt-4">
    <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"><?= $timeAuto ?> seconds</div>
</div>

<form id="collect-form" method="POST" action="<?= site_url('autofaucet') ?>">
<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
    <input type="hidden" name="action" value="collect">
</form>
                </div>
                <!-- Jobb oldalsáv -->
                <div class="col-12 col-lg-3 banner banner-right">
                    <?= $settings['banner_right_autofaucet']; ?>
                </div>
            </div>

            <!-- Alsó banner (row után) -->
            <div class="text-center banner">
                <?= $settings['banner_footer_autofaucet']; ?>
            </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let interval;
    let timeLeft = <?= $timeAuto ?>; // Countdown idő (másodperc)
    const focusRequired = "<?= $focusAuto ?>" === "yes"; // PHP változó áthozása

    function startCountdown() {
        const progressBar = document.getElementById('progress-bar');

        interval = setInterval(() => {
            if (!focusRequired || document.hasFocus()) { // Ellenőrzi a fókusz szükségességét
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    document.getElementById('collect-form').submit(); // Automatikus POST kérés
                } else {
                    timeLeft--;
                    progressBar.style.width = (timeLeft / <?= $timeAuto ?>) * 100 + '%';
                    progressBar.innerText = timeLeft + ' seconds';
                }
            }
        }, 1000);
    }

    // Indítsa az autofaucetet az oldal betöltésekor
    startCountdown();

    // Automatikusan elrejti az üzeneteket 5 másodperc után
    setTimeout(function () {
        document.getElementById('message-box').style.display = 'none';
    }, 5000); // 5000 ms = 5 másodperc
});
</script>
