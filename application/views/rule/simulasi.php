<div class="page-header">
    <div>
        <h1 class="page-title">Uji Simulasi Forward Chaining</h1>
        <p class="page-subtitle">Alat uji coba inferensi pakar untuk menguji kombinasi gejala klinis secara langsung dan mengamati jejak penalaran maju sistem.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="<?= base_url('rule'); ?>" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Basis Aturan
        </a>
    </div>
</div>

<!-- PRESET QUICK TEST BUTTONS (Dari Data Pasien di Excel) -->
<div class="card" style="margin-bottom: 1.5rem; background: #f8fafc;">
    <div class="card-body" style="padding: 1.25rem;">
        <div style="font-size: 0.85rem; font-weight: 700; color: #475569; margin-bottom: 0.5rem;">
            <i class="fa-solid fa-flask" style="color: #6366f1;"></i> Muat Skenario Uji Coba Cepat (Berdasarkan Sampel Excel):
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <button type="button" class="btn btn-sm btn-outline" onclick="loadPreset(['G01', 'G02', 'G14'])">
                Kasus 1: Insomnia (G01, G02, G14)
            </button>
            <button type="button" class="btn btn-sm btn-outline" onclick="loadPreset(['G04', 'G05', 'G06'])">
                Kasus 2: Sleep Apnea (G04, G05, G06)
            </button>
            <button type="button" class="btn btn-sm btn-outline" onclick="loadPreset(['G07', 'G08'])">
                Kasus 3: Restless Legs Syndrome (G07, G08)
            </button>
            <button type="button" class="btn btn-sm btn-outline" onclick="loadPreset(['G09', 'G10', 'G11'])">
                Kasus 4: Narkolepsi (G09, G10, G11)
            </button>
            <button type="button" class="btn btn-sm btn-outline" onclick="loadPreset(['G12', 'G13'])">
                Kasus 5: Parasomnia (G12, G13)
            </button>
            <button type="button" class="btn btn-sm btn-outline" onclick="loadPreset(['G03'])">
                Kasus 6: Hanya G03 (Tidak Ada Indikasi)
            </button>
            <button type="button" class="btn btn-sm btn-outline" onclick="loadPreset(['G01', 'G03', 'G05', 'G07', 'G09', 'G10', 'G12'])">
                Kasus 7: Campuran/Mifzal (Tidak Ada Indikasi)
            </button>
        </div>
    </div>
</div>

<form action="<?= base_url('rule/simulasi'); ?>" method="POST" id="formSimulasi">
    <div class="card">
        <div class="card-header" style="flex-wrap: wrap; gap: 0.75rem;">
            <div class="card-title">
                <i class="fa-solid fa-list-check" style="color: #4f46e5;"></i>
                Pilih Gejala untuk Disimulasikan
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span class="badge badge-primary">Terpilih: <span id="selectedGejalaCount"><?= count($selected_gejala); ?></span> Gejala</span>
                <button type="button" class="btn btn-sm btn-outline" id="btnSelectAll">Pilih Semua</button>
                <button type="button" class="btn btn-sm btn-outline" id="btnResetAll">Reset</button>
            </div>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 0.75rem;">
                <?php foreach ($gejala as $g): ?>
                    <?php $isChecked = in_array($g->kode_gejala, $selected_gejala); ?>
                    <label class="symptom-card <?= $isChecked ? 'selected' : ''; ?>" for="sim_<?= $g->kode_gejala; ?>" style="padding: 0.75rem 1rem;">
                        <input type="checkbox" name="gejala[]" value="<?= $g->kode_gejala; ?>" id="sim_<?= $g->kode_gejala; ?>" <?= $isChecked ? 'checked' : ''; ?>>
                        <div style="flex: 1;">
                            <div class="symptom-code"><?= $g->kode_gejala; ?></div>
                            <div class="symptom-title" style="font-size: 0.85rem;"><?= html_escape($g->nama_gejala); ?></div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer" style="text-align: right;">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-play"></i> Jalankan Simulasi Pelacakan Maju
            </button>
        </div>
    </div>
