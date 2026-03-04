<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<?php
$statusMap = [
    'draft'           => ['label' => 'Menunggu Direktur', 'class' => 'bg-yellow-100 text-yellow-700'],
    'approved_1'      => ['label' => 'Disetujui Direktur', 'class' => 'bg-blue-100 text-blue-700'],
    'rejected_1'      => ['label' => 'Ditolak Direktur', 'class' => 'bg-red-100 text-red-700'],
    'processed_admin' => ['label' => 'Diproses Admin', 'class' => 'bg-purple-100 text-purple-700'],
    'approved_2'      => ['label' => 'Disetujui (Biaya)', 'class' => 'bg-primary-100 text-primary-700'],
    'rejected_2'      => ['label' => 'Ditolak (Biaya)', 'class' => 'bg-red-100 text-red-700'],
    'sent_finance'    => ['label' => 'Di Keuangan', 'class' => 'bg-indigo-100 text-indigo-700'],
    'completed'       => ['label' => 'Selesai', 'class' => 'bg-primary-100 text-primary-800'],
];
?>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <?php
    $counts = array_count_values(array_column($perjalanan, 'status'));
    $stats  = [
        ['label' => 'Total Pengajuan', 'value' => count($perjalanan), 'icon' => 'fa-suitcase', 'color' => 'text-primary-600 bg-primary-50'],
        ['label' => 'Menunggu', 'value' => ($counts['draft'] ?? 0), 'icon' => 'fa-clock', 'color' => 'text-yellow-600 bg-yellow-50'],
        ['label' => 'Disetujui', 'value' => ($counts['approved_2'] ?? 0) + ($counts['completed'] ?? 0), 'icon' => 'fa-check-circle', 'color' => 'text-blue-600 bg-blue-50'],
        ['label' => 'Selesai', 'value' => ($counts['completed'] ?? 0), 'icon' => 'fa-flag-checkered', 'color' => 'text-primary-600 bg-primary-50'],
    ];
    foreach ($stats as $s): ?>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center <?= $s['color'] ?>">
            <i class="fas <?= $s['icon'] ?> text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= $s['value'] ?></p>
            <p class="text-xs text-gray-500"><?= $s['label'] ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Action button -->
<div class="flex justify-between items-center mb-4">
    <h3 class="font-semibold text-gray-700">Daftar Perjalanan Dinas</h3>
    <a href="/pegawai/create" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-plus"></i> Ajukan Baru
    </a>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php if (empty($perjalanan)): ?>
        <div class="text-center py-16 text-gray-400">
            <i class="fas fa-suitcase text-5xl mb-4 text-gray-200"></i>
            <p class="font-medium">Belum ada pengajuan perjalanan dinas</p>
            <a href="/pegawai/create" class="mt-4 inline-block bg-primary-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-primary-700 transition">
                Ajukan Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Surat</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tujuan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($perjalanan as $p): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 font-mono text-xs text-gray-600"><?= esc($p['nomor_surat']) ?></td>
                        <td class="px-5 py-4">
                            <p class="font-medium text-gray-800"><?= esc($p['tujuan']) ?></p>
                            <p class="text-xs text-gray-400"><?= esc($p['kota_tujuan']) ?></p>
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs">
                            <?= date('d M Y', strtotime($p['tanggal_berangkat'])) ?> &mdash; <?= date('d M Y', strtotime($p['tanggal_pulang'])) ?>
                        </td>
                        <td class="px-5 py-4">
                            <?php $st = $statusMap[$p['status']] ?? ['label' => $p['status'], 'class' => 'bg-gray-100 text-gray-600']; ?>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $st['class'] ?>"><?= $st['label'] ?></span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-gray-700">
                            <?= $p['total_pengajuan'] > 0 ? 'Rp ' . number_format($p['total_pengajuan'], 0, ',', '.') : '-' ?>
                        </td>
                        <td class="px-5 py-4">
                            <a href="/pegawai/show/<?= $p['id'] ?>" class="text-primary-600 hover:text-primary-800 font-semibold text-xs">
                                Detail <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
