<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Riwayat Konsultasi</h1>
        <p class="page-subtitle">Rekapitulasi seluruh riwayat konsultasi pasien dan hasil diagnosa Forward Chaining.</p>
    </div>
    <div>
        <a href="<?= base_url('laporan/cetak?' . http_build_query($filters)); ?>" target="_blank" class="btn btn-primary">
            <i class="fa-solid fa-print"></i> Cetak Rekapitulasi Laporan
        </a>
    </div>
</div>

<!-- FILTER CARD -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-filter" style="color: #4f46e5;"></i>
            Filter Data Konsultasi
        </div>
    </div>
    <div class="card-body">
        <form action="<?= base_url('laporan'); ?>" method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="start_date">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?= html_escape($filters['start_date'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="end_date">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?= html_escape($filters['end_date'] ?? ''); ?>">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="kode_penyakit">Hasil Diagnosa</label>
                    <select name="kode_penyakit" id="kode_penyakit" class="form-control">
                        <option value="">-- Semua Hasil --</option>
                        <?php foreach ($penyakit as $p): ?>
                            <option value="<?= $p->kode_penyakit; ?>" <?= ($filters['kode_penyakit'] == $p->kode_penyakit) ? 'selected' : ''; ?>>
                                <?= $p->kode_penyakit; ?> - <?= html_escape($p->nama_penyakit); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="NULL" <?= ($filters['kode_penyakit'] === 'NULL') ? 'selected' : ''; ?>>Belum Ada Indikasi Rule</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="search">Pencarian Pasien / RM</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Nama / No. RM..." value="<?= html_escape($filters['search'] ?? ''); ?>">
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem;">
                <a href="<?= base_url('laporan'); ?>" class="btn btn-secondary btn-sm">Reset Filter</a>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i> Terapkan Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- DATA TABLE -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-table-list" style="color: #0ea5e9;"></i>
            Data Konsultasi (Ditemukan: <?= count($konsultasi); ?> Data)
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Usia & JK</th>
                        <th>Tanggal</th>
                        <th>Gejala Terpilih</th>
                        <th>Hasil Diagnosa</th>
                        <th style="width: 140px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($konsultasi)): ?>
                        <?php $no = 1; foreach ($konsultasi as $k): ?>
                            <tr>
                                <td><?= $no++; ?></td>
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
                                    <a href="<?= base_url('konsultasi/hasil/' . $k->id_konsultasi); ?>" class="btn btn-sm btn-outline" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('konsultasi/cetak/' . $k->id_konsultasi); ?>" target="_blank" class="btn btn-sm btn-secondary" title="Cetak Surat Pasien">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('<?= base_url('laporan/hapus/' . $k->id_konsultasi); ?>', 'Hapus riwayat konsultasi pasien <?= html_escape($k->nama_pasien); ?>?')" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: #94a3b8; padding: 2.5rem 1rem;">
                                Tidak ada data konsultasi yang sesuai dengan filter.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
