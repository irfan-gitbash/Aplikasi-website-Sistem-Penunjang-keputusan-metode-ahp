<!-- views/bobot/kemampuan.php -->
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-balance-scale"></i> Bobot Kemampuan</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/spk_ahp/bobot/save_kemampuan">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Bobot</th>
                                        <?php foreach ($namaKriteria as $kriteria): ?>
                                            <th><?= $kriteria ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < count($namaKriteria); $i++): ?>
                                        <tr>
                                            <th class="bg-light"><?= $namaKriteria[$i] ?></th>
                                            <?php for ($j = 0; $j < count($namaKriteria); $j++): ?>
                                                <td>
                                                    <?php if ($i == $j): ?>
                                                        <input type="text" class="form-control" name="matriks[<?= $i ?>][<?= $j ?>]" value="1.0" readonly>
                                                    <?php elseif ($i > $j): ?>
                                                        <input type="text" class="form-control" name="matriks[<?= $i ?>][<?= $j ?>]" value="<?= number_format($matriksKriteria[$i][$j], 2) ?>" readonly>
                                                    <?php else: ?>
                                                        <input type="number" step="0.01" min="0.01" class="form-control" name="matriks[<?= $i ?>][<?= $j ?>]" value="<?= number_format($matriksKriteria[$i][$j], 2) ?>" required>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-block w-100">SIMPAN</button>
                        </div>
                    </form>
                    
                    <?php if (isset($konsisten)): ?>
                        <div class="mt-4">
                            <h5>Hasil Perhitungan</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Kriteria</th>
                                            <th>Bobot Prioritas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < count($namaKriteria); $i++): ?>
                                            <tr>
                                                <td><?= $namaKriteria[$i] ?> (<?= $deskripsiKriteria[$i] ?>)</td>
                                                <td><?= number_format($bobotPrioritas[$i], 4) ?></td>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3">
                                <p>
                                    <strong>Consistency Ratio (CR):</strong> <?= number_format($CR, 4) ?>
                                    <?php if ($konsisten): ?>
                                        <span class="text-success"><i class="fas fa-check-circle"></i> Konsisten</span>
                                    <?php else: ?>
                                        <span class="text-danger"><i class="fas fa-times-circle"></i> Tidak Konsisten</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control {
        height: 35px;
        font-size: 14px;
    }
    
    .table th, .table td {
        padding: 0.5rem;
        vertical-align: middle;
    }
    
    /* Responsif untuk layar kecil */
    @media (max-width: 600px) {
        .form-control {
            height: 30px;
            font-size: 12px;
            padding: 0.25rem 0.5rem;
        }
        
        .table th, .table td {
            padding: 0.25rem;
            font-size: 12px;
        }
    }
</style>