<div class="container mt-5">

    <!-- Siker vagy hibaüzenet -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <!-- Shop Settings -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Shop Settings</h5>
        </div>
        <div class="card-body">
        <form method="post" action="<?= base_url('admin/settings_save') ?>">
                <div class="mb-3">
                    <label for="energyshop_status" class="form-label">Energy Shop Status</label>
                    <select name="energyshop_status" id="energyshop_status" class="form-select">
                        <option value="on" <?= isset($settings['energyshop_status']) && $settings['energyshop_status'] === 'on' ? 'selected' : '' ?>>On</option>
                        <option value="off" <?= isset($settings['energyshop_status']) && $settings['energyshop_status'] === 'off' ? 'selected' : '' ?>>Off</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="banner_header_energyshop" class="form-label">Header Banner</label>
                    <textarea name="banner_header_energyshop" id="banner_header_energyshop" class="form-control"><?= $settings['banner_header_energyshop'] ?? '' ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="banner_footer_energyshop" class="form-label">Footer Banner</label>
                    <textarea name="banner_footer_energyshop" id="banner_footer_energyshop" class="form-control"><?= $settings['banner_footer_energyshop'] ?? '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
            </form>
        </div>
    </div>

    <!-- Csomagok táblázat -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Energy Cost</th>
                <th>ZERO Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($data['packages'] as $package): ?>
        <tr>
            <td><?= $package['id'] ?></td>
            <td><?= $package['name'] ?></td>
            <td><?= $package['energy_cost'] ?></td>
            <td><?= $package['zero_amount'] ?></td>
            <td>
                <!-- Szerkesztés -->
                <button class="btn btn-primary btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editPackageModal"
                        data-id="<?= $package['id'] ?>"
                        data-name="<?= $package['name'] ?>"
                        data-energy="<?= $package['energy_cost'] ?>"
                        data-zero="<?= $package['zero_amount'] ?>">
                    Edit
                </button>
                <!-- Törlés -->
                <a href="<?= base_url('admin/delete_package/' . $package['id']) ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Are you sure you want to delete this package?')">
                    Delete
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

    </table>

    <!-- Új csomag hozzáadása gomb -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPackageModal">Add New Package</button>
</div>

<!-- Új csomag hozzáadása modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('admin/energy_shop') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPackageModalLabel">Add New Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="save_package">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="energy_cost" class="form-label">Energy Cost</label>
                        <input type="number" class="form-control" id="energy_cost" name="energy_cost" required>
                    </div>
                    <div class="mb-3">
                        <label for="zero_amount" class="form-label">ZERO Amount</label>
                        <input type="number" step="0.00000001" class="form-control" id="zero_amount" name="zero_amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Szerkesztés modal -->
<div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('admin/energy_shop') ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="save_package">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_energy_cost" class="form-label">Energy Cost</label>
                        <input type="number" class="form-control" id="edit_energy_cost" name="energy_cost" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_zero_amount" class="form-label">ZERO Amount</label>
                        <input type="number" step="0.00000001" class="form-control" id="edit_zero_amount" name="zero_amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editPackageModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const energy = button.getAttribute('data-energy');
        const zero = button.getAttribute('data-zero');

        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_energy_cost').value = energy;
        document.getElementById('edit_zero_amount').value = zero;
    });
});
</script>
