<div class="page-header">
    <div>
        <h1 class="page-title">Profil Pengguna</h1>
        <p class="page-subtitle">Kelola informasi data pribadi dan keamanan akun Anda.</p>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-user-gear" style="color: #4f46e5;"></i>
            Perbarui Data Akun
        </div>
    </div>
    <div class="card-body">
        <form action="<?= base_url('profil/update'); ?>" method="POST">
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" class="form-control" value="<?= html_escape($user->username); ?>" disabled style="background: #f1f5f9; cursor: not-allowed;">
                <small style="color: #64748b; font-size: 0.75rem;">Username bersifat permanen dan tidak dapat diubah.</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="role">Hak Akses (Role)</label>
                <div>
                    <span class="badge <?= $user->role === 'admin' ? 'badge-primary' : 'badge-success'; ?>" style="font-size: 0.85rem; padding: 4px 12px;">
                        <?= $user->role === 'admin' ? 'Administrator (Pakar Medis)' : 'Pasien (Pengguna Biasa)'; ?>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="nama">Nama Lengkap *</label>
                <input type="text" name="nama" id="nama" class="form-control" value="<?= html_escape($user->nama); ?>" required>
            </div>

            <hr style="border: none; border-top: 1px dashed #e2e8f0; margin: 1.5rem 0;">
            <div style="font-size: 0.9rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem;">
                <i class="fa-solid fa-lock" style="color: #6366f1;"></i> Ganti Password (Opsional)
            </div>

            <div class="form-group">
                <label class="form-label" for="password_lama">Password Saat Ini</label>
                <input type="password" name="password_lama" id="password_lama" class="form-control" placeholder="Masukkan jika ingin mengganti password">
            </div>

            <div class="form-group">
                <label class="form-label" for="password_baru">Password Baru</label>
                <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Minimal 5 karakter">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="password_konfirmasi">Konfirmasi Password Baru</label>
                <input type="password" name="password_konfirmasi" id="password_konfirmasi" class="form-control" placeholder="Ulangi password baru">
            </div>

            <div style="margin-top: 1.5rem; text-align: right;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>
</div>
