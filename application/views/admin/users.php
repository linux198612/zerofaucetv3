<h2>User Management</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Address</th>
            <th>Balance</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['address']) ?></td>
            <td><?= number_format($user['balance'], 8) ?></td>
            <td>
                <a href="<?= base_url('admin/user_details/' . $user['id']) ?>" class="btn btn-info btn-sm">Details</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
