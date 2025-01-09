<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>

<p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p>
<p><strong>Balance:</strong> <?= number_format($user['balance'], 8) ?> ZER</p>
<p><strong>Energy:</strong> <?= htmlspecialchars($user['energy']) ?></p>

<h3>Manual Edit</h3>
<form method="post" action="<?= base_url('admin/user_details/' . $user['id']) ?>">
    <div class="form-group">
        <label for="balance">Balance (ZER)</label>
        <input type="text" class="form-control" id="balance" name="balance" value="<?= htmlspecialchars($user['balance']) ?>" required>
    </div>
    <div class="form-group">
        <label for="energy">Energy</label>
        <input type="number" class="form-control" id="energy" name="energy" value="<?= htmlspecialchars($user['energy']) ?>" required>
    </div>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>


<h3>Manual Withdrawal</h3>
<form method="post" action="<?= base_url('admin/process_withdrawal/' . $user['id']) ?>">
    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="text" step="0.01" class="form-control" id="amount" name="amount" required>
    </div>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
    <button type="submit" class="btn btn-primary">Withdraw</button>
</form>

<a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Back to Users</a>

