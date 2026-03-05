<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPPD - <?= esc($perjalanan['nomor_surat']) ?></title>
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
            padding: 15mm 15mm 20mm 20mm;
            box-shadow: 0 0 5px rgba(0,0,0,.15);
        }

        /* ─── Header ───────────────────────────────────── */
        .header {
            text-align: center;
            margin-bottom: 8px;
        }
        .header h1 {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            display: inline-block;
        }

        /* ─── Info Grid ─────────────────────────────────── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px 20px;
            margin: 8px 0;
            font-size: 10.5pt;
        }
        .info-row {
            display: grid;
            grid-template-columns: 110px 10px 1fr;
            gap: 0;
        }
        .info-row .label { font-weight: normal; }
        .info-row .sep   { text-align: center; }
        .info-row .val   { font-weight: bold; }

        /* ─── Table ─────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: middle;
        }
        th {
            text-align: center;
            font-weight: bold;
            background: #e8e8e8;
        }
        .cat-header td {
            font-weight: bold;
            background: #f0f0f0;
            font-size: 10.5pt;
        }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .total-row td {
            font-weight: bold;
            background: #e8e8e8;
            font-size: 10.5pt;
        }

        /* ─── Signature ─────────────────────────────────── */
        .signature-section {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
        }
        .sign-block {
            width: 45%;
            text-align: center;
        }
        .sign-date {
            font-size: 10.5pt;
            margin-bottom: 4px;
            text-align: left;
        }
        .sign-title {
            font-size: 10.5pt;
            margin-bottom: 60px;
        }
        .sign-name {
            font-weight: bold;
            font-size: 11pt;
            border-top: 1px solid #000;
            padding-top: 4px;
            display: inline-block;
            min-width: 160px;
        }
        .sign-position {
            font-size: 10pt;
            color: #333;
        }

        /* ─── Note ──────────────────────────────────────── */
        .note-section {
            margin-top: 14px;
            border-top: 1px solid #000;
            padding-top: 6px;
        }
        .note-section .note-label {
            font-weight: bold;
            font-size: 10.5pt;
            margin-bottom: 6px;
        }
        .note-content {
            font-size: 10pt;
            white-space: pre-wrap;
            line-height: 1.6;
            columns: 2;
            column-gap: 20px;
        }

        /* ─── Print controls ─────────────────────────────── */
        .print-toolbar {
            width: 210mm;
            margin: 0 auto 6mm;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        .btn-print {
            background: #166534;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            font-family: sans-serif;
        }
        .btn-back {
            background: #fff;
            color: #333;
            border: 1px solid #ccc;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            font-family: sans-serif;
        }

        @media print {
            body { background: #fff; }
            .print-toolbar { display: none; }
            .page { margin: 0; box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="print-toolbar">
    <a href="javascript:history.back()" class="btn-back">&#8592; Kembali</a>
    <button class="btn-print" onclick="window.print()">&#128438; Cetak / Simpan PDF</button>
</div>

<?php
// Group rincian by judul
$grouped = [];
foreach ($rincian as $r) {
    $judul = $r['judul'] ?: 'Lainnya';
    $grouped[$judul][] = $r;
}

// Indonesian date helper
function tglIndo($date) {
    if (!$date) return '-';
    $bulan = ['', 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $ts    = strtotime($date);
    return $hari[date('w', $ts)] . ', ' . date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}
?>

<div class="page">

    <!-- Header -->
    <div class="header">
        <h1>FORMULIR PENGAJUAN DANA OPERASIONAL TUGAS LUAR KOTA</h1>
    </div>

    <!-- Info -->
    <div class="info-grid">
        <div class="info-row">
            <span class="label">Nama</span>
            <span class="sep">:</span>
            <span class="val"><?= esc($perjalanan['pegawai_name']) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Jabatan</span>
            <span class="sep">:</span>
            <span class="val"><?= esc($perjalanan['pegawai_email'] ?? '') ?></span>
        </div>
        <div class="info-row">
            <span class="label">Tempat Tujuan</span>
            <span class="sep">:</span>
            <span class="val"><?= esc($perjalanan['kota_tujuan']) . ' — ' . esc($perjalanan['tujuan']) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Keperluan</span>
            <span class="sep">:</span>
            <span class="val"><?= esc($perjalanan['keperluan']) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Tgl Berangkat</span>
            <span class="sep">:</span>
            <span class="val"><?= tglIndo($perjalanan['tanggal_berangkat']) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Tgl Pulang</span>
            <span class="sep">:</span>
            <span class="val"><?= tglIndo($perjalanan['tanggal_pulang']) ?></span>
        </div>
        <div class="info-row">
            <span class="label">Jam Berangkat</span>
            <span class="sep">:</span>
            <span class="val"><?= esc($perjalanan['jam_berangkat'] ?? '-') ?></span>
        </div>
        <div class="info-row">
            <span class="label">Jam Pulang</span>
            <span class="sep">:</span>
            <span class="val"><?= esc($perjalanan['jam_pulang'] ?? '-') ?></span>
        </div>
    </div>

    <!-- Rincian Biaya Table -->
    <table>
        <thead>
            <tr>
                <th style="width:35px">NOMOR</th>
                <th>URAIAN</th>
                <th style="width:55px">BANYAK</th>
                <th style="width:55px">SATUAN</th>
                <th colspan="2">NOMINAL</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="width:90px">SATUAN</th>
                <th style="width:100px">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
        <?php $no = 1; $grandTotal = 0; foreach ($grouped as $judul => $items): ?>
            <!-- Category header row -->
            <tr class="cat-header">
                <td class="text-center"><?= $no++ ?></td>
                <td><?= esc($judul) ?> :</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <!-- Item rows -->
            <?php foreach ($items as $r): $grandTotal += $r['total']; ?>
            <tr>
                <td></td>
                <td>
                    <?php
                    if (!empty($r['keterangan'])) {
                        $uraian = $r['keterangan'];
                    } else {
                        $uraian = $r['uraian'];
                        if (!empty($r['nama_kendaraan'])) {
                            $uraian .= ' ' . $r['nama_kendaraan'] . ' ' . $r['nomor_polisi'];
                        }
                    }
                    echo esc($uraian);
                    ?>
                </td>
                <td class="text-center"><?= $r['qty'] ?></td>
                <td class="text-center"><?= esc($r['satuan']) ?></td>
                <td class="text-right">Rp &nbsp;<?= number_format($r['harga'], 0, ',', '.') ?></td>
                <td class="text-right">Rp &nbsp;<?= number_format($r['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right" style="font-size:11pt; letter-spacing:0.5px;">TOTAL PENGAJUAN DANA</td>
                <td class="text-right" style="font-size:11pt;">Rp &nbsp;<?= number_format($grandTotal, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

    <!-- Signature Section -->
    <div class="signature-section">

        <div class="sign-block" style="text-align:left;">
            <div class="sign-date">Tanggal pengajuan, <?= tglIndo($perjalanan['created_at'] ?? date('Y-m-d')) ?></div>
            <div class="sign-title">Yang Membuat,</div>
            <div class="sign-name"><?= esc($admin_user ? $admin_user['name'] : $perjalanan['pegawai_name']) ?></div>
            <div class="sign-position"><?= esc($admin_user ? 'Staff Admin' : 'Pegawai') ?></div>
        </div>

        <div class="sign-block" style="text-align:right;">
            <div class="sign-date">&nbsp;</div>
            <div class="sign-title">Di Setujui,</div>
            <div class="sign-name"><?= esc($direktur_user ? $direktur_user['name'] : '_______________') ?></div>
            <div class="sign-position">Direktur</div>
        </div>

    </div>

    <!-- Note / Itinerary -->
    <?php if (! empty($perjalanan['catatan'])): ?>
    <div class="note-section">
        <div class="note-label">NOTE :</div>
        <div class="note-content"><?= esc($perjalanan['catatan']) ?></div>
    </div>
    <?php endif; ?>

</div><!-- .page -->

</body>
</html>
