<div class="page-header">
    <div>
        <h1 class="page-title">Hasil Diagnosa Gangguan Tidur</h1>
        <p class="page-subtitle">Laporan inferensi metode Forward Chaining untuk konsultasi <strong><?= html_escape($konsultasi->no_rm); ?></strong>.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="<?= base_url('konsultasi/cetak/' . $konsultasi->id_konsultasi); ?>" target="_blank" class="btn btn-secondary">
            <i class="fa-solid fa-print"></i> Cetak Hasil
        </a>
        <a href="<?= base_url('konsultasi'); ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Konsultasi Baru
        </a>
    </div>
</div>

<!-- DATA PASIEN SUMMARY -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-user-check" style="color: #4f46e5;"></i>
            Data Identitas Pasien
        </div>
        <span class="badge badge-secondary"><?= date('d F Y, H:i', strtotime($konsultasi->tanggal)); ?></span>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem;">
            <div>
                <span style="font-size: 0.8rem; color: #64748b; display: block;">No. Rekam Medis</span>
                <strong style="color: #0f172a; font-size: 1.05rem;"><?= html_escape($konsultasi->no_rm ?: '-'); ?></strong>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: #64748b; display: block;">Nama Pasien</span>
                <strong style="color: #0f172a; font-size: 1.05rem;"><?= html_escape($konsultasi->nama_pasien ?: $konsultasi->user_nama); ?></strong>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: #64748b; display: block;">Usia / Jenis Kelamin</span>
                <strong style="color: #0f172a; font-size: 1.05rem;"><?= $konsultasi->usia ? $konsultasi->usia . ' Tahun' : '-'; ?> / <?= $konsultasi->jenis_kelamin ?: '-'; ?></strong>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: #64748b; display: block;">Keluhan Utama</span>
                <div style="color: #334155; font-size: 0.95rem; font-weight: 500;"><?= html_escape($konsultasi->keluhan ?: '-'); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- HASIL DIAGNOSA UTAMA -->
