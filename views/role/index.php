<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-tag"></i> Data Role</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <a href="role/add" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Role Baru</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Role</th>
                                <th>Deskripsi</th>
                                <th>Permissions</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($roles && $roles->rowCount() > 0): ?>
                                <?php $no = 1; while ($role = $roles->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($role['name']) ?></td>
                                        <td><?= htmlspecialchars($role['description']) ?></td>
                                        <td>
                                            <?php if (!empty($role['permissions'])): ?>
                                                <?php $permissions = explode(',', $role['permissions']); ?>
                                                <?php foreach ($permissions as $permission): ?>
                                                    <span class="badge bg-info"><?= htmlspecialchars($permission) ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Tidak ada permission</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="role/edit/<?= $role['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <a href="javascript:void(0)" onclick="confirmDelete(<?= $role['id'] ?>)" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data role</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus role ini?')) {
        window.location.href = 'role/delete/' + id;
    }
}
</script>