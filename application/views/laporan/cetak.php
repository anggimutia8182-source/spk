<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Konsultasi & Diagnosa - SomnoExpert</title>
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
            max-width: 950px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.825rem;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
        }
        table th {
            background: #f8fafc;
            color: #334155;
            font-weight: 700;
        }
        .stat-box {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }
        .stat-num {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
        }
        .stat-desc {
            font-size: 0.75rem;
            color: #64748b;
        }
        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .actions-bar {
            max-width: 950px;
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
            .sheet { box-shadow: none; padding: 20px; max-width: 100%; }
            .actions-bar { display: none; }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <button onclick="window.print()" class="btn btn-print"><i class="fa-solid fa-print"></i> Cetak Dokumen</button>
        <button onclick="window.close()" class="btn btn-close"><i class="fa-solid fa-xmark"></i> Tutup</button>
    </div>

    <div class="sheet">
        <div class="header">
            <div class="logo-title">
                <h1>SOMNOEXPERT KLINIK KESEHATAN TIDUR</h1>
                <p>Sistem Pakar Identifikasi Jenis Gangguan Tidur Menggunakan Metode Forward Chaining</p>
                <p style="font-size: 0.75rem;">Jl. Kesehatan No. 10 &bull; Telp: (021) 555-0199 &bull; Website: somnoexpert.med</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.8rem; color: #64748b;">Tanggal Cetak:</div>
                <div style="font-weight: 700; color: #0f172a;"><?= date('d F Y, H:i'); ?></div>
            </div>
        </div>

        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="font-size: 1.25rem; margin: 0; text-transform: uppercase; letter-spacing: 0.05em; color: #0f172a;">
                LAPORAN REKAPITULASI KONSULTASI & DIAGNOSA PASIEN
            </h2>
            <?php if (!empty($filters['start_date']) || !empty($filters['end_date'])): ?>
                <p style="font-size: 0.85rem; color: #64748b; margin-top: 4px;">
                    Periode: <?= $filters['start_date'] ?: 'Awal'; ?> s/d <?= $filters['end_date'] ?: 'Sekarang'; ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- STATS -->
        <div class="stat-box">
            <div class="stat-item">
                <div class="stat-num"><?= count($konsultasi); ?></div>
                <div class="stat-desc">Total Pasien Terdata</div>
            </div>
            <div class="stat-item">
                <div class="stat-num"><?= $stats['terdiagnosa']; ?></div>
                <div class="stat-desc">Terindikasi Gangguan Tidur</div>
            </div>
            <div class="stat-item">
                <div class="stat-num"><?= $stats['tidak_terindikasi']; ?></div>
                <div class="stat-desc">Belum Ada Indikasi Spesifik</div>
            </div>
        </div>

        <!-- TABLE -->
        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th style="width: 80px;">No. RM</th>
                    <th>Nama Pasien</th>
                    <th style="width: 80px;">Usia/JK</th>
                    <th style="width: 100px;">Tanggal</th>
                    <th>Gejala Masukan (Fakta)</th>
                    <th style="width: 140px;">Hasil Diagnosa</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($konsultasi)): ?>
                    <?php $no = 1; foreach ($konsultasi as $k): ?>
                        <tr>
                            <td style="text-align: center;"><?= $no++; ?></td>
                            <td><strong><?= html_escape($k->no_rm ?: '-'); ?></strong></td>
                            <td><?= html_escape($k->nama_pasien ?: $k->user_nama); ?></td>
                            <td><?= $k->usia ? $k->usia . ' th' : '-'; ?> / <?= $k->jenis_kelamin ? substr($k->jenis_kelamin, 0, 1) : '-'; ?></td>
                            <td><?= date('d/m/Y', strtotime($k->tanggal)); ?></td>
                            <td style="font-size: 0.75rem;">
                                <?php if (!empty($k->gejala)): ?>
                                    <?= implode(', ', array_map(function($g) { return $g->kode_gejala; }, $k->gejala)); ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($k->hasil_diagnosa && $k->nama_penyakit): ?>
                                    <strong><?= html_escape($k->nama_penyakit); ?></strong>
                                <?php else: ?>
                                    <em style="color: #64748b;">Belum Ada Indikasi</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 1.5rem;">Tidak ada data konsultasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- SIGNATURE -->
        <div class="signature-area">
            <div class="signature-box">
                <div style="font-size: 0.85rem; color: #64748b;"><?= date('d F Y'); ?></div>
                <div style="font-size: 0.85rem; color: #64748b;">Kepala Unit Rekam Medis & Pakar,</div>
                <div style="height: 60px;"></div>
                <div style="font-weight: 700; text-decoration: underline;">Dr. Hendra Gunawan, Sp.S</div>
                <div style="font-size: 0.75rem; color: #64748b;">NIP. 19820415 200801 1 003</div>
            </div>
        </div>
    </div>

</body>
</html>
