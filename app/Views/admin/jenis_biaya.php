<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<div class="mb-4 flex items-center justify-between">
    <a href="/admin" class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center gap-1">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
    </a>
    <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-tags mr-2 text-primary-500"></i>Master Jenis Biaya</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Form tambah / edit -->
    <div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
            <div class="bg-gradient-to-r from-primary-700 to-primary-600 px-5 py-4">
                <h4 class="text-white font-semibold text-sm" id="formTitle">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Jenis Biaya
                </h4>
            </div>
            <form action="/admin/jenis-biaya/store" method="POST" class="p-5 space-y-4" id="jenisBiayaForm">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="editId" value="">

                <?php if (session()->has('errors')): ?>
                <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-xs text-red-600 space-y-1">
                    <?php foreach (session('errors') as $err): ?>
                    <p><i class="fas fa-exclamation-circle mr-1"></i><?= esc($err) ?></p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Jenis Biaya <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="editNama" value="<?= old('nama') ?>" required
                        placeholder="Contoh: BBM PP, Uang Makan, Penginapan..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Satuan Default <span class="text-red-500">*</span></label>
                    <select name="satuan_default" id="editSatuan" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                        <option value="Kali"  <?= old('satuan_default') === 'Kali'  ? 'selected' : '' ?>>Kali</option>
                        <option value="Hari"  <?= old('satuan_default') === 'Hari'  ? 'selected' : '' ?>>Hari</option>
                        <option value="Malam" <?= old('satuan_default') === 'Malam' ? 'selected' : '' ?>>Malam</option>
                        <option value="PP"    <?= old('satuan_default') === 'PP'    ? 'selected' : '' ?>>PP (Pergi-Pulang)</option>
                        <option value="Liter" <?= old('satuan_default') === 'Liter' ? 'selected' : '' ?>>Liter</option>
                        <option value="Km"    <?= old('satuan_default') === 'Km'    ? 'selected' : '' ?>>Km</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Harga Default (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_default" id="editHarga" value="<?= old('harga_default', 0) ?>" required min="0" step="500"
                        placeholder="0"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    <p class="text-xs text-gray-400 mt-1">Isi 0 jika harga bervariasi setiap pengajuan.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                        <input type="checkbox" name="butuh_kendaraan" id="editKendaraan" value="1" class="mr-1">
                        Wajib Pilih Kendaraan
                    </label>
                    <p class="text-xs text-gray-400">Centang untuk jenis biaya seperti BBM atau Parkir.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan</label>
                    <textarea name="keterangan" id="editKeterangan" rows="2"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"
                        placeholder="Deskripsi singkat jenis biaya ini..."><?= old('keterangan') ?></textarea>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                        <i class="fas fa-save mr-1"></i> <span id="btnLabel">Simpan</span>
                    </button>
                    <button type="button" id="btnReset" onclick="resetForm()" class="hidden px-4 py-2.5 rounded-xl text-sm border border-gray-200 hover:border-red-300 hover:text-red-500 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar jenis biaya -->
    <div class="lg:col-span-2">

        <?php if (session()->has('success')): ?>
        <div class="bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm mb-4 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> <?= session('success') ?>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h4 class="font-semibold text-gray-700 text-sm">Daftar Jenis Biaya (<?= count($list) ?>)</h4>
                <p class="text-xs text-gray-400">Data ini menjadi pilihan dropdown saat input rincian biaya.</p>
            </div>

            <?php if (empty($list)): ?>
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-tags text-5xl text-gray-200 mb-3"></i>
                <p class="text-sm">Belum ada data jenis biaya</p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Jenis Biaya</th>
                            <th class="px-4 py-3 text-center">Satuan</th>
                            <th class="px-4 py-3 text-right">Harga Default</th>
                            <th class="px-4 py-3 text-center">Kendaraan</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($list as $j): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800"><?= esc($j['nama']) ?></p>
                                <?php if ($j['keterangan']): ?>
                                <p class="text-xs text-gray-400"><?= esc($j['keterangan']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-medium">
                                    <?= esc($j['satuan_default']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-gray-700">
                                <?= $j['harga_default'] > 0 ? 'Rp ' . number_format($j['harga_default'], 0, ',', '.') : '<span class="text-gray-400 text-xs">Variatif</span>' ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($j['butuh_kendaraan']): ?>
                                <span class="inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full font-medium">
                                    <i class="fas fa-car"></i> Wajib
                                </span>
                                <?php else: ?>
                                <span class="text-xs text-gray-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($j['aktif']): ?>
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
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button"
                                        onclick="editRow(<?= htmlspecialchars(json_encode($j), ENT_QUOTES) ?>)"
                                        class="text-xs px-3 py-1 rounded-lg border border-gray-200 hover:border-primary-400 hover:text-primary-600 transition"
                                        title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <a href="/admin/jenis-biaya/toggle/<?= $j['id'] ?>"
                                        class="text-xs px-3 py-1 rounded-lg border border-gray-200 hover:border-amber-400 hover:text-amber-600 transition"
                                        title="<?= $j['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                        <i class="fas fa-<?= $j['aktif'] ? 'toggle-on' : 'toggle-off' ?>"></i>
                                    </a>
                                    <a href="/admin/jenis-biaya/delete/<?= $j['id'] ?>"
                                        onclick="return confirm('Hapus jenis biaya ini?')"
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

<script>
function editRow(data) {
    document.getElementById('editId').value         = data.id;
    document.getElementById('editNama').value       = data.nama;
    document.getElementById('editHarga').value      = data.harga_default;
    document.getElementById('editKeterangan').value = data.keterangan || '';
    document.getElementById('editKendaraan').checked = data.butuh_kendaraan == 1;
    document.getElementById('btnLabel').textContent = 'Perbarui';
    document.getElementById('btnReset').classList.remove('hidden');
    document.getElementById('formTitle').innerHTML  = '<i class="fas fa-pencil-alt mr-2"></i>Edit Jenis Biaya';

    const satuanEl = document.getElementById('editSatuan');
    for (let o of satuanEl.options) {
        o.selected = (o.value === data.satuan_default);
    }
    satuanEl.dispatchEvent(new Event('change'));

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('editId').value         = '';
    document.getElementById('editNama').value       = '';
    document.getElementById('editHarga').value      = '0';
    document.getElementById('editKeterangan').value = '';
    document.getElementById('editKendaraan').checked = false;
    document.getElementById('btnLabel').textContent  = 'Simpan';
    document.getElementById('btnReset').classList.add('hidden');
    document.getElementById('formTitle').innerHTML   = '<i class="fas fa-plus-circle mr-2"></i>Tambah Jenis Biaya';
}
</script>

<?= $this->endSection() ?>
