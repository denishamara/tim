<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<?php
$statusMap = [
    'draft'           => ['label' => 'Menunggu Direktur', 'class' => 'bg-yellow-100 text-yellow-700'],
    'approved_1'      => ['label' => 'Perlu Diproses Admin', 'class' => 'bg-blue-100 text-blue-700'],
    'rejected_1'      => ['label' => 'Ditolak Direktur', 'class' => 'bg-red-100 text-red-700'],
    'processed_admin' => ['label' => 'Menunggu Direktur (Biaya)', 'class' => 'bg-purple-100 text-purple-700'],
    'approved_2'      => ['label' => 'Di Keuangan', 'class' => 'bg-primary-100 text-primary-700'],
    'rejected_2'      => ['label' => 'Biaya Ditolak', 'class' => 'bg-red-100 text-red-700'],
    'sent_finance'    => ['label' => 'Di Keuangan', 'class' => 'bg-indigo-100 text-indigo-700'],
    'completed'       => ['label' => 'Selesai', 'class' => 'bg-primary-100 text-primary-800'],
];
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h4 class="font-semibold text-gray-700 text-sm flex items-center gap-2">
            <i class="fas fa-archive text-gray-400"></i> Arsip Semua Perjalanan Dinas
        </h4>
        <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full text-xs font-bold"><?= count($list) ?> data</span>
    </div>

    <?php if (empty($list)): ?>
        <div class="text-center py-16 text-gray-400">
            <i class="fas fa-box-open text-5xl text-gray-200 mb-3"></i>
            <p>Arsip masih kosong</p>
        </div>
    <?php else: ?>
        <!-- Filter row -->
        <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
            <input type="text" id="searchInput" placeholder="Cari nama, nomor, tujuan..."
                class="w-full md:w-80 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="arsipTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs text-gray-500">No. Surat</th>
                        <th class="px-5 py-3 text-left text-xs text-gray-500">Pegawai</th>
                        <th class="px-5 py-3 text-left text-xs text-gray-500">Tujuan</th>
                        <th class="px-5 py-3 text-left text-xs text-gray-500">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs text-gray-500">Status</th>
                        <th class="px-5 py-3 text-left text-xs text-gray-500">Total</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($list as $p): ?>
                    <tr class="hover:bg-gray-50 transition arsip-row">
                        <td class="px-5 py-3 font-mono text-xs text-gray-600"><?= esc($p['nomor_surat']) ?></td>
                        <td class="px-5 py-3 font-medium text-gray-800"><?= esc($p['pegawai_name']) ?></td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-700"><?= esc($p['tujuan']) ?></p>
                            <p class="text-xs text-gray-400"><?= esc($p['kota_tujuan']) ?></p>
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-500">
                            <?= date('d M Y', strtotime($p['tanggal_berangkat'])) ?>
                        </td>
                        <td class="px-5 py-3">
                            <?php $st = $statusMap[$p['status']] ?? ['label' => $p['status'], 'class' => 'bg-gray-100 text-gray-600']; ?>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $st['class'] ?>"><?= $st['label'] ?></span>
                        </td>
                        <td class="px-5 py-3 font-semibold text-gray-700 text-xs">
                            <?= $p['total_pengajuan'] > 0 ? 'Rp ' . number_format($p['total_pengajuan'], 0, ',', '.') : '-' ?>
                        </td>
                        <td class="px-5 py-3">
                            <a href="/admin/show/<?= $p['id'] ?>" class="text-primary-600 hover:text-primary-800 text-xs font-semibold">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('searchInput')?.addEventListener('input', function() {
    const val = this.value.toLowerCase();
    document.querySelectorAll('.arsip-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>

<?= $this->endSection() ?>
