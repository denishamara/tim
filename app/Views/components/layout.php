<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' — ' : '' ?>SPPD Jaldin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',300:'#86efac',400:'#4ade80',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534',900:'#14532d' },
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        .sidebar-link.active { background: rgba(255,255,255,0.15); border-left: 4px solid #86efac; }
        .sidebar-link:hover  { background: rgba(255,255,255,0.1); }
        @keyframes fadeIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
        .fade-in { animation: fadeIn .35s ease; }
        ::-webkit-scrollbar{width:6px} ::-webkit-scrollbar-track{background:#f0fdf4} ::-webkit-scrollbar-thumb{background:#22c55e;border-radius:3px}
    </style>
</head>
<body class="bg-gray-50 font-sans">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-primary-800 to-primary-900 text-white flex flex-col fixed inset-y-0 left-0 z-30 shadow-2xl">
        <!-- Logo -->
        <div class="px-6 py-5 border-b border-primary-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-400 rounded-xl flex items-center justify-center shadow">
                    <i class="fas fa-plane text-primary-900 text-lg"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold leading-tight">SPPD</h1>
                    <p class="text-primary-300 text-xs">Jaldin System</p>
                </div>
            </div>
        </div>

        <!-- User info -->
        <div class="px-6 py-4 border-b border-primary-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-primary-500 rounded-full flex items-center justify-center text-sm font-bold">
                    <?= strtoupper(substr(session()->get('name') ?? 'U', 0, 1)) ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold truncate"><?= esc(session()->get('name')) ?></p>
                    <span class="text-xs bg-primary-600 px-2 py-0.5 rounded-full capitalize"><?= esc(session()->get('role')) ?></span>
                </div>
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            <?php $role = session()->get('role'); ?>

            <?php if ($role === 'pegawai'): ?>
                <a href="/pegawai" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?= str_contains(current_url(), '/pegawai') ? 'active' : '' ?>">
                    <i class="fas fa-list-alt w-5 text-center text-primary-300"></i> Perjalanan Saya
                </a>
                <a href="/pegawai/create" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all">
                    <i class="fas fa-plus-circle w-5 text-center text-primary-300"></i> Ajukan Perjalanan
                </a>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
                <a href="/admin" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?= current_url() === base_url('admin') ? 'active' : '' ?>">
                    <i class="fas fa-tasks w-5 text-center text-primary-300"></i> Proses Pengajuan
                </a>
                <a href="/admin/arsip" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?= str_contains(current_url(), '/arsip') ? 'active' : '' ?>">
                    <i class="fas fa-archive w-5 text-center text-primary-300"></i> Arsip Perjalanan
                </a>
                <a href="/admin/kendaraan" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?= str_contains(current_url(), '/kendaraan') ? 'active' : '' ?>">
                    <i class="fas fa-car w-5 text-center text-primary-300"></i> Data Kendaraan
                </a>
                <a href="/admin/jenis-biaya" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all <?= str_contains(current_url(), '/jenis-biaya') ? 'active' : '' ?>">
                    <i class="fas fa-tags w-5 text-center text-primary-300"></i> Master Jenis Biaya
                </a>
            <?php endif; ?>

            <?php if ($role === 'direktur'): ?>
                <a href="/direktur" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all">
                    <i class="fas fa-check-circle w-5 text-center text-primary-300"></i> Persetujuan
                </a>
            <?php endif; ?>

            <?php if ($role === 'keuangan'): ?>
                <a href="/keuangan" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all">
                    <i class="fas fa-wallet w-5 text-center text-primary-300"></i> Dana Operasional
                </a>
            <?php endif; ?>
        </nav>

        <!-- Logout -->
        <div class="px-4 py-4 border-t border-primary-700">
            <a href="/logout" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-red-300 hover:bg-red-900/30 transition-all">
                <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
            </a>
        </div>
    </aside>

    <!-- Main content -->
    <div class="ml-64 flex-1 flex flex-col">
        <!-- Topbar -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between shadow-sm sticky top-0 z-20">
            <h2 class="text-lg font-semibold text-gray-800"><?= isset($title) ? esc($title) : 'Dashboard' ?></h2>
            <div class="flex items-center gap-4 text-sm text-gray-500">
                <i class="fas fa-calendar"></i>
                <?= date('l, d F Y') ?>
            </div>
        </header>

        <!-- Alerts -->
        <div class="px-6 pt-4">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="fade-in flex items-center gap-3 bg-primary-50 border border-primary-200 text-primary-800 px-4 py-3 rounded-xl mb-2">
                    <i class="fas fa-check-circle text-primary-500"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="fade-in flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-2">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="fade-in bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-2">
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <!-- Page content -->
        <main class="p-6 flex-1">
            <?= $this->renderSection('content') ?>
        </main>

        <footer class="text-center text-xs text-gray-400 py-4 border-t border-gray-100">
            &copy; <?= date('Y') ?> SPPD Jaldin &mdash; Sistem Perjalanan Dinas
        </footer>
    </div>
</div>

<script>
// Auto-dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 5000);
</script>
</body>
</html>
