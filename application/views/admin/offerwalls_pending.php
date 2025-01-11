<div class="container">

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <?php if (!empty($offerwalls)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Offerwall</th>
                    <th>IP Address</th>
                    <th>Amount</th>
                    <th>Transaction ID</th>
                    <th>Available At</th>
                    <th>Claim Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offerwalls as $offer): ?>
                    <tr>
                        <td><?= $offer['id'] ?></td>
                        <td><?= $offer['user_id'] ?></td>
                        <td><?= $offer['offerwall'] ?></td>
                        <td><?= $offer['ip_address'] ?></td>
                        <td><?= $offer['amount'] ?></td>
                        <td><?= $offer['trans_id'] ?></td>
                        <td><?= date('Y-m-d H:i:s', $offer['available_at']) ?></td>
                        <td><?= date('Y-m-d H:i:s', $offer['claim_time']) ?></td>
                        <td>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?= $offer['id'] ?>">
										  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="id" value="<?= $offer['id'] ?>">
										  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No pending offerwalls found.</div>
    <?php endif; ?>
</div>

