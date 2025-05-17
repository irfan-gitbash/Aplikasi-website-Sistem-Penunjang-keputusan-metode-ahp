<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-eye"></i> Detail Pengajuan Pinjaman</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="/spk_ahp/pengajuan" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Kode Pengajuan</th>
                            <td><?= htmlspecialchars($pengajuanData['kode_pengajuan']) ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <td><?= date('d-m-Y', strtotime($pengajuanData['tanggal_pengajuan'])) ?></td>
                        </tr>
                        <tr>
                            <th>Nasabah</th>
                            <td><?= htmlspecialchars($pengajuanData['nama_nasabah']) ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah Pinjaman</th>
                            <td>Rp <?= number_format($pengajuanData['jumlah_pinjaman'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>Jangka Waktu</th>
                            <td><?= $pengajuanData['jangka_waktu'] ?> bulan</td>
                        </tr>
                        <tr>
                            <th>Tujuan Pinjaman</th>
                            <td><?= htmlspecialchars($pengajuanData['tujuan_pinjaman']) ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Usaha</th>
                            <td><?= htmlspecialchars($pengajuanData['jenis_usaha']) ?></td>
                        </tr>
                        <tr>
                            <th>Lama Usaha</th>
                            <td><?= $pengajuanData['lama_usaha'] ?> tahun</td>
                        </tr>
                        <tr>
                            <th>Kondisi Ekonomi</th>
                            <td><?= htmlspecialchars($pengajuanData['kondisi_ekonomi']) ?></td>
                        </tr>
                        <tr>
                            <th>Karakter</th>
                            <td><?= htmlspecialchars($pengajuanData['karakter']) ?></td>
                        </tr>
                        <tr>
                            <th>Modal</th>
                            <td><?= htmlspecialchars($pengajuanData['modal']) ?></td>
                        </tr>
                        <tr>
                            <th>Kemampuan</th>
                            <td><?= htmlspecialchars($pengajuanData['kemampuan']) ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if (isset($pengajuanData['status_pengajuan']) && $pengajuanData['status_pengajuan'] == 'Diterima'): ?>
                                    <span class="badge bg-success">Diterima</span>
                                <?php elseif (isset($pengajuanData['status_pengajuan']) && $pengajuanData['status_pengajuan'] == 'Ditolak'): ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Diajukan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td><?= date('d-m-Y H:i:s', strtotime($pengajuanData['created_at'])) ?></td>
                        </tr>
                    </table>
                </div>
                
                <div class="mt-3">
                    <a href="/spk_ahp/pengajuan/edit/<?= $pengajuanData['id'] ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                    <a href="javascript:void(0)" onclick="confirmDelete(<?= $pengajuanData['id'] ?>)" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = '/spk_ahp/pengajuan/delete/' + id;
    }
}
</script>