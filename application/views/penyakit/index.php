<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Data Gangguan Tidur (Penyakit)</h1>
        <p class="page-subtitle">Basis kesimpulan / goal dalam sistem pakar beserta rekomendasi penanganan klinis.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" onclick="openModal('modalTambahPenyakit')">
            <i class="fa-solid fa-plus"></i> Tambah Penyakit Baru
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-bed" style="color: #4f46e5;"></i>
            Daftar Jenis Gangguan Tidur (Total: <?= count($penyakit); ?>)
        </div>
        <div style="max-width: 250px; width: 100%;">
            <input type="text" id="tableSearch" class="form-control" placeholder="Cari kode / nama penyakit...">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom table-searchable">
                <thead>
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th style="width: 120px;">Kode Penyakit</th>
                        <th style="width: 220px;">Nama Gangguan Tidur</th>
                        <th>Saran Penanganan Awal</th>
                        <th style="width: 140px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($penyakit)): ?>
                        <?php $no = 1; foreach ($penyakit as $p): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><span class="badge badge-primary"><?= $p->kode_penyakit; ?></span></td>
                                <td><strong><?= html_escape($p->nama_penyakit); ?></strong></td>
                                <td style="color: #475569; line-height: 1.5;"><?= html_escape($p->saran_penanganan ?: '-'); ?></td>
                                <td style="text-align: right;">
                                    <button type="button" class="btn btn-sm btn-outline" onclick="editPenyakit('<?= $p->kode_penyakit; ?>', '<?= addslashes(html_escape($p->nama_penyakit)); ?>', '<?= addslashes(html_escape($p->saran_penanganan)); ?>')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('<?= base_url('penyakit/hapus/' . $p->kode_penyakit); ?>', 'Hapus data <?= $p->kode_penyakit; ?>?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 2rem;">Belum ada data penyakit.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PENYAKIT -->
<div class="modal-backdrop" id="modalTambahPenyakit">
    <div class="modal-box">
        <form action="<?= base_url('penyakit/tambah'); ?>" method="POST">
            <div class="modal-header">
                <div class="modal-title"><i class="fa-solid fa-plus-circle" style="color: #4f46e5;"></i> Tambah Gangguan Tidur Baru</div>
                <button type="button" class="modal-close" onclick="closeModal('modalTambahPenyakit')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="add_kode_penyakit">Kode Penyakit *</label>
                    <input type="text" name="kode_penyakit" id="add_kode_penyakit" class="form-control" placeholder="Contoh: P06" required maxlength="5">
                </div>
                <div class="form-group">
                    <label class="form-label" for="add_nama_penyakit">Nama Gangguan Tidur *</label>
                    <input type="text" name="nama_penyakit" id="add_nama_penyakit" class="form-control" placeholder="Nama penyakit..." required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="add_saran_penanganan">Saran Penanganan Awal</label>
                    <textarea name="saran_penanganan" id="add_saran_penanganan" class="form-control" placeholder="Saran penanganan medis / gaya hidup..." rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTambahPenyakit')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Penyakit</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT PENYAKIT -->
<div class="modal-backdrop" id="modalEditPenyakit">
    <div class="modal-box">
        <form id="formEditPenyakit" action="" method="POST">
            <div class="modal-header">
                <div class="modal-title"><i class="fa-solid fa-pen-to-square" style="color: #4f46e5;"></i> Edit Penyakit <span id="editKodePenyakitText" class="badge badge-primary"></span></div>
                <button type="button" class="modal-close" onclick="closeModal('modalEditPenyakit')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="edit_nama_penyakit">Nama Gangguan Tidur *</label>
                    <input type="text" name="nama_penyakit" id="edit_nama_penyakit" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="edit_saran_penanganan">Saran Penanganan Awal</label>
                    <textarea name="saran_penanganan" id="edit_saran_penanganan" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditPenyakit')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Perbarui Penyakit</button>
            </div>
        </form>
    </div>
</div>

<script>
function editPenyakit(kode, nama, saran) {
    document.getElementById('editKodePenyakitText').innerText = kode;
    document.getElementById('edit_nama_penyakit').value = nama;
    document.getElementById('edit_saran_penanganan').value = saran;
    document.getElementById('formEditPenyakit').action = '<?= base_url('penyakit/edit/'); ?>' + kode;
    openModal('modalEditPenyakit');
}
</script>
