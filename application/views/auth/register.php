<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pasien - SPK Gangguan Tidur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
</head>
<body style="background: radial-gradient(circle at center, #1e293b, #0f172a); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem;">

    <div style="max-width: 480px; width: 100%;">
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
                    <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a;">Registrasi Akun Baru</h2>
                    <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;">Daftarkan diri Anda untuk melakukan konsultasi mandiri</p>
                </div>
            </div>

            <div class="card-body" style="padding: 1.75rem;">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-error" style="margin-bottom: 1.25rem;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <div><?= $this->session->flashdata('error'); ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/register_process'); ?>" method="POST">
                    <div class="form-group">
                        <label class="form-label" for="nama"><i class="fa-solid fa-id-card" style="color: #6366f1; margin-right: 4px;"></i> Nama Lengkap *</label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Budi Santoso" required autofocus>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="username"><i class="fa-solid fa-user" style="color: #6366f1; margin-right: 4px;"></i> Username *</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Contoh: budisantoso" required autocomplete="username">
                        <small style="color: #64748b; font-size: 0.75rem;">Gunakan huruf dan angka tanpa spasi.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password"><i class="fa-solid fa-lock" style="color: #6366f1; margin-right: 4px;"></i> Password *</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 5 karakter" required autocomplete="new-password" style="padding-right: 2.75rem;">
                            <button type="button" class="toggle-pass-btn" data-target="password" aria-label="Lihat password" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #64748b; padding: 0.25rem; font-size: 0.95rem;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirm"><i class="fa-solid fa-check-double" style="color: #6366f1; margin-right: 4px;"></i> Konfirmasi Password *</label>
                        <div style="position: relative;">
                            <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Ulangi password" required autocomplete="new-password" style="padding-right: 2.75rem;">
                            <button type="button" class="toggle-pass-btn" data-target="password_confirm" aria-label="Lihat konfirmasi password" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: #64748b; padding: 0.25rem; font-size: 0.95rem;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.95rem; margin-top: 0.5rem;">
                        <i class="fa-solid fa-user-plus"></i> Buat Akun & Masuk
                    </button>
                </form>
            </div>

            <div class="card-footer" style="text-align: center; font-size: 0.85rem; color: #64748b;">
                Sudah memiliki akun? <a href="<?= base_url('login'); ?>" style="font-weight: 700; color: var(--primary);">Masuk ke sini</a>
            </div>
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="<?= base_url(); ?>" style="color: #94a3b8; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-pass-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (input && icon) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            });
        });
    </script>
</body>
</html>
