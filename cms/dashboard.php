<?php
/**
 * CMS Dashboard
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

check_auth();

$pdo = getDB();
$successMsg = '';
$errorMsg = '';
$activeTab = $_GET['tab'] ?? 'general';

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ──────────────────────────────────────────────
    // ACTION: update_general_settings
    // ──────────────────────────────────────────────
    if ($action === 'update_general') {
        $hero_title = trim($_POST['hero_title'] ?? '');
        $hero_subtitle = trim($_POST['hero_subtitle'] ?? '');
        $about_heading = trim($_POST['about_heading'] ?? '');
        $about_p1 = trim($_POST['about_p1'] ?? '');
        $about_p2 = trim($_POST['about_p2'] ?? '');
        $whatsapp_number = trim($_POST['whatsapp_number'] ?? '');
        $whatsapp_message = trim($_POST['whatsapp_message'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $schedule = trim($_POST['schedule'] ?? '');
        $instagram_url = trim($_POST['instagram_url'] ?? '');
        $tiktok_url = trim($_POST['tiktok_url'] ?? '');

        if (!empty($hero_title) && !empty($hero_subtitle) && !empty($about_p1) && !empty($about_p2) && !empty($whatsapp_number)) {
            try {
                $stmt = $pdo->prepare("UPDATE `landing_settings` SET 
                    hero_title = ?, hero_subtitle = ?, about_heading = ?, about_p1 = ?, about_p2 = ?,
                    whatsapp_number = ?, whatsapp_message = ?, address = ?, schedule = ?,
                    instagram_url = ?, tiktok_url = ? WHERE id = 1");
                $stmt->execute([
                    $hero_title, $hero_subtitle, $about_heading, $about_p1, $about_p2,
                    $whatsapp_number, $whatsapp_message, $address, $schedule,
                    $instagram_url, $tiktok_url
                ]);
                $successMsg = 'Pengaturan umum berhasil disimpan!';
            } catch (PDOException $e) {
                $errorMsg = 'Gagal menyimpan data: ' . $e->getMessage();
            }
        } else {
            $errorMsg = 'Kolom penting (Hero, Tentang Kami, WhatsApp) tidak boleh kosong.';
        }
    }

    // ──────────────────────────────────────────────
    // ACTION: add_service
    // ──────────────────────────────────────────────
    elseif ($action === 'add_service') {
        $name = trim($_POST['name'] ?? '');
        $subname = trim($_POST['subname'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = intval($_POST['price'] ?? 0);
        $icon_name = trim($_POST['icon_name'] ?? 'bolt');
        $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;
        $sort_order = intval($_POST['sort_order'] ?? 0);

        if (!empty($name) && !empty($description) && $price > 0) {
            try {
                $stmt = $pdo->prepare("INSERT INTO `services` (name, subname, description, price, icon_name, is_best_seller, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $subname, $description, $price, $icon_name, $is_best_seller, $sort_order]);
                $successMsg = 'Layanan berhasil ditambahkan!';
            } catch (PDOException $e) {
                $errorMsg = 'Gagal menambahkan layanan: ' . $e->getMessage();
            }
        } else {
            $errorMsg = 'Nama, deskripsi, dan harga layanan harus valid.';
        }
    }

    // ──────────────────────────────────────────────
    // ACTION: edit_service
    // ──────────────────────────────────────────────
    elseif ($action === 'edit_service') {
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $subname = trim($_POST['subname'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = intval($_POST['price'] ?? 0);
        $icon_name = trim($_POST['icon_name'] ?? 'bolt');
        $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;
        $sort_order = intval($_POST['sort_order'] ?? 0);

        if ($id > 0 && !empty($name) && !empty($description) && $price > 0) {
            try {
                $stmt = $pdo->prepare("UPDATE `services` SET name = ?, subname = ?, description = ?, price = ?, icon_name = ?, is_best_seller = ?, sort_order = ? WHERE id = ?");
                $stmt->execute([$name, $subname, $description, $price, $icon_name, $is_best_seller, $sort_order, $id]);
                $successMsg = 'Layanan berhasil diperbarui!';
            } catch (PDOException $e) {
                $errorMsg = 'Gagal memperbarui layanan: ' . $e->getMessage();
            }
        } else {
            $errorMsg = 'Data layanan tidak valid.';
        }
    }

    // ──────────────────────────────────────────────
    // ACTION: delete_service
    // ──────────────────────────────────────────────
    elseif ($action === 'delete_service') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM `services` WHERE id = ?");
                $stmt->execute([$id]);
                $successMsg = 'Layanan berhasil dihapus!';
            } catch (PDOException $e) {
                $errorMsg = 'Gagal menghapus layanan: ' . $e->getMessage();
            }
        }
    }

    // ──────────────────────────────────────────────
    // ACTION: add_testimonial
    // ──────────────────────────────────────────────
    elseif ($action === 'add_testimonial') {
        $name = trim($_POST['name'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $rating = intval($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');

        if (!empty($name) && !empty($comment) && $rating >= 1 && $rating <= 5) {
            try {
                $stmt = $pdo->prepare("INSERT INTO `testimonials` (name, role, rating, comment) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $role, $rating, $comment]);
                $successMsg = 'Testimoni berhasil ditambahkan!';
            } catch (PDOException $e) {
                $errorMsg = 'Gagal menambahkan testimoni: ' . $e->getMessage();
            }
        } else {
            $errorMsg = 'Nama, ulasan, dan rating harus valid.';
        }
    }

    // ──────────────────────────────────────────────
    // ACTION: delete_testimonial
    // ──────────────────────────────────────────────
    elseif ($action === 'delete_testimonial') {
        $id = intval($_POST['id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM `testimonials` WHERE id = ?");
                $stmt->execute([$id]);
                $successMsg = 'Testimoni berhasil dihapus!';
            } catch (PDOException $e) {
                $errorMsg = 'Gagal menghapus testimoni: ' . $e->getMessage();
            }
        }
    }

    // ──────────────────────────────────────────────
    // ACTION: change_password
    // ──────────────────────────────────────────────
    elseif ($action === 'change_password') {
        $old_pass = trim($_POST['old_password'] ?? '');
        $new_pass = trim($_POST['new_password'] ?? '');
        $confirm_pass = trim($_POST['confirm_password'] ?? '');

        if (!empty($old_pass) && !empty($new_pass) && !empty($confirm_pass)) {
            if ($new_pass === $confirm_pass) {
                try {
                    // Get current user password hash
                    $stmt = $pdo->prepare("SELECT password_hash FROM `users` WHERE id = ?");
                    $stmt->execute([$_SESSION['admin_id']]);
                    $user_hash = $stmt->fetchColumn();

                    if ($user_hash && password_verify($old_pass, $user_hash)) {
                        $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                        $update = $pdo->prepare("UPDATE `users` SET password_hash = ? WHERE id = ?");
                        $update->execute([$new_hash, $_SESSION['admin_id']]);
                        $successMsg = 'Password berhasil diubah!';
                    } else {
                        $errorMsg = 'Password lama salah.';
                    }
                } catch (PDOException $e) {
                    $errorMsg = 'Gagal mengubah password: ' . $e->getMessage();
                }
            } else {
                $errorMsg = 'Password baru dan konfirmasi password tidak cocok.';
            }
        } else {
            $errorMsg = 'Wajib mengisi semua kolom password.';
        }
    }
}

// Fetch Current Settings, Services & Testimonials
try {
    $settings = $pdo->query("SELECT * FROM `landing_settings` WHERE id = 1")->fetch();
    $services = $pdo->query("SELECT * FROM `services` ORDER BY sort_order ASC, id ASC")->fetchAll();
    $testimonials = $pdo->query("SELECT * FROM `testimonials` ORDER BY id DESC")->fetchAll();
} catch (PDOException $e) {
    die("<pre style='padding:2rem;color:red;'>Gagal memuat database: " . $e->getMessage() . "\nSilakan jalankan db_setup.php terlebih dahulu.</pre>");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Dashboard | Bufflab Clean Shoes</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .font-headline {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-slate-900 text-white flex flex-col shrink-0">
        <!-- Brand -->
        <div class="p-6 border-b border-slate-800 flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-md">
                <span class="material-symbols-outlined !text-xl font-bold">dry_cleaning</span>
            </div>
            <div class="flex flex-col leading-none">
                <span class="font-headline text-md font-black tracking-tight">BUFFLAB</span>
                <span class="text-[9px] tracking-[0.2em] font-bold text-blue-400 uppercase mt-0.5">Control Panel</span>
            </div>
        </div>

        <!-- User Profile Info -->
        <div class="px-6 py-4 bg-slate-950/40 border-b border-slate-800 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-slate-300 font-bold">
                <?= strtoupper(substr($_SESSION['admin_username'], 0, 1)) ?>
            </div>
            <div class="flex flex-col text-sm">
                <span class="font-semibold text-slate-200"><?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                <span class="text-xs text-slate-500">Administrator</span>
            </div>
        </div>

        <!-- Nav Links -->
        <nav class="flex-grow p-4 space-y-1">
            <a href="?tab=general" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $activeTab === 'general' ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">settings_suggest</span>
                <span>Pengaturan Umum</span>
            </a>
            <a href="?tab=services" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $activeTab === 'services' ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">shopping_basket</span>
                <span>Kelola Layanan</span>
            </a>
            <a href="?tab=testimonials" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $activeTab === 'testimonials' ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">reviews</span>
                <span>Kelola Testimoni</span>
            </a>
            <a href="?tab=password" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?= $activeTab === 'password' ? 'bg-blue-600 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                <span class="material-symbols-outlined">lock_reset</span>
                <span>Keamanan</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-800 space-y-2">
            <a href="../index.php" target="_blank" class="flex items-center justify-center gap-2 w-full bg-slate-800 hover:bg-slate-700 text-slate-300 py-2.5 rounded-xl text-xs font-semibold transition-colors">
                <span class="material-symbols-outlined !text-sm">open_in_new</span> Lihat Website
            </a>
            <a href="logout.php" class="flex items-center justify-center gap-2 w-full bg-red-900/40 hover:bg-red-900/60 text-red-200 py-2.5 rounded-xl text-xs font-semibold transition-colors">
                <span class="material-symbols-outlined !text-sm">logout</span> Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-grow flex flex-col min-w-0">
        <!-- Header -->
        <header class="bg-white border-b border-slate-200 py-4 px-6 md:px-8 flex justify-between items-center shadow-sm">
            <h1 class="text-xl font-bold text-slate-800 font-headline">
                <?php 
                if ($activeTab === 'services') echo 'Kelola Layanan Shoecare';
                elseif ($activeTab === 'testimonials') echo 'Kelola Testimoni & Review';
                elseif ($activeTab === 'password') echo 'Keamanan Akun';
                else echo 'Pengaturan Landing Page';
                ?>
            </h1>
            <span class="text-xs text-slate-400 font-medium">Server Local Time: <?= date('d M Y, H:i') ?></span>
        </header>

        <!-- Body -->
        <div class="flex-grow p-6 md:p-8 max-w-5xl w-full mx-auto">
            <!-- Alert Messages -->
            <?php if (!empty($successMsg)): ?>
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 text-sm px-4 py-3 rounded-2xl mb-6 flex items-start gap-2 animate-fade-in">
                    <span class="material-symbols-outlined text-emerald-600 !text-xl mt-0.5">check_circle</span>
                    <span><?= htmlspecialchars($successMsg) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($errorMsg)): ?>
                <div class="bg-red-500/10 border border-red-500/30 text-red-800 text-sm px-4 py-3 rounded-2xl mb-6 flex items-start gap-2 animate-fade-in">
                    <span class="material-symbols-outlined text-red-600 !text-xl mt-0.5">error</span>
                    <span><?= htmlspecialchars($errorMsg) ?></span>
                </div>
            <?php endif; ?>

            <!-- ──────────────────────────────────────────────
                 TAB: GENERAL SETTINGS
                 ────────────────────────────────────────────── -->
            <?php if ($activeTab === 'general'): ?>
                <form action="dashboard.php?tab=general" method="POST" class="space-y-6">
                    <input type="hidden" name="action" value="update_general">

                    <!-- Hero Copy Section -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2 border-b pb-2 mb-4">
                            <span class="material-symbols-outlined text-blue-600">home</span> Hero Section
                        </h2>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Headline Utama (Hero Title)</label>
                            <input type="text" name="hero_title" value="<?= htmlspecialchars($settings['hero_title']) ?>" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Sub-Headline (Hero Subtitle)</label>
                            <textarea name="hero_subtitle" rows="3" required
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"><?= htmlspecialchars($settings['hero_subtitle']) ?></textarea>
                        </div>
                    </div>

                    <!-- Tentang Kami Section -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2 border-b pb-2 mb-4">
                            <span class="material-symbols-outlined text-blue-600">info</span> Section Tentang Kami
                        </h2>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Section (Default: Tentang Kami)</label>
                            <input type="text" name="about_heading" value="<?= htmlspecialchars($settings['about_heading']) ?>" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Paragraf 1 (Latar Belakang & Identitas)</label>
                            <textarea name="about_p1" rows="4" required
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"><?= htmlspecialchars($settings['about_p1']) ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Paragraf 2 (Teknik & Komitmen)</label>
                            <textarea name="about_p2" rows="4" required
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"><?= htmlspecialchars($settings['about_p2']) ?></textarea>
                        </div>
                    </div>

                    <!-- Kontak & Alamat Section -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2 border-b pb-2 mb-4">
                            <span class="material-symbols-outlined text-blue-600">contacts</span> Kontak, Alamat & Jam Operasional
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor WhatsApp Bisnis</label>
                                <input type="text" name="whatsapp_number" value="<?= htmlspecialchars($settings['whatsapp_number']) ?>" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"
                                       placeholder="Contoh: 6285123456789">
                                <p class="text-[10px] text-slate-400 mt-1">Harus diawali kode negara tanpa tanda tambah (+) atau spasi. Contoh: 62851...</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jadwal / Jam Kerja</label>
                                <input type="text" name="schedule" value="<?= htmlspecialchars($settings['schedule']) ?>" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"
                                       placeholder="Contoh: Senin - Sabtu: 09:00 - 18:00 WIB">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pesan Awal WhatsApp (Prefill Text)</label>
                            <textarea name="whatsapp_message" rows="2" required
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"><?= htmlspecialchars($settings['whatsapp_message']) ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Fisik / Lab</label>
                            <textarea name="address" rows="2" required
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"><?= htmlspecialchars($settings['address']) ?></textarea>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2 border-b pb-2 mb-4">
                            <span class="material-symbols-outlined text-blue-600">share</span> Tautan Sosial Media
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Link Instagram</label>
                                <input type="url" name="instagram_url" value="<?= htmlspecialchars($settings['instagram_url']) ?>"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Link TikTok</label>
                                <input type="url" name="tiktok_url" value="<?= htmlspecialchars($settings['tiktok_url']) ?>"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold font-headline text-sm px-6 py-3.5 rounded-xl shadow-md transition-all flex items-center gap-2">
                            Simpan Perubahan <span class="material-symbols-outlined text-sm">save</span>
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <!-- ──────────────────────────────────────────────
                 TAB: SERVICES MANAGER
                 ────────────────────────────────────────────── -->
            <?php if ($activeTab === 'services'): ?>
                <div class="space-y-8">
                    
                    <!-- Add Service Button (Toggle Collapse) -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="bg-slate-50 border-b border-slate-200 p-5 flex justify-between items-center cursor-pointer" onclick="toggleAddForm()">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600">add_circle</span> Tambah Layanan Baru
                            </h3>
                            <span class="material-symbols-outlined text-slate-400" id="add-toggle-icon">expand_more</span>
                        </div>

                        <form id="add-service-form" action="dashboard.php?tab=services" method="POST" class="p-6 space-y-4 hidden border-t border-slate-200">
                            <input type="hidden" name="action" value="add_service">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Layanan</label>
                                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm" placeholder="Contoh: Fast Clean">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Subname / Deskripsi Pendek</label>
                                    <input type="text" name="subname" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm" placeholder="Contoh: Quick Wash">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga (Rp)</label>
                                <input type="number" name="price" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm" placeholder="Contoh: 30000">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Lengkap Layanan</label>
                                <textarea name="description" rows="3" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm" placeholder="Jelaskan detail tindakan perawatan..."></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Ikon (Material Symbol)</label>
                                    <select name="icon_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm">
                                        <option value="bolt">Lightning (bolt)</option>
                                        <option value="sanitizer">Wash Clean (sanitizer)</option>
                                        <option value="diamond">Premium Diamond (diamond)</option>
                                        <option value="wb_sunny">Sun/Unyellowing (wb_sunny)</option>
                                        <option value="format_paint">Paint/Repaint (format_paint)</option>
                                        <option value="verified">Shield Verified (verified)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Urutan Tampil (Sort Order)</label>
                                    <input type="number" name="sort_order" value="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm">
                                </div>
                                <div class="flex items-center pt-6 pl-2">
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="is_best_seller" value="1" class="rounded text-blue-600 focus:ring-blue-500 border-slate-300 w-5 h-5">
                                        <span class="text-sm text-slate-700 font-semibold">Tandai Best Seller</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold font-headline text-xs px-5 py-3 rounded-xl shadow-md transition-all flex items-center gap-1">
                                    Simpan Layanan <span class="material-symbols-outlined text-sm">check</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Services Table/List -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600">list</span> Daftar Layanan Terdaftar
                        </h3>

                        <div class="space-y-4">
                            <?php foreach ($services as $srv): ?>
                                <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-all hover:border-slate-200">
                                    <div class="flex items-start gap-4">
                                        <!-- Service Icon -->
                                        <div class="w-10 h-10 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined !text-xl"><?= htmlspecialchars($srv['icon_name']) ?></span>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <h4 class="font-bold text-slate-800 text-base"><?= htmlspecialchars($srv['name']) ?></h4>
                                                <?php if ($srv['subname']): ?>
                                                    <span class="text-xs bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md font-medium"><?= htmlspecialchars($srv['subname']) ?></span>
                                                <?php endif; ?>
                                                <?php if ($srv['is_best_seller']): ?>
                                                    <span class="text-[10px] bg-amber-500 text-white px-2 py-0.5 rounded-full font-bold uppercase tracking-wider shadow-sm shadow-amber-500/10">Best Seller</span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-xs text-slate-500 mt-1 max-w-xl leading-relaxed"><?= htmlspecialchars($srv['description']) ?></p>
                                            <div class="mt-2 text-sm">
                                                Harga: <span class="font-extrabold text-blue-600">Rp <?= number_format($srv['price'], 0, ',', '.') ?></span>
                                                <span class="text-slate-300 mx-2">|</span>
                                                Urutan: <span class="font-semibold text-slate-600"><?= htmlspecialchars($srv['sort_order']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Actions -->
                                    <div class="flex items-center gap-2 self-end md:self-center">
                                        <!-- Edit Modal Trigger -->
                                        <button onclick="openEditModal(<?= htmlspecialchars(json_encode($srv)) ?>)" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-xl text-xs font-semibold flex items-center gap-1 transition-colors">
                                            <span class="material-symbols-outlined !text-sm">edit</span> Edit
                                        </button>
                                        <!-- Delete Trigger -->
                                        <form action="dashboard.php?tab=services" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                                            <input type="hidden" name="action" value="delete_service">
                                            <input type="hidden" name="id" value="<?= $srv['id'] ?>">
                                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-xl text-xs font-semibold flex items-center gap-1 transition-colors">
                                                <span class="material-symbols-outlined !text-sm">delete</span> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit Service Modal Dialog (Modal Overlay) -->
                <div id="edit-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 items-center justify-center hidden p-4">
                    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 flex flex-col gap-4 relative animate-scale-up">
                        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                            <span class="material-symbols-outlined !text-2xl">close</span>
                        </button>
                        
                        <h3 class="font-headline font-bold text-slate-800 text-lg border-b pb-2 mb-2">Edit Layanan Shoecare</h3>
                        
                        <form action="dashboard.php?tab=services" method="POST" class="space-y-4">
                            <input type="hidden" name="action" value="edit_service">
                            <input type="hidden" name="id" id="edit-id">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Layanan</label>
                                    <input type="text" name="name" id="edit-name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Subname</label>
                                    <input type="text" name="subname" id="edit-subname" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Harga (Rp)</label>
                                <input type="number" name="price" id="edit-price" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Layanan</label>
                                <textarea name="description" id="edit-description" rows="3" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 text-sm"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ikon</label>
                                    <select name="icon_name" id="edit-icon" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 text-sm">
                                        <option value="bolt">Lightning (bolt)</option>
                                        <option value="sanitizer">Wash Clean (sanitizer)</option>
                                        <option value="diamond">Premium Diamond (diamond)</option>
                                        <option value="wb_sunny">Sun/Unyellowing (wb_sunny)</option>
                                        <option value="format_paint">Paint/Repaint (format_paint)</option>
                                        <option value="verified">Shield Verified (verified)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Urutan Tampil</label>
                                    <input type="number" name="sort_order" id="edit-sort" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 text-sm">
                                </div>
                                <div class="flex items-center pt-6 pl-2">
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input type="checkbox" name="is_best_seller" id="edit-bestseller" value="1" class="rounded text-blue-600 border-slate-300 w-5 h-5">
                                        <span class="text-sm text-slate-700 font-semibold">Best Seller</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-2 border-t mt-4">
                                <button type="button" onclick="closeEditModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-3 rounded-xl text-xs font-semibold">Batal</button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl text-xs font-semibold flex items-center gap-1">Simpan <span class="material-symbols-outlined !text-sm">save</span></button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    function toggleAddForm() {
                        const form = document.getElementById('add-service-form');
                        const icon = document.getElementById('add-toggle-icon');
                        if (form.classList.contains('hidden')) {
                            form.classList.remove('hidden');
                            icon.textContent = 'expand_less';
                        } else {
                            form.classList.add('hidden');
                            icon.textContent = 'expand_more';
                        }
                    }

                    function openEditModal(srv) {
                        document.getElementById('edit-id').value = srv.id;
                        document.getElementById('edit-name').value = srv.name;
                        document.getElementById('edit-subname').value = srv.subname;
                        document.getElementById('edit-price').value = srv.price;
                        document.getElementById('edit-description').value = srv.description;
                        document.getElementById('edit-icon').value = srv.icon_name;
                        document.getElementById('edit-sort').value = srv.sort_order;
                        document.getElementById('edit-bestseller').checked = parseInt(srv.is_best_seller) === 1;

                        const modal = document.getElementById('edit-modal');
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }

                    function closeEditModal() {
                        const modal = document.getElementById('edit-modal');
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                </script>
            <?php endif; ?>

            <!-- ──────────────────────────────────────────────
                 TAB: TESTIMONIALS MANAGER
                 ────────────────────────────────────────────── -->
            <?php if ($activeTab === 'testimonials'): ?>
                <div class="space-y-8">
                    
                    <!-- Add Testimonial Card (Toggle Collapse) -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="bg-slate-50 border-b border-slate-200 p-5 flex justify-between items-center cursor-pointer" onclick="toggleAddTestimonialForm()">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600">add_circle</span> Tambah Testimoni Baru
                            </h3>
                            <span class="material-symbols-outlined text-slate-400" id="add-tst-toggle-icon">expand_more</span>
                        </div>

                        <form id="add-testimonial-form" action="dashboard.php?tab=testimonials" method="POST" class="p-6 space-y-4 hidden border-t border-slate-200">
                            <input type="hidden" name="action" value="add_testimonial">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Pelanggan</label>
                                    <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm" placeholder="Contoh: Sarah Amelia">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rating (1-5 Bintang)</label>
                                    <select name="rating" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm">
                                        <option value="5">⭐⭐⭐⭐⭐ (5 Bintang)</option>
                                        <option value="4">⭐⭐⭐⭐ (4 Bintang)</option>
                                        <option value="3">⭐⭐⭐ (3 Bintang)</option>
                                        <option value="2">⭐⭐ (2 Bintang)</option>
                                        <option value="1">⭐ (1 Bintang)</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keterangan / Peran / Asal (Role/Location)</label>
                                <input type="text" name="role" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm" placeholder="Contoh: Sneakerhead, Surabaya atau via Instagram">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ulasan / Review (Comment)</label>
                                <textarea name="comment" rows="4" required class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm" placeholder="Tulis komentar ulasan pelanggan dari Instagram..."></textarea>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold font-headline text-xs px-5 py-3 rounded-xl shadow-md transition-all flex items-center gap-1">
                                    Simpan Testimoni <span class="material-symbols-outlined text-sm">check</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Testimonials List -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600">reviews</span> Daftar Ulasan & Testimoni
                        </h3>

                        <div class="space-y-4">
                            <?php if (empty($testimonials)): ?>
                                <p class="text-sm text-slate-500 italic py-4 text-center">Belum ada testimoni terdaftar.</p>
                            <?php else: ?>
                                <?php foreach ($testimonials as $tst): ?>
                                    <div class="p-5 border border-slate-100 rounded-2xl bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-all hover:border-slate-200">
                                        <div class="flex-grow">
                                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                                <h4 class="font-bold text-slate-800 text-base"><?= htmlspecialchars($tst['name']) ?></h4>
                                                <?php if ($tst['role']): ?>
                                                    <span class="text-xs bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md font-medium"><?= htmlspecialchars($tst['role']) ?></span>
                                                <?php endif; ?>
                                                <div class="flex text-amber-500 gap-0.5 ml-2">
                                                    <?php for ($i = 0; $i < $tst['rating']; $i++): ?>
                                                        <span class="material-symbols-outlined !text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <p class="text-xs text-slate-500 italic leading-relaxed mt-2">"<?= htmlspecialchars($tst['comment']) ?>"</p>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex items-center gap-2 shrink-0 self-end md:self-center">
                                            <form action="dashboard.php?tab=testimonials" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?')">
                                                <input type="hidden" name="action" value="delete_testimonial">
                                                <input type="hidden" name="id" value="<?= $tst['id'] ?>">
                                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-xl text-xs font-semibold flex items-center gap-1 transition-colors">
                                                    <span class="material-symbols-outlined !text-sm">delete</span> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <script>
                    function toggleAddTestimonialForm() {
                        const form = document.getElementById('add-testimonial-form');
                        const icon = document.getElementById('add-tst-toggle-icon');
                        if (form.classList.contains('hidden')) {
                            form.classList.remove('hidden');
                            icon.textContent = 'expand_less';
                        } else {
                            form.classList.add('hidden');
                            icon.textContent = 'expand_more';
                        }
                    }
                </script>
            <?php endif; ?>

            <!-- ──────────────────────────────────────────────
                 TAB: SECURITY SETTINGS
                 ────────────────────────────────────────────── -->
            <?php if ($activeTab === 'password'): ?>
                <form action="dashboard.php?tab=password" method="POST" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm max-w-xl space-y-4">
                    <input type="hidden" name="action" value="change_password">
                    
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2 border-b pb-2 mb-4">
                        <span class="material-symbols-outlined text-blue-600">lock</span> Ubah Password Administrator
                    </h2>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Saat Ini (Lama)</label>
                        <input type="password" name="old_password" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3.5 px-4 text-slate-800 focus:outline-none focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Baru</label>
                        <input type="password" name="new_password" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3.5 px-4 text-slate-800 focus:outline-none focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3.5 px-4 text-slate-800 focus:outline-none focus:border-blue-500 text-sm">
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold font-headline text-xs px-5 py-3 rounded-xl shadow-md transition-all flex items-center gap-1">
                            Ubah Password <span class="material-symbols-outlined text-sm">key</span>
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>

</body>
</html>
