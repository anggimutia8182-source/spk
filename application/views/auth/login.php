<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SPK Gangguan Tidur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
</head>
<body style="background: radial-gradient(circle at center, #1e293b, #0f172a); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem;">

    <div style="max-width: 440px; width: 100%;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="<?= base_url(); ?>" style="display: inline-flex; align-items: center; gap: 0.75rem; text-decoration: none;">
                <div class="brand-icon" style="width: 48px; height: 48px; font-size: 1.25rem;">
                    <i class="fa-solid fa-moon"></i>
                </div>
                <div style="text-align: left;">
                    <span style="font-size: 1.5rem; font-weight: 800; color: white;">Somno<span style="color:#38bdf8;">Expert</span></span>
                    <span style="display: block; font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-transform: uppercase;">SPK Gangguan Tidur</span>
                </div>
            </a>
        </div>

        <div class="card" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); border: 1px solid rgba(255,255,255,0.1); background: #ffffff;">
            <div class="card-header" style="background: #f8fafc; text-align: center; justify-content: center; padding: 1.5rem;">
                <div>
                    <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a;">Masuk ke Sistem</h2>
                    <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;">Gunakan akun Anda untuk memulai konsultasi</p>
                </div>
            </div>

            <div class="card-body" style="padding: 1.75rem;">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-error" style="margin-bottom: 1.25rem;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div><?= $this->session->flashdata('error'); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success" style="margin-bottom: 1.25rem;">
                        <i class="fa-solid fa-circle-check"></i>
                        <div><?= $this->session->flashdata('success'); ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/login_process'); ?>" method="POST">
                    <div class="form-group">
                        <label class="form-label" for="username"><i class="fa-solid fa-user" style="color: #6366f1; margin-right: 4px;"></i> Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required autofocus autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password"><i class="fa-solid fa-lock" style="color: #6366f1; margin-right: 4px;"></i> Password</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required autocomplete="current-password" style="padding-right: 2.75rem;">
                            <button type="button" id="togglePasswordBtn" aria-label="Lihat password" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #64748b; padding: 0.25rem; font-size: 0.95rem;">
                                <i class="fa-solid fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; font-size: 0.85rem;">
                        <label style="display: inline-flex; align-items: center; gap: 0.45rem; cursor: pointer; color: #475569; user-select: none;">
                            <input type="checkbox" name="remember_me" value="1" style="accent-color: var(--primary); cursor: pointer;">
                            <span>Ingat saya</span>
                        </label>
                        <span style="color: #94a3b8; font-size: 0.8rem;"><i class="fa-solid fa-shield-halved"></i> Sesi Aman</span>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.95rem;">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang
                    </button>
                </form>

                <div style="margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px dashed #e2e8f0;">
                    <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: #64748b; margin-bottom: 0.75rem; text-align: center;">
                        <i class="fa-solid fa-bolt" style="color:#f59e0b;"></i> Klik Akun Demo Cepat
                    </div>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <button type="button" class="btn btn-sm btn-outline demo-btn" style="flex: 1; min-width: 110px;" onclick="fillDemo('admin', 'admin123', this)">
                            <i class="fa-solid fa-user-shield" style="color: #6366f1;"></i> Admin Pakar
                        </button>
                        <button type="button" class="btn btn-sm btn-outline demo-btn" style="flex: 1; min-width: 110px;" onclick="fillDemo('budi', 'user123', this)">
                            <i class="fa-solid fa-user" style="color: #0ea5e9;"></i> Pasien (Budi)
                        </button>
                        <button type="button" class="btn btn-sm btn-outline demo-btn" style="flex: 1; min-width: 110px;" onclick="fillDemo('user', 'user123', this)">
                            <i class="fa-solid fa-user-tag" style="color: #10b981;"></i> Pasien (Demo)
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-footer" style="text-align: center; font-size: 0.85rem; color: #64748b;">
                Belum memiliki akun? <a href="<?= base_url('register'); ?>" style="font-weight: 700; color: var(--primary);">Daftar di sini</a>
            </div>
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="<?= base_url(); ?>" style="color: #94a3b8; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        function fillDemo(user, pass, btn) {
            document.getElementById('username').value = user;
            document.getElementById('password').value = pass;
            
            // Visual feedback
            document.querySelectorAll('.demo-btn').forEach(b => {
                b.style.borderColor = '';
                b.style.backgroundColor = '';
            });
            if (btn) {
                btn.style.borderColor = 'var(--primary)';
                btn.style.backgroundColor = '#eff6ff';
            }
        }

        // Toggle Password Visibility
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                }
            });
        }
    </script>
</body>
</html>
