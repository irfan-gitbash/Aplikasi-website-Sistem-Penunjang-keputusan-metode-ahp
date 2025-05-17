<div class="row">
    <div class="col-md-12">
        <?php if (isset($_SESSION['welcome_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['welcome_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['welcome_message']); ?>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-tachometer-alt"></i> Dashboard</h5>
            </div>
            <div class="card-body">
                <h4 class="text-center mb-4">Selamat Datang di Sistem Penunjang Keputusan Pinjaman Usaha Dagang</h4>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body text-center">
                                <h1><i class="fas fa-users"></i></h1>
                                <h5>Data Nasabah</h5>
                                <h3><?= $total_nasabah ?? 0 ?></h3>
                                <a href="nasabah" class="btn btn-light btn-sm mt-2">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body text-center">
                                <h1><i class="fas fa-file-alt"></i></h1>
                                <h5>Data Pengajuan</h5>
                                <h3><?= $total_pengajuan ?? 0 ?></h3>
                                <a href="pengajuan" class="btn btn-light btn-sm mt-2">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark mb-3">
                            <div class="card-body text-center">
                                <h1><i class="fas fa-check-circle"></i></h1>
                                <h5>Pengajuan Diterima</h5>
                                <h3><?= $pengajuan_diterima ?? 0 ?></h3>
                                <a href="pengajuan" class="btn btn-dark btn-sm mt-2">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white mb-3">
                            <div class="card-body text-center">
                                <h1><i class="fas fa-times-circle"></i></h1>
                                <h5>Pengajuan Ditolak</h5>
                                <h3><?= $pengajuan_ditolak ?? 0 ?></h3>
                                <a href="pengajuan" class="btn btn-light btn-sm mt-2">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Setelah bagian Pengajuan Terbaru -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Data Nasabah Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Nasabah</th>
                                                <th>Nama Lengkap</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Alamat</th>
                                                <th>No. Telepon</th>
                                                <th>Penghasilan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($nasabah_terbaru) && count($nasabah_terbaru) > 0): ?>
                                                <?php $no = 1; foreach ($nasabah_terbaru as $nasabah): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($nasabah['kode_nasabah']) ?></td>
                                                    <td><?= htmlspecialchars($nasabah['nama_lengkap']) ?></td>
                                                    <td><?= htmlspecialchars($nasabah['jenis_kelamin']) ?></td>
                                                    <td><?= htmlspecialchars($nasabah['alamat']) ?></td>
                                                    <td><?= htmlspecialchars($nasabah['no_telepon']) ?></td>
                                                    <td>Rp <?= number_format($nasabah['penghasilan_bulanan'], 0, ',', '.') ?></td>
                                                    <td>
                                                        <a href="nasabah/edit/<?= $nasabah['id'] ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                        <a href="javascript:void(0)" onclick="confirmDelete(<?= $nasabah['id'] ?>)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">Tidak ada data nasabah terbaru</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Pengajuan Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Nasabah</th>
                                                <th>Jumlah Pinjaman</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($pengajuan_terbaru) && count($pengajuan_terbaru) > 0): ?>
                                                <?php $no = 1; foreach ($pengajuan_terbaru as $pengajuan): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= date('d-m-Y', strtotime($pengajuan['tanggal'])) ?></td>
                                                    <td><?= $pengajuan['nama_nasabah'] ?></td>
                                                    <td>Rp <?= number_format($pengajuan['jumlah_pinjaman'], 0, ',', '.') ?></td>
                                                    <td>
                                                        <!-- Gunakan hanya kode ini -->
                                                        <?php if (isset($pengajuan['status_pengajuan']) && $pengajuan['status_pengajuan'] == 'Diterima'): ?>
                                                            <span class="badge bg-success">Diterima</span>
                                                        <?php elseif (isset($pengajuan['status_pengajuan']) && $pengajuan['status_pengajuan'] == 'Ditolak'): ?>
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        <?php elseif (isset($pengajuan['status_pengajuan']) && $pengajuan['status_pengajuan'] == 'Diproses'): ?>
                                                            <span class="badge bg-warning text-dark">Diproses</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning text-dark">Diajukan</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="pengajuan/detail?id=<?= $pengajuan['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Tidak ada data pengajuan terbaru</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>