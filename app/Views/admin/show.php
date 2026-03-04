<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<div class="mb-4 flex items-center justify-between">
    <a href="/admin" class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center gap-1">
        <i class="fas fa-arrow-left text-xs"></i> Kembali
    </a>
    <a href="/print/<?= $perjalanan['id'] ?>" target="_blank"
        class="flex items-center gap-2 bg-white border border-gray-200 hover:border-primary-400 hover:text-primary-700 text-gray-600 text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
        <i class="fas fa-print"></i> Cetak / PDF
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Detail & Rincian -->
    <div class="lg:col-span-2 space-y-5">
        <!-- Info perjalanan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-primary-700 to-primary-600 px-5 py-4">
                <h4 class="text-white font-semibold text-sm"><?= esc($perjalanan['nomor_surat']) ?></h4>
                <p class="text-primary-200 text-xs mt-0.5">Perjalanan Dinas — <?= esc($perjalanan['pegawai_name']) ?></p>
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
                    <p class="font-medium"><?= date('d M Y', strtotime($perjalanan['tanggal_berangkat'])) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Pulang</p>
                    <p class="font-medium"><?= date('d M Y', strtotime($perjalanan['tanggal_pulang'])) ?></p>
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

        <!-- Form tambah rincian -->
        <?php if ($perjalanan['status'] === 'approved_1'): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-primary-50">
                <h4 class="font-semibold text-primary-700 text-sm flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Tambah Rincian Biaya
                </h4>
            </div>
            <form action="/admin/rincian/add/<?= $perjalanan['id'] ?>" method="POST" class="p-5 space-y-3">
                <?= csrf_field() ?>

                <!-- Row 1: Judul + Kendaraan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Judul Biaya <span class="text-red-500">*</span></label>
                        <select id="judulSelect" name="judul" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Biaya Penginapan">Biaya Penginapan</option>
                            <option value="Biaya Transportasi">Biaya Transportasi</option>
                            <option value="BBM PP">BBM PP</option>
                            <option value="BBM Dilokasi">BBM Dilokasi</option>
                            <option value="Parkir">Parkir</option>
                            <option value="Uang Makan">Uang Makan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Kendaraan
                            <span id="kendaraanRequired" class="text-red-500 hidden">*</span>
                            <span id="kendaraanOptional" class="text-gray-400">(jika ada)</span>
                        </label>
                        <select id="kendaraanSelect" name="kendaraan_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                            <option value="">-- Tanpa Kendaraan --</option>
                            <?php foreach ($kendaraan as $kid => $knama): ?>
                            <option value="<?= $kid ?>" data-nama="<?= esc($knama) ?>"><?= esc($knama) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Keterangan -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan / Uraian Detail <span class="text-red-500">*</span></label>
                    <input type="text" id="keteranganInput" name="keterangan" required
                        placeholder="Pilih kategori terlebih dahulu..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                </div>

                <!-- Row 3: Qty, Satuan, Harga -->
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Banyak <span class="text-red-500">*</span></label>
                        <input type="number" name="qty" required min="1" value="1" step="0.5"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Satuan <span class="text-red-500">*</span></label>
                        <select id="satuanSelect" name="satuan" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                            <option value="Kali">Kali</option>
                            <option value="Malam">Malam</option>
                            <option value="PP">PP</option>
                            <option value="Hari">Hari</option>
                            <option value="Liter">Liter</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" required min="0" step="500"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400"
                            placeholder="175000">
                    </div>
                </div>

                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-plus mr-1"></i> Tambah Baris
                </button>
            </form>

            <script>
            (function(){
                const judulSel     = document.getElementById('judulSelect');
                const kendaraanSel = document.getElementById('kendaraanSelect');
                const keteranganIn = document.getElementById('keteranganInput');
                const satuanSel    = document.getElementById('satuanSelect');
                const kReq         = document.getElementById('kendaraanRequired');
                const kOpt         = document.getElementById('kendaraanOptional');

                const hints = {
                    'Biaya Penginapan':  { ph: 'Contoh: Penginapan Cedors ov/Nizar', satuan: 'Malam' },
                    'Biaya Transportasi':{ ph: 'Contoh: Tol Prambanan - Demak', satuan: 'Kali' },
                    'BBM PP':            { ph: 'Pilih kendaraan → terisi otomatis', satuan: 'PP' },
                    'BBM Dilokasi':      { ph: 'Pilih kendaraan → terisi otomatis', satuan: 'PP' },
                    'Parkir':            { ph: 'Pilih kendaraan → terisi otomatis', satuan: 'Kali' },
                    'Uang Makan':        { ph: 'Contoh: M. Nizar Zulmi Syaifullah', satuan: 'Kali' },
                    'Lainnya':           { ph: 'Isi keterangan detail...', satuan: 'Kali' },
                };

                const isAutoFill = v => ['BBM PP','BBM Dilokasi','Parkir'].includes(v);

                function setSatuan(val) {
                    for (let o of satuanSel.options) {
                        if (o.value === val) { o.selected = true; break; }
                    }
                }

                function fillFromKendaraan() {
                    const judul = judulSel.value;
                    if (!isAutoFill(judul)) return;
                    const opt = kendaraanSel.options[kendaraanSel.selectedIndex];
                    keteranganIn.value = (opt && opt.value) ? (opt.dataset.nama || opt.text) : '';
                    keteranganIn.dataset.auto = '1';
                }

                judulSel.addEventListener('change', function() {
                    const val = this.value;
                    const h   = hints[val] || { ph: 'Isi keterangan...', satuan: 'Kali' };
                    keteranganIn.placeholder = h.ph;
                    setSatuan(h.satuan);

                    if (isAutoFill(val)) {
                        kReq.classList.remove('hidden');
                        kOpt.classList.add('hidden');
                        fillFromKendaraan();
                    } else {
                        kReq.classList.add('hidden');
                        kOpt.classList.remove('hidden');
                        if (keteranganIn.dataset.auto === '1') {
                            keteranganIn.value = '';
                            keteranganIn.dataset.auto = '0';
                        }
                    }
                });

                kendaraanSel.addEventListener('change', fillFromKendaraan);
            })();
            </script>
        </div>
        <?php endif; ?>

        <!-- Tabel rincian -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h4 class="font-semibold text-gray-700 text-sm">Daftar Rincian Biaya</h4>
                <?php if (! empty($rincian)): ?>
                    <?php $total = array_sum(array_column($rincian, 'total')); ?>
                    <span class="font-bold text-primary-600 text-sm">Total: Rp <?= number_format($total, 0, ',', '.') ?></span>
                <?php endif; ?>
            </div>
            <?php if (empty($rincian)): ?>
                <div class="text-center py-10 text-gray-400 text-sm">
                    <i class="fas fa-table text-4xl text-gray-200 mb-2"></i>
                    <p>Belum ada rincian biaya</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs text-gray-500">Uraian</th>
                                <th class="px-4 py-2 text-center text-xs text-gray-500">Qty</th>
                                <th class="px-4 py-2 text-left text-xs text-gray-500">Satuan</th>
                                <th class="px-4 py-2 text-right text-xs text-gray-500">Harga</th>
                                <th class="px-4 py-2 text-right text-xs text-gray-500">Total</th>
                                <?php if ($perjalanan['status'] === 'approved_1'): ?><th class="px-4 py-2"></th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php $grandTotal = 0; foreach ($rincian as $r): $grandTotal += $r['total']; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2.5">
                                    <p class="font-semibold text-xs text-primary-700"><?= esc($r['judul'] ?? '') ?></p>
                                    <p class="text-gray-700 text-sm"><?= esc($r['keterangan'] ?? $r['uraian']) ?></p>
                                    <?php if (! empty($r['nama_kendaraan'])): ?>
                                    <p class="text-xs text-gray-400"><i class="fas fa-car mr-1"></i><?= esc($r['nama_kendaraan']) ?> (<?= esc($r['nomor_polisi']) ?>)</p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2.5 text-center"><?= $r['qty'] ?></td>
                                <td class="px-4 py-2.5 text-gray-500"><?= esc($r['satuan']) ?></td>
                                <td class="px-4 py-2.5 text-right">Rp <?= number_format($r['harga'], 0, ',', '.') ?></td>
                                <td class="px-4 py-2.5 text-right font-semibold">Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
                                <?php if ($perjalanan['status'] === 'approved_1'): ?>
                                <td class="px-4 py-2.5">
                                    <a href="/admin/rincian/delete/<?= $perjalanan['id'] ?>/<?= $r['id'] ?>"
                                        onclick="return confirm('Hapus baris ini?')"
                                        class="text-red-400 hover:text-red-600 text-xs">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-primary-50">
                            <tr>
                                <td colspan="<?= $perjalanan['status'] === 'approved_1' ? 5 : 4 ?>" class="px-4 py-3 text-right font-bold text-primary-700">TOTAL PENGAJUAN DANA</td>
                                <td class="px-4 py-3 text-right font-bold text-primary-700">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Submit to director button -->
        <?php if ($perjalanan['status'] === 'approved_1' && ! empty($rincian)): ?>
        <form action="/admin/submit/<?= $perjalanan['id'] ?>" method="POST"
            onsubmit="return confirm('Kirim rincian biaya ke direktur untuk persetujuan?')">
            <?= csrf_field() ?>
            <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-semibold py-3 rounded-xl transition shadow">
                <i class="fas fa-paper-plane mr-2"></i> Kirim ke Direktur untuk Persetujuan Biaya
            </button>
        </form>
        <?php endif; ?>
    </div>

    <!-- Sidebar: dokumen + logs -->
    <div class="space-y-5">
        <!-- Dokumen -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm">Dokumen Lampiran</h4>
            </div>
            <div class="p-4 space-y-2">
                <?php if (empty($dokumen)): ?>
                    <p class="text-xs text-gray-400 text-center py-4">Tidak ada dokumen</p>
                <?php else: foreach ($dokumen as $d): ?>
                    <a href="<?= base_url($d['path_file']) ?>" target="_blank"
                        class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-primary-50 transition text-sm">
                        <i class="fas fa-file-alt text-primary-500"></i>
                        <span class="flex-1 text-gray-700 truncate text-xs"><?= esc($d['nama_file']) ?></span>
                        <i class="fas fa-external-link-alt text-gray-400 text-xs"></i>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- History logs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h4 class="font-semibold text-gray-700 text-sm">Riwayat Proses</h4>
            </div>
            <div class="p-4">
                <?php if (empty($logs)): ?>
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($logs as $log): ?>
                        <div class="border-l-2 border-primary-200 pl-3">
                            <p class="text-xs font-semibold text-gray-700 capitalize"><?= esc($log['role']) ?></p>
                            <p class="text-xs text-gray-500"><?= esc($log['catatan']) ?></p>
                            <p class="text-xs text-gray-400"><?= $log['approved_at'] ? date('d M Y H:i', strtotime($log['approved_at'])) : '-' ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
