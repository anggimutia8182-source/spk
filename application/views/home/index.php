<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-grid">
        <div>
            <div class="hero-badge">
                <i class="fa-solid fa-brain"></i> Sistem Pakar AI Berbasis Forward Chaining
            </div>
            <h1 class="hero-title">
                Identifikasi Dini <span>Gangguan Tidur</span> Anda Secara Cepat & Tepat
            </h1>
            <p class="hero-desc">
                Sistem Pendukung Keputusan medis cerdas untuk mendiagnosa jenis gangguan tidur seperti Insomnia, Sleep Apnea, RLS, Narkolepsi, dan Parasomnia dengan inferensi pelacakan maju fakta klinis.
            </p>
            <div class="hero-actions">
                <?php if (isset($user) && $user): ?>
                    <a href="<?= base_url('konsultasi'); ?>" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-stethoscope"></i> Mulai Konsultasi Sekarang
                    </a>
                    <a href="<?= base_url('dashboard'); ?>" class="btn btn-outline btn-lg" style="color:white; border-color:rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-gauge-high"></i> Buka Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('register'); ?>" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-user-plus"></i> Registrasi Pasien Baru
                    </a>
                    <a href="<?= base_url('login'); ?>" class="btn btn-outline btn-lg" style="color:white; border-color:rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk Akun
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <div class="hero-preview-card">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                    <div style="font-weight: 700; color: #f8fafc; font-size: 1.05rem;">
                        <i class="fa-solid fa-chart-line" style="color:#38bdf8; margin-right: 6px;"></i> Basis Pengetahuan Sistem
                    </div>
                    <span class="badge badge-success">Forward Chaining</span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                    <div style="background: rgba(15, 23, 42, 0.6); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="font-size: 1.75rem; font-weight: 800; color: #38bdf8;"><?= $total_gejala; ?></div>
                        <div style="font-size: 0.8rem; color: #94a3b8;">Basis Gejala (Fakta)</div>
                    </div>
                    <div style="background: rgba(15, 23, 42, 0.6); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="font-size: 1.75rem; font-weight: 800; color: #818cf8;"><?= $total_penyakit; ?></div>
                        <div style="font-size: 0.8rem; color: #94a3b8;">Jenis Gangguan (Goal)</div>
                    </div>
                </div>

                <div style="background: rgba(15, 23, 42, 0.6); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <span style="font-size:0.85rem; color:#cbd5e1; font-weight:600;">Rule Base Produksi</span>
                        <span style="font-size:0.8rem; color:#10b981; font-weight:700;"><?= $total_rule; ?> Aturan Aktif</span>
                    </div>
                    <p style="font-size:0.75rem; color:#94a3b8; line-height: 1.4;">
                        Pelacakan berbasis IF [Gejala...] THEN [Penyakit], mengevaluasi fakta di memori kerja hingga kesimpulan terbukti.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT CONTAINER -->
