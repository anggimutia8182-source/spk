<div class="page-header">
    <div>
        <h1 class="page-title">Basis Aturan (Rule Base)</h1>
        <p class="page-subtitle">Aturan produksi logika <strong>IF [Gejala...] THEN [Penyakit]</strong> untuk mesin inferensi Forward Chaining.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="<?= base_url('rule/matriks'); ?>" class="btn btn-secondary">
            <i class="fa-solid fa-table-cells"></i> Lihat Matriks & Tabel Keputusan
        </a>
        <button type="button" class="btn btn-primary" onclick="openModal('modalTambahRule')">
            <i class="fa-solid fa-plus"></i> Tambah Rule Baru
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-diagram-project" style="color: #4f46e5;"></i>
            Daftar Aturan Produksi (Total: <?= count($rules); ?> Aturan)
        </div>
        <div style="max-width: 250px; width: 100%;">
            <input type="text" id="tableSearch" class="form-control" placeholder="Cari rule / penyakit...">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom table-searchable">
                <thead>
                    <tr>
                        <th style="width: 100px;">Kode Rule</th>
                        <th style="width: 200px;">Kesimpulan (THEN)</th>
                        <th>Kondisi Premis (IF) Gejala</th>
                        <th style="width: 120px; text-align: center;">Total Gejala</th>
                        <th style="width: 140px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rules)): ?>
                        <?php foreach ($rules as $r): ?>
                            <tr>
                                <td><span class="badge badge-primary" style="font-size: 0.85rem;"><?= $r->id_rule; ?></span></td>
                                <td>
                                    <strong><?= html_escape($r->nama_penyakit); ?></strong>
                                    <div style="font-size: 0.75rem; color: #64748b;"><?= $r->kode_penyakit; ?></div>
                                </td>
                                <td>
                                    <div style="margin-bottom: 5px;">
                                        <code><?= implode(' AND ', $r->gejala_codes); ?></code>
                                    </div>
                                    <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                        <?php foreach ($r->gejala as $g): ?>
                                            <span class="badge badge-secondary" title="<?= html_escape($g->keterangan); ?>">
                                                <strong><?= $g->kode_gejala; ?>:</strong> <?= html_escape($g->nama_gejala); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge badge-success"><?= count($r->gejala); ?> Gejala</span>
                                </td>
                                <td style="text-align: right;">
                                    <button type="button" class="btn btn-sm btn-outline" onclick="editRule('<?= $r->id_rule; ?>', '<?= $r->kode_penyakit; ?>', <?= html_escape(json_encode($r->gejala_codes)); ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('<?= base_url('rule/hapus/' . $r->id_rule); ?>', 'Hapus rule <?= $r->id_rule; ?>?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 2rem;">Belum ada data rule.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH RULE -->
<div class="modal-backdrop" id="modalTambahRule">
    <div class="modal-box" style="max-width: 700px;">
        <form action="<?= base_url('rule/tambah'); ?>" method="POST">
            <div class="modal-header">
                <div class="modal-title"><i class="fa-solid fa-plus-circle" style="color: #4f46e5;"></i> Tambah Rule Produksi Baru</div>
                <button type="button" class="modal-close" onclick="closeModal('modalTambahRule')">&times;</button>
            </div>
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="add_id_rule">Kode Rule *</label>
                        <input type="text" name="id_rule" id="add_id_rule" class="form-control" value="<?= html_escape($next_rule_id); ?>" required maxlength="5">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="add_kode_penyakit">Diagnosa Penyakit (THEN) *</label>
                        <select name="kode_penyakit" id="add_kode_penyakit" class="form-control" required>
                            <option value="">-- Pilih Penyakit --</option>
                            <?php foreach ($penyakit as $p): ?>
                                <option value="<?= $p->kode_penyakit; ?>"><?= $p->kode_penyakit; ?> - <?= html_escape($p->nama_penyakit); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Gejala-Gejala Premis (IF) *</label>
                    <small style="display:block; color:#64748b; margin-bottom: 0.5rem;">Centang seluruh gejala yang wajib terpenuhi agar kesimpulan rule tercapai:</small>
                    <div style="max-height: 280px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.75rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; background: #f8fafc;">
                        <?php foreach ($gejala as $g): ?>
                            <label style="display: flex; align-items: flex-start; gap: 8px; font-size: 0.825rem; background: white; padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0; cursor: pointer;">
                                <input type="checkbox" name="gejala[]" value="<?= $g->kode_gejala; ?>" style="margin-top: 3px;">
                                <div>
                                    <strong style="color: #4f46e5;"><?= $g->kode_gejala; ?>:</strong> <?= html_escape($g->nama_gejala); ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTambahRule')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Rule</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT RULE -->
<div class="modal-backdrop" id="modalEditRule">
    <div class="modal-box" style="max-width: 700px;">
        <form id="formEditRule" action="" method="POST">
            <div class="modal-header">
                <div class="modal-title"><i class="fa-solid fa-pen-to-square" style="color: #4f46e5;"></i> Edit Rule <span id="editRuleIdText" class="badge badge-primary"></span></div>
                <button type="button" class="modal-close" onclick="closeModal('modalEditRule')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="edit_kode_penyakit">Diagnosa Penyakit (THEN) *</label>
                    <select name="kode_penyakit" id="edit_kode_penyakit" class="form-control" required>
                        <option value="">-- Pilih Penyakit --</option>
                        <?php foreach ($penyakit as $p): ?>
                            <option value="<?= $p->kode_penyakit; ?>"><?= $p->kode_penyakit; ?> - <?= html_escape($p->nama_penyakit); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Gejala-Gejala Premis (IF) *</label>
                    <div style="max-height: 280px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 8px; padding: 0.75rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; background: #f8fafc;" id="editGejalaCheckboxes">
                        <?php foreach ($gejala as $g): ?>
                            <label style="display: flex; align-items: flex-start; gap: 8px; font-size: 0.825rem; background: white; padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0; cursor: pointer;">
                                <input type="checkbox" name="gejala[]" value="<?= $g->kode_gejala; ?>" id="edit_g_<?= $g->kode_gejala; ?>" style="margin-top: 3px;">
                                <div>
                                    <strong style="color: #4f46e5;"><?= $g->kode_gejala; ?>:</strong> <?= html_escape($g->nama_gejala); ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditRule')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Perbarui Rule</button>
            </div>
        </form>
    </div>
</div>

<script>
function editRule(idRule, kodePenyakit, gejalaList) {
    document.getElementById('editRuleIdText').innerText = idRule;
    document.getElementById('edit_kode_penyakit').value = kodePenyakit;
    document.getElementById('formEditRule').action = '<?= base_url('rule/edit/'); ?>' + idRule;

    // reset all checkboxes
    document.querySelectorAll('#editGejalaCheckboxes input[type="checkbox"]').forEach(cb => {
        cb.checked = gejalaList.includes(cb.value);
    });

    openModal('modalEditRule');
}
</script>
