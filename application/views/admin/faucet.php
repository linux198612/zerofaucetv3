<div class="container">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/settings_save') ?>">

        <div class="mb-3">
            <label for="faucet_status" class="form-label">Faucet status</label>
            <select name="faucet_status" id="faucet_status" class="form-select">
                <option value="on" <?= isset($settings['faucet_status']) && $settings['faucet_status'] === 'on' ? 'selected' : '' ?>>On</option>
                <option value="off" <?= isset($settings['faucet_status']) && $settings['faucet_status'] === 'off' ? 'selected' : '' ?>>Off</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="faucet_timer" class="form-label">Faucet Timer (seconds)</label>
            <input type="text" name="faucet_timer" id="faucet_timer" value="<?= isset($settings['faucet_timer']) ? $settings['faucet_timer'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="faucet_daily_limit" class="form-label">Daily Limit</label>
            <input type="text" name="faucet_daily_limit" id="faucet_daily_limit" value="<?= isset($settings['faucet_daily_limit']) ? $settings['faucet_daily_limit'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="faucet_reward" class="form-label">Reward (example: 0.0001 ZER)</label>
            <input type="text" name="faucet_reward" id="faucet_reward" value="<?= isset($settings['faucet_reward']) ? $settings['faucet_reward'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hcaptcha_pub_key" class="form-label">hcaptcha pub key</label>
            <input type="text" name="hcaptcha_pub_key" id="hcaptcha_pub_key" value="<?= isset($settings['hcaptcha_pub_key']) ? $settings['hcaptcha_pub_key'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="hcaptcha_sec_key" class="form-label">hcaptcha secret key</label>
            <input type="text" name="hcaptcha_sec_key" id="hcaptcha_sec_key" value="<?= isset($settings['hcaptcha_sec_key']) ? $settings['hcaptcha_sec_key'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
        <label for="banner_header_faucet" class="form-label">Banner Header</label>
        <textarea class="form-control" id="banner_header_faucet" name="banner_header_faucet" rows="3"><?= $settings['banner_header_faucet'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
        <label for="banner_footer_faucet" class="form-label">Banner Footer</label>
        <textarea class="form-control" id="banner_footer_faucet" name="banner_footer_faucet" rows="3"><?= $settings['banner_footer_faucet'] ?? '' ?></textarea>
       </div>

       <div class="mb-3">
        <label for="banner_left_faucet" class="form-label">Banner Left</label>
        <textarea class="form-control" id="banner_left_faucet" name="banner_left_faucet" rows="3"><?= $settings['banner_left_faucet'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
        <label for="banner_right_faucet" class="form-label">Banner Right</label>
        <textarea class="form-control" id="banner_right_faucet" name="banner_right_faucet" rows="3"><?= $settings['banner_right_faucet'] ?? '' ?></textarea>
        </div>

			<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>