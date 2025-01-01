<style>
.custom-table {
    width: 100%;
    border-radius: 5px;  /* Az egész táblázat körül */
    border: 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    background: rgba(255, 255, 255, 0.1);
    color: white;
    overflow: hidden;  /* Megakadályozza, hogy a border-radius-t megzavard */
}

.custom-table th, .custom-table td {
    padding: 8px 12px;
}

.custom-table th {
    background-color: #2a5298 !important; /* Kékes árnyalat a fejlécnek */
    color: white;
}

.custom-table tr:nth-child(even) {
    background-color: rgba(255, 255, 255, 0.2);
}

.txidlink a {
    color: white !important;
}
</style>

<h3 class="page-title">Withdraw</h3>

<div class="text-center">
<?= $settings['banner_header_withdraw']; ?>
</div>

<?php if ($this->session->flashdata('message')): ?>
    <div class="alert alert-info">
        <?= $this->session->flashdata('message') ?>
    </div>
<?php endif; ?>
<div class="text-center">

<p class="page-title">Minimum withdrawal amount: <?= $minZero ?> ZER</p>

<?php if ($balance >= $minZero): ?>
    <form method="POST" action="" id="withdrawForm">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <input type="hidden" name="withdraw" value="1">
        <button type="submit" class="btn btn-primary" id="withdrawButton">Request Withdrawal (<?= number_format($balance, 8) ?> ZER)</button>
    </form>
<?php else: ?>
    <p class="text-danger">Insufficient balance. You need at least <?= $minZero ?> ZER to withdraw.</p>
<?php endif; ?>

<?php if ($settings['manual_withdraw'] === 'on'): ?>
    <p class="text-warning">All withdrawals will be manually approved.</p>
<?php endif; ?>

</div>

<h3 class="mt-4 text-center page-title">Your Last 10 Withdrawals</h3>
<table class="custom-table">
    <thead>
        <tr>
            <th>Amount</th>
            <th>Transaction ID</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($withdrawals)): ?>
            <tr>
                <td colspan="4" class="text-center">No withdrawals yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($withdrawals as $withdrawal): ?>
                <tr>
                    <td><?= number_format($withdrawal['amount'], 8) ?> ZER</td>
                    <td class="txidlink">
                        <a href="https://zerochain.info/tx/<?= $withdrawal['txid'] ?>" target="_blank">
                            <?= substr($withdrawal['txid'], 0, 10) ?>...
                        </a>
                    </td>
                    <td>
                        <?php 
                            $statusClass = '';
                            switch ($withdrawal['status']) {
                                case 'Paid':
                                    $statusClass = 'text-success'; // Zöld
                                    break;
                                case 'Pending':
                                    $statusClass = 'text-warning'; // Narancs
                                    break;
                                case 'Rejected':
                                    $statusClass = 'text-danger'; // Piros
                                    break;
                                default:
                                    $statusClass = 'text-muted'; // Alapértelmezett
                                    break;
                            }
                        ?>
                        <span class="<?= $statusClass ?>"><b><?= $withdrawal['status'] ?></b></span>
                    </td>
                    <td><?= date('Y-m-d H:i:s', strtotime($withdrawal['requested_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<script>
    // A gomb inaktívvá tétele a kifizetés gombra kattintás után
    document.getElementById('withdrawForm').addEventListener('submit', function() {
        var submitButton = document.getElementById('withdrawButton');
        submitButton.disabled = true;
        submitButton.innerHTML = 'Processing...'; // Változtathatod a szöveget is, ha akarod
    });
</script>
