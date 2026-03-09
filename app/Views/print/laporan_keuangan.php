<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - <?= date('d-m-Y') ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #f5f5f5;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            background: #fff;
            padding: 15mm;
            box-shadow: 0 0 5px rgba(0,0,0,.15);
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10pt;
            color: #333;
        }

        /* Info Section */
        .info-section {
            margin: 15px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }
        .info-section table {
            width: 100%;
            font-size: 10pt;
        }
        .info-section td {
            padding: 3px 5px;
        }
        .info-section td:first-child {
            width: 150px;
            font-weight: bold;
        }

        /* Summary Stats */
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 20px 0;
        }
        .stat-box {
            border: 2px solid #333;
            padding: 10px;
            text-align: center;
        }
        .stat-box .label {
            font-size: 9pt;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-box .value {
            font-size: 14pt;
            font-weight: bold;
        }

        /* Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 9pt;
        }
        table.data-table th {
            background: #333;
            color: #fff;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #000;
        }
        table.data-table td {
            padding: 6px 5px;
            border: 1px solid #333;
            vertical-align: top;
        }
        table.data-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        table.data-table tfoot td {
            font-weight: bold;
            background: #e9e9e9;
            border-top: 2px solid #000;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 9pt;
            color: #666;
            text-align: center;
        }

        /* Print styles */
        @media print {
            body { background: #fff; }
            .page { 
                margin: 0; 
                box-shadow: none;
                width: auto;
                min-height: auto;
            }
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN KEUANGAN PERJALANAN DINAS</h1>
            <p>Sistem Manajemen Perjalanan Dinas</p>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <table>
                <tr>
                    <td>Periode Laporan</td>
                    <td>: <?= date('d F Y', strtotime($startDate)) ?> s/d <?= date('d F Y', strtotime($endDate)) ?></td>
                </tr>
                <tr>
                    <td>Tanggal Cetak</td>
                    <td>: <?= date('d F Y, H:i') ?> WIB</td>
                </tr>
                <tr>
                    <td>Dicetak Oleh</td>
                    <td>: <?= esc(session()->get('name') ?? 'Sistem') ?></td>
                </tr>
            </table>
        </div>

        <!-- Summary Statistics -->
        <div class="summary-stats">
            <div class="stat-box">
                <div class="label">Total Perjalanan</div>
                <div class="value"><?= $totalPerjalanan ?></div>
            </div>
            <div class="stat-box">
                <div class="label">Total Biaya</div>
                <div class="value">Rp <?= number_format($totalBiaya, 0, ',', '.') ?></div>
            </div>
            <div class="stat-box">
                <div class="label">Rata-rata Biaya</div>
                <div class="value">Rp <?= number_format($totalPerjalanan > 0 ? $totalBiaya / $totalPerjalanan : 0, 0, ',', '.') ?></div>
            </div>
        </div>

        <!-- Data Table -->
        <?php if (!empty($perjalanan)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th style="width: 90px;">Nomor Surat</th>
                    <th style="width: 120px;">Pegawai</th>
                    <th>Tujuan</th>
                    <th style="width: 80px;">Tgl Berangkat</th>
                    <th style="width: 80px;">Tgl Pulang</th>
                    <th style="width: 100px;" class="text-right">Total Biaya</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($perjalanan as $p): 
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td style="font-size: 8pt;"><?= esc($p['nomor_surat']) ?></td>
                    <td><?= esc($p['pegawai_name']) ?></td>
                    <td><?= esc($p['tujuan']) ?>, <?= esc($p['kota_tujuan']) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['tanggal_berangkat'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['tanggal_pulang'])) ?></td>
                    <td class="text-right">Rp <?= number_format($p['total_biaya'], 0, ',', '.') ?></td>
                </tr>
                <!-- Detail Rincian Biaya -->
                <?php if (!empty($p['rincian'])): ?>
                <tr>
                    <td colspan="7" style="padding: 5px 10px; background: #f5f5f5;">
                        <strong>Rincian Biaya:</strong><br>
                        <table style="width: 100%; margin-top: 5px; font-size: 8pt;">
                            <?php foreach ($p['rincian'] as $r): ?>
                            <tr>
                                <td style="border: none; padding: 2px 5px; width: 200px;">
                                    <?= esc($r['nama_jenis_biaya'] ?? $r['judul'] ?? '-') ?>
                                </td>
                                <td style="border: none; padding: 2px 5px;">
                                    <?= esc($r['keterangan'] ?? '-') ?>
                                </td>
                                <td style="border: none; padding: 2px 5px; width: 80px; text-align: right;">
                                    Rp <?= number_format($r['total'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right">TOTAL KESELURUHAN:</td>
                    <td class="text-right">Rp <?= number_format($totalBiaya, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
        <?php else: ?>
        <div style="text-align: center; padding: 40px; color: #999;">
            <p>Tidak ada data perjalanan dalam periode ini</p>
        </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="footer">
            <p>Laporan ini dibuat secara otomatis oleh sistem</p>
            <p>Dicetak pada: <?= date('d F Y, H:i:s') ?> WIB</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
