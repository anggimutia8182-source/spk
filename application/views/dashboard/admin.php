<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Pakar</h1>
        <p class="page-subtitle">Selamat datang kembali, <strong><?= html_escape($user->nama); ?></strong>. Kelola basis pengetahuan dan pantau aktivitas konsultasi sistem.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="<?= base_url('konsultasi'); ?>" class="btn btn-primary">
            <i class="fa-solid fa-stethoscope"></i> Uji Konsultasi Baru
        </a>
        <a href="<?= base_url('rule/simulasi'); ?>" class="btn btn-secondary">
            <i class="fa-solid fa-vial-virus"></i> Uji Simulasi
        </a>
    </div>
</div>

<!-- STAT CARDS -->
<div class="stat-grid">
    <div class="stat-card primary">
        <div class="stat-icon primary">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <div>
            <div class="stat-value"><?= $total_gejala; ?></div>
            <div class="stat-label">Total Gejala (Fakta)</div>
        </div>
    </div>

    <div class="stat-card secondary">
        <div class="stat-icon secondary">
            <i class="fa-solid fa-bed"></i>
        </div>
        <div>
            <div class="stat-value"><?= $total_penyakit; ?></div>
            <div class="stat-label">Jenis Gangguan Tidur</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-icon success">
            <i class="fa-solid fa-diagram-project"></i>
        </div>
        <div>
            <div class="stat-value"><?= $total_rule; ?></div>
            <div class="stat-label">Basis Aturan (Rule Base)</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-icon warning">
            <i class="fa-solid fa-file-medical"></i>
        </div>
        <div>
            <div class="stat-value"><?= $stats['total']; ?></div>
            <div class="stat-label">Total Konsultasi Selesai</div>
        </div>
    </div>
</div>

<!-- CHARTS & SUMMARY ROW -->
<div style="display: grid; grid-template-columns: 1.3fr 0.7fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- CHART CARD -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-chart-column" style="color: #4f46e5;"></i>
                Distribusi Diagnosa Pasien
            </div>
            <span class="badge badge-primary">Statistik Klinis</span>
        </div>
        <div class="card-body">
            <div style="height: 280px; position: relative;">
                <canvas id="diagnosaChart"></canvas>
            </div>
        </div>
    </div>

    <!-- QUICK INFO / HEALTH INSIGHTS -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-circle-info" style="color: #0ea5e9;"></i>
                Ringkasan Diagnosa
            </div>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: #f8fafc; border-radius: 8px;">
                    <span style="font-weight: 600; color: #475569;">Teridentifikasi Gangguan</span>
                    <span class="badge badge-success" style="font-size: 0.85rem; padding: 4px 10px;"><?= $stats['terdiagnosa']; ?> Kasus</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: #f8fafc; border-radius: 8px;">
                    <span style="font-weight: 600; color: #475569;">Belum Ada Indikasi</span>
                    <span class="badge badge-warning" style="font-size: 0.85rem; padding: 4px 10px;"><?= $stats['tidak_terindikasi']; ?> Kasus</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: #f8fafc; border-radius: 8px;">
                    <span style="font-weight: 600; color: #475569;">Total Akun Pengguna</span>
                    <span class="badge badge-secondary" style="font-size: 0.85rem; padding: 4px 10px;"><?= $total_user; ?> Akun</span>
                </div>

                <div style="margin-top: 0.5rem; padding: 1rem; background: #eff6ff; border-radius: 10px; border: 1px solid #bfdbfe;">
                    <div style="font-size: 0.85rem; font-weight: 700; color: #1e40af; margin-bottom: 0.25rem;">
                        <i class="fa-solid fa-lightbulb"></i> Metode Inferensi:
                    </div>
                    <p style="font-size: 0.8rem; color: #3b82f6; line-height: 1.4;">
                        Forward Chaining menggunakan fakta input gejala pasien dan mengevaluasi basis aturan secara maju hingga kesimpulan penyakit diperoleh.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RECENT CONSULTATIONS TABLE -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-clock-rotate-left" style="color: #6366f1;"></i>
            Konsultasi Terbaru
        </div>
        <a href="<?= base_url('laporan'); ?>" class="btn btn-sm btn-outline">
            Lihat Semua Laporan <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Usia & JK</th>
                        <th>Tanggal</th>
                        <th>Gejala Terpilih</th>
                        <th>Hasil Diagnosa</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_konsultasi)): ?>
                        <?php foreach ($recent_konsultasi as $k): ?>
                            <tr>
                                <td><strong style="color: #4f46e5;"><?= html_escape($k->no_rm ?: '-'); ?></strong></td>
                                <td>
                                    <strong><?= html_escape($k->nama_pasien ?: $k->user_nama); ?></strong>
                                    <?php if ($k->user_nama): ?>
                                        <div style="font-size: 0.75rem; color: #64748b;">User: <?= html_escape($k->username); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= $k->usia ? $k->usia . ' th' : '-'; ?> (<?= $k->jenis_kelamin ?: '-'; ?>)</td>
                                <td><?= date('d M Y, H:i', strtotime($k->tanggal)); ?></td>
                                <td>
                                    <?php if (!empty($k->gejala)): ?>
                                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                            <?php foreach (array_slice($k->gejala, 0, 4) as $g): ?>
                                                <span class="badge badge-secondary" title="<?= html_escape($g->nama_gejala); ?>"><?= $g->kode_gejala; ?></span>
                                            <?php endforeach; ?>
                                            <?php if (count($k->gejala) > 4): ?>
                                                <span class="badge badge-secondary">+<?= count($k->gejala) - 4; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #94a3b8; font-size: 0.8rem;">-</span>
                                    <?php endif; ?>
                                </td>
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
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #94a3b8; padding: 2rem;">
                                Belum ada riwayat konsultasi pasien.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('diagnosaChart');
    if (ctx) {
        const labels = [
            <?php foreach ($stats['penyakit_stats'] as $ps): ?>
                '<?= addslashes($ps->nama_penyakit); ?>',
            <?php endforeach; ?>
            'Belum Ada Indikasi'
        ];
        const dataVals = [
            <?php foreach ($stats['penyakit_stats'] as $ps): ?>
                <?= $ps->total; ?>,
            <?php endforeach; ?>
            <?= $stats['tidak_terindikasi']; ?>
        ];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pasien',
                    data: dataVals,
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.85)',
                        'rgba(14, 165, 233, 0.85)',
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                        'rgba(148, 163, 184, 0.85)'
                    ],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
});
</script>
