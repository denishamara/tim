<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h1>
            <p class="text-sm text-gray-500 mt-1">Analisis dan ringkasan perjalanan dinas</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= site_url('keuangan') ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-filter text-primary-600"></i>
        Filter Laporan
    </h3>
    <form method="GET" action="<?= site_url('keuangan/laporan') ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
            <input type="date" name="start_date" value="<?= esc($filters['start_date']) ?>" 
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
            <input type="date" name="end_date" value="<?= esc($filters['end_date']) ?>" 
                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="all" <?= $filters['status'] === 'all' ? 'selected' : '' ?>>Semua Status</option>
                <option value="approved_2" <?= $filters['status'] === 'approved_2' ? 'selected' : '' ?>>Disetujui Direktur</option>
                <option value="sent_finance" <?= $filters['status'] === 'sent_finance' ? 'selected' : '' ?>>Dikirim ke Keuangan</option>
                <option value="completed" <?= $filters['status'] === 'completed' ? 'selected' : '' ?>>Selesai</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Pegawai</label>
            <select name="user_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Pegawai</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= $filters['user_id'] == $user['id'] ? 'selected' : '' ?>>
                        <?= esc($user['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="md:col-span-4 flex gap-2">
            <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-semibold transition">
                <i class="fas fa-search mr-2"></i>Tampilkan Laporan
            </button>
            <a href="<?= site_url('keuangan/laporan') ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-redo mr-2"></i>Reset Filter
            </a>
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-file-invoice text-xl"></i>
            </div>
        </div>
        <p class="text-3xl font-bold mb-1"><?= $totalPerjalanan ?></p>
        <p class="text-blue-100 text-sm">Total Perjalanan</p>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-xl"></i>
            </div>
        </div>
        <p class="text-2xl font-bold mb-1">Rp <?= number_format($totalBiaya, 0, ',', '.') ?></p>
        <p class="text-green-100 text-sm">Total Biaya</p>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
        </div>
        <p class="text-2xl font-bold mb-1">Rp <?= number_format($avgBiaya, 0, ',', '.') ?></p>
        <p class="text-orange-100 text-sm">Rata-rata Biaya</p>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-calendar-alt text-xl"></i>
            </div>
        </div>
        <p class="text-xl font-bold mb-1"><?= date('d M', strtotime($filters['start_date'])) ?> - <?= date('d M Y', strtotime($filters['end_date'])) ?></p>
        <p class="text-purple-100 text-sm">Periode Laporan</p>
    </div>
</div>

<!-- Export Buttons -->
<div class="flex gap-2 mb-6">
    <a href="<?= site_url('keuangan/exportPdf?' . http_build_query($filters)) ?>" target="_blank"
       class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition">
        <i class="fas fa-file-pdf mr-2"></i>Export PDF
    </a>
    <a href="<?= site_url('keuangan/exportExcel?' . http_build_query($filters)) ?>"
       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition">
        <i class="fas fa-file-excel mr-2"></i>Export Excel
    </a>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Biaya Per Status -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-pie text-primary-600"></i>
            Biaya Per Status
        </h3>
        <?php if (empty($biayaPerStatus)): ?>
            <p class="text-center text-gray-400 text-sm py-8">Tidak ada data</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php 
                $statusLabels = [
                    'approved_2' => ['label' => 'Disetujui Direktur', 'color' => 'blue'],
                    'sent_finance' => ['label' => 'Dikirim ke Keuangan', 'color' => 'yellow'],
                    'completed' => ['label' => 'Selesai', 'color' => 'green'],
                ];
                foreach ($biayaPerStatus as $status => $data): 
                    $statusLabel = $statusLabels[$status]['label'] ?? $status;
                    $color = $statusLabels[$status]['color'] ?? 'gray';
                    $percentage = $totalBiaya > 0 ? ($data['total'] / $totalBiaya) * 100 : 0;
                ?>
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-700 font-medium"><?= esc($statusLabel) ?></span>
                            <span class="text-gray-600"><?= $data['count'] ?> perjalanan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-<?= $color ?>-500 h-full rounded-full transition-all" style="width: <?= number_format($percentage, 1) ?>%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-20 text-right">Rp <?= number_format($data['total'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Biaya Per Jenis -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-bar text-primary-600"></i>
            Biaya Per Jenis (Top 5)
        </h3>
        <?php if (empty($biayaPerJenis)): ?>
            <p class="text-center text-gray-400 text-sm py-8">Tidak ada data</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php 
                $colors = ['indigo', 'blue', 'green', 'yellow', 'red'];
                $index = 0;
                foreach (array_slice($biayaPerJenis, 0, 5, true) as $jenis => $total): 
                    $percentage = $totalBiaya > 0 ? ($total / $totalBiaya) * 100 : 0;
                    $color = $colors[$index % count($colors)];
                    $index++;
                ?>
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-700 font-medium"><?= esc($jenis) ?></span>
                            <span class="text-gray-500 text-xs"><?= number_format($percentage, 1) ?>%</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-<?= $color ?>-500 h-full rounded-full transition-all" style="width: <?= number_format($percentage, 1) ?>%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-24 text-right">Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Detail Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-table text-primary-600"></i>
            Detail Perjalanan
        </h3>
    </div>
    
    <?php if (empty($perjalanan)): ?>
        <div class="text-center py-12">
            <i class="fas fa-inbox text-6xl text-gray-200 mb-4"></i>
            <p class="text-gray-400">Tidak ada data perjalanan dalam periode ini</p>
            <p class="text-sm text-gray-400 mt-1">Coba ubah filter atau periode tanggal</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">No</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Nomor Surat</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Pegawai</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Tujuan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-600">Total Biaya</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php 
                    $no = 1;
                    foreach ($perjalanan as $p): 
                        $statusLabels = [
                            'approved_2' => ['text' => 'Disetujui Direktur', 'class' => 'bg-blue-100 text-blue-700'],
                            'sent_finance' => ['text' => 'Dikirim ke Keuangan', 'class' => 'bg-yellow-100 text-yellow-700'],
                            'completed' => ['text' => 'Selesai', 'class' => 'bg-green-100 text-green-700'],
                        ];
                        $statusInfo = $statusLabels[$p['status']] ?? ['text' => $p['status'], 'class' => 'bg-gray-100 text-gray-700'];
                    ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3 text-gray-600"><?= $no++ ?></td>
                            <td class="px-5 py-3">
                                <span class="font-mono text-xs text-gray-600"><?= esc($p['nomor_surat']) ?></span>
                            </td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-gray-800"><?= esc($p['pegawai_name']) ?></p>
                                <p class="text-xs text-gray-500"><?= esc($p['pegawai_email']) ?></p>
                            </td>
                            <td class="px-5 py-3">
                                <p class="text-gray-800"><?= esc($p['tujuan']) ?></p>
                                <p class="text-xs text-gray-500"><?= esc($p['kota_tujuan']) ?></p>
                            </td>
                            <td class="px-5 py-3">
                                <p class="text-gray-700"><?= date('d M Y', strtotime($p['tanggal_berangkat'])) ?></p>
                                <p class="text-xs text-gray-500">s/d <?= date('d M Y', strtotime($p['tanggal_pulang'])) ?></p>
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium <?= $statusInfo['class'] ?>">
                                    <?= esc($statusInfo['text']) ?>
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <span class="font-semibold text-gray-800">Rp <?= number_format($p['total_biaya'], 0, ',', '.') ?></span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="<?= site_url('keuangan/show/' . $p['id']) ?>" 
                                   class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-medium transition">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <td colspan="6" class="px-5 py-3 text-right font-semibold text-gray-800">Total:</td>
                        <td class="px-5 py-3 text-right font-bold text-primary-600 text-base">
                            Rp <?= number_format($totalBiaya, 0, ',', '.') ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
