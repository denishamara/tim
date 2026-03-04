<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<div class="mb-4 flex items-center justify-between">
    <a href="/admin" class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center gap-1">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
    </a>
    <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-car mr-2 text-primary-500"></i>Data Kendaraan Kantor</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Form tambah kendaraan -->
    <div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
            <div class="bg-gradient-to-r from-primary-700 to-primary-600 px-5 py-4">
                <h4 class="text-white font-semibold text-sm"><i class="fas fa-plus-circle mr-2"></i>Tambah Kendaraan</h4>
            </div>
            <form action="/admin/kendaraan/store" method="POST" class="p-5 space-y-4">
                <?= csrf_field() ?>

                <?php if (session()->has('errors')): ?>
                <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-xs text-red-600 space-y-1">
                    <?php foreach (session('errors') as $err): ?>
                    <p><i class="fas fa-exclamation-circle mr-1"></i><?= esc($err) ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Kendaraan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kendaraan" value="<?= old('nama_kendaraan') ?>" required
                        placeholder="Mitsubishi Mirage"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor Polisi <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_polisi" value="<?= old('nomor_polisi') ?>" required
                        placeholder="B 1744 EYC"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 uppercase">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Kendaraan</label>
                    <select name="jenis" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                        <option value="Kendaraan Roda 4" <?= old('jenis') === 'Kendaraan Roda 4' ? 'selected' : '' ?>>Kendaraan Roda 4</option>
                        <option value="Kendaraan Roda 2" <?= old('jenis') === 'Kendaraan Roda 2' ? 'selected' : '' ?>>Kendaraan Roda 2</option>
                        <option value="Kendaraan Roda 6+" <?= old('jenis') === 'Kendaraan Roda 6+' ? 'selected' : '' ?>>Kendaraan Roda 6+</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"
                        placeholder="Kendaraan operasional kantor..."><?= old('keterangan') ?></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                    <i class="fas fa-plus mr-1"></i> Simpan Kendaraan
                </button>
            </form>
        </div>
    </div>

    <!-- Daftar kendaraan -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h4 class="font-semibold text-gray-700 text-sm">Daftar Kendaraan (<?= count($list) ?>)</h4>
            </div>

            <?php if (empty($list)): ?>
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-car text-5xl text-gray-200 mb-3"></i>
                <p class="text-sm">Belum ada data kendaraan</p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Kendaraan</th>
                            <th class="px-4 py-3 text-left">No. Polisi</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($list as $k): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800"><?= esc($k['nama_kendaraan']) ?></p>
                                <?php if ($k['keterangan']): ?>
                                <p class="text-xs text-gray-400"><?= esc($k['keterangan']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 font-mono font-bold text-primary-700"><?= esc($k['nomor_polisi']) ?></td>
                            <td class="px-4 py-3 text-gray-500 text-xs"><?= esc($k['jenis']) ?></td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($k['aktif']): ?>
                                <span class="inline-flex items-center gap-1 text-xs bg-green-100 text-green-700 px-2.5 py-1 rounded-full font-medium">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Aktif
                                </span>
                                <?php else: ?>
                                <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full font-medium">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Nonaktif
                                </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="/admin/kendaraan/toggle/<?= $k['id'] ?>"
                                        class="text-xs px-3 py-1 rounded-lg border border-gray-200 hover:border-primary-400 hover:text-primary-600 transition"
                                        title="<?= $k['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                        <i class="fas fa-<?= $k['aktif'] ? 'toggle-on' : 'toggle-off' ?>"></i>
                                    </a>
                                    <a href="/admin/kendaraan/delete/<?= $k['id'] ?>"
                                        onclick="return confirm('Hapus kendaraan ini?')"
                                        class="text-xs px-3 py-1 rounded-lg border border-red-100 text-red-400 hover:bg-red-50 hover:text-red-600 transition">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