<div style="max-width: 1200px; margin: 3rem auto; padding: 0 1.5rem;">
    <!-- SECTION: DAFTAR GANGGUAN TIDUR -->
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <span class="badge badge-primary" style="margin-bottom: 0.5rem;">Klasifikasi Klinis</span>
        <h2 style="font-size: 2rem; font-weight: 800; color: #0f172a;">5 Jenis Gangguan Tidur yang Diidentifikasi</h2>
        <p style="color: #64748b; max-width: 600px; margin: 0.5rem auto 0;">
            Sistem pakar mengelompokkan gejala berdasarkan pola keluhan dan memberikan saran penanganan awal terpercaya.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 3.5rem;">
        <?php foreach ($penyakit as $p): ?>
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header" style="background: #f8fafc;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span class="badge badge-primary" style="font-size: 0.8rem;"><?= $p->kode_penyakit; ?></span>
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin: 0;"><?= html_escape($p->nama_penyakit); ?></h3>
                    </div>
                </div>
                <div class="card-body">
                    <div style="font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 0.35rem;">
                        <i class="fa-solid fa-notes-medical" style="color: #4f46e5; margin-right: 5px;"></i> Saran Penanganan Awal:
                    </div>
                    <p style="font-size: 0.875rem; color: #64748b; line-height: 1.5;">
                        <?= html_escape($p->saran_penanganan); ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- SECTION: CARA KERJA FORWARD CHAINING -->
    <div class="card" style="background: linear-gradient(135deg, #f8fafc, #eff6ff); border: 1px solid #bfdbfe; margin-bottom: 3.5rem;">
        <div class="card-body" style="padding: 2.5rem 2rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <span class="badge badge-primary">Metodologi Inferensi</span>
                <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-top: 0.5rem;">
                    Alur Inferensi Forward Chaining (Pelacakan Maju)
                </h2>
                <p style="color: #64748b; font-size: 0.95rem;">
                    Metode inferensi yang memulai penalaran dari sekumpulan fakta yang diketahui (gejala yang dialami) menuju kesimpulan akhir (diagnosa penyakit).
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
                <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e2e8f0; text-align: center;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #e0e7ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; margin: 0 auto 1rem;">
                        1
                    </div>
                    <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Input Gejala (Fakta)</h4>
                    <p style="font-size: 0.85rem; color: #64748b;">
                        Pasien memilih gejala-gejala yang dirasakan saat konsultasi. Fakta disimpan di <em>Working Memory</em>.
                    </p>
                </div>

                <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e2e8f0; text-align: center;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; margin: 0 auto 1rem;">
                        2
                    </div>
                    <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Pencocokan Aturan</h4>
                    <p style="font-size: 0.85rem; color: #64748b;">
                        Mesin inferensi menguji premis (IF) setiap Rule R1-R5 terhadap fakta yang ada di memori kerja.
                    </p>
                </div>

                <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e2e8f0; text-align: center;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; margin: 0 auto 1rem;">
                        3
                    </div>
                    <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Eksekusi Rule (Fire)</h4>
                    <p style="font-size: 0.85rem; color: #64748b;">
                        Jika seluruh gejala dalam rule terpenuhi, rule dieksekusi dan kesimpulan ditambahkan sebagai fakta baru.
                    </p>
                </div>

                <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e2e8f0; text-align: center;">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; margin: 0 auto 1rem;">
                        4
                    </div>
                    <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Hasil & Rekomendasi</h4>
                    <p style="font-size: 0.85rem; color: #64748b;">
                        Sistem mengeluarkan jenis gangguan tidur terindikasi beserta panduan penanganan awal dan riwayat tercatat.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION: TIPS SLEEP HYGIENE -->
    <div style="margin-bottom: 3rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <span class="badge badge-success">Panduan Gaya Hidup Sehat</span>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-top: 0.5rem;">
                Prinsip Sleep Hygiene (Kebersihan Tidur)
            </h2>
            <p style="color: #64748b;">Langkah sederhana untuk meningkatkan kualitas istirahat Anda setiap malam.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem;">
            <div class="card" style="margin-bottom:0; padding:1.25rem;">
                <div style="color: #4f46e5; font-size: 1.5rem; margin-bottom: 0.75rem;"><i class="fa-regular fa-clock"></i></div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Jadwal Tidur Konsisten</h4>
                <p style="font-size: 0.85rem; color: #64748b;">Tidur dan bangun pada waktu yang sama setiap hari, termasuk di akhir pekan.</p>
            </div>
            <div class="card" style="margin-bottom:0; padding:1.25rem;">
                <div style="color: #0ea5e9; font-size: 1.5rem; margin-bottom: 0.75rem;"><i class="fa-solid fa-mug-hot"></i></div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Batasi Kafein & Layar</h4>
                <p style="font-size: 0.85rem; color: #64748b;">Hindari kopi, rokok, dan paparan cahaya gawai minimal 1-2 jam sebelum beranjak tidur.</p>
            </div>
            <div class="card" style="margin-bottom:0; padding:1.25rem;">
                <div style="color: #10b981; font-size: 1.5rem; margin-bottom: 0.75rem;"><i class="fa-solid fa-bed"></i></div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Ciptakan Suasana Nyaman</h4>
                <p style="font-size: 0.85rem; color: #64748b;">Pastikan kamar tidur dalam kondisi sejuk, gelap, dan hening untuk memicu melatonin.</p>
            </div>
            <div class="card" style="margin-bottom:0; padding:1.25rem;">
                <div style="color: #f59e0b; font-size: 1.5rem; margin-bottom: 0.75rem;"><i class="fa-solid fa-person-running"></i></div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.35rem;">Aktivitas Fisik Teratur</h4>
                <p style="font-size: 0.85rem; color: #64748b;">Berolahraga rutin di pagi atau sore hari, hindari latihan berat mendekati jam tidur.</p>
            </div>
        </div>
    </div>
</div>
