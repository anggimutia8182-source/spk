<div class="page-header">
    <div>
        <h1 class="page-title">Matriks Rule-Gejala & Tabel Keputusan</h1>
        <p class="page-subtitle">Visualisasi relasi silang basis pengetahuan sistem pakar sesuai dokumen analisis dan perancangan.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="<?= base_url('rule'); ?>" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Rule Base
        </a>
        <a href="<?= base_url('rule/simulasi'); ?>" class="btn btn-primary">
            <i class="fa-solid fa-vial-virus"></i> Uji Simulasi
        </a>
    </div>
</div>

<!-- SECTION 1: MATRIKS RELASI RULE DAN GEJALA -->
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-table-cells" style="color: #4f46e5;"></i>
            1. Matriks Relasi Rule dan Gejala (Basis Pengetahuan)
        </div>
        <span class="badge badge-success">Sheet: Matriks Rule-Gejala</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom matrix-table">
                <thead>
                    <tr>
                        <th style="min-width: 90px; text-align: left;">Kode Rule</th>
                        <th style="min-width: 180px; text-align: left;">Diagnosa (THEN)</th>
                        <?php foreach ($matrix_data['gejala'] as $g): ?>
                            <th title="<?= html_escape($g->nama_gejala); ?>"><?= $g->kode_gejala; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matrix_data['rules'] as $r): ?>
                        <tr>
                            <td style="text-align: left;"><strong style="color: #4f46e5;"><?= $r->id_rule; ?></strong></td>
                            <td style="text-align: left;"><strong><?= html_escape($r->nama_penyakit); ?></strong></td>
                            <?php foreach ($matrix_data['gejala'] as $g): ?>
                                <?php $is_in = in_array($g->kode_gejala, $r->gejala_codes); ?>
                                <td class="<?= $is_in ? 'matched' : 'empty'; ?>">
                                    <?= $is_in ? 'X' : '-'; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer" style="font-size: 0.8rem; color: #64748b;">
        <span style="display: inline-block; width: 14px; height: 14px; background: #dcfce7; border: 1px solid #86efac; vertical-align: middle; border-radius: 3px; margin-right: 4px;"></span>
        Sel berwarna hijau dengan tanda '<strong>X</strong>' menandakan gejala merupakan bagian dari rule terkait.
    </div>
</div>

<!-- SECTION 2: TABEL KEPUTUSAN (MATRIKS GEJALA x PENYAKIT) -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-table" style="color: #0ea5e9;"></i>
            2. Tabel Keputusan (Matriks Gejala &times; Penyakit)
        </div>
        <span class="badge badge-primary">Sheet: Tabel Keputusan</span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom matrix-table">
                <thead>
                    <tr>
                        <th style="width: 90px; text-align: left;">Kode</th>
                        <th style="min-width: 280px; text-align: left;">Nama Gejala Klinis</th>
                        <?php foreach ($matrix_data['penyakit'] as $p): ?>
                            <th style="min-width: 140px; text-align: center;">
                                <div><?= $p->kode_penyakit; ?></div>
                                <div style="font-size: 0.7rem; font-weight: 500; text-transform: none;"><?= html_escape($p->nama_penyakit); ?></div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matrix_data['gejala'] as $g): ?>
                        <tr>
                            <td style="text-align: left;"><span class="badge badge-secondary"><?= $g->kode_gejala; ?></span></td>
                            <td style="text-align: left;">
                                <strong><?= html_escape($g->nama_gejala); ?></strong>
                                <?php if (!empty($g->keterangan)): ?>
                                    <div style="font-size: 0.75rem; color: #64748b;"><?= html_escape($g->keterangan); ?></div>
                                <?php endif; ?>
                            </td>
                            <?php foreach ($matrix_data['penyakit'] as $p): ?>
                                <?php 
                                    $rel = isset($matrix_data['penyakit_gejala_map'][$p->kode_penyakit]) 
                                           && in_array($g->kode_gejala, $matrix_data['penyakit_gejala_map'][$p->kode_penyakit]);
                                ?>
                                <td class="<?= $rel ? 'matched' : 'empty'; ?>">
                                    <?= $rel ? 'X' : '-'; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer" style="font-size: 0.8rem; color: #64748b;">
        Catatan: Gejala yang tidak memiliki tanda <strong>X</strong> pada tabel keputusan (seperti <em>G03: Merasa lelah meski sudah tidur cukup lama</em>) merupakan gejala pendukung non-spesifik yang belum diikat oleh rule diagnostik manapun.
    </div>
</div>
