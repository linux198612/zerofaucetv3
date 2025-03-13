<h1 class="page-title"><?= $pageTitle ?></h1>

<?php
// 1 Credit átszámítása Zero-ra
$creditInUSD = 1 * 0.00001; // 1 Credit USD-ben
$creditInZero = $creditInUSD / $settings['currency_value']; // USD-ből Zero-ra konvertálás
?>

<p style="text-align: center; font-size: 24px; color: white; font-weight: bold;">
    <strong>1 Credit is equal to:</strong> <?= number_format($creditInZero, 8) ?> Zero
</p>

<?= $iframe ?>
