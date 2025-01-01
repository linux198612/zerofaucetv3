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

<h1 class="mb-4 page-title">Energy Shop</h1>
<div class="text-center">
<?= $settings['banner_header_energyshop']; ?>
</div>

<p class="page-title">Your energy: <?= $user_energy ?></p>
<table class="custom-table">
    <thead>
        <tr>
            <th>Package</th>
            <th>Cost</th>
            <th>Reward</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($packages as $package): ?>
        <tr>
            <td><?= $package->name ?></td>
            <td><?= $package->energy_cost ?> Energy</td>
            <td><?= number_format($package->zero_amount, 4) ?> ZER</td>
            <td>
                <?php if ($user_energy >= $package->energy_cost): ?>
							<form method="post" action="<?= site_url('energyshop/buy') ?>" onsubmit="disableButton(<?= $package->id ?>)">
							    <input type="hidden" name="packageId" value="<?= $package->id ?>">
							    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
							    <button type="submit" id="buyButton-<?= $package->id ?>" class="btn btn-primary">Buy</button>
							</form>
                <?php else: ?>
                    <button class="btn btn-secondary btn-sm" disabled>Not Enough Energy</button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>
<div class="text-center">
<?= $settings['banner_footer_energyshop']; ?>
</div>
<script>
function disableButton(packageId) {
    // Gomb letiltása és szöveg módosítása
    document.getElementById('buyButton-' + packageId).disabled = true;
    document.getElementById('buyButton-' + packageId).innerHTML = 'Processing...';
}
</script>