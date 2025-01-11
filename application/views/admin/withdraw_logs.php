<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success">
        <?= $this->session->flashdata('success') ?>
    </div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Address</th>
            <th>Error Message</th>
            <th>Logged At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($logs)): ?>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['id'] ?></td>
                    <td><?= $log['user_id'] ?></td>
                    <td><?= number_format($log['amount'], 8) ?></td>
                    <td><?= htmlspecialchars($log['address']) ?></td>
                    <td><?= htmlspecialchars($log['error_message']) ?></td>
                    <td><?= $log['logged_at'] ?></td>
                    <td>
                        <a href="<?= base_url('admin/delete_withdraw_log/' . $log['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this log entry?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">No logs found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