<div class="card" style="margin-bottom: 2rem; border-left: 5px solid <?= $inference['is_identified'] ? 'var(--success)' : 'var(--warning)'; ?>;">
    <div class="card-body" style="padding: 2rem;">
        <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            <div style="flex: 1; min-width: 300px;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <span class="badge <?= $inference['is_identified'] ? 'badge-success' : 'badge-warning'; ?>" style="font-size: 0.85rem; padding: 4px 10px;">
                        <?= $inference['is_identified'] ? 'Terindikasi Gangguan Tidur' : 'Hasil Belum Memenuhi Rule'; ?>
                    </span>
                    <span style="font-size: 0.85rem; color: #64748b;">
                        <?= $inference['total_rules_fired']; ?> dari <?= $inference['total_rules_evaluated']; ?> Rule Terpenuhi
                    </span>
                </div>

                <?php if ($inference['is_identified']): ?>
                    <h2 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem;">
                        <?= html_escape($inference['primary_diagnosa']->nama_penyakit); ?>
                        <span style="font-size: 1.15rem; color: #64748b; font-weight: 600;">(<?= $inference['primary_diagnosa']->kode_penyakit; ?>)</span>
                    </h2>

                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 1.25rem; margin-top: 1rem;">
                        <div style="font-size: 0.9rem; font-weight: 700; color: #065f46; margin-bottom: 0.35rem;">
                            <i class="fa-solid fa-stethoscope"></i> Saran Penanganan Medis / Awal:
                        </div>
                        <p style="font-size: 0.925rem; color: #047857; line-height: 1.6; margin: 0;">
                            <?= html_escape($inference['primary_diagnosa']->saran_penanganan); ?>
                        </p>
                    </div>
                <?php else: ?>
                    <h2 style="font-size: 1.65rem; font-weight: 800; color: #92400e; margin-bottom: 0.5rem;">
                        Tidak Ada Indikasi Spesifik
                    </h2>
                    <p style="font-size: 0.95rem; color: #78350f; line-height: 1.5;">
                        Kombinasi gejala yang Anda masukkan belum memenuhi syarat lengkap dari salah satu aturan (rule base) jenis gangguan tidur klinis di sistem kami.
                    </p>

                    <?php if (!empty($inference['highest_partial'])): ?>
                        <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 1.25rem; margin-top: 1rem;">
                            <div style="font-size: 0.9rem; font-weight: 700; color: #92400e; margin-bottom: 0.35rem;">
                                <i class="fa-solid fa-triangle-exclamation"></i> Indikasi Terdekat (Kecocokan Parsial):
                            </div>
                            <p style="font-size: 0.9rem; color: #b45309; line-height: 1.5;">
                                Gejala Anda memiliki kecocokan tertinggi sebesar <strong><?= $inference['highest_partial']['percentage']; ?>%</strong> dengan <strong><?= html_escape($inference['highest_partial']['rule']->nama_penyakit); ?></strong> (Rule <?= $inference['highest_partial']['rule']->id_rule; ?>), namun masih kurang gejala: 
                                <strong><?= implode(', ', $inference['highest_partial']['missing_gejala']); ?></strong>.
                            </p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- GEJALA YANG DIALAMI PASIEN -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; max-width: 360px; width: 100%;">
                <div style="font-size: 0.85rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fa-solid fa-list-check" style="color: #4f46e5;"></i> Gejala yang Dialami</span>
                    <span class="badge badge-primary"><?= count($inference['input_symptoms']); ?> Gejala</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <?php foreach ($inference['input_symptoms'] as $gs): ?>
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.825rem; color: #334155;">
                            <span class="badge badge-secondary" style="font-size: 0.75rem;"><?= $gs->kode_gejala; ?></span>
                            <span><?= html_escape($gs->nama_gejala); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- EVALUASI ATURAN (RULE EVALUATION) -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-diagram-project" style="color: #0ea5e9;"></i>
            C. Evaluasi Rule (Forward Chaining)
        </div>
        <span class="badge badge-secondary">Pencocokan Premis IF-THEN</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 100px;">Kode Rule</th>
                        <th>Kondisi Premis (IF)</th>
                        <th>Kesimpulan (THEN)</th>
                        <th style="text-align: center;">Kecocokan Gejala</th>
                        <th style="text-align: center;">Hasil Evaluasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inference['evaluations'] as $ev): ?>
                        <tr style="<?= $ev['is_fired'] ? 'background-color: #f0fdf4;' : ''; ?>">
                            <td><strong style="color: #4f46e5;"><?= $ev['id_rule']; ?></strong></td>
                            <td>
                                <code><?= $ev['if_condition']; ?></code>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 3px;">
                                    Terpenuhi: <strong><?= !empty($ev['matched_gejala']) ? implode(', ', $ev['matched_gejala']) : '-'; ?></strong>
                                    <?php if (!empty($ev['missing_gejala'])): ?>
                                        <span style="color: #94a3b8;"> | Kurang: <?= implode(', ', $ev['missing_gejala']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><strong><?= html_escape($ev['nama_penyakit']); ?></strong> (<?= $ev['kode_penyakit']; ?>)</td>
                            <td style="text-align: center;">
                                <div style="font-weight: 700; color: <?= $ev['is_fired'] ? '#15803d' : ($ev['percentage'] > 0 ? '#b45309' : '#94a3b8'); ?>;">
                                    <?= $ev['percentage']; ?>% (<?= $ev['total_matched']; ?>/<?= $ev['total_required']; ?>)
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($ev['is_fired']): ?>
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Ya (Terpenuhi)</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Tidak</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- LANGKAH PELACAKAN FORWARD CHAINING TRACE TABLE -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-shoe-prints" style="color: #6366f1;"></i>
            D. Simulasi Langkah Pelacakan Maju (Trace Forward Chaining)
        </div>
        <span class="badge badge-primary">Sesuai Dokumen Perancangan SPK</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Langkah</th>
                        <th>Fakta di Working Memory</th>
                        <th>Rule Diuji</th>
                        <th style="text-align: center;">Kondisi Terpenuhi?</th>
                        <th>Fakta Baru Ditambahkan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inference['steps'] as $st): ?>
                        <tr>
                            <td style="text-align: center;"><strong><?= $st['step']; ?></strong></td>
                            <td><code style="font-size: 0.8rem;"><?= html_escape($st['working_memory']); ?></code></td>
                            <td><?= $st['rule_tested']; ?></td>
                            <td style="text-align: center;">
                                <?php if (strpos($st['condition_met'], 'Ya') !== false): ?>
                                    <span class="badge badge-success"><?= $st['condition_met']; ?></span>
                                <?php elseif ($st['condition_met'] === '-'): ?>
                                    <span style="color: #94a3b8;">-</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?= $st['condition_met']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (strpos($st['new_facts'], '+') !== false || strpos($st['new_facts'], 'Hasil') !== false): ?>
                                    <strong style="color: #15803d;"><?= html_escape($st['new_facts']); ?></strong>
                                <?php else: ?>
                                    <span style="color: #64748b;"><?= html_escape($st['new_facts']); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
