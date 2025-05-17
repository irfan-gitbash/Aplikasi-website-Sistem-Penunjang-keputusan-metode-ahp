<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Role</h5>
            </div>
            <div class="card-body">
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <?php if (isset($success) && !empty($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                
                <?php if (isset($role) && $role): ?>
                <form method="POST" action="role/edit/<?= $role['id'] ?>">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($role['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($role['description']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            <?php 
                            $modules = [];
                            while ($permission = $permissions->fetch(PDO::FETCH_ASSOC)) {
                                if (!isset($modules[$permission['module']])) {
                                    $modules[$permission['module']] = [];
                                }
                                $modules[$permission['module']][] = $permission;
                            }
                            
                            foreach ($modules as $module => $modulePermissions): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 text-capitalize"><?= $module ?></h6>
                                        </div>
                                        <div class="card-body">
                                            <?php foreach ($modulePermissions as $permission): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $permission['id'] ?>" id="perm<?= $permission['id'] ?>" <?= in_array($permission['id'], $rolePermissions) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="perm<?= $permission['id'] ?>">
                                                        <?= $permission['display_name'] ?>
                                                        <small class="text-muted d-block"><?= $permission['description'] ?></small>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="role" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </form>
                <?php else: ?>
                    <div class="alert alert-danger">Role tidak ditemukan</div>
                    <a href="role" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>