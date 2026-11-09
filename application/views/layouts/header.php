<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? html_escape($title) : 'Sistem Pakar Gangguan Tidur'; ?> - Forward Chaining</title>
    <meta name="description" content="Sistem Pakar Identifikasi Jenis Gangguan Tidur Menggunakan Metode Forward Chaining">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- TOP NAVBAR -->
    <header class="navbar-app">
        <div class="nav-container">
            <a href="<?= base_url(); ?>" class="brand-logo">
                <div class="brand-icon">
                    <i class="fa-solid fa-moon"></i>
                </div>
                <div>
                    <span style="letter-spacing: -0.02em;">Somno<span style="color:#38bdf8;">Expert</span></span>
                    <span style="font-size: 0.65rem; display:block; color:#94a3b8; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase;">SPK Gangguan Tidur</span>
                </div>
            </a>

            <nav>
                <ul class="nav-menu">
                    <?php if (isset($user) && $user): ?>
                        <li>
                            <a href="<?= base_url('dashboard'); ?>" class="nav-link <?= ($this->uri->segment(1) == 'dashboard') ? 'active' : ''; ?>">
                                <i class="fa-solid fa-gauge-high"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('konsultasi'); ?>" class="nav-link <?= ($this->uri->segment(1) == 'konsultasi' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
                                <i class="fa-solid fa-stethoscope"></i> Konsultasi
                            </a>
                        </li>
                        <li>
                            <div class="nav-user-badge">
                                <div class="nav-avatar">
                                    <?= strtoupper(substr($user->nama, 0, 1)); ?>
                                </div>
                                <span><?= html_escape($user->nama); ?></span>
                                <span class="badge <?= $user->role === 'admin' ? 'badge-primary' : 'badge-success'; ?>" style="font-size: 0.7rem; padding: 2px 8px;">
                                    <?= $user->role === 'admin' ? 'Pakar' : 'Pasien'; ?>
                                </span>
                            </div>
                        </li>
                        <li>
                            <a href="<?= base_url('logout'); ?>" class="btn btn-sm btn-danger" style="border-radius: 20px;">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?= base_url(); ?>" class="nav-link <?= ($this->uri->segment(1) == '') ? 'active' : ''; ?>">
                                <i class="fa-solid fa-house"></i> Beranda
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('login'); ?>" class="nav-link <?= ($this->uri->segment(1) == 'login') ? 'active' : ''; ?>">
                                <i class="fa-solid fa-right-to-bracket"></i> Masuk
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('register'); ?>" class="btn btn-sm btn-primary" style="border-radius: 20px;">
                                <i class="fa-solid fa-user-plus"></i> Daftar Pasien
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <?php if (isset($user) && $user): ?>
    <!-- APP WRAPPER WITH SIDEBAR FOR LOGGED IN USERS -->
    <div class="app-wrapper">
        <aside class="sidebar no-print">
            <div class="sidebar-title">Menu Utama</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= base_url('dashboard'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-chart-pie"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('konsultasi'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'konsultasi' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-stethoscope"></i> Konsultasi Baru
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('konsultasi/riwayat'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'konsultasi' && $this->uri->segment(2) == 'riwayat') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Konsultasi
                    </a>
                </li>
            </ul>

            <?php if ($user->role === 'admin'): ?>
            <div class="sidebar-title">Kelola Sistem (Pakar)</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= base_url('gejala'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'gejala') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-clipboard-list"></i> Data Gejala
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('penyakit'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'penyakit') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-bed"></i> Data Penyakit
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('rule'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'rule' && $this->uri->segment(2) == '') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-diagram-project"></i> Basis Aturan (Rule)
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('rule/matriks'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'rule' && $this->uri->segment(2) == 'matriks') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-table-cells"></i> Matriks & Tabel Keputusan
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('rule/simulasi'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'rule' && $this->uri->segment(2) == 'simulasi') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-vial-virus"></i> Uji Simulasi Forward Chaining
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('laporan'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'laporan') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-file-invoice"></i> Laporan Konsultasi
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'user') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-users-gear"></i> Kelola User
                    </a>
                </li>
            </ul>
            <?php endif; ?>

            <div class="sidebar-title">Pengaturan</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?= base_url('profil'); ?>" class="sidebar-link <?= ($this->uri->segment(1) == 'profil') ? 'active' : ''; ?>">
                        <i class="fa-solid fa-user-circle"></i> Profil Pengguna
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('logout'); ?>" class="sidebar-link" style="color: #f87171;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                    </a>
                </li>
            </ul>
        </aside>

        <main class="main-content">
            <!-- Flash Message Alerts -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
                    <div><?= $this->session->flashdata('success'); ?></div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation" style="font-size: 1.25rem;"></i>
                    <div><?= $this->session->flashdata('error'); ?></div>
                </div>
            <?php endif; ?>

    <?php endif; ?>
