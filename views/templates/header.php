<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Pinjaman Usaha Dagang - PT. BPR KERTAMULIA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/spk_ahp/assets/css/style.css">
    <style>
        /* Styling untuk header */
        .navbar-brand {
            font-size: 0.95rem;
            font-weight: 500;
        }
        .nav-link {
            font-size: 0.85rem;
            padding: 0.5rem 0.7rem !important;
            margin: 0 0.2rem;
        }
        .navbar-nav .nav-item {
            margin: 0 0.2rem;
        }
        /* Menambahkan hover effect */
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-1">
        <div class="container">
            <a class="navbar-brand" href="/spk_ahp/dashboard">SPK AHP - PT. BPR KERTAMULIA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                     <!-- Tambahkan menu Profil Perusahaan di sini -->
                     <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/company/profile"><i class="fas fa-building"></i> Profil Perusahaan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/dashboard"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/nasabah"><i class="fas fa-users"></i> Data Nasabah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/pengajuan"><i class="fas fa-file-alt"></i> Data Pengajuan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/bobot"><i class="fas fa-balance-scale"></i> Data Bobot</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/perhitungan"><i class="fas fa-calculator"></i> Perhitungan</a>
                    </li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/user"><i class="fas fa-user-cog"></i> Data User</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <!-- Ganti bagian dropdown user yang ada dengan kode berikut -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?= isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'User' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <div class="dropdown-item text-center">
                                    <div class="mb-2">
                                        <i class="fas fa-user-circle fa-3x"></i>
                                    </div>
                                    <h6><?= isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'User' ?></h6>
                                    <small class="text-muted"><?= isset($_SESSION['username']) ? '@'.$_SESSION['username'] : '' ?></small>
                                    <small class="d-block text-muted"><?= isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : '' ?></small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/spk_ahp/user/profile"><i class="fas fa-user-cog"></i> Profil Saya</a></li>
                            <li><a class="dropdown-item" href="/spk_ahp/user/change-password"><i class="fas fa-key"></i> Ubah Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/spk_ahp/auth/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">