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
    
    <?php if (!empty($campaigns)): ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <p><strong>Total Available Ads:</strong> <?php echo $totalCampaigns; ?></p>
                        <p><strong>Total Rewards:</strong> <?php echo number_format($totalRewards, 2); ?> Credit</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4" id="campaigns-list">
            <?php foreach ($campaigns as $index => $campaign): ?>
                <div class="col campaign-card" id="campaign-<?php echo $index; ?>">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($campaign['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($campaign['description']); ?></p>
                            <p><strong>Reward:</strong> <?php echo htmlspecialchars($campaign['reward']) . ' ' . htmlspecialchars($campaign['currency_name']); ?></p>
                            <p><strong>Duration:</strong> <?php echo htmlspecialchars($campaign['duration']) . ' seconds'; ?></p>
                            <a href="<?php echo $campaign['url']; ?>" target="_blank" class="btn btn-primary btn-sm mt-auto" onclick="hideCampaign(<?php echo $index; ?>)">View Ad</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No available campaigns.</p>
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
