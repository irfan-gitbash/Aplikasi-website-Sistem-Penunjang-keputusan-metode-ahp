<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-users"></i> Data Nasabah</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <!-- Tombol tambah nasabah dan export PDF -->
                <div class="row mb-3">
                    <div class="col-md-12 d-flex justify-content-start">
                        <a href="/spk_ahp/nasabah/add" class="btn btn-success me-2"><i class="fas fa-plus-circle"></i> Tambah Nasabah</a>
                        <button class="btn btn-secondary me-2" id="refreshBtn"><i class="fas fa-sync-alt"></i> Refresh</button>
                        <button class="btn btn-primary" id="exportBtn"><i class="fas fa-file-pdf"></i> Export (PDF)</button>
                    </div>
                </div>
                
                <!-- Dan pada bagian aksi -->
                <td>
                    <a href="/spk_ahp/nasabah/edit/<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0)" onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                </td>
                
                <!-- Dan pada script confirmDelete -->
                <script>
                function confirmDelete(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        window.location.href = '/spk_ahp/nasabah/delete/' + id;
                    }
                }
                
                $(document).ready(function() {
                    $('#dataTable').DataTable();
                    
                    $('#refreshBtn').click(function() {
                        location.reload();
                    });
                    
                    $('#exportBtn').click(function() {
                        window.location.href = '/spk_ahp/nasabah/export_pdf';
                    });
                });
                </script>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Nasabah</th>
                                <th>Nama Lengkap</th>
                                <th>NIK</th>
                                <th>Jenis Kelamin</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
                                <th>No. Telepon</th>
                                <th>Pekerjaan</th>
                                <th>Penghasilan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = $nasabah->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['kode_nasabah']) ?></td>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($row['nik']) ?></td>
                                <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                                <td><?= htmlspecialchars($row['tempat_lahir']) ?></td>
                                <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                                <td><?= htmlspecialchars($row['pekerjaan']) ?></td>
                                <td>Rp <?= number_format($row['penghasilan_bulanan'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($row['status_pernikahan']) ?></td>
                                <td>
                                    <a href="/spk_ahp/nasabah/edit/<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>