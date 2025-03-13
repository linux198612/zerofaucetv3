<div class="container mt-5">

    <!-- Siker vagy hibaüzenet -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>


    <div class="card mb-4">
        <div class="card-body">
        <form method="post" action="<?= base_url('admin/settings_save') ?>">
                <div class="mb-3">
                    <label for="offerwalls_status" class="form-label">Offerwalls Status</label>
                    <select name="offerwalls_status" id="offerwalls_status" class="form-select">
                        <option value="on" <?= isset($settings['offerwalls_status']) && $settings['offerwalls_status'] === 'on' ? 'selected' : '' ?>>On</option>
                        <option value="off" <?= isset($settings['offerwalls_status']) && $settings['offerwalls_status'] === 'off' ? 'selected' : '' ?>>Off</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            </form>
        </div>
    </div>
    
        <div class="card mb-4">
        <div class="card-header">
            <h5>Bitcotasks Settings</h5>
        </div>
        <div class="card-body">
        <div class="alert alert-info">Postback url: <?= base_url('confirm/bitcotasks') ?></div>
        <form method="post" action="<?= base_url('admin/settings_save') ?>">
                <div class="mb-3">
                    <label for="bitcotasks_status" class="form-label">Bitcotasks Status</label>
                    <select name="bitcotasks_status" id="bitcotasks_status" class="form-select">
                        <option value="on" <?= isset($settings['bitcotasks_status']) && $settings['bitcotasks_status'] === 'on' ? 'selected' : '' ?>>On</option>
                        <option value="off" <?= isset($settings['bitcotasks_status']) && $settings['bitcotasks_status'] === 'off' ? 'selected' : '' ?>>Off</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bitcotasks_ptc_status" class="form-label">Bitcotasks PTC Status</label>
                    <select name="bitcotasks_ptc_status" id="bitcotasks_ptc_status" class="form-select">
                        <option value="on" <?= isset($settings['bitcotasks_ptc_status']) && $settings['bitcotasks_ptc_status'] === 'on' ? 'selected' : '' ?>>On</option>
                        <option value="off" <?= isset($settings['bitcotasks_ptc_status']) && $settings['bitcotasks_ptc_status'] === 'off' ? 'selected' : '' ?>>Off</option>
                    </select>
                </div>
                                <div class="mb-3">
                    <label for="bitcotasks_shortlinks_status" class="form-label">Bitcotasks Shortlinks Status</label>
                    <select name="bitcotasks_shortlinks_status" id="bitcotasks_shortlinks_status" class="form-select">
                        <option value="on" <?= isset($settings['bitcotasks_shortlinks_status']) && $settings['bitcotasks_shortlinks_status'] === 'on' ? 'selected' : '' ?>>On</option>
                        <option value="off" <?= isset($settings['bitcotasks_shortlinks_status']) && $settings['bitcotasks_shortlinks_status'] === 'off' ? 'selected' : '' ?>>Off</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bitcotasks_api" class="form-label">Bitcotasks Api Key</label>
                    <input type="text" name="bitcotasks_api" id="bitcotasks_api" value="<?= isset($settings['bitcotasks_api']) ? $settings['bitcotasks_api'] : '' ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="bitcotasks_secret" class="form-label">Bitcotasks Secret Key</label>
                    <input type="text" name="bitcotasks_secret" id="bitcotasks_secret" value="<?= isset($settings['bitcotasks_secret']) ? $settings['bitcotasks_secret'] : '' ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="bitcotasks_bearer_token" class="form-label">Bitcotasks Bearer Token</label>
                    <input type="text" name="bitcotasks_bearer_token" id="bitcotasks_bearer_token" value="<?= isset($settings['bitcotasks_bearer_token']) ? $settings['bitcotasks_bearer_token'] : '' ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            </form>
        </div>
    </div>
    
            <div class="card-body">
        <div class="alert alert-info">Postback url: <?= base_url('confirm/offerwallmedia') ?></div>
        <form method="post" action="<?= base_url('admin/settings_save') ?>">
                <div class="mb-3">
                    <label for="offerwallmedia_status" class="form-label">Offerwallmedia Status</label>
                    <select name="offerwallmedia_status" id="offerwallmedia_status" class="form-select">
                        <option value="on" <?= isset($settings['offerwallmedia_status']) && $settings['offerwallmedia_status'] === 'on' ? 'selected' : '' ?>>On</option>
                        <option value="off" <?= isset($settings['offerwallmedia_status']) && $settings['offerwallmedia_status'] === 'off' ? 'selected' : '' ?>>Off</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="offerwallmedia_api" class="form-label">Offerwallmedia Api Key</label>
                    <input type="text" name="offerwallmedia_api" id="offerwallmedia_api" value="<?= isset($settings['offerwallmedia_api']) ? $settings['offerwallmedia_api'] : '' ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="offerwallmedia_secret" class="form-label">Offerwallmedia Secret Key</label>
                    <input type="text" name="offerwallmedia_secret" id="offerwallmedia_secret" value="<?= isset($settings['offerwallmedia_secret']) ? $settings['offerwallmedia_secret'] : '' ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            </form>
        </div>
    </div>


</div>