</form>

<?php if ($inference !== null): ?>
    <!-- HASIL SIMULASI -->
    <div class="card" style="margin-top: 2rem; border-left: 5px solid <?= $inference['is_identified'] ? 'var(--success)' : 'var(--warning)'; ?>;">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="badge <?= $inference['is_identified'] ? 'badge-success' : 'badge-warning'; ?>" style="font-size: 0.85rem;">
                        <?= $inference['is_identified'] ? 'Rule Berhasil Dieksekusi (Fired)' : 'Tidak Ada Rule yang Terpenuhi Penuh'; ?>
                    </span>
                    <h2 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-top: 0.5rem;">
                        <?= $inference['is_identified'] ? html_escape($inference['primary_diagnosa']->nama_penyakit) . ' (' . $inference['primary_diagnosa']->kode_penyakit . ')' : 'Tidak Ada Indikasi Spesifik'; ?>
                    </h2>
                    <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.25rem;">
                        Fakta Masukan: <code><?= !empty($inference['selected_gejala']) ? implode(', ', $inference['selected_gejala']) : '(Kosong)'; ?></code>
                    </p>
                </div>
                <div>
                    <div style="text-align: right;">
                        <span style="font-size: 0.8rem; color: #64748b;">Rule Fired:</span>
                        <div style="font-size: 1.5rem; font-weight: 800; color: <?= $inference['is_identified'] ? 'var(--success)' : 'var(--warning)'; ?>;">
                            <?= $inference['total_rules_fired']; ?> / <?= $inference['total_rules_evaluated']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EVALUASI ATURAN -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-diagram-project" style="color: #0ea5e9;"></i>
                Hasil Evaluasi Aturan (IF-THEN)
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Kode Rule</th>
                            <th>Kondisi (IF)</th>
                            <th>Diagnosa (THEN)</th>
                            <th style="text-align: center;">Kecocokan</th>
                            <th style="text-align: center;">Hasil Evaluasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inference['evaluations'] as $ev): ?>
                            <tr style="<?= $ev['is_fired'] ? 'background-color: #f0fdf4;' : ''; ?>">
                                <td><strong style="color: #4f46e5;"><?= $ev['id_rule']; ?></strong></td>
                                <td><code><?= $ev['if_condition']; ?></code></td>
                                <td><strong><?= html_escape($ev['nama_penyakit']); ?></strong></td>
                                <td style="text-align: center;">
                                    <strong><?= $ev['percentage']; ?>%</strong> (<?= $ev['total_matched']; ?>/<?= $ev['total_required']; ?>)
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($ev['is_fired']): ?>
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Ya</span>
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

    <!-- TRACE STEPS TABLE -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-shoe-prints" style="color: #6366f1;"></i>
                Detail Jejak Inferensi Forward Chaining (Trace Steps)
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="width: 70px; text-align: center;">Langkah</th>
                            <th>Working Memory</th>
                            <th>Rule Diuji</th>
                            <th style="text-align: center;">Kondisi Terpenuhi?</th>
                            <th>Fakta Baru Ditambahkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inference['steps'] as $st): ?>
                            <tr>
                                <td style="text-align: center;"><strong><?= $st['step']; ?></strong></td>
                                <td><code><?= html_escape($st['working_memory']); ?></code></td>
                                <td><?= $st['rule_tested']; ?></td>
                                <td style="text-align: center;"><?= $st['condition_met']; ?></td>
                                <td><strong><?= html_escape($st['new_facts']); ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
function loadPreset(gejalaCodes) {
    document.querySelectorAll('#formSimulasi input[type="checkbox"]').forEach(cb => {
        cb.checked = gejalaCodes.includes(cb.value);
        const card = cb.closest('.symptom-card');
        if (card) {
            if (cb.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        }
    });
    const counter = document.getElementById('selectedGejalaCount');
    if (counter) counter.innerText = gejalaCodes.length;
    document.getElementById('formSimulasi').submit();
}
</script>
