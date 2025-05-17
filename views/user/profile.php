<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-circle"></i> Profil Saya</h5>
            </div>
            <div class="card-body">
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <?php if (isset($success) && !empty($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-7x text-primary"></i>
                        </div>
                        <h4><?= htmlspecialchars($userData['nama_lengkap']) ?></h4>
                        <p class="text-muted">@<?= htmlspecialchars($userData['username']) ?></p>
                        <p><span class="badge bg-primary"><?= ucfirst(htmlspecialchars($userData['role'])) ?></span></p>
                        <div class="mt-3">
                            <a href="/spk_ahp/user/change-password/<?= $userData['id'] ?>" class="btn btn-outline-primary">
                                <i class="fas fa-key"></i> Ubah Password
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <form action="/spk_ahp/user/profile" method="post" id="profileForm">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($userData['username']) ?>" required>
                                <div class="form-text text-muted">Username hanya boleh berisi huruf, angka, dan underscore</div>
                            </div>
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($userData['nama_lengkap']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>">
                                <div class="form-text text-muted">Pastikan email valid untuk menerima notifikasi</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <input type="text" class="form-control" id="role" value="<?= ucfirst(htmlspecialchars($userData['role'])) ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <input type="text" class="form-control" id="status" value="<?= $userData['status'] == 1 ? 'Aktif' : 'Nonaktif' ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="last_login" class="form-label">Login Terakhir</label>
                                <input type="text" class="form-control" id="last_login" value="<?= isset($userData['last_login']) ? date('d-m-Y H:i:s', strtotime($userData['last_login'])) : '-' ?>" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.getElementById('profileForm').addEventListener('submit', function(event) {
    const username = document.getElementById('username').value;
    const namaLengkap = document.getElementById('nama_lengkap').value;
    const email = document.getElementById('email').value;
    let isValid = true;
    let errorMessage = '';
    
    // Validate username (only letters, numbers, and underscore)
    if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        isValid = false;
        errorMessage += 'Username hanya boleh berisi huruf, angka, dan underscore.\n';
    }
    
    // Validate nama_lengkap (not empty)
    if (namaLengkap.trim() === '') {
        isValid = false;
        errorMessage += 'Nama Lengkap tidak boleh kosong.\n';
    }
    
    // Validate email if provided
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        isValid = false;
        errorMessage += 'Format Email tidak valid.\n';
    }
    
    if (!isValid) {
        event.preventDefault();
        alert(errorMessage);
    }
});
</script>