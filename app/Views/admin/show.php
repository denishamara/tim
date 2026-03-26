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

        <!-- Form tambah rincian -->
        <?php if ($perjalanan['status'] === 'approved_1'): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-primary-50">
                <h4 class="font-semibold text-primary-700 text-sm flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> Tambah Rincian Biaya
                </h4>
            </div>
            <form action="/admin/rincian/add/<?= $perjalanan['id'] ?>" method="POST" class="p-5 space-y-4" id="rincianForm">
                <?= csrf_field() ?>

                <!-- Header: hanya Jenis Biaya (kendaraan dipilih per-baris) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Biaya <span class="text-red-500">*</span></label>
                    <select id="judulSelect" name="jenis_biaya_id" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                        <option value="">-- Pilih Jenis Biaya --</option>
                        <?php foreach ($jenis_biaya as $jb): ?>
                        <option value="<?= $jb['id'] ?>"
                            data-nama="<?= esc($jb['nama']) ?>"
                            data-satuan="<?= esc($jb['satuan_default']) ?>"
                            data-harga="<?= $jb['harga_default'] ?>"
                            data-kendaraan="<?= $jb['butuh_kendaraan'] ?>">
                            <?= esc($jb['nama']) ?>
                            <?php if ($jb['harga_default'] > 0): ?>
                                — Rp <?= number_format($jb['harga_default'], 0, ',', '.') ?>/<?= esc($jb['satuan_default']) ?>
                            <?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <a href="/admin/jenis-biaya" class="text-xs text-primary-500 hover:underline mt-1 inline-block">
                        <i class="fas fa-cog mr-0.5"></i> Kelola jenis biaya
                    </a>
                </div>

                <!-- Template data kendaraan (dipakai JS untuk build options) -->
                <template id="kendaraanOptions">
                    <option value="">-- Pilih Kendaraan --</option>
                    <?php foreach ($kendaraan as $kid => $knama): ?>
                    <option value="<?= $kid ?>" data-nama="<?= esc($knama) ?>"><?= esc($knama) ?></option>
                    <?php endforeach; ?>
                </template>

                <!-- Dynamic multi-row section -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-semibold text-gray-600">
                            Baris Keterangan &amp; Biaya <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal ml-1">(satu jenis biaya bisa lebih dari 1 orang/baris)</span>
                        </label>
                        <button type="button" id="tambahBaris"
                            class="flex items-center gap-1 text-xs bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold px-3 py-1.5 rounded-lg transition border border-primary-200">
                            <i class="fas fa-user-plus"></i> Tambah Orang / Baris
                        </button>
                    </div>

                    <!-- Column headers (desktop) -->
                    <div id="colHeaders" class="hidden md:grid gap-2 px-1 mb-1" style="grid-template-columns:1fr 70px 110px 150px 36px;">
                        <span class="text-xs text-gray-400 col-header-uraian">Keterangan / Uraian</span>
                        <span class="text-xs text-gray-400">Banyak</span>
                        <span class="text-xs text-gray-400">Satuan</span>
                        <span class="text-xs text-gray-400">Harga Satuan (Rp)</span>
                        <span></span>
                    </div>

                    <div id="barisContainer" class="space-y-2">
                        <!-- Row pertama (template) -->
                        <div class="baris-row grid gap-2 items-center bg-gray-50 hover:bg-gray-100 rounded-lg px-2 py-2 transition"
                             style="grid-template-columns:1fr 70px 110px 150px 36px;">
                            <!-- Uraian cell: kendaraan select (hidden by default) + teks keterangan -->
                            <div class="relative">
                                <select name="kendaraan_id[]" class="baris-kendaraan hidden w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                                    <!-- options injected by JS -->
                                </select>
                                <input type="text" name="keterangan[]" required
                                    placeholder="Nama orang / uraian detail..."
                                    class="baris-keterangan w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                            </div>
                            <input type="number" name="qty[]" required value="1" min="0.5" step="0.5"
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-center focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                            <select name="satuan[]"
                                class="baris-satuan w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                                <option value="Kali">Kali</option>
                                <option value="Hari">Hari</option>
                                <option value="Malam">Malam</option>
                                <option value="PP">PP</option>
                                <option value="Liter">Liter</option>
                                <option value="Km">Km</option>
                            </select>
                            <input type="number" name="harga[]" required min="0" step="500"
                                class="baris-harga w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white"
                                placeholder="0">
                            <button type="button" class="hapus-baris flex items-center justify-center w-8 h-8 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition text-xl leading-none font-bold" title="Hapus baris">&times;</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-save mr-1"></i> Simpan Semua Baris
                </button>
            </form>

            <script>
            (function(){
                const judulSel  = document.getElementById('judulSelect');
                const container = document.getElementById('barisContainer');
                const colHeader = document.querySelector('.col-header-uraian');

                // Build kendaraan options HTML once from <template>
                const kendaraanTpl = document.getElementById('kendaraanOptions');
                const kendaraanOptionsHTML = kendaraanTpl ? kendaraanTpl.innerHTML : '';

                let needKend = false;

                function getDefaultSatuan() {
                    const opt = judulSel.options[judulSel.selectedIndex];
                    return opt ? (opt.dataset.satuan || 'Kali') : 'Kali';
                }
                function getDefaultHarga() {
                    const opt = judulSel.options[judulSel.selectedIndex];
                    return opt ? (parseFloat(opt.dataset.harga) || 0) : 0;
                }

                function setSatuan(sel, val) {
                    for (let o of sel.options) {
                        if (o.value === val) { o.selected = true; break; }
                    }
                }

                function applyDefaultsToRow(row) {
                    const satuanSel = row.querySelector('.baris-satuan');
                    const hargaIn   = row.querySelector('.baris-harga');
                    const sat       = getDefaultSatuan();
                    const harga     = getDefaultHarga();
                    if (satuanSel) setSatuan(satuanSel, sat);
                    if (hargaIn && harga > 0) hargaIn.value = harga;
                }

                /** Switch a row between kendaraan-mode and teks-mode */
                function setRowMode(row, isKend) {
                    const kendSel  = row.querySelector('.baris-kendaraan');
                    const ketInput = row.querySelector('.baris-keterangan');

                    if (isKend) {
                        // Populate options if empty
                        if (kendSel.options.length <= 1) {
                            kendSel.innerHTML = kendaraanOptionsHTML;
                        }
                        kendSel.classList.remove('hidden');
                        kendSel.required = true;
                        ketInput.classList.add('hidden');
                        ketInput.required = false;
                    } else {
                        kendSel.classList.add('hidden');
                        kendSel.required = false;
                        kendSel.value = '';
                        ketInput.classList.remove('hidden');
                        ketInput.required = true;
                    }
                }

                function setAllRowModes(isKend) {
                    container.querySelectorAll('.baris-row').forEach(row => setRowMode(row, isKend));
                    if (colHeader) colHeader.textContent = isKend ? 'Kendaraan' : 'Keterangan / Uraian';
                }

                /** When kendaraan is picked in a row, auto-fill hidden keterangan input */
                function attachKendaraanHandler(row) {
                    const kendSel  = row.querySelector('.baris-kendaraan');
                    const ketInput = row.querySelector('.baris-keterangan');
                    kendSel.addEventListener('change', function() {
                        const opt = this.options[this.selectedIndex];
                        ketInput.value = (opt && opt.value) ? (opt.dataset.nama || opt.text) : '';
                    });
                }

                function updateDeleteButtons() {
                    const rows = container.querySelectorAll('.baris-row');
                    rows.forEach(row => {
                        const btn = row.querySelector('.hapus-baris');
                        if (btn) btn.disabled = rows.length === 1;
                    });
                }

                function attachDeleteHandler(row) {
                    row.querySelector('.hapus-baris').addEventListener('click', function() {
                        if (container.querySelectorAll('.baris-row').length > 1) {
                            row.remove();
                            updateDeleteButtons();
                        }
                    });
                }

                function initRow(row) {
                    attachDeleteHandler(row);
                    attachKendaraanHandler(row);
                }

                // Init first row
                initRow(container.querySelector('.baris-row'));
                updateDeleteButtons();

                // Tambah Baris
                document.getElementById('tambahBaris').addEventListener('click', function() {
                    const firstRow = container.querySelector('.baris-row');
                    const newRow   = firstRow.cloneNode(true);
                    // Reset values
                    const kend = newRow.querySelector('.baris-kendaraan');
                    const ket  = newRow.querySelector('.baris-keterangan');
                    if (kend) { kend.value = ''; kend.innerHTML = kendaraanOptionsHTML; }
                    if (ket)  { ket.value  = ''; }
                    newRow.querySelector('input[name="qty[]"]').value = '1';
                    applyDefaultsToRow(newRow);
                    setRowMode(newRow, needKend);
                    initRow(newRow);
                    container.appendChild(newRow);
                    updateDeleteButtons();
                    // Focus first visible input
                    const focusEl = needKend
                        ? newRow.querySelector('.baris-kendaraan')
                        : newRow.querySelector('.baris-keterangan');
                    if (focusEl) focusEl.focus();
                });

                // Jenis biaya change
                judulSel.addEventListener('change', function() {
                    const opt = this.options[this.selectedIndex];
                    if (!opt || !opt.value) return;
                    needKend = opt.dataset.kendaraan === '1';
                    setAllRowModes(needKend);
                    container.querySelectorAll('.baris-row').forEach(applyDefaultsToRow);
                });
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
                        <tbody>
                            <?php
                                $grandTotal = 0;
                                $prevJudul  = null;
                                $rowIndex   = 0;
                                foreach ($rincian as $r):
                                    $grandTotal += $r['total'];
                                    $isNewGroup  = ($r['judul'] !== $prevJudul);
                                    $prevJudul   = $r['judul'];
                                    $rowIndex++;
                            ?>
                            <?php if ($isNewGroup && $rowIndex > 1): ?>
                            <tr><td colspan="<?= $perjalanan['status'] === 'approved_1' ? 6 : 5 ?>" class="p-0"><div class="border-t-2 border-primary-100"></div></td></tr>
                            <?php endif; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2.5">
                                    <?php if ($isNewGroup && !empty($r['judul'])): ?>
                                    <p class="font-semibold text-xs text-primary-700 mb-0.5"><?= esc($r['judul']) ?></p>
                                    <?php endif; ?>
                                    <p class="text-gray-700 text-sm <?= $isNewGroup ? '' : 'pl-3 border-l-2 border-primary-100' ?>"><?= esc($r['keterangan'] ?? $r['uraian']) ?></p>
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
