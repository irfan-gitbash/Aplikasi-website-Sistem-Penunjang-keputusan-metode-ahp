<div class="container-fluid py-4">
    <?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); endif; ?>
    
    <div class="card shadow" style="max-width: 500px; margin: 0 auto;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Ekonomi</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="/spk_ahp/bobot/ekonomi">
                <div class="table-responsive">
                    <table class="table table-bordered" style="background-color: #f8f9fa;">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Bobot</th>
                                <?php foreach ($namaKriteria as $kriteria): ?>
                                    <th style="width: 80px; text-align: center;"><?= $kriteria ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($namaKriteria); $i++): ?>
                                <tr>
                                    <th style="text-align: center;"><?= $namaKriteria[$i] ?></th>
                                    <?php for ($j = 0; $j < count($namaKriteria); $j++): ?>
                                        <td style="text-align: center;">
                                            <?php if ($i == $j): ?>
                                                <input type="text" class="form-control" name="matriks[<?= $i ?>][<?= $j ?>]" value="1.0" readonly style="width: 60px; text-align: center; margin: 0 auto;">
                                            <?php elseif ($i > $j): ?>
                                                <input type="text" class="form-control" name="matriks[<?= $i ?>][<?= $j ?>]" value="<?= number_format($matriksKriteria[$i][$j], 2) ?>" readonly style="width: 60px; text-align: center; margin: 0 auto;">
                                            <?php else: ?>
                                                <input type="number" step="0.01" min="0.01" class="form-control" name="matriks[<?= $i ?>][<?= $j ?>]" value="<?= number_format($matriksKriteria[$i][$j], 2) ?>" required style="width: 60px; text-align: center; margin: 0 auto;">
                                            <?php endif; ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary" style="width: 150px;">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    
    .form-control {
        padding: 4px;
        height: auto;
    }
    
    .table th, .table td {
        padding: 8px;
        vertical-align: middle;
    }
</style>

<script>
    // Auto close alert after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        var alertElement = document.querySelector('.alert');
        if (alertElement) {
            setTimeout(function() {
                var alert = bootstrap.Alert.getOrCreateInstance(alertElement);
                alert.close();
            }, 3000);
        }
    });
</script>