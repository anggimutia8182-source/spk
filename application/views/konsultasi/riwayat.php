<div class="page-header">
    <div>
        <h1 class="page-title">Riwayat Konsultasi Pasien</h1>
        <p class="page-subtitle">Daftar riwayat konsultasi dan hasil diagnosa gangguan tidur yang pernah Anda lakukan.</p>
    </div>
    <div>
        <a href="<?= base_url('konsultasi'); ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Konsultasi Baru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-clock-rotate-left" style="color: #4f46e5;"></i>
            Data Riwayat Konsultasi
        </div>
        <div style="max-width: 250px; width: 100%;">
            <input type="text" id="tableSearch" class="form-control" placeholder="Cari No. RM / Tanggal...">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom table-searchable">
                <thead>
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th>No. Rekam Medis</th>
                        <th>Tanggal Konsultasi</th>
                        <th>Gejala yang Dipilih</th>
                        <th>Hasil Diagnosa</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($konsultasi)): ?>
                        <?php $no = 1; foreach ($konsultasi as $k): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong style="color: #4f46e5;"><?= html_escape($k->no_rm ?: '-'); ?></strong></td>
                                <td><?= date('d M Y, H:i', strtotime($k->tanggal)); ?></td>
                                <td>
                                    <?php if (!empty($k->gejala)): ?>
                                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                            <?php foreach ($k->gejala as $g): ?>
                                                <span class="badge badge-secondary" title="<?= html_escape($g->nama_gejala); ?>"><?= $g->kode_gejala; ?></span>
                                            <?php endforeach; ?>
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
                                    <a href="<?= base_url('konsultasi/cetak/' . $k->id_konsultasi); ?>" target="_blank" class="btn btn-sm btn-secondary">
                                        <i class="fa-solid fa-print"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #94a3b8; padding: 2.5rem 1rem;">
                                Belum ada riwayat konsultasi. <a href="<?= base_url('konsultasi'); ?>">Mulai sekarang</a>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
