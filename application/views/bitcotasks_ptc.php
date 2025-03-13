<style>
    .card {
        border: 0px;
    }
    .card-header {
        background:  #1e3a5f; /* Sötétkék háttér */
        color: #fff;
    }

    .card-body {
        background: #2a5298;
        color: #fff;
    }
</style>

<div class="container">
    <h1 class="mb-4 page-title">PTC Bitcotasks</h1>
    
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($campaigns)): ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?php 
                        // Total Rewards in Zero kiszámítása
                        $totalRewardsInUSD = $totalRewards * 0.00001; // Összjutalom centben (USD)
                        $totalRewardsInZero = $totalRewardsInUSD / $settings['currency_value']; // Zero Coin-ban
                        ?>
                        <p><strong>Total Available Ads:</strong> <?php echo $totalCampaigns; ?></p>
                        <p><strong>Total Rewards:</strong> <?php echo number_format($totalRewards, 2); ?> Credit</p>
                        <p><strong>Total Rewards in Zero:</strong> <?php echo number_format($totalRewardsInZero, 8); ?> Zero</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4" id="campaigns-list">
            <?php foreach ($campaigns as $index => $campaign): 
                // Számítsuk ki a Reward értékét Zero-ban
                $currencyValue = $settings['currency_value']; // Elérjük a currency_value-t a settings tömbből
                $rewardInUSD = $campaign['reward'] * 0.00001; // Creditből USD
                $rewardInZero = $rewardInUSD / $currencyValue; // USD-ből Zero-ra konvertálás
            ?>
                <div class="col campaign-card" id="campaign-<?php echo $index; ?>">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($campaign['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($campaign['description']); ?></p>
                            <p><strong>Reward:</strong> <?php echo htmlspecialchars($campaign['reward']) . ' Credit'; ?></p>
                            <p><strong>Reward in Zero:</strong> <?php echo number_format($rewardInZero, 8); ?> Zero</p>
                            <p><strong>Duration:</strong> <?php echo htmlspecialchars($campaign['duration']) . ' seconds'; ?></p>
                            <a href="<?php echo $campaign['url']; ?>" target="_blank" class="btn btn-primary btn-sm mt-auto" onclick="hideCampaign(<?php echo $index; ?>)">View Ad</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="page-title text-center">No available ptc ads.</p>
    <?php endif; ?>
</div>

<script>
    function hideCampaign(index) {
        // Get the campaign card by ID and hide it
        const campaignCard = document.getElementById(`campaign-${index}`);
        if (campaignCard) {
            campaignCard.style.display = 'none';
        }
    }
</script>


