<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<div class="mb-4 flex items-center justify-between">
    <a href="/keuangan" class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center gap-1">
        <i class="fas fa-arrow-left text-xs"></i> Kembali
    </a>
    <a href="/print/<?= $perjalanan['id'] ?>" target="_blank"
        class="flex items-center gap-2 bg-white border border-gray-200 hover:border-primary-400 hover:text-primary-700 text-gray-600 text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
        <i class="fas fa-print"></i> Cetak / PDF
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
        <!-- Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-primary-700 to-primary-600 px-5 py-4">
                <h4 class="text-white font-semibold text-sm"><?= esc($perjalanan['nomor_surat']) ?></h4>
                <p class="text-primary-200 text-xs">Pegawai: <?= esc($perjalanan['pegawai_name']) ?></p>
            </div>
            <div class="p-5 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs mb-1">Tujuan</p>
                    <p class="font-medium"><?= esc($perjalanan['tujuan']) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Kota</p>
                    <p class="font-medium"><?= esc($perjalanan['kota_tujuan']) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Berangkat</p>
                    <p class="font-medium"><?= date('d F Y', strtotime($perjalanan['tanggal_berangkat'])) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Pulang</p>
                    <p class="font-medium"><?= date('d F Y', strtotime($perjalanan['tanggal_pulang'])) ?></p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs mb-1">Keperluan</p>
                    <p class="font-medium"><?= nl2br(esc($perjalanan['keperluan'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Rincian Biaya -->
        <?php if (! empty($rincian)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm">Rincian Biaya Operasional</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">No.</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Uraian</th>
                            <th class="px-4 py-2 text-center text-xs text-gray-500">Qty</th>
                            <th class="px-4 py-2 text-left text-xs text-gray-500">Satuan</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">Harga</th>
                            <th class="px-4 py-2 text-right text-xs text-gray-500">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php $no = 1; $grandTotal = 0; foreach ($rincian as $r): $grandTotal += $r['total']; ?>
                        <tr>
                            <td class="px-4 py-2.5 text-gray-400"><?= $no++ ?></td>
                            <td class="px-4 py-2.5"><?= esc($r['uraian']) ?></td>
                            <td class="px-4 py-2.5 text-center"><?= $r['qty'] ?></td>
                            <td class="px-4 py-2.5 text-gray-500"><?= esc($r['satuan']) ?></td>
                            <td class="px-4 py-2.5 text-right">Rp <?= number_format($r['harga'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2.5 text-right font-semibold">Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-primary-50">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right font-bold text-primary-700 text-sm">TOTAL PENGAJUAN DANA</td>
                            <td class="px-4 py-3 text-right font-bold text-primary-700 text-sm">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Complete action -->
        <?php if ($perjalanan['status'] === 'approved_2'): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h4 class="font-semibold text-gray-700 text-sm mb-3">Catat Dana Operasional</h4>
            <form action="/keuangan/complete/<?= $perjalanan['id'] ?>" method="POST">
                <?= csrf_field() ?>
                <textarea name="catatan" rows="2" placeholder="Catatan keuangan (opsional)..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none mb-3"></textarea>
                <button type="submit" onclick="return confirm('Tandai sebagai selesai dicatat?')"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-check-circle mr-2"></i> Catat Dana Selesai
                </button>
            </form>
        </div>
        <?php elseif ($perjalanan['status'] === 'completed'): ?>
        <div class="bg-primary-50 border border-primary-200 rounded-2xl p-5 text-center">
            <i class="fas fa-check-circle text-3xl text-primary-500 mb-2"></i>
            <p class="font-semibold text-primary-700">Dana Operasional Telah Dicatat</p>
            <p class="text-xs text-primary-500 mt-1">Perjalanan dinas ini telah selesai diproses</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Dokumen & Logs -->
    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm">Dokumen Lampiran</h4>
            </div>
            <div class="p-4 space-y-2">
                <?php if (empty($dokumen)): ?>
                    <p class="text-xs text-gray-400 text-center py-4">Tidak ada dokumen</p>
                <?php else: foreach ($dokumen as $d): ?>
                    <a href="<?= base_url($d['path_file']) ?>" target="_blank"
                        class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-primary-50 transition">
                        <i class="fas fa-file-alt text-primary-500 text-sm"></i>
                        <span class="flex-1 text-gray-700 truncate text-xs"><?= esc($d['nama_file']) ?></span>
                        <i class="fas fa-external-link-alt text-gray-400 text-xs"></i>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm">Jejak Persetujuan</h4>
            </div>
            <div class="p-4 space-y-3">
                <?php if (empty($logs)): ?>
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat</p>
                <?php else: foreach ($logs as $log): ?>
                    <div class="border-l-2 border-primary-200 pl-3">
                        <p class="text-xs font-semibold text-gray-700 capitalize"><?= esc($log['role']) ?></p>
                        <p class="text-xs text-gray-500"><?= esc($log['catatan']) ?></p>
                        <p class="text-xs text-gray-400"><?= $log['approved_at'] ? date('d M Y H:i', strtotime($log['approved_at'])) : '-' ?></p>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
