<h3>Pending Withdrawals</h3>
<?php if ($this->session->flashdata('message')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('message'); ?>
    </div>
<?php elseif ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger">
        <?= $this->session->flashdata('error'); ?>
    </div>
<?php endif; ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($withdrawals as $withdrawal): ?>
            <tr>
                <td><?= $withdrawal['id'] ?></td>
                <td><?= $withdrawal['user_id'] ?></td>
                <td><?= number_format($withdrawal['amount'], 8) ?> ZER</td>
                <td><?= date('Y-m-d H:i:s', strtotime($withdrawal['requested_at'])) ?></td>
                <td>
                    <form method="POST" style="display: inline-block;">
                        <input type="hidden" name="id" value="<?= $withdrawal['id'] ?>">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                        <button name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                        <button name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
