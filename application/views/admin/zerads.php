<div class="container mt-5">

    <!-- Siker vagy hibaüzenet -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>


    <div class="card mb-4">

        <div class="card-body">
 			<div class="alert alert-info">Postback url: <?= base_url('confirm/zerads') ?>?pwd=<?= $settings['zerads_password']; ?></div> 
        <form method="post" action="<?= base_url('admin/settings_save') ?>">
                <div class="mb-3">
                    <label for="zerads_ptc_status" class="form-label">Zerads PTC Status</label>
                    <select name="zerads_ptc_status" id="zerads_ptc_status" class="form-select">
                        <option value="on" <?= isset($settings['zerads_ptc_status']) && $settings['zerads_ptc_status'] === 'on' ? 'selected' : '' ?>>On</option>
                        <option value="off" <?= isset($settings['zerads_ptc_status']) && $settings['zerads_ptc_status'] === 'off' ? 'selected' : '' ?>>Off</option>
                    </select>
                </div>
                                <div class="mb-3">
                    <label for="zerads_id" class="form-label">Zerads ID</label>
                    <input type="text" name="zerads_id" id="zerads_id" value="<?= isset($settings['zerads_id']) ? $settings['zerads_id'] : '' ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="zerads_exchange_value" class="form-label">Zerads Exchange Value</label>
                    <input type="text" name="zerads_exchange_value" id="zerads_exchange_value" value="<?= isset($settings['zerads_exchange_value']) ? $settings['zerads_exchange_value'] : '' ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="zerads_password" class="form-label">ZerAds PTC Password</label>
                    <input type="text" name="zerads_password" id="zerads_password" value="<?= isset($settings['zerads_password']) ? $settings['zerads_password'] : '' ?>" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            </form>
        </div>
    </div>
   


</div>