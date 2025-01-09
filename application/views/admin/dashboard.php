<div class="container">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/settings_save') ?>">

        <div class="mb-3">
            <label for="faucet_name" class="form-label">Faucet Name</label>
            <input type="text" name="faucet_name" id="faucet_name" value="<?= isset($settings['faucet_name']) ? $settings['faucet_name'] : '' ?>" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="referral_percent" class="form-label">Referral Reward (%)</label>
            <input type="text" name="referral_percent" id="referral_percent" value="<?= isset($settings['referral_percent']) ? $settings['referral_percent'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="maintenance" class="form-label">Maintenance Mode</label>
            <select name="maintenance" id="maintenance" class="form-select">
                <option value="off" <?= isset($settings['maintenance']) && $settings['maintenance'] === 'off' ? 'selected' : '' ?>>Off</option>
                <option value="on" <?= isset($settings['maintenance']) && $settings['maintenance'] === 'on' ? 'selected' : '' ?>>On</option>
            </select>
        </div>
        
        <div class="mb-3">
        <label for="banner_header_dashboard" class="form-label">Banner Header</label>
        <textarea class="form-control" id="banner_header_dashboard" name="banner_header_dashboard" rows="3"><?= $settings['banner_header_dashboard'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
        <label for="banner_footer_dashboard" class="form-label">Banner Footer</label>
        <textarea class="form-control" id="banner_footer_dashboard" name="banner_footer_dashboard" rows="3"><?= $settings['banner_footer_dashboard'] ?? '' ?></textarea>
       </div>
			<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>

