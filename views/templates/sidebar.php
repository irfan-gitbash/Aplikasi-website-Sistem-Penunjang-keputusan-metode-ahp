<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/spk_ahp/dashboard">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <!-- Tambahkan menu Profil Perusahaan di sini -->
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/company/profile">
                            <i class="fas fa-building"></i> Profil Perusahaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/nasabah">
                            <i class="fas fa-users"></i> Data Nasabah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/pengajuan">
                            <i class="fas fa-file-alt"></i> Data Pengajuan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/bobot">
                            <i class="fas fa-balance-scale"></i> Data Bobot
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/perhitungan">
                            <i class="fas fa-calculator"></i> Perhitungan
                        </a>
                    </li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/spk_ahp/user">
                            <i class="fas fa-user-cog"></i> Data User
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Konten utama akan ditampilkan di sini -->