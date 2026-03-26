<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<div class="mb-4 flex items-center justify-between">
    <a href="/direktur" class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center gap-1">
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
                <p class="text-primary-200 text-xs">Diajukan oleh: <?= esc($perjalanan['pegawai_name']) ?></p>
            </div>
            <div class="p-5 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs mb-1">Tujuan</p>
                    <p class="font-medium"><?= esc($perjalanan['tujuan']) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Kota Tujuan</p>
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
                <?php if (! empty($perjalanan['catatan'])): ?>
                <div class="col-span-2 border-t border-gray-100 pt-3">
                    <p class="text-gray-400 text-xs mb-1"><i class="fas fa-sticky-note mr-1"></i>Catatan / Itinerary</p>
                    <pre class="text-sm text-gray-700 font-sans whitespace-pre-wrap"><?= esc($perjalanan['catatan']) ?></pre>
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <?php foreach ($peserta as $p): ?>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-sm">
                            <?= strtoupper(substr($p['name'], 0, 1)) ?>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm"><?= esc($p['name']) ?></p>
                            <p class="text-xs text-gray-500"><?= esc($p['email']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Rincian Biaya (jika ada) -->
        <?php if (! empty($rincian)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h4 class="font-semibold text-gray-700 text-sm">Rincian Biaya Operasional</h4>
                <span class="font-bold text-primary-600 text-sm">Rp <?= number_format(array_sum(array_column($rincian, 'total')), 0, ',', '.') ?></span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50"><tr>
                        <th class="px-4 py-2 text-left text-xs text-gray-500">Uraian</th>
                        <th class="px-4 py-2 text-center text-xs text-gray-500">Qty</th>
                        <th class="px-4 py-2 text-left text-xs text-gray-500">Satuan</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">Harga</th>
                        <th class="px-4 py-2 text-right text-xs text-gray-500">Total</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php $gt = 0; foreach ($rincian as $r): $gt += $r['total']; ?>
                        <tr>
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
                            <td colspan="4" class="px-4 py-3 text-right font-bold text-primary-700">TOTAL PENGAJUAN DANA</td>
                            <td class="px-4 py-3 text-right font-bold text-primary-700">Rp <?= number_format($gt, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Action buttons -->
        <?php if (in_array($perjalanan['status'], ['draft', 'processed_admin'])): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h4 class="font-semibold text-gray-700 text-sm mb-4">Ambil Keputusan</h4>
            <div class="flex gap-3">
                <form action="/direktur/approve/<?= $perjalanan['id'] ?>" method="POST" class="flex-1">
                    <?= csrf_field() ?>
                    <input type="hidden" name="catatan" value="Disetujui oleh direktur.">
                    <button type="submit" onclick="return confirm('Setujui pengajuan ini?')"
                        class="w-full bg-primary-600 hover:bg-primary-700 text-white py-3 rounded-xl text-sm font-semibold transition">
                        <i class="fas fa-check-circle mr-2"></i> Setujui
                    </button>
                </form>
                <button onclick="document.getElementById('rejectForm').style.display='block'"
                    class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 py-3 rounded-xl text-sm font-semibold transition border border-red-100">
                    <i class="fas fa-times-circle mr-2"></i> Tolak
                </button>
            </div>
            <form id="rejectForm" action="/direktur/reject/<?= $perjalanan['id'] ?>" method="POST" class="mt-4" style="display:none">
                <?= csrf_field() ?>
                <textarea name="catatan" rows="3" required placeholder="Tuliskan alasan penolakan..."
                    class="w-full px-4 py-3 border border-red-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none mb-3"></textarea>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl text-sm font-semibold">
                    Konfirmasi Penolakan
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Dokumen & Log -->
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
                <h4 class="font-semibold text-gray-700 text-sm">Riwayat Proses</h4>
            </div>
            <div class="p-4">
                <?php $timeline = sppd_process_timeline($perjalanan, $logs); ?>
                <div class="relative">
                    <div class="absolute left-3.5 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    <div class="space-y-4">
                        <?php foreach ($timeline as $step): ?>
                            <?php
                                $isDone = $step['state'] === 'done';
                                $isRejected = $step['state'] === 'rejected';
                                $dotClass = $isDone ? 'bg-green-100 text-green-600' : ($isRejected ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400');
                                $titleClass = $isDone ? 'text-gray-800' : ($isRejected ? 'text-red-700' : 'text-gray-500');
                                $descClass = $isDone ? 'text-gray-500' : ($isRejected ? 'text-red-500' : 'text-gray-400');
                                $icon = $isDone ? 'fa-check' : ($isRejected ? 'fa-times' : 'fa-clock');
                            ?>
                            <div class="relative pl-9">
                                <div class="absolute left-0 w-7 h-7 rounded-full flex items-center justify-center <?= $dotClass ?>">
                                    <i class="fas <?= $icon ?> text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold <?= $titleClass ?>"><?= esc($step['label']) ?></p>
                                    <p class="text-xs <?= $descClass ?>"><?= esc($step['detail']) ?></p>
                                    <p class="text-xs text-gray-400 mt-0.5"><?= esc($step['time']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
