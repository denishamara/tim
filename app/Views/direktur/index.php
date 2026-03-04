<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<?php
$statusMap = [
    'draft'           => ['label' => 'Menunggu Persetujuan', 'class' => 'bg-yellow-100 text-yellow-700'],
    'approved_1'      => ['label' => 'Disetujui - Di Admin', 'class' => 'bg-blue-100 text-blue-700'],
    'rejected_1'      => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700'],
    'processed_admin' => ['label' => 'Menunggu Persetujuan Biaya', 'class' => 'bg-purple-100 text-purple-700'],
    'approved_2'      => ['label' => 'Biaya Disetujui', 'class' => 'bg-primary-100 text-primary-700'],
    'rejected_2'      => ['label' => 'Biaya Ditolak', 'class' => 'bg-red-100 text-red-700'],
    'completed'       => ['label' => 'Selesai', 'class' => 'bg-primary-100 text-primary-800'],
];
?>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center">
            <i class="fas fa-clock text-yellow-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= count($pending_trip) ?></p>
            <p class="text-xs text-gray-500">Menunggu Persetujuan Perjalanan</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
            <i class="fas fa-file-invoice-dollar text-purple-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= count($pending_rincian) ?></p>
            <p class="text-xs text-gray-500">Menunggu Persetujuan Biaya</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
            <i class="fas fa-check-double text-primary-600 text-xl"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800"><?= count($processed) ?></p>
            <p class="text-xs text-gray-500">Sudah Diproses</p>
        </div>
    </div>
</div>

<!-- Tahap 1: Persetujuan Perjalanan -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-yellow-100 bg-yellow-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></div>
            <h4 class="font-semibold text-yellow-700 text-sm">Tahap 1 — Persetujuan Perjalanan Dinas</h4>
        </div>
        <span class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full text-xs font-bold"><?= count($pending_trip) ?></span>
    </div>
    <?php if (empty($pending_trip)): ?>
        <div class="text-center py-10 text-gray-400 text-sm">
            <i class="fas fa-check-circle text-4xl text-gray-200 mb-2"></i>
            <p>Tidak ada pengajuan yang menunggu persetujuan</p>
        </div>
    <?php else: ?>
        <div class="divide-y divide-gray-50">
            <?php foreach ($pending_trip as $p): ?>
            <div class="px-5 py-4 hover:bg-gray-50 transition flex items-center justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-mono text-xs text-gray-400"><?= esc($p['nomor_surat']) ?></span>
                    </div>
                    <p class="font-semibold text-gray-800 text-sm"><?= esc($p['pegawai_name']) ?></p>
                    <p class="text-xs text-gray-500"><?= esc($p['tujuan']) ?>, <?= esc($p['kota_tujuan']) ?></p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        <?= date('d M Y', strtotime($p['tanggal_berangkat'])) ?> — <?= date('d M Y', strtotime($p['tanggal_pulang'])) ?>
                    </p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="/direktur/show/<?= $p['id'] ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                        Detail
                    </a>
                    <form action="/direktur/approve/<?= $p['id'] ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" onclick="return confirm('Setujui perjalanan ini?')"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition">
                            <i class="fas fa-check mr-1"></i> Setujui
                        </button>
                    </form>
                    <button onclick="openRejectModal(<?= $p['id'] ?>, 'trip')"
                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-semibold transition">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Tahap 2: Persetujuan Rincian Biaya -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-purple-100 bg-purple-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></div>
            <h4 class="font-semibold text-purple-700 text-sm">Tahap 2 — Persetujuan Rincian Biaya</h4>
        </div>
        <span class="bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full text-xs font-bold"><?= count($pending_rincian) ?></span>
    </div>
    <?php if (empty($pending_rincian)): ?>
        <div class="text-center py-10 text-gray-400 text-sm">
            <i class="fas fa-check-circle text-4xl text-gray-200 mb-2"></i>
            <p>Tidak ada rincian biaya yang menunggu persetujuan</p>
        </div>
    <?php else: ?>
        <div class="divide-y divide-gray-50">
            <?php foreach ($pending_rincian as $p): ?>
            <div class="px-5 py-4 hover:bg-gray-50 transition flex items-center justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <span class="font-mono text-xs text-gray-400"><?= esc($p['nomor_surat']) ?></span>
                    <p class="font-semibold text-gray-800 text-sm"><?= esc($p['pegawai_name']) ?></p>
                    <p class="text-xs text-gray-500"><?= esc($p['tujuan']) ?></p>
                    <?php if ($p['total_pengajuan'] > 0): ?>
                        <p class="text-xs font-semibold text-purple-600 mt-0.5">Total: Rp <?= number_format($p['total_pengajuan'], 0, ',', '.') ?></p>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="/direktur/show/<?= $p['id'] ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                        Lihat Rincian
                    </a>
                    <form action="/direktur/approve/<?= $p['id'] ?>" method="POST" class="inline">
                        <?= csrf_field() ?>
                        <button type="submit" onclick="return confirm('Setujui rincian biaya ini?')"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition">
                            <i class="fas fa-check mr-1"></i> Setujui Biaya
                        </button>
                    </form>
                    <button onclick="openRejectModal(<?= $p['id'] ?>, 'rincian')"
                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-semibold transition">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Riwayat -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
        <h4 class="font-semibold text-gray-700 text-sm">Riwayat Keputusan</h4>
    </div>
    <?php if (empty($processed)): ?>
        <div class="text-center py-8 text-gray-400 text-sm">Belum ada riwayat</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">No. Surat</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Pegawai</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Tujuan</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Status</th>
                    <th class="px-5 py-3 text-left text-xs text-gray-500">Total Biaya</th>
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
                        <td class="px-5 py-3 font-semibold text-gray-700 text-xs">
                            <?= $p['total_pengajuan'] > 0 ? 'Rp ' . number_format($p['total_pengajuan'], 0, ',', '.') : '-' ?>
                        </td>
                        <td class="px-5 py-3">
                            <a href="/direktur/show/<?= $p['id'] ?>" class="text-primary-600 hover:text-primary-800 text-xs font-semibold">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h4 class="font-bold text-gray-800 mb-4 text-lg">Alasan Penolakan</h4>
        <form id="rejectForm" method="POST">
            <?= csrf_field() ?>
            <textarea name="catatan" rows="3" required placeholder="Berikan alasan penolakan..."
                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none mb-4"></textarea>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl text-sm font-semibold transition">
                    Konfirmasi Tolak
                </button>
                <button type="button" onclick="closeRejectModal()" class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(id) {
    document.getElementById('rejectForm').action = '/direktur/reject/' + id;
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>

<?= $this->endSection() ?>
