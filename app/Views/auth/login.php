<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SPPD Jaldin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',300:'#86efac',400:'#4ade80',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534',900:'#14532d' } } } }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body class="bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-700 to-primary-600 px-8 py-8 text-white text-center">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur">
                    <i class="fas fa-plane text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold">SPPD Jaldin</h1>
                <p class="text-primary-200 text-sm mt-1">Sistem Perjalanan Dinas Online</p>
            </div>

            <!-- Form -->
            <div class="px-8 py-8">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 mb-5 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="flex items-center gap-2 bg-primary-50 border border-primary-200 text-primary-700 rounded-xl px-4 py-3 mb-5 text-sm">
                        <i class="fas fa-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="/login" method="POST">
                    <?= csrf_field() ?>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope text-sm"></i></span>
                                <input type="email" name="email" value="<?= old('email') ?>" required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition"
                                    placeholder="email@perusahaan.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                                <input type="password" name="password" required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition"
                                    placeholder="••••••••">
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-primary-200 text-sm">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-xs text-center text-gray-400 mb-3">Akun Demo</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-500">
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="font-semibold text-gray-600">Pegawai</p>
                            <p>nizar@jaldin.com</p>
                            <p>pegawai123</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="font-semibold text-gray-600">Admin</p>
                            <p>admin@jaldin.com</p>
                            <p>admin123</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="font-semibold text-gray-600">Direktur</p>
                            <p>direktur@jaldin.com</p>
                            <p>direktur123</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="font-semibold text-gray-600">Keuangan</p>
                            <p>keuangan@jaldin.com</p>
                            <p>keuangan123</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-center text-primary-300 text-xs mt-4">&copy; <?= date('Y') ?> SPPD Jaldin</p>
    </div>
</body>
</html>
