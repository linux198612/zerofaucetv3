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

</style>
<div class="container mt-4">
    <h2 class="page-title">Referral Page</h2>
    
    <div class="row g-6 mt-6">
        <div class="col-md-6">
            <div class="card dashboard-card text-center p-4">
                <h5 class="card-title">Referral Percent</h5>
                <p class="highlight"><?= $refPercent ?>%</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card dashboard-card text-center p-4">
                <h5 class="card-title">Total Referrals</h5>
                <p class="highlight"><?= count($referrals) ?> User(s)</p>
            </div>
        </div>
    </div>
    
    <div class="mb-3">
        <label for="referralLink" class="form-label"><strong class="page-title">Your Referral Link:</strong></label>
        <input type="text" class="form-control" id="referralLink" value="<?= $referralLink ?>" readonly>
    </div>

    <script>
    document.getElementById("referralLink").addEventListener("click", function() {
        this.select();
        this.setSelectionRange(0, 99999);
        document.execCommand("copy");
    });
    </script>
    
    <table class="custom-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Address</th>
            <th>Referral Earnings</th>
            <th>Joined</th>
            <th>Last Activity</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($referrals)): ?>
            <tr>
                <td colspan="5" class="text-center">No referrals yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($referrals as $index => $referral): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td>
                        <?= substr($referral['address'], 0, 5) ?>...<?= substr($referral['address'], -5) ?>
                    </td>
                    <td><?= number_format($referral['referral_earnings'], 8) ?> ZER</td>
                    <td><?= date('Y-m-d H:i:s', $referral['joined']) ?></td>
                    <td><?= date('Y-m-d H:i:s', $referral['last_activity']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
