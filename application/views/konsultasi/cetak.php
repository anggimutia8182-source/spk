<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Hasil Diagnosa - <?= html_escape($konsultasi->no_rm); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .sheet {
            background: #ffffff;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .header {
            border-bottom: 3px double #0f172a;
            padding-bottom: 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo-title h1 {
            font-size: 1.4rem;
            margin: 0;
            color: #0f172a;
            font-weight: 800;
        }
        .logo-title p {
            margin: 3px 0 0;
            font-size: 0.85rem;
            color: #64748b;
        }
        .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin: 20px 0 12px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            font-size: 0.9rem;
        }
        .info-row {
            display: flex;
        }
        .info-label {
            width: 140px;
            color: #64748b;
            font-weight: 500;
        }
        .info-val {
            font-weight: 600;
            color: #0f172a;
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            text-align: left;
        }
        table th {
            background: #f8fafc;
            color: #334155;
            font-weight: 700;
        }
        .result-box {
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 18px;
            margin-top: 15px;
        }
        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .actions-bar {
            max-width: 800px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-print { background: #4f46e5; color: white; }
        .btn-close { background: #64748b; color: white; }

        @media print {
            body { background: white; padding: 0; }
            .sheet { box-shadow: none; padding: 20px; }
            .actions-bar { display: none; }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <button onclick="window.print()" class="btn btn-print"><i class="fa-solid fa-print"></i> Cetak / Simpan PDF</button>
        <button onclick="window.close()" class="btn btn-close"><i class="fa-solid fa-xmark"></i> Tutup</button>
    </div>

    <div class="sheet">
        <div class="header">
            <div class="logo-title">
                <h1>SOMNOEXPERT KLINIK TIDUR</h1>
                <p>Sistem Pakar Identifikasi Jenis Gangguan Tidur &bull; Metode Forward Chaining</p>
                <p style="font-size: 0.75rem;">Jl. Kesehatan No. 10 &bull; Telp: (021) 555-0199 &bull; Website: somnoexpert.med</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 1.1rem; font-weight: 800; color: #4f46e5;"><?= html_escape($konsultasi->no_rm ?: 'RM-0001'); ?></div>
                <div style="font-size: 0.8rem; color: #64748b;"><?= date('d F Y', strtotime($konsultasi->tanggal)); ?></div>
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="font-size: 1.15rem; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; color: #0f172a;">
                SURAT HASIL DIAGNOSA KONSULTASI GANGGUAN TIDUR
            </h2>
        </div>

        <div class="section-title">I. Identitas Pasien</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">No. Rekam Medis:</div>
                <div class="info-val"><?= html_escape($konsultasi->no_rm ?: '-'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Periksa:</div>
                <div class="info-val"><?= date('d M Y, H:i', strtotime($konsultasi->tanggal)); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Pasien:</div>
                <div class="info-val"><?= html_escape($konsultasi->nama_pasien ?: $konsultasi->user_nama); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Jenis Kelamin:</div>
                <div class="info-val"><?= $konsultasi->jenis_kelamin ?: '-'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Usia:</div>
                <div class="info-val"><?= $konsultasi->usia ? $konsultasi->usia . ' Tahun' : '-'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Keluhan Utama:</div>
                <div class="info-val"><?= html_escape($konsultasi->keluhan ?: '-'); ?></div>
            </div>
        </div>

        <div class="section-title">II. Gejala Klinis yang Dialami (Fakta Masukan)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th style="width: 90px;">Kode</th>
                    <th>Nama Gejala</th>
                    <th>Keterangan Klinis</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($inference['input_symptoms'])): ?>
                    <?php $no = 1; foreach ($inference['input_symptoms'] as $g): ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++; ?></td>
                            <td><strong><?= $g->kode_gejala; ?></strong></td>
                            <td><?= html_escape($g->nama_gejala); ?></td>
                            <td style="color: #64748b; font-size: 0.8rem;"><?= html_escape($g->keterangan); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8;">Tidak ada data gejala.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="section-title">III. Kesimpulan Diagnosa Sistem Pakar</div>
        <div class="result-box">
            <?php if ($inference['is_identified']): ?>
                <div style="font-size: 0.85rem; color: #15803d; font-weight: 700; text-transform: uppercase;">
                    <i class="fa-solid fa-circle-check"></i> Hasil Diagnosa Teridentifikasi (Forward Chaining)
                </div>
                <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin: 6px 0;">
                    <?= html_escape($inference['primary_diagnosa']->nama_penyakit); ?> (<?= $inference['primary_diagnosa']->kode_penyakit; ?>)
                </div>
                <div style="margin-top: 10px; font-size: 0.875rem;">
                    <strong>Saran Penanganan Medis / Tindak Lanjut:</strong>
                    <p style="margin: 4px 0 0; color: #334155; line-height: 1.5;">
                        <?= html_escape($inference['primary_diagnosa']->saran_penanganan); ?>
                    </p>
                </div>
            <?php else: ?>
                <div style="font-size: 0.85rem; color: #b45309; font-weight: 700; text-transform: uppercase;">
                    <i class="fa-solid fa-circle-info"></i> Hasil Belum Menemukan Indikasi Gangguan Spesifik
                </div>
                <div style="font-size: 1.15rem; font-weight: 700; color: #92400e; margin: 6px 0;">
                    Tidak ada indikasi gangguan tidur tertentu
                </div>
                <p style="font-size: 0.85rem; color: #78350f; margin: 4px 0 0;">
                    Kombinasi gejala yang dimasukkan belum memenuhi syarat lengkap aturan klinis yang ada. Disarankan memantau perkembangan pola tidur atau berkonsultasi langsung ke dokter spesialis jika keluhan berlanjut.
                </p>
            <?php endif; ?>
        </div>

        <div class="signature-area">
            <div class="signature-box">
                <div style="font-size: 0.85rem; color: #64748b;">Pasien / Pengguna,</div>
                <div style="height: 60px;"></div>
                <div style="font-weight: 700; text-decoration: underline;"><?= html_escape($konsultasi->nama_pasien ?: $konsultasi->user_nama); ?></div>
            </div>

            <div class="signature-box">
                <div style="font-size: 0.85rem; color: #64748b;"><?= date('d F Y'); ?></div>
                <div style="font-size: 0.85rem; color: #64748b;">Pakar Sistem / Dokter Pemeriksa,</div>
                <div style="height: 50px;"></div>
                <div style="font-weight: 700; text-decoration: underline;">Dr. Spesialis Saraf / Pakar Tidur</div>
                <div style="font-size: 0.75rem; color: #64748b;">SIP: 445/SOMNO-SPK/2026</div>
            </div>
        </div>
    </div>

</body>
</html>
