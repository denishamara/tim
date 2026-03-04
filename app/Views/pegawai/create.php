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

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload Dokumen Pendukung</label>
                    <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-primary-400 hover:bg-primary-50 transition bg-gray-50">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-300 mb-1"></i>
                            <p class="text-sm text-gray-500">Klik untuk upload atau seret file ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">PDF, DOC, JPG, PNG (maks. 5MB)</p>
                        </div>
                        <input type="file" name="dokumen" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </label>
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
