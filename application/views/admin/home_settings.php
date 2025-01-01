<?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

<form method="post" action="<?= base_url('admin/settings_save') ?>">
    <div class="mb-3">
        <label for="banner_header_home" class="form-label">Top Center Banner</label>
        <textarea class="form-control" id="banner_header_home" name="banner_header_home" rows="3"><?= $settings['banner_header_home'] ?? '' ?></textarea>
    </div>
    <div class="mb-3">
        <label for="banner_footer_home" class="form-label">Bottom Center Banner</label>
        <textarea class="form-control" id="banner_footer_home" name="banner_footer_home" rows="3"><?= $settings['banner_footer_home'] ?? '' ?></textarea>
    </div>
		<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>
