<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Data Pengguna Sistem</h1>
        <p class="page-subtitle">Daftar akun Administrator (Pakar) dan Pasien yang terdaftar di sistem.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" onclick="openModal('modalTambahUser')">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-users" style="color: #4f46e5;"></i>
            Data Pengguna Terdaftar (Total: <?= count($users); ?>)
        </div>
        <div style="max-width: 250px; width: 100%;">
            <input type="text" id="tableSearch" class="form-control" placeholder="Cari nama / username...">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table-custom table-searchable">
                <thead>
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role Akses</th>
                        <th style="width: 140px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>#<?= $u->id_user; ?></td>
                                <td><strong><?= html_escape($u->nama); ?></strong></td>
                                <td><code><?= html_escape($u->username); ?></code></td>
                                <td>
                                    <?php if ($u->role === 'admin'): ?>
                                        <span class="badge badge-primary"><i class="fa-solid fa-user-shield"></i> Pakar (Admin)</span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-user"></i> Pasien (User)</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <button type="button" class="btn btn-sm btn-outline" onclick="editUser(<?= $u->id_user; ?>, '<?= addslashes(html_escape($u->nama)); ?>', '<?= addslashes(html_escape($u->username)); ?>', '<?= $u->role; ?>')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <?php if ($u->id_user != $user->id_user): ?>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('<?= base_url('user/hapus/' . $u->id_user); ?>', 'Hapus pengguna <?= html_escape($u->nama); ?>?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94a3b8; padding: 2rem;">Belum ada user.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH USER -->
<div class="modal-backdrop" id="modalTambahUser">
    <div class="modal-box">
        <form action="<?= base_url('user/tambah'); ?>" method="POST">
            <div class="modal-header">
                <div class="modal-title"><i class="fa-solid fa-user-plus" style="color: #4f46e5;"></i> Tambah Pengguna Baru</div>
                <button type="button" class="modal-close" onclick="closeModal('modalTambahUser')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="add_nama">Nama Lengkap *</label>
                    <input type="text" name="nama" id="add_nama" class="form-control" placeholder="Nama lengkap..." required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="add_username">Username *</label>
                    <input type="text" name="username" id="add_username" class="form-control" placeholder="Username login..." required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="add_password">Password *</label>
                    <input type="password" name="password" id="add_password" class="form-control" placeholder="Password minimal 5 karakter..." required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="add_role">Role Akses *</label>
                    <select name="role" id="add_role" class="form-control" required>
                        <option value="user">Pasien (User Biasa)</option>
                        <option value="admin">Administrator (Pakar Medis)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTambahUser')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan User</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT USER -->
<div class="modal-backdrop" id="modalEditUser">
    <div class="modal-box">
        <form id="formEditUser" action="" method="POST">
            <div class="modal-header">
                <div class="modal-title"><i class="fa-solid fa-user-pen" style="color: #4f46e5;"></i> Edit Pengguna</div>
                <button type="button" class="modal-close" onclick="closeModal('modalEditUser')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="edit_nama">Nama Lengkap *</label>
                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit_username">Username *</label>
                    <input type="text" name="username" id="edit_username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="edit_password">Ganti Password (Opsional)</label>
                    <input type="password" name="password" id="edit_password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" for="edit_role">Role Akses *</label>
                    <select name="role" id="edit_role" class="form-control" required>
                        <option value="user">Pasien (User Biasa)</option>
                        <option value="admin">Administrator (Pakar Medis)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditUser')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Perbarui User</button>
            </div>
        </form>
    </div>
</div>

<script>
function editUser(id, nama, username, role) {
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_password').value = '';
    document.getElementById('formEditUser').action = '<?= base_url('user/edit/'); ?>' + id;
    openModal('modalEditUser');
}
</script>
