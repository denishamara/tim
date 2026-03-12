<?= $this->extend('components/layout') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-primary-700 to-primary-600 px-6 py-5">
            <h3 class="text-white font-semibold text-lg flex items-center gap-2">
                <i class="fas fa-paper-plane"></i> Form Pengajuan Perjalanan Dinas
            </h3>
            <p class="text-primary-200 text-sm mt-1">Isi formulir berikut untuk mengajukan perjalanan dinas</p>
        </div>

        <form action="/pegawai/store" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tujuan / Instansi Dikunjungi <span class="text-red-500">*</span></label>
                    <input type="text" name="tujuan" value="<?= old('tujuan') ?>" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent"
                        placeholder="Contoh: RS PKU Muhammadiyah Mayong">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kota Tujuan <span class="text-red-500">*</span></label>
                    <input type="text" name="kota_tujuan" value="<?= old('kota_tujuan') ?>" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent"
                        placeholder="Contoh: Kudus, Jawa Tengah">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keperluan / Maksud Perjalanan <span class="text-red-500">*</span></label>
                    <textarea name="keperluan" rows="3" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent resize-none"
                        placeholder="Contoh: Marketing - Penawaran SIMRS"><?= old('keperluan') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Berangkat <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_berangkat" value="<?= old('tanggal_berangkat') ?>" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jam Berangkat</label>
                    <input type="time" name="jam_berangkat" value="<?= old('jam_berangkat') ?>"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Pulang <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pulang" value="<?= old('tanggal_pulang') ?>" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jam Pulang</label>
                    <input type="time" name="jam_pulang" value="<?= old('jam_pulang') ?>"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-sticky-note text-primary-400 mr-1"></i> Catatan / Itinerary Kunjungan
                    </label>
                    <textarea name="catatan" rows="6"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent resize-y"
                        placeholder="Senin, 23 Feb: RS PKU Mayong (follow up), RSI NU Cakra Medika&#10;Selasa, 24 Feb: RSIA Harapan Bunda (follow up)&#10;..."><?= old('catatan') ?></textarea>
                    <p class="text-xs text-gray-400 mt-1">Isi jadwal kunjungan harian (opsional)</p>
                </div>

                <!-- Peserta Perjalanan (Multiple Users) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-users text-primary-400 mr-1"></i> Peserta Perjalanan
                    </label>
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 max-h-64 overflow-y-auto">
                        <p class="text-xs text-gray-500 mb-3">Pilih pegawai yang akan ikut dalam perjalanan dinas ini:</p>
                        <div class="space-y-2">
                            <?php foreach ($users as $user): ?>
                                <label class="flex items-center gap-3 p-2.5 bg-white border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-primary-50 cursor-pointer transition">
                                    <input type="checkbox" name="peserta_ids[]" value="<?= $user['id'] ?>" 
                                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-2 focus:ring-primary-500"
                                           <?= $user['id'] == session()->get('user_id') ? 'checked' : '' ?>>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800"><?= esc($user['name']) ?></p>
                                        <p class="text-xs text-gray-500"><?= esc($user['email']) ?></p>
                                    </div>
                                    <?php if ($user['id'] == session()->get('user_id')): ?>
                                        <span class="text-xs bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full font-medium">Anda</span>
                                    <?php endif; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">
                        <i class="fas fa-info-circle"></i> Centang nama pegawai yang akan ikut dalam perjalanan ini
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload Dokumen Pendukung</label>
                    <label id="uploadZone" class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-primary-400 hover:bg-primary-50 transition bg-gray-50">
                        <!-- State: belum ada file -->
                        <div id="uploadEmpty" class="text-center">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-1"></i>
                            <p class="text-sm text-gray-500">Klik untuk upload atau seret file ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">PDF, DOC, JPG, PNG (maks. 5MB)</p>
                        </div>
                        <!-- State: sudah ada file (disembunyikan sampai file dipilih) -->
                        <div id="uploadFilled" class="hidden text-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-1.5">
                                <i class="fas fa-check text-green-600 text-lg"></i>
                            </div>
                            <p id="uploadFileName" class="text-sm font-semibold text-green-700 max-w-xs truncate px-4"></p>
                            <p class="text-xs text-green-500 mt-0.5">File siap diupload · <span class="underline">Ganti file</span></p>
                        </div>
                        <input type="file" id="dokumenInput" name="dokumen" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </label>
                    <script>
                    (function(){
                        const input    = document.getElementById('dokumenInput');
                        const zone     = document.getElementById('uploadZone');
                        const empty    = document.getElementById('uploadEmpty');
                        const filled   = document.getElementById('uploadFilled');
                        const nameEl   = document.getElementById('uploadFileName');

                        function showFile(file) {
                            if (!file) return;
                            nameEl.textContent = file.name;
                            empty.classList.add('hidden');
                            filled.classList.remove('hidden');
                            zone.classList.remove('border-gray-200', 'bg-gray-50');
                            zone.classList.add('border-green-400', 'bg-green-50');
                        }

                        input.addEventListener('change', function() {
                            showFile(this.files[0]);
                        });

                        // Drag & drop support
                        zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('border-primary-400','bg-primary-50'); });
                        zone.addEventListener('dragleave', e => { zone.classList.remove('border-primary-400','bg-primary-50'); });
                        zone.addEventListener('drop', function(e) {
                            e.preventDefault();
                            zone.classList.remove('border-primary-400','bg-primary-50');
                            const file = e.dataTransfer.files[0];
                            if (file) {
                                // Transfer to input
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                input.files = dt.files;
                                showFile(file);
                            }
                        });
                    })();
                    </script>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-semibold py-3 rounded-xl text-sm transition shadow-sm shadow-primary-200">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan
                </button>
                <a href="/pegawai"
                    class="px-6 py-3 border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
