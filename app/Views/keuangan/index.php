<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<?php
$statusMap = [
    'approved_2' => ['label' => 'Menunggu Pencatatan', 'class' => 'bg-primary-100 text-primary-700'],
    'completed'  => ['label' => 'Selesai Dicatat', 'class' => 'bg-primary-100 text-primary-800'],
];
?>

<!-- Stats -->
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
            <i class="fas fa-file-invoice-dollar text-primary-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= count($pending) ?></p>
            <p class="text-xs text-gray-500">Perlu Dicatat</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center">
            <i class="fas fa-check-double text-gray-600 text-xl"></i>
        </div>
        <div>
            <?php $totalDana = array_sum(array_column($completed, 'total_pengajuan')); ?>
            <p class="text-2xl font-bold text-gray-800"><?= count($completed) ?></p>
            <p class="text-xs text-gray-500">Selesai Dicatat</p>
        </div>
    </div>
</div>

<?php if ($totalDana > 0): ?>
<div class="bg-gradient-to-r from-primary-700 to-primary-600 rounded-2xl p-5 mb-6 text-white shadow-lg">
    <p class="text-primary-200 text-xs mb-1">Total Dana Operasional Tercatat</p>
    <p class="text-3xl font-bold">Rp <?= number_format($totalDana, 0, ',', '.') ?></p>
</div>
<?php endif; ?>

<!-- Pending: approved_2 -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-primary-100 bg-primary-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></div>
            <h4 class="font-semibold text-primary-700 text-sm">Dana Operasional Siap Dicatat</h4>
        </div>
        <span class="bg-primary-100 text-primary-700 px-2.5 py-1 rounded-full text-xs font-bold"><?= count($pending) ?></span>
    </div>
    <?php if (empty($pending)): ?>
        <div class="text-center py-10 text-gray-400 text-sm">
            <i class="fas fa-check-circle text-4xl text-gray-200 mb-2"></i>
            <p>Tidak ada dana operasional yang perlu dicatat</p>
        </div>
    <?php else: ?>
        <div class="divide-y divide-gray-50">
            <?php foreach ($pending as $p): ?>
            <div class="px-5 py-4 hover:bg-gray-50 transition flex items-center justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <span class="font-mono text-xs text-gray-400"><?= esc($p['nomor_surat']) ?></span>
                    <p class="font-semibold text-gray-800 text-sm"><?= esc($p['pegawai_name']) ?></p>
                    <p class="text-xs text-gray-500"><?= esc($p['tujuan']) ?>, <?= esc($p['kota_tujuan']) ?></p>
                    <p class="text-xs text-gray-400">
                        <?= date('d M Y', strtotime($p['tanggal_berangkat'])) ?> — <?= date('d M Y', strtotime($p['tanggal_pulang'])) ?>
                    </p>
                    <?php if ($p['total_pengajuan'] > 0): ?>
                        <p class="text-sm font-bold text-primary-600 mt-1">Rp <?= number_format($p['total_pengajuan'], 0, ',', '.') ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="/keuangan/show/<?= $p['id'] ?>" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                        Detail
                    </a>
                    <button onclick="openCompleteModal(<?= $p['id'] ?>)"
                        class="px-4 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition">
                        <i class="fas fa-check mr-1"></i> Catat Selesai
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Completed -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
        <h4 class="font-semibold text-gray-700 text-sm">Riwayat Dana Operasional Selesai</h4>
    </div>
    <?php if (empty($completed)): ?>
        <div class="text-center py-8 text-gray-400 text-sm">Belum ada data selesai</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">No. Surat</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Pegawai</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Tujuan</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Tanggal</th>
                    <th class="px-5 py-3 text-right text-xs text-gray-500">Total Dana</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($completed as $p): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono text-xs text-gray-600"><?= esc($p['nomor_surat']) ?></td>
                        <td class="px-5 py-3 font-medium text-gray-800"><?= esc($p['pegawai_name']) ?></td>
                        <td class="px-5 py-3 text-gray-700"><?= esc($p['tujuan']) ?></td>
                        <td class="px-5 py-3 text-xs text-gray-500"><?= date('d M Y', strtotime($p['tanggal_berangkat'])) ?></td>
                        <td class="px-5 py-3 text-right font-bold text-primary-600">
                            Rp <?= number_format($p['total_pengajuan'], 0, ',', '.') ?>
                        </td>
                        <td class="px-5 py-3">
                            <a href="/keuangan/show/<?= $p['id'] ?>" class="text-primary-600 hover:text-primary-800 text-xs font-semibold">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-primary-600"></i>
            </div>
            <h4 class="font-bold text-gray-800">Konfirmasi Pencatatan</h4>
        </div>
        <p class="text-sm text-gray-500 mb-4">Dana operasional akan dicatat sebagai selesai dalam sistem. Tambahkan catatan jika perlu.</p>
        <form id="completeForm" method="POST">
            <?= csrf_field() ?>
            <textarea name="catatan" rows="2" placeholder="Catatan keuangan (opsional)..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none mb-4"></textarea>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2.5 rounded-xl text-sm font-semibold">
                    <i class="fas fa-check mr-1"></i> Catat Selesai
                </button>
                <button type="button" onclick="document.getElementById('completeModal').classList.add('hidden')"
                    class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCompleteModal(id) {
    document.getElementById('completeForm').action = '/keuangan/complete/' + id;
    document.getElementById('completeModal').classList.remove('hidden');
}
</script>

<?= $this->endSection() ?>
