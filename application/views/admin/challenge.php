<div class="container mt-4">
    <h1><?= $pageTitle ?></h1>
    <!-- Siker vagy hibaüzenet -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>


    <div class="card mb-4">
        <div class="card-body">
        <form method="post" action="<?= base_url('admin/settings_save') ?>">
                <div class="mb-3">
                    <label for="challenge_status" class="form-label">Challenge Status</label>
                    <select name="challenge_status" id="challenge_status" class="form-select">
                        <option value="on" <?= isset($settings['challenge_status']) && $settings['challenge_status'] === 'on' ? 'selected' : '' ?>>On</option>
                        <option value="off" <?= isset($settings['challenge_status']) && $settings['challenge_status'] === 'off' ? 'selected' : '' ?>>Off</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            </form>
        </div>
    </div>
    <?php if ($view === 'list'): ?>
        <a href="<?= base_url('admin/challenge/add') ?>" class="btn btn-primary mb-3">Add New Challenge</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Target</th>
                    <th>Reward</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($challenges as $challenge): ?>
                    <tr>
                        <td><?= $challenge['id'] ?></td>
                        <td><?= $challenge['name'] ?></td>
                        <td><?= $challenge['type'] ?></td>
                        <td><?= $challenge['target'] ?></td>
                        <td><?= $challenge['reward'] ?></td>
                        <td>
                            <a href="<?= base_url('admin/challenge/edit/' . $challenge['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= base_url('admin/challenge/delete/' . $challenge['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this challenge?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($view === 'form'): ?>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="name">Challenge Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= isset($challenge) ? $challenge['name'] : '' ?>" required>
            </div>
            <div class="form-group mb-3">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="faucet" <?= isset($challenge) && $challenge['type'] === 'faucet' ? 'selected' : '' ?>>Faucet</option>
                    <option value="offerwall" <?= isset($challenge) && $challenge['type'] === 'offerwall' ? 'selected' : '' ?>>Offerwall</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="target">Target</label>
                <input type="text" name="target" id="target" class="form-control" value="<?= isset($challenge) ? $challenge['target'] : '' ?>" required>
            </div>
            <div class="form-group mb-3">
                <label for="reward">Reward</label>
                <input type="text" name="reward" id="reward" class="form-control" value="<?= isset($challenge) ? $challenge['reward'] : '' ?>" required>
            </div>
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            <button type="submit" class="btn btn-success">Save Challenge</button>
            <a href="<?= base_url('admin/challenge') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    <?php endif; ?>
</div>
