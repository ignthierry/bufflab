<?php
/**
 * CMS Login Page
 */

require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT * FROM `users` WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_id'] = $user['id'];
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error = 'Username atau Password salah.';
            }
        } catch (PDOException $e) {
            $error = 'Koneksi database bermasalah: ' . $e->getMessage();
        }
    } else {
        $error = 'Username dan Password wajib diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Bufflab Clean Shoes</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #090d16;
            background-image: 
                radial-gradient(at 0% 0%, hsla(220, 100%, 10%, 1) 0, transparent 50%), 
                radial-gradient(at 100% 100%, hsla(222, 100%, 8%, 1) 0, transparent 50%);
        }
        .font-headline {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 p-8 shadow-2xl relative overflow-hidden">
        <!-- Accent Glow -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-blue-500/20 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <!-- Brand Logo -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/20 mb-3">
                    <span class="material-symbols-outlined !text-3xl font-bold">dry_cleaning</span>
                </div>
                <h1 class="font-headline text-2xl font-black tracking-tight text-white">BUFFLAB</h1>
                <p class="text-blue-400 font-headline text-xs font-bold tracking-[0.2em] uppercase mt-1">Clean Shoes CMS</p>
            </div>

            <h2 class="text-xl font-bold text-white mb-6 text-center">Masuk ke Panel Admin</h2>

            <?php if (!empty($error)): ?>
                <div class="bg-red-500/10 border border-red-500/30 text-red-200 text-sm px-4 py-3 rounded-xl mb-6 flex items-start gap-2">
                    <span class="material-symbols-outlined text-red-400 !text-xl mt-0.5">error</span>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-5">
                <div>
                    <label for="username" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Username</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 !text-xl">person</span>
                        <input type="text" name="username" id="username" required
                               class="w-full bg-white/5 border border-white/10 rounded-2xl py-3.5 pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"
                               placeholder="Masukkan username Anda">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 !text-xl">lock</span>
                        <input type="password" name="password" id="password" required
                               class="w-full bg-white/5 border border-white/10 rounded-2xl py-3.5 pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all text-sm"
                               placeholder="Masukkan password Anda">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white py-4 rounded-2xl font-headline font-bold text-sm shadow-lg shadow-blue-600/20 transition-all mt-4 flex items-center justify-center gap-2">
                    Masuk Sekarang <span class="material-symbols-outlined text-sm">login</span>
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="../index.php" class="text-xs text-gray-400 hover:text-white transition-colors inline-flex items-center gap-1">
                    <span class="material-symbols-outlined !text-sm">arrow_back</span> Kembali ke Landingpage
                </a>
            </div>
        </div>
    </div>

</body>
</html>
