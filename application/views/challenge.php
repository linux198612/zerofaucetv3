
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
<div class="container">
    <h1 class="mb-4 page-title">Daily Challenges</h1>

    <!-- Flash üzenetek -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <table class="custom-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Progress</th>
                <th>Reward</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($challenges as $challenge): ?>
                <tr>
                    <td><?= $challenge['name'] ?></td>
                    <td>
                        <!-- Jelenlegi teljesítmény kiírása -->
                        <?php if ($challenge['type'] === 'faucet'): ?>
                            <?= $challenge['progress'] ?> / <?= $challenge['target'] ?> faucet
                        <?php elseif ($challenge['type'] === 'offerwall'): ?>
                            <?= $challenge['progress'] ?> / <?= $challenge['target'] ?> credits
                        <?php endif; ?>
                    </td>
                    <td><?= $challenge['reward'] ?> ZER</td>
                    <td>
                        <?php if ($challenge['progress'] >= $challenge['target']): ?>
                            <?php if ($challenge['alreadyClaimed']): ?>
                                <button class="btn btn-secondary" disabled>Reward Already Claimed</button>
                            <?php else: ?>
                                <form method="POST" action="<?= base_url('challenge/claim_reward/'.$challenge['id']) ?>">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                    <button type="submit" class="btn btn-primary">Claim Reward</button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Not Completed</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

