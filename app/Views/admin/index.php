<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<?php
$statusMap = [
    'draft'           => ['label' => 'Menunggu Direktur', 'class' => 'bg-yellow-100 text-yellow-700'],
    'approved_1'      => ['label' => 'Perlu Diproses', 'class' => 'bg-blue-100 text-blue-700'],
    'rejected_1'      => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700'],
    'processed_admin' => ['label' => 'Di Direktur (Biaya)', 'class' => 'bg-purple-100 text-purple-700'],
    'approved_2'      => ['label' => 'Di Keuangan', 'class' => 'bg-primary-100 text-primary-700'],
    'rejected_2'      => ['label' => 'Biaya Ditolak', 'class' => 'bg-red-100 text-red-700'],
    'completed'       => ['label' => 'Selesai', 'class' => 'bg-primary-100 text-primary-800'],
];
?>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
            <i class="fas fa-inbox text-blue-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= count($pending) ?></p>
            <p class="text-xs text-gray-500">Perlu Diproses</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
            <i class="fas fa-cogs text-purple-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= count($processed) ?></p>
            <p class="text-xs text-gray-500">Sudah Diproses</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
            <i class="fas fa-archive text-primary-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= count($arsip) ?></p>
            <p class="text-xs text-gray-500">Di Arsip</p>
        </div>
    </div>
</div>

<!-- Quick action: email test -->
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6 flex items-center justify-between">
    <div>
        <p class="text-sm font-semibold text-gray-700">Uji Notifikasi Email</p>
        <p class="text-xs text-gray-500">Kirim email test ke akun admin yang sedang login untuk cek konfigurasi SMTP.</p>
    </div>
    <form action="/admin/test-email" method="POST">
        <?= csrf_field() ?>
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-xs font-semibold transition">
            <i class="fas fa-paper-plane mr-1"></i> Kirim Email Test
        </button>
    </form>
</div>

<!-- Pending processing: approved_1 by direktur -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 bg-blue-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
            <h4 class="font-semibold text-blue-700 text-sm">Pengajuan Perlu Diproses (Sudah Disetujui Direktur)</h4>
        </div>
        <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-bold"><?= count($pending) ?></span>
    </div>
    <?php if (empty($pending)): ?>
        <div class="text-center py-10 text-gray-400">
            <i class="fas fa-inbox text-4xl text-gray-200 mb-3"></i>
            <p class="text-sm">Tidak ada pengajuan yang perlu diproses</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">No. Surat</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Pegawai</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Tujuan</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Tanggal</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($pending as $p): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono text-xs text-gray-600"><?= esc($p['nomor_surat']) ?></td>
                        <td class="px-5 py-3 font-medium text-gray-800"><?= esc($p['pegawai_name']) ?></td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-700"><?= esc($p['tujuan']) ?></p>
                            <p class="text-xs text-gray-400"><?= esc($p['kota_tujuan']) ?></p>
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-500"><?= date('d M Y', strtotime($p['tanggal_berangkat'])) ?></td>
                        <td class="px-5 py-3">
                            <a href="/admin/show/<?= $p['id'] ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                Proses <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Processed -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h4 class="font-semibold text-gray-700 text-sm">Sudah Diproses</h4>
        <a href="/admin/arsip" class="text-primary-600 text-xs hover:underline">Lihat Semua Arsip →</a>
    </div>
    <?php if (empty($processed)): ?>
        <div class="text-center py-8 text-gray-400 text-sm">Belum ada data</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">No. Surat</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Pegawai</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Tujuan</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Status</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Total</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($processed as $p): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono text-xs text-gray-600"><?= esc($p['nomor_surat']) ?></td>
                        <td class="px-5 py-3 font-medium text-gray-800"><?= esc($p['pegawai_name']) ?></td>
                        <td class="px-5 py-3 text-gray-700"><?= esc($p['tujuan']) ?></td>
                        <td class="px-5 py-3">
                            <?php $st = $statusMap[$p['status']] ?? ['label' => $p['status'], 'class' => 'bg-gray-100 text-gray-600']; ?>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $st['class'] ?>"><?= $st['label'] ?></span>
                        </td>
                        <td class="px-5 py-3 text-gray-700 font-semibold text-xs">
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

<?= $this->endSection() ?>
