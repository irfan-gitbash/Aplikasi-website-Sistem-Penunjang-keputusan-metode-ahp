<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-<?= isset($userData) ? 'edit' : 'plus' ?>"></i> <?= isset($userData) ? 'Edit' : 'Tambah' ?> Pengguna</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="/spk_ahp/user" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <?php if (isset($success) && !empty($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                
                <form action="<?= isset($userData) ? '/spk_ahp/user/edit/'.$userData['id'] : '/spk_ahp/user/add' ?>" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($userData) ? htmlspecialchars($userData['username']) : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <?= isset($userData) ? '(Kosongkan jika tidak ingin mengubah)' : '' ?></label>
                        <input type="password" class="form-control" id="password" name="password" <?= isset($userData) ? '' : 'required' ?>>
                    </div>
                    <!-- Tambahkan setelah field password -->
                    <?php if (!isset($userData)): ?>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= isset($userData) ? htmlspecialchars($userData['nama_lengkap']) : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($userData) ? htmlspecialchars($userData['email']) : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" <?= (isset($userData) && $userData['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="manager" <?= (isset($userData) && $userData['role'] == 'manager') ? 'selected' : '' ?>>Manager</option>
                            <option value="staff" <?= (isset($userData) && $userData['role'] == 'staff') ? 'selected' : '' ?>>Staff</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1" <?= (isset($userData) && $userData['status'] == 1) ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= (isset($userData) && $userData['status'] == 0) ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <!-- Hapus atau komentari bagian ini jika ada -->
                    <!--
                    <div class="mb-3">
                        <label for="phone" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= isset($userData) ? htmlspecialchars($userData['phone']) : '' ?>">
                    </div>
                    -->
                    <button type="submit" class="btn btn-primary"><?= isset($userData) ? 'Update' : 'Simpan' ?></button>
                </form>
            </div>
        </div>
    </div>
</div>