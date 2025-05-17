<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK Pinjaman Usaha Dagang PT. BPR KERTAMULIA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
  
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>SPK Pinjaman Usaha Dagang</h4>
                        <h5>PT. BPR KERTAMULIA</h5>
                    </div>
                    <div class="card-body">
                        <!-- Kalimat sambutan dengan animasi Bootstrap di tengah -->
                        <div class="row justify-content-center text-center">
                            <div class="col-12">
                                <div class="welcome-text animate__animated animate__fadeInDown">
                                    <h5>Selamat Datang di Sistem Penunjang Keputusan</h5>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tampilan waktu dengan animasi di tengah -->
                        <div class="row justify-content-center text-center">
                            <div class="col-12">
                                <div class="datetime-container animate__animated animate__fadeIn">
                                    <div class="datetime-display time-animate" id="current-datetime">Memuat waktu...</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="login-form animate__animated animate__fadeInUp">
                            <?php if (isset($error) && !empty($error)): ?>
                            <div class="alert alert-danger">
                                <?= $error ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (isset($username_valid) && $username_valid): ?>
                            <div class="alert alert-info">
                                Username benar, silakan masukkan password yang sesuai.
                            </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="login">
                                <div class="mb-3 mt-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <small>&copy; <?= date('Y') ?> PT. BPR KERTAMULIA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk menampilkan waktu realtime
        function updateDateTime() {
            const now = new Date();
            
            // Array nama hari dalam bahasa Indonesia
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            
            // Array nama bulan dalam bahasa Indonesia
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            // Format tanggal: Hari, DD Bulan YYYY
            const dateString = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
            
            // Format waktu: HH:MM:SS
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            
            // Gabungkan tanggal dan waktu dengan spasi yang lebih banyak
            document.getElementById('current-datetime').innerHTML = `${dateString} &nbsp;&nbsp;-&nbsp;&nbsp; ${timeString}`;
        }
        
        // Update waktu setiap 1 detik
        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>
</body>
</html>