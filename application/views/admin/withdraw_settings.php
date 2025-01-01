<div class="container">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/settings_save') ?>">
        
        <div class="mb-3">
            <label for="zerochain_api" class="form-label">Zerochain API:</label>
            <input type="text" name="zerochain_api" id="zerochain_api" value="<?= isset($settings['zerochain_api']) ? $settings['zerochain_api'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="zerochain_privatekey" class="form-label">Zerochain Private Key</label>
            <input type="text" name="zerochain_privatekey" id="zerochain_privatekey" value="<?= isset($settings['zerochain_privatekey']) ? $settings['zerochain_privatekey'] : '' ?>" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label for="min_withdraw" class="form-label">Minimum withdraw: (ZER)</label>
            <input type="text" name="min_withdraw" id="min_withdraw" value="<?= isset($settings['min_withdraw']) ? $settings['min_withdraw'] : '' ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="manual_withdraw" class="form-label">Manual Withdraw status (Admin approval is required for payments.):</label>
            <select name="manual_withdraw" id="manual_withdraw" class="form-select">
                <option value="off" <?= isset($settings['manual_withdraw']) && $settings['manual_withdraw'] === 'off' ? 'selected' : '' ?>>Off</option>
                <option value="on" <?= isset($settings['manual_withdraw']) && $settings['manual_withdraw'] === 'on' ? 'selected' : '' ?>>On</option>
            </select>
        </div>

        
        <div class="mb-3">
        <label for="banner_header_withdraw" class="form-label">Banner Header</label>
        <textarea class="form-control" id="banner_header_withdraw" name="banner_header_withdraw" rows="3"><?= $settings['banner_header_withdraw'] ?? '' ?></textarea>
        </div>
			<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
