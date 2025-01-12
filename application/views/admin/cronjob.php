<div class="container mt-5">

    <!-- Siker vagy hibaüzenet -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>


    <div class="card mb-4">
        <div class="card-body">
 			<div class="alert alert-info">Coingecko cronjob: <?= base_url('cronjob/coingecko') ?><br>
													CoinMarketCap cronjob: <?= base_url('cronjob/cmc') ?>
 			</div> 
        <form method="post" action="<?= base_url('admin/settings_save') ?>">
		         <div class="mb-3">
		             <label for="currency_value" class="form-label">Zero currency value: (manual exchange rate setting without cronjob)</label>
		             <input type="text" name="currency_value" id="currency_value" value="<?= isset($settings['currency_value']) ? $settings['currency_value'] : '' ?>" class="form-control">
		         </div>
                <div class="mb-3">
                    <label for="cmc_api" class="form-label">CoinMarketCap api</label>
                    <input type="text" name="cmc_api" id="cmc_api" value="<?= isset($settings['cmc_api']) ? $settings['cmc_api'] : '' ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            </form>
        </div>
    </div>
   


</div>