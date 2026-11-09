<div class="page-header">
    <div>
        <h1 class="page-title">Form Konsultasi Diagnosa</h1>
        <p class="page-subtitle">Isi data identitas pasien dan pilih gejala-gejala yang sedang Anda rasakan untuk dianalisis oleh sistem pakar.</p>
    </div>
</div>

<form action="<?= base_url('konsultasi/proses'); ?>" method="POST" id="formKonsultasi">
    <!-- BAGIAN A: IDENTITAS PASIEN -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-id-card-clip" style="color: #4f46e5;"></i>
                A. Data Identitas Pasien
            </div>
            <span class="badge badge-primary">No. RM: <?= html_escape($no_rm); ?></span>
        </div>
        <div class="card-body">
            <input type="hidden" name="no_rm" value="<?= html_escape($no_rm); ?>">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem;">
                <div class="form-group">
                    <label class="form-label" for="nama_pasien">Nama Pasien *</label>
                    <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" value="<?= html_escape($user->nama); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="usia">Usia (Tahun) *</label>
                    <input type="number" name="usia" id="usia" class="form-control" placeholder="Contoh: 28" min="1" max="120" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="jenis_kelamin">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label" for="keluhan">Keluhan Utama *</label>
                <textarea name="keluhan" id="keluhan" class="form-control" placeholder="Ceritakan keluhan atau gejala yang paling mengganggu tidur Anda..." rows="2" required></textarea>
            </div>
        </div>
    </div>

    <!-- BAGIAN B: CHECKLIST GEJALA -->
    <div class="card">
        <div class="card-header" style="flex-wrap: wrap; gap: 0.75rem;">
            <div class="card-title">
                <i class="fa-solid fa-clipboard-check" style="color: #10b981;"></i>
                B. Input Gejala Pasien (Basis Fakta)
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="font-size: 0.85rem; font-weight: 600; color: #475569;">
                    Terpilih: <span id="selectedGejalaCount" class="badge badge-primary" style="font-size: 0.85rem;">0</span> Gejala
                </div>
                <button type="button" class="btn btn-sm btn-outline" id="btnSelectAll">
                    <i class="fa-solid fa-check-double"></i> Pilih Semua
                </button>
                <button type="button" class="btn btn-sm btn-outline" id="btnResetAll">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
            </div>
        </div>
        <div class="card-body">
            <p style="font-size: 0.875rem; color: #64748b; margin-bottom: 1.25rem;">
                Centang setiap gejala di bawah ini yang dialami atau dirasakan pasien dalam kurun waktu terakhir.
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1rem;">
                <?php foreach ($gejala as $g): ?>
                    <label class="symptom-card" for="gejala_<?= $g->kode_gejala; ?>">
                        <input type="checkbox" name="gejala[]" value="<?= $g->kode_gejala; ?>" id="gejala_<?= $g->kode_gejala; ?>">
                        <div style="flex: 1;">
                            <div class="symptom-code"><?= $g->kode_gejala; ?></div>
                            <div class="symptom-title"><?= html_escape($g->nama_gejala); ?></div>
                            <?php if (!empty($g->keterangan)): ?>
                                <div class="symptom-desc"><?= html_escape($g->keterangan); ?></div>
                            <?php endif; ?>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer" style="display: flex; justify-content: flex-end; align-items: center; gap: 1rem;">
            <a href="<?= base_url('dashboard'); ?>" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-microchip"></i> Proses Diagnosa (Forward Chaining)
            </button>
        </div>
    </div>
</form>
