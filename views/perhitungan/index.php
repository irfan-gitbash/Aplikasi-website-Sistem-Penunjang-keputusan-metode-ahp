<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Perhitungan AHP</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Hasil Perhitungan</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <a href="/spk_ahp/perhitungan/hitungPengajuan" class="btn btn-primary mr-2">Hitung</a>
                                <a href="/spk_ahp/perhitungan/exportPDF" class="btn btn-secondary">Export (PDF)</a>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Bobot</th>
                                            <th>Peringkat</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Ambil hasil perhitungan dari database
                                        $hasil = $this->perhitungan->getHasilPerhitungan();
                                        
                                        if (empty($hasil)) {
                                            echo '<tr><td colspan="4" class="text-center">Belum ada data perhitungan. Silakan klik tombol Hitung.</td></tr>';
                                        } else {
                                            foreach ($hasil as $row) {
                                                echo '<tr>';
                                                echo '<td>' . $row['nama_lengkap'] . '</td>';
                                                // Format bobot dengan 5 digit desimal (menghilangkan digit setelah 00000)
                                                echo '<td>' . number_format($row['nilai_bobot'], 5, '.', '') . '</td>';
                                                echo '<td>' . $row['peringkat'] . '</td>';
                                                echo '<td>' . $row['status'] . '</td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>