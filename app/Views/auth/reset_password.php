<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — SPPD Jaldin</title>
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
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 p-2">
                    <img src="/image/logo techno medic.png" alt="Logo SPPD Jaldin" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-bold">Reset Password</h1>
                <p class="text-primary-200 text-sm mt-1">Buat password baru untuk akun Anda</p>
            </div>

            <!-- Form -->
            <div class="px-8 py-8">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 mb-5 text-sm">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 mb-5 text-sm">
                        <div class="flex items-center gap-2 font-semibold mb-2">
                            <i class="fas fa-exclamation-circle"></i>
                            Terjadi kesalahan:
                        </div>
                        <ul class="list-disc list-inside space-y-1 pl-2">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="/reset-password" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= esc($token) ?>">
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                                <input type="password" name="password" id="password" required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition"
                                    placeholder="Minimal 6 karakter">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                                <input type="password" name="password_confirm" id="password_confirm" required
                                    class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition"
                                    placeholder="Ulangi password">
                                <span id="match-icon" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                    <i class="fas fa-check-circle text-primary-500"></i>
                                </span>
                                <span id="unmatch-icon" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                                    <i class="fas fa-times-circle text-red-500"></i>
                                </span>
                            </div>
                            <p id="match-message" class="mt-2 text-sm hidden">
                                <span class="text-primary-600 flex items-center gap-1">
                                    <i class="fas fa-check"></i> Password sudah cocok!
                                </span>
                            </p>
                            <p id="unmatch-message" class="mt-2 text-sm hidden">
                                <span class="text-red-600 flex items-center gap-1">
                                    <i class="fas fa-times"></i> Password tidak cocok
                                </span>
                            </p>
                        </div>
                        
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-primary-200 text-sm">
                            <i class="fas fa-key mr-2"></i> Reset Password
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm text-center text-gray-600">
                        <a href="/login" class="text-primary-600 hover:text-primary-700 font-semibold">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <p class="text-center text-primary-300 text-xs mt-4">&copy; <?= date('Y') ?> SPPD Jaldin</p>
    </div>

    <script>
        // Validasi password matching real-time
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        const matchIcon = document.getElementById('match-icon');
        const unmatchIcon = document.getElementById('unmatch-icon');
        const matchMessage = document.getElementById('match-message');
        const unmatchMessage = document.getElementById('unmatch-message');

        function checkPasswordMatch() {
            const passwordValue = password.value;
            const confirmValue = passwordConfirm.value;

            if (confirmValue.length === 0) {
                // Hide all indicators when confirm field is empty
                matchIcon.classList.add('hidden');
                unmatchIcon.classList.add('hidden');
                matchMessage.classList.add('hidden');
                unmatchMessage.classList.add('hidden');
                return;
            }

            if (passwordValue === confirmValue) {
                // Passwords match
                matchIcon.classList.remove('hidden');
                unmatchIcon.classList.add('hidden');
                matchMessage.classList.remove('hidden');
                unmatchMessage.classList.add('hidden');
                passwordConfirm.classList.remove('border-red-300');
                passwordConfirm.classList.add('border-primary-300');
            } else {
                // Passwords don't match
                matchIcon.classList.add('hidden');
                unmatchIcon.classList.remove('hidden');
                matchMessage.classList.add('hidden');
                unmatchMessage.classList.remove('hidden');
                passwordConfirm.classList.remove('border-primary-300');
                passwordConfirm.classList.add('border-red-300');
            }
        }

        // Listen to input events on both password fields
        password.addEventListener('input', checkPasswordMatch);
        passwordConfirm.addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>
