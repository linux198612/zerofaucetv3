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
    
    .card-footer {
        background:  #1e3a5f; /* Sötétkék háttér */
        color: #fff;
    }

</style>

<div class="container">
    <h1 class="my-4 page-title">Bitcotasks Shortlinks</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($shortlinks['data'])): ?>
         <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
         				   <p><strong>Total clickable shortlinks:</strong> <?= $total_clicks ?></p>
      				      <p><strong>Total available reward:</strong> <?= number_format($total_reward, 2) . ' ' . htmlspecialchars($shortlinks['data'][0]['currency_name'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards -->
        <div class="row">
            <?php foreach ($shortlinks['data'] as $index => $link): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($link['title']) ?></h5>
                            <p class="card-text">
                                <strong>Reward:</strong> <?= htmlspecialchars($link['reward']) . ' ' . htmlspecialchars($link['currency_name']) ?><br>
                                <strong>Available:</strong> <?= htmlspecialchars($link['available']) ?><br>
                                <strong>Limit:</strong> <?= htmlspecialchars($link['limit']) ?>
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="<?= htmlspecialchars($link['url']) ?>" class="btn btn-primary" target="_blank">Visit</a>
                        </div>
                    </div>
                </div>

                <?php if (($index + 1) % 4 == 0): ?>
                    </div><div class="row">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No shortlinks available.</p>
    <?php endif; ?>
</div>
