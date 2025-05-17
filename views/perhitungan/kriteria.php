<!-- views/perhitungan/kriteria.php -->
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator"></i> Perhitungan AHP - Kriteria</h5>
                </div>
                <div class="card-body">
                    <h5 class="mb-3">Matriks Perbandingan Berpasangan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Bobot</th>
                                    <?php foreach ($namaKriteria as $i => $kriteria): ?>
                                        <th><?= $kriteria ?> (<?= $deskripsiKriteria[$i] ?>)</th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < count($namaKriteria); $i++): ?>
                                    <tr>
                                        <th class="bg-light"><?= $namaKriteria[$i] ?> (<?= $deskripsiKriteria[$i] ?>)</th>
                                        <?php for ($j = 0; $j < count($namaKriteria); $j++): ?>
                                            <td><?= number_format($matriksKriteria[$i][$j], 2) ?></td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endfor; ?>
                                <tr class="bg-light">
                                    <th>Jumlah</th>
                                    <?php foreach ($jumlahKolom as $jumlah): ?>
                                        <td><?= number_format($jumlah, 2) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Matriks Normalisasi</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Bobot</th>
                                    <?php foreach ($namaKriteria as $kriteria): ?>
                                        <th><?= $kriteria ?></th>
                                    <?php endforeach; ?>
                                    <th>Jumlah</th>
                                    <th>Prioritas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < count($namaKriteria); $i++): ?>
                                    <tr>
                                        <th class="bg-light"><?= $namaKriteria[$i] ?></th>
                                        <?php 
                                        $jumlahBaris = 0;
                                        for ($j = 0; $j < count($namaKriteria); $j++): 
                                            $jumlahBaris += $matriksNormalisasi[$i][$j];
                                        ?>
                                            <td><?= number_format($matriksNormalisasi[$i][$j], 3) ?></td>
                                        <?php endfor; ?>
                                        <td><?= number_format($jumlahBaris, 3) ?></td>
                                        <td class="bg-light font-weight-bold"><?= number_format($bobotPrioritas[$i], 3) ?></td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Uji Konsistensi</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Jumlah Baris</th>
                                    <th>Bobot Prioritas</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalHasil = 0;
                                for ($i = 0; $i < count($namaKriteria); $i++): 
                                    $hasil = $matriksBobot[$i] / $bobotPrioritas[$i];
                                    $totalHasil += $hasil;
                                ?>
                                    <tr>
                                        <td><?= $namaKriteria[$i] ?></td>
                                        <td><?= number_format($matriksBobot[$i], 4) ?></td>
                                        <td><?= number_format($bobotPrioritas[$i], 4) ?></td>
                                        <td><?= number_format($hasil, 4) ?></td>
                                    </tr>
                                <?php endfor; ?>
                                <tr class="bg-light">
                                    <td colspan="3">Total</td>
                                    <td><?= number_format($totalHasil, 4) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">λ max</td>
                                    <td><?= number_format($lambdaMax, 4) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Consistency Index (CI)</td>
                                    <td><?= number_format($CI, 4) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Random Index (RI)</td>
                                    <td><?= number_format($RI, 4) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Consistency Ratio (CR)</td>
                                    <td class="<?= $konsisten ? 'bg-success text-white' : 'bg-danger text-white' ?>">
                                        <?= number_format($CR, 4) ?> (<?= $konsisten ? 'Konsisten' : 'Tidak Konsisten' ?>)
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <div class="alert <?= $konsisten ? 'alert-success' : 'alert-danger' ?>">
                            <h5>Hasil Perhitungan:</h5>
                            <p>Consistency Ratio (CR): <?= number_format($CR, 4) ?></p>
                            <p>Matriks <?= $konsisten ? 'KONSISTEN' : 'TIDAK KONSISTEN' ?></p>
                            <?php if (!$konsisten): ?>
                                <p>Nilai CR harus <= 0.1 agar matriks dianggap konsisten.</p>
                            <?php endif; ?>
                        </div>
                        
                        <h5 class="mt-4">Bobot Prioritas:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Kriteria</th>
                                        <th>Bobot</th>
                                        <th>Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < count($namaKriteria); $i++): ?>
                                        <tr>
                                            <td><?= $namaKriteria[$i] ?> (<?= $deskripsiKriteria[$i] ?>)</td>
                                            <td><?= number_format($bobotPrioritas[$i], 4) ?></td>
                                            <td><?= number_format($bobotPrioritas[$i] * 100, 2) ?>%</td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>