<div class="container">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/settings_save') ?>">

        <div class="mb-3">
            <label for="autofaucet_status" class="form-label">Autofaucet Status</label>
            <select name="autofaucet_status" id="autofaucet_status" class="form-select">
            <option value="on" <?= isset($settings['autofaucet_status']) && $settings['autofaucet_status'] === 'on' ? 'selected' : '' ?>>On</option>
            <option value="off" <?= isset($settings['autofaucet_status']) && $settings['autofaucet_status'] === 'off' ? 'selected' : '' ?>>Off</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="autofaucet_reward" class="form-label">Autofaucet Reward</label>
            <input type="text" name="autofaucet_reward" id="autofaucet_reward" value="<?= isset($settings['autofaucet_reward']) ? $settings['autofaucet_reward'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="autofaucet_interval" class="form-label">Autofaucet Interval (seconds)</label>
            <input type="text" name="autofaucet_interval" id="autofaucet_interval" value="<?= isset($settings['autofaucet_interval']) ? $settings['autofaucet_interval'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="autofocus" class="form-label">Autofocus</label>
            <select name="autofocus" id="autofocus" class="form-select">
                <option value="yes" <?= isset($settings['autofocus']) && $settings['autofocus'] === 'yes' ? 'selected' : '' ?>>Yes</option>
                <option value="no" <?= isset($settings['autofocus']) && $settings['autofocus'] === 'no' ? 'selected' : '' ?>>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="rewardEnergy" class="form-label">Reward Energy</label>
            <input type="text" name="rewardEnergy" id="rewardEnergy" value="<?= isset($settings['rewardEnergy']) ? $settings['rewardEnergy'] : '' ?>" class="form-control">
        </div>

        <div class="mb-3">
        <label for="banner_header_autofaucet" class="form-label">Banner Header</label>
        <textarea class="form-control" id="banner_header_autofaucet" name="banner_header_autofaucet" rows="3"><?= $settings['banner_header_autofaucet'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
        <label for="banner_footer_autofaucet" class="form-label">Banner Footer</label>
        <textarea class="form-control" id="banner_footer_autofaucet" name="banner_footer_autofaucet" rows="3"><?= $settings['banner_footer_autofaucet'] ?? '' ?></textarea>
       </div>

       <div class="mb-3">
        <label for="banner_left_autofaucet" class="form-label">Banner Left</label>
        <textarea class="form-control" id="banner_left_autofaucet" name="banner_left_autofaucet" rows="3"><?= $settings['banner_left_autofaucet'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
        <label for="banner_right_autofaucet" class="form-label">Banner Right</label>
        <textarea class="form-control" id="banner_right_autofaucet" name="banner_right_autofaucet" rows="3"><?= $settings['banner_right_autofaucet'] ?? '' ?></textarea>
        </div>
			<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
