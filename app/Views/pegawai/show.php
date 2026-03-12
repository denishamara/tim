<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<?php
$statusMap = [
    'draft'           => ['label' => 'Menunggu Persetujuan Direktur', 'icon' => 'fa-clock', 'class' => 'bg-yellow-50 text-yellow-700 border border-yellow-200'],
    'approved_1'      => ['label' => 'Disetujui Direktur — Diproses Admin', 'icon' => 'fa-check', 'class' => 'bg-blue-50 text-blue-700 border border-blue-200'],
    'rejected_1'      => ['label' => 'Ditolak Direktur', 'icon' => 'fa-times', 'class' => 'bg-red-50 text-red-700 border border-red-200'],
    'processed_admin' => ['label' => 'Biaya Dikirim ke Direktur', 'icon' => 'fa-spinner', 'class' => 'bg-purple-50 text-purple-700 border border-purple-200'],
    'approved_2'      => ['label' => 'Biaya Disetujui — Di Keuangan', 'icon' => 'fa-check-double', 'class' => 'bg-primary-50 text-primary-700 border border-primary-200'],
    'rejected_2'      => ['label' => 'Biaya Ditolak Direktur', 'icon' => 'fa-times-circle', 'class' => 'bg-red-50 text-red-700 border border-red-200'],
    'completed'       => ['label' => 'Selesai', 'icon' => 'fa-flag-checkered', 'class' => 'bg-primary-50 text-primary-800 border border-primary-200'],
];
$st = $statusMap[$perjalanan['status']] ?? ['label' => $perjalanan['status'], 'icon' => 'fa-info-circle', 'class' => 'bg-gray-50 text-gray-600 border border-gray-200'];
?>

