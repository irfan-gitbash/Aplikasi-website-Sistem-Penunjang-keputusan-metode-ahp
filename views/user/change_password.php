<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-key"></i> Ubah Password</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <?php if ($_SESSION['user_id'] == $userData['id']): ?>
                        <a href="/spk_ahp/user/profile" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Profil</a>
                    <?php else: ?>
                        <a href="/spk_ahp/user" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <?php endif; ?>
                </div>
                
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
                    </div>
                    <div class="col-md-8">
                        <form action="/spk_ahp/user/change-password/<?= $userData['id'] ?>" method="post" id="passwordForm">
                            <?php if ($_SESSION['role'] != 'admin' || $_SESSION['user_id'] == $userData['id']): ?>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    Password harus memenuhi kriteria berikut:
                                    <ul class="mb-0">
                                        <li id="length" class="text-danger">Minimal 8 karakter</li>
                                        <li id="uppercase" class="text-danger">Minimal 1 huruf besar</li>
                                        <li id="lowercase" class="text-danger">Minimal 1 huruf kecil</li>
                                        <li id="number" class="text-danger">Minimal 1 angka</li>
                                        <li id="special" class="text-danger">Minimal 1 karakter khusus (!@#$%^&*)</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div id="password-match" class="form-text text-danger">Password tidak cocok</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="progress">
                                    <div id="password-strength" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" id="submit-btn" disabled>Ubah Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});

// Password strength checker
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirm_password');
const lengthCheck = document.getElementById('length');
const uppercaseCheck = document.getElementById('uppercase');
const lowercaseCheck = document.getElementById('lowercase');
const numberCheck = document.getElementById('number');
const specialCheck = document.getElementById('special');
const passwordMatch = document.getElementById('password-match');
const passwordStrength = document.getElementById('password-strength');
const submitBtn = document.getElementById('submit-btn');

function updatePasswordStrength() {
    const password = passwordInput.value;
    let strength = 0;
    let validations = 0;
    
    // Check length
    if (password.length >= 8) {
        lengthCheck.classList.remove('text-danger');
        lengthCheck.classList.add('text-success');
        strength += 20;
        validations++;
    } else {
        lengthCheck.classList.remove('text-success');
        lengthCheck.classList.add('text-danger');
    }
    
    // Check uppercase
    if (/[A-Z]/.test(password)) {
        uppercaseCheck.classList.remove('text-danger');
        uppercaseCheck.classList.add('text-success');
        strength += 20;
        validations++;
    } else {
        uppercaseCheck.classList.remove('text-success');
        uppercaseCheck.classList.add('text-danger');
    }
    
    // Check lowercase
    if (/[a-z]/.test(password)) {
        lowercaseCheck.classList.remove('text-danger');
        lowercaseCheck.classList.add('text-success');
        strength += 20;
        validations++;
    } else {
        lowercaseCheck.classList.remove('text-success');
        lowercaseCheck.classList.add('text-danger');
    }
    
    // Check number
    if (/[0-9]/.test(password)) {
        numberCheck.classList.remove('text-danger');
        numberCheck.classList.add('text-success');
        strength += 20;
        validations++;
    } else {
        numberCheck.classList.remove('text-success');
        numberCheck.classList.add('text-danger');
    }
    
    // Check special character
    if (/[!@#$%^&*]/.test(password)) {
        specialCheck.classList.remove('text-danger');
        specialCheck.classList.add('text-success');
        strength += 20;
        validations++;
    } else {
        specialCheck.classList.remove('text-success');
        specialCheck.classList.add('text-danger');
    }
    
    // Update progress bar
    passwordStrength.style.width = strength + '%';
    passwordStrength.setAttribute('aria-valuenow', strength);
    
    // Update progress bar color
    if (strength < 40) {
        passwordStrength.className = 'progress-bar bg-danger';
    } else if (strength < 80) {
        passwordStrength.className = 'progress-bar bg-warning';
    } else {
        passwordStrength.className = 'progress-bar bg-success';
    }
    
    // Check if passwords match
    const confirmPassword = confirmPasswordInput.value;
    if (confirmPassword) {
        if (password === confirmPassword) {
            passwordMatch.classList.remove('text-danger');
            passwordMatch.classList.add('text-success');
            passwordMatch.textContent = 'Password cocok';
        } else {
            passwordMatch.classList.remove('text-success');
            passwordMatch.classList.add('text-danger');
            passwordMatch.textContent = 'Password tidak cocok';
        }
    }
    
    // Enable/disable submit button
    if (validations === 5 && (!confirmPassword || password === confirmPassword)) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

passwordInput.addEventListener('input', updatePasswordStrength);
confirmPasswordInput.addEventListener('input', updatePasswordStrength);

// Form validation
document.getElementById('passwordForm').addEventListener('submit', function(event) {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    
    if (password !== confirmPassword) {
        event.preventDefault();
        alert('Password dan konfirmasi password tidak sama!');
    }
});
</script>