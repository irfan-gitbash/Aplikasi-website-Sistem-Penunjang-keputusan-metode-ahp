<!-- views/bobot/karakter.php -->
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-balance-scale"></i> Bobot Karakter</h5>
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
                    
                    <!-- Perbaikan: Ubah action ke save_karakter -->
                    <form method="POST" action="/spk_ahp/bobot/save_karakter">
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
                    
                    <!-- Tampilkan hasil perhitungan jika ada -->
                    <?php if (isset($konsisten)): ?>
                        <!-- Kode untuk menampilkan hasil perhitungan -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>