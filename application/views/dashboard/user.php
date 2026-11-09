<div class="page-header">
    <div>
        <h1 class="page-title">Halo, <?= html_escape($user->nama); ?>!</h1>
        <p class="page-subtitle">Selamat datang di portal konsultasi kesehatan tidur SomnoExpert.</p>
    </div>
    <div>
        <a href="<?= base_url('konsultasi'); ?>" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-stethoscope"></i> Mulai Konsultasi Baru
        </a>
    </div>
</div>

<!-- WELCOME BANNER -->
<div class="card" style="background: linear-gradient(135deg, #1e1b4b, #312e81); color: white; border: none; margin-bottom: 2rem;">
    <div class="card-body" style="padding: 2rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            <div style="max-width: 650px;">
                <span class="badge" style="background: rgba(255,255,255,0.2); color: #c7d2fe; margin-bottom: 0.75rem;">
                    <i class="fa-solid fa-shield-heart"></i> Konsultasi Mandiri Cepat & Akurat
                </span>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                    Punya Keluhan Mengenai Pola Tidur Anda?
                </h2>
                <p style="color: #cbd5e1; font-size: 0.95rem; line-height: 1.6;">
                    Identifikasi gejala tidur Anda secara instan dengan mesin inferensi Forward Chaining untuk mengetahui kemungkinan Insomnia, Apnea Tidur, RLS, Narkolepsi, atau Parasomnia.
                </p>
            </div>
            <div>
                <a href="<?= base_url('konsultasi'); ?>" class="btn btn-light" style="background: #ffffff; color: #1e1b4b; font-weight: 700; padding: 0.75rem 1.5rem; border-radius: var(--radius-sm);">
                    <i class="fa-solid fa-arrow-right"></i> Periksa Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<!-- STAT & RIWAYAT -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- RECENT CONSULTATION TABLE -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-clock-rotate-left" style="color: #4f46e5;"></i>
                Riwayat Konsultasi Terakhir
            </div>
            <a href="<?= base_url('konsultasi/riwayat'); ?>" class="btn btn-sm btn-outline">
                Semua Riwayat
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>No. RM</th>
                            <th>Tanggal</th>
                            <th>Hasil Diagnosa</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recent_konsultasi)): ?>
                            <?php foreach ($recent_konsultasi as $k): ?>
                                <tr>
                                    <td><strong style="color: #4f46e5;"><?= html_escape($k->no_rm ?: '-'); ?></strong></td>
                                    <td><?= date('d M Y, H:i', strtotime($k->tanggal)); ?></td>
                                    <td>
                                        <?php if ($k->hasil_diagnosa && $k->nama_penyakit): ?>
                                            <span class="badge badge-success">
                                                <i class="fa-solid fa-check"></i> <?= html_escape($k->nama_penyakit); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">
                                                <i class="fa-solid fa-circle-question"></i> Belum Memenuhi Rule
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="<?= base_url('konsultasi/hasil/' . $k->id_konsultasi); ?>" class="btn btn-sm btn-outline">
                                            <i class="fa-solid fa-eye"></i> Detail
                                        </a>
                                        <a href="<?= base_url('konsultasi/cetak/' . $k->id_konsultasi); ?>" target="_blank" class="btn btn-sm btn-secondary">
                                            <i class="fa-solid fa-print"></i> Cetak
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #94a3b8; padding: 2.5rem 1rem;">
                                    <div style="font-size: 2rem; margin-bottom: 0.5rem; color: #cbd5e1;"><i class="fa-regular fa-clipboard"></i></div>
                                    Anda belum pernah melakukan konsultasi.<br>
                                    <a href="<?= base_url('konsultasi'); ?>" class="btn btn-sm btn-primary" style="margin-top: 0.75rem;">
                                        <i class="fa-solid fa-plus"></i> Mulai Konsultasi Pertama
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- QUICK STAT CARD & TIPS -->
    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div class="card" style="margin-bottom: 0;">
            <div class="card-body">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="fa-solid fa-notes-medical"></i>
                    </div>
                    <div>
                        <div style="font-size: 1.75rem; font-weight: 800; color: #0f172a;"><?= $total_konsultasi; ?></div>
                        <div style="font-size: 0.85rem; color: #64748b;">Total Konsultasi Anda</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom: 0; background: #f8fafc;">
            <div class="card-header" style="background: transparent;">
                <div class="card-title" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-lightbulb" style="color: #f59e0b;"></i> Tips Tidur Nyenyak
                </div>
            </div>
            <div class="card-body" style="padding-top: 0.5rem;">
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.85rem; color: #475569;">
                    <li style="display: flex; gap: 0.5rem;">
                        <i class="fa-solid fa-check" style="color: #10b981; margin-top: 3px;"></i>
                        Tidur dan bangun pada jam yang konsisten setiap hari.
                    </li>
                    <li style="display: flex; gap: 0.5rem;">
                        <i class="fa-solid fa-check" style="color: #10b981; margin-top: 3px;"></i>
                        Hindari layar gadget 1 jam sebelum waktu tidur.
                    </li>
                    <li style="display: flex; gap: 0.5rem;">
                        <i class="fa-solid fa-check" style="color: #10b981; margin-top: 3px;"></i>
                        Jaga suhu kamar tidur tetap sejuk dan pencahayaan redup.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