<div class="mb-4 flex items-center justify-between">
    <a href="/pegawai" class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center gap-1">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke Daftar
    </a>
    <a href="/print/<?= $perjalanan['id'] ?>" target="_blank"
        class="flex items-center gap-2 bg-white border border-gray-200 hover:border-primary-400 hover:text-primary-700 text-gray-600 text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
        <i class="fas fa-print"></i> Cetak / PDF
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main info -->
    <div class="lg:col-span-2 space-y-5">
        <!-- Status banner -->
        <div class="<?= $st['class'] ?> rounded-2xl px-5 py-4 flex items-center gap-3">
            <i class="fas <?= $st['icon'] ?> text-lg"></i>
            <div>
                <p class="font-semibold text-sm">Status: <?= $st['label'] ?></p>
                <p class="text-xs opacity-70">Nomor: <?= esc($perjalanan['nomor_surat']) ?></p>
            </div>
        </div>

        <!-- Detail -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm">Detail Perjalanan Dinas</h4>
            </div>
            <div class="p-5 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs mb-1">Tujuan</p>
                    <p class="font-medium text-gray-800"><?= esc($perjalanan['tujuan']) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Kota Tujuan</p>
                    <p class="font-medium text-gray-800"><?= esc($perjalanan['kota_tujuan']) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Tanggal Berangkat</p>
                    <p class="font-medium text-gray-800"><?= date('d F Y', strtotime($perjalanan['tanggal_berangkat'])) ?><?= $perjalanan['jam_berangkat'] ? ' — ' . $perjalanan['jam_berangkat'] : '' ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Tanggal Pulang</p>
                    <p class="font-medium text-gray-800"><?= date('d F Y', strtotime($perjalanan['tanggal_pulang'])) ?><?= $perjalanan['jam_pulang'] ? ' — ' . $perjalanan['jam_pulang'] : '' ?></p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs mb-1">Keperluan</p>
                    <p class="font-medium text-gray-800"><?= nl2br(esc($perjalanan['keperluan'])) ?></p>
                </div>
                <?php if (! empty($perjalanan['catatan'])): ?>
                <div class="col-span-2 border-t border-gray-100 pt-3">
                    <p class="text-gray-400 text-xs mb-1"><i class="fas fa-sticky-note mr-1"></i>Catatan / Itinerary</p>
                    <pre class="text-sm text-gray-700 font-sans whitespace-pre-wrap"><?= esc($perjalanan['catatan']) ?></pre>
                </div>
                <?php endif; ?>
                <?php if ($perjalanan['total_pengajuan'] > 0): ?>
                <div class="col-span-2 bg-primary-50 rounded-xl p-3">
                    <p class="text-gray-400 text-xs mb-1">Total Dana Operasional</p>
                    <p class="font-bold text-primary-700 text-xl">Rp <?= number_format($perjalanan['total_pengajuan'], 0, ',', '.') ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Peserta Perjalanan -->
        <?php if (!empty($peserta)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm flex items-center gap-2">
                    <i class="fas fa-users text-primary-600"></i>
                    Peserta Perjalanan (<?= count($peserta) ?> orang)
                </h4>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 gap-3">
                    <?php foreach ($peserta as $p): ?>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm">
                            <?= strtoupper(substr($p['name'], 0, 1)) ?>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm"><?= esc($p['name']) ?></p>
                            <p class="text-xs text-gray-500"><?= esc($p['email']) ?></p>
                            <?php if (!empty($p['jabatan'])): ?>
                                <p class="text-xs text-primary-600 mt-0.5"><?= esc($p['jabatan']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Rincian Biaya -->
        <?php if (! empty($rincian)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm">Rincian Biaya</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Uraian</th>
                            <th class="px-4 py-2 text-center text-xs text-gray-500">Qty</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Satuan</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">Harga</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php $grandTotal = 0; foreach ($rincian as $r): $grandTotal += $r['total']; ?>
                        <tr>
                            <td class="px-4 py-2.5">
                                <?php if (!empty($r['judul'])): ?>
                                <p class="font-semibold text-xs text-primary-700"><?= esc($r['judul']) ?></p>
                                <?php endif; ?>
                                <p><?= esc($r['keterangan'] ?: $r['uraian']) ?></p>
                                <?php if (!empty($r['nama_kendaraan'])): ?>
                                <p class="text-xs text-gray-400"><i class="fas fa-car mr-1"></i><?= esc($r['nama_kendaraan']) ?> (<?= esc($r['nomor_polisi']) ?>)</p>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2.5 text-center"><?= $r['qty'] ?></td>
                            <td class="px-4 py-2.5 text-gray-500"><?= esc($r['satuan']) ?></td>
                            <td class="px-4 py-2.5 text-right">Rp <?= number_format($r['harga'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2.5 text-right font-semibold">Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-primary-50">
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-right font-bold text-primary-700">TOTAL</td>
                            <td class="px-4 py-3 text-right font-bold text-primary-700">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar: docs + timeline -->
    <div class="space-y-5">
        <!-- Dokumen -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm">Dokumen Lampiran</h4>
            </div>
            <div class="p-4 space-y-2">
                <?php if (empty($dokumen)): ?>
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada dokumen</p>
                <?php else: foreach ($dokumen as $d): ?>
                    <a href="<?= base_url($d['path_file']) ?>" target="_blank"
                        class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-primary-50 transition text-sm">
                        <i class="fas fa-file-alt text-primary-500"></i>
                        <span class="flex-1 text-gray-700 truncate text-xs"><?= esc($d['nama_file']) ?></span>
                        <i class="fas fa-external-link-alt text-gray-400 text-xs"></i>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm">Riwayat Proses</h4>
            </div>
            <div class="p-4">
                <?php if (empty($logs)): ?>
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat</p>
                <?php else: ?>
                    <div class="relative">
                        <div class="absolute left-3.5 top-0 bottom-0 w-0.5 bg-gray-100"></div>
                        <div class="space-y-4">
                            <?php foreach ($logs as $log): ?>
                            <div class="relative pl-9">
                                <div class="absolute left-0 w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center">
                                    <i class="fas fa-check text-primary-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700 capitalize"><?= esc($log['role']) ?>: <?= esc($log['approver_name'] ?? '-') ?></p>
                                    <p class="text-xs text-gray-500"><?= esc($log['catatan']) ?></p>
                                    <p class="text-xs text-gray-400 mt-0.5"><?= $log['approved_at'] ? date('d M Y H:i', strtotime($log['approved_at'])) : '-' ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
