<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Data Gejala</h1>
        <p class="page-subtitle">Basis fakta klinis yang digunakan dalam aturan inferensi Forward Chaining.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" onclick="openModal('modalTambahGejala')">
            <i class="fa-solid fa-plus"></i> Tambah Gejala Baru
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-clipboard-list" style="color: #4f46e5;"></i>
            Daftar Gejala (Total: <?= count($gejala); ?>)
        </div>
        <div style="max-width: 250px; width: 100%;">
            <input type="text" id="tableSearch" class="form-control" placeholder="Cari kode / nama gejala...">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom table-searchable">
                <thead>
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th style="width: 110px;">Kode Gejala</th>
                        <th>Nama Gejala</th>
                        <th>Keterangan / Definisi Klinis</th>
                        <th style="width: 140px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($gejala)): ?>
                        <?php $no = 1; foreach ($gejala as $g): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><span class="badge badge-primary"><?= $g->kode_gejala; ?></span></td>
                                <td><strong><?= html_escape($g->nama_gejala); ?></strong></td>
                                <td style="color: #64748b;"><?= html_escape($g->keterangan ?: '-'); ?></td>
                                <td style="text-align: right;">
                                    <button type="button" class="btn btn-sm btn-outline" onclick="editGejala('<?= $g->kode_gejala; ?>', '<?= addslashes(html_escape($g->nama_gejala)); ?>', '<?= addslashes(html_escape($g->keterangan)); ?>')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('<?= base_url('gejala/hapus/' . $g->kode_gejala); ?>', 'Hapus gejala <?= $g->kode_gejala; ?>?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 2rem;">Belum ada data gejala.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH GEJALA -->
<div class="modal-backdrop" id="modalTambahGejala">
    <div class="modal-box">
        <form action="<?= base_url('gejala/tambah'); ?>" method="POST">
            <div class="modal-header">
                <div class="modal-title"><i class="fa-solid fa-plus-circle" style="color: #4f46e5;"></i> Tambah Gejala Baru</div>
                <button type="button" class="modal-close" onclick="closeModal('modalTambahGejala')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="add_kode_gejala">Kode Gejala *</label>
                    <input type="text" name="kode_gejala" id="add_kode_gejala" class="form-control" placeholder="Contoh: G15" required maxlength="5">
                    <small style="color: #64748b; font-size: 0.75rem;">Format standar G01, G02, dst.</small>
                </div>
                <div class="form-group">
                    <label class="form-label" for="add_nama_gejala">Nama Gejala *</label>
                    <input type="text" name="nama_gejala" id="add_nama_gejala" class="form-control" placeholder="Nama gejala klinis..." required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="add_keterangan">Keterangan / Definisi</label>
                    <textarea name="keterangan" id="add_keterangan" class="form-control" placeholder="Penjelasan atau durasi gejala..." rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTambahGejala')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Gejala</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT GEJALA -->
<div class="modal-backdrop" id="modalEditGejala">
    <div class="modal-box">
        <form id="formEditGejala" action="" method="POST">
            <div class="modal-header">
                <div class="modal-title"><i class="fa-solid fa-pen-to-square" style="color: #4f46e5;"></i> Edit Gejala <span id="editKodeText" class="badge badge-primary"></span></div>
                <button type="button" class="modal-close" onclick="closeModal('modalEditGejala')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="edit_nama_gejala">Nama Gejala *</label>
                    <input type="text" name="nama_gejala" id="edit_nama_gejala" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="edit_keterangan">Keterangan / Definisi</label>
                    <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditGejala')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Perbarui Gejala</button>
            </div>
        </form>
    </div>
</div>

<script>
function editGejala(kode, nama, ket) {
    document.getElementById('editKodeText').innerText = kode;
    document.getElementById('edit_nama_gejala').value = nama;
    document.getElementById('edit_keterangan').value = ket;
    document.getElementById('formEditGejala').action = '<?= base_url('gejala/edit/'); ?>' + kode;
    openModal('modalEditGejala');
}
</script>
