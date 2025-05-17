<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-file-alt"></i> Data Pengajuan</h5>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <div class="row mb-3">
                <div class="col-md-12 d-flex justify-content-start">
                    <a href="/spk_ahp/pengajuan/add" class="btn btn-success me-2"><i class="fas fa-plus-circle"></i> Tambah Pengajuan</a>
                    <button class="btn btn-secondary me-2" id="refreshBtn"><i class="fas fa-sync-alt"></i> Refresh</button>
                    <button class="btn btn-primary" id="exportBtn"><i class="fas fa-file-pdf"></i> Export (PDF)</button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Karakter</th>
                            <th>Modal</th>
                            <th>Kemampuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = $pengajuan->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_nasabah']) ?></td>
                            <td><?= htmlspecialchars($row['karakter']) ?></td>
                            <td><?= htmlspecialchars($row['modal']) ?></td>
                            <td><?= htmlspecialchars($row['kemampuan']) ?></td>
                            <td>
                                <?php 
                                $status = htmlspecialchars($row['status_pengajuan'] ?? 'Diajukan');
                                $badge_class = '';
                                switch($status) {
                                    case 'Diajukan':
                                        $badge_class = 'bg-info';
                                        break;
                                    case 'Diproses':
                                        $badge_class = 'bg-warning';
                                        break;
                                    case 'Diterima':
                                        $badge_class = 'bg-success';
                                        break;
                                    case 'Ditolak':
                                        $badge_class = 'bg-danger';
                                        break;
                                    default:
                                        $badge_class = 'bg-secondary';
                                }
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= $status ?></span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Ubah Status
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item update-status" href="#" data-id="<?= $row['id'] ?>" data-status="Diajukan">Diajukan</a></li>
                                        <li><a class="dropdown-item update-status" href="#" data-id="<?= $row['id'] ?>" data-status="Diproses">Diproses</a></li>
                                        <li><a class="dropdown-item update-status" href="#" data-id="<?= $row['id'] ?>" data-status="Diterima">Diterima</a></li>
                                        <li><a class="dropdown-item update-status" href="#" data-id="<?= $row['id'] ?>" data-status="Ditolak">Ditolak</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($pengajuan->rowCount() == 0): ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pengajuan</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "paging": true,
        "ordering": true,
        "info": true,
        "responsive": true
    });
    
    $('#refreshBtn').click(function() {
        location.reload();
    });
    
    $('#exportBtn').click(function() {
        window.location.href = '/spk_ahp/pengajuan/export_pdf';
    });
    
    // Fungsi untuk update status pengajuan
    $('.update-status').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var status = $(this).data('status');
        var row = $(this).closest('tr');
        
        console.log('Updating status for ID: ' + id + ' to: ' + status); // Tambahkan log untuk debugging
        
        if (confirm('Apakah Anda yakin ingin mengubah status menjadi ' + status + '?')) {
            $.ajax({
                url: '/spk_ahp/pengajuan/update_status',
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response); // Tambahkan log untuk debugging
                    
                    if (response.success) {
                        // Perbarui badge status tanpa reload halaman
                        var badgeClass = '';
                        switch(status) {
                            case 'Diajukan':
                                badgeClass = 'bg-info';
                                break;
                            case 'Diproses':
                                badgeClass = 'bg-warning';
                                break;
                            case 'Diterima':
                                badgeClass = 'bg-success';
                                break;
                            case 'Ditolak':
                                badgeClass = 'bg-danger';
                                break;
                            default:
                                badgeClass = 'bg-secondary';
                        }
                        
                        // Perbarui badge di kolom status
                        row.find('td:eq(5) .badge').removeClass().addClass('badge ' + badgeClass).text(status);
                        
                        alert('Status berhasil diubah');
                    } else if (response.error) {
                        alert('Error: ' + response.error);
                    } else {
                        alert('Terjadi kesalahan yang tidak diketahui');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }
    });
});
</script>