<?php
/**
 * Database Setup Script for Bufflab Clean Shoes
 * Creates necessary tables and seeds initial copywriting content.
 */

require_once __DIR__ . '/config.php';

echo "<pre style='background:#0f172a;color:#38bdf8;padding:2rem;font-family:monospace;font-size:14px;border-radius:12px;box-shadow:0 10px 15px -3px rgba(0,0,0,0.3);line-height:1.5;'>";
echo "╔══════════════════════════════════════════════╗\n";
echo "║   BUFFLAB CLEAN SHOES — DATABASE SETUP       ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

try {
    $pdo = getDB();
    echo "✓ Connected to MySQL database successfully!\n\n";

    // ──────────────────────────────────────────────
    // TABLE: users
    // ──────────────────────────────────────────────
    echo "Creating 'users' table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password_hash` VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Ensure default user exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM `users`");
    if ($stmt->fetchColumn() == 0) {
        $username = 'luna';
        $password = 'N2145tb@';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $insert = $pdo->prepare("INSERT INTO `users` (username, password_hash) VALUES (?, ?)");
        $insert->execute([$username, $hash]);
        echo "  → Default administrator user created!\n";
        echo "    Username: <strong>$username</strong>\n";
        echo "    Password: <strong>$password</strong> (Please change after logging in!)\n";
    } else {
        echo "  → Users table already populated.\n";
    }
    echo "\n";

    // ──────────────────────────────────────────────
    // TABLE: landing_settings
    // ──────────────────────────────────────────────
    echo "Creating 'landing_settings' table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `landing_settings` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `hero_title` TEXT NOT NULL,
        `hero_subtitle` TEXT NOT NULL,
        `about_heading` VARCHAR(255) DEFAULT 'Tentang Kami',
        `about_p1` TEXT NOT NULL,
        `about_p2` TEXT NOT NULL,
        `whatsapp_number` VARCHAR(50) NOT NULL,
        `whatsapp_message` TEXT NOT NULL,
        `address` TEXT NOT NULL,
        `schedule` VARCHAR(255) NOT NULL,
        `instagram_url` VARCHAR(255) DEFAULT '#',
        `tiktok_url` VARCHAR(255) DEFAULT '#',
        `tos_url` VARCHAR(255) DEFAULT '#',
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Seed default landing settings
    $stmt = $pdo->query("SELECT COUNT(*) FROM `landing_settings`");
    if ($stmt->fetchColumn() == 0) {
        $insert = $pdo->prepare("INSERT INTO `landing_settings` (
            hero_title, hero_subtitle, about_heading, about_p1, about_p2, 
            whatsapp_number, whatsapp_message, address, schedule, instagram_url, tiktok_url, tos_url
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $insert->execute([
            'Step Up Your Game with Clean Kicks',
            'Kembalikan kesegaran sepatu favorit Anda dengan layanan pembersihan profesional yang mendalam dan presisi.',
            'Tentang Kami',
            'Bufflab Clean Shoes adalah penyedia layanan perawatan dan pembersihan sepatu profesional (shoecare) yang berbasis di Surabaya. Kami lahir dari pemahaman bahwa sepatu bukan sekadar alas kaki, melainkan bagian dari identitas, rasa percaya diri, dan investasi penampilan Anda.',
            'Dengan menggunakan teknik pencucian manual (hand-wash) yang presisi, cairan pembersih khusus (premium cleaner) yang aman untuk berbagai jenis bahan, serta tenaga kerja yang terlatih, kami berkomitmen untuk mengembalikan kesegaran sepatu kesayangan Anda tanpa merusak serat materialnya.',
            '6287776666680',
            'Halo Bufflab Clean Shoes Surabaya! Saya tertarik dengan layanan shoecare Anda dan ingin bertanya lebih lanjut.',
            'Jl. Bronggalan 2G No.51, Pacar Kembang, Kec. Tambaksari, Surabaya, Jawa Timur 60132, Indonesia',
            'Senin - Sabtu: 09:00 - 18:00 WIB',
            'https://www.instagram.com/bufflabcleanshoes',
            'https://tiktok.com/@bufflab.clean',
            '#'
        ]);
        echo "  → Seeded default copywriting and settings successfully!\n";
    } else {
        echo "  → Landing settings table already populated.\n";
    }
    echo "\n";

    // ──────────────────────────────────────────────
    // TABLE: services
    // ──────────────────────────────────────────────
    echo "Creating 'services' table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `services` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `subname` VARCHAR(100) DEFAULT '',
        `description` TEXT NOT NULL,
        `price` INT NOT NULL,
        `icon_name` VARCHAR(50) NOT NULL DEFAULT 'bolt',
        `is_best_seller` TINYINT(1) DEFAULT 0,
        `sort_order` INT DEFAULT 0,
        `is_active` TINYINT(1) DEFAULT 1,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Seed default services
    $stmt = $pdo->query("SELECT COUNT(*) FROM `services`");
    if ($stmt->fetchColumn() == 0) {
        $insert = $pdo->prepare("INSERT INTO `services` (name, subname, description, price, icon_name, is_best_seller, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $defaultServices = [
            [
                'Fast Clean', 
                'Quick Wash', 
                'Pembersihan cepat pada area midsole dan upper, cocok untuk Anda yang memiliki mobilitas tinggi.', 
                30000, 
                'bolt', 
                0, 
                1
            ],
            [
                'Deep Clean', 
                'Kebersihan Total', 
                'Pembersihan menyeluruh secara intensif mulai dari outsole, midsole, upper, insole, hingga tali sepatu.', 
                50000, 
                'sanitizer', 
                1, 
                2
            ],
            [
                'Premium Treatment', 
                'Suede, Leather, Nubuck', 
                'Perawatan khusus untuk sepatu berbahan sensitif (Suede, Leather, Nubuck) menggunakan formula premium.', 
                75000, 
                'diamond', 
                0, 
                3
            ],
            [
                'Unyellowing', 
                'Anti-Oxidation', 
                'Treatment khusus untuk menghilangkan efek menguning pada midsole sepatu akibat oksidasi.', 
                45000, 
                'wb_sunny', 
                0, 
                4
            ],
            [
                'Repaint / Recolor', 
                'Restore Color', 
                'Mengembalikan warna sepatu yang sudah pudar agar tampak seperti baru kembali.', 
                120000, 
                'format_paint', 
                0, 
                5
            ]
        ];

        foreach ($defaultServices as $srv) {
            $insert->execute($srv);
        }
        echo "  → Seeded " . count($defaultServices) . " default shoecare services successfully!\n";
    } else {
        echo "  → Services table already populated.\n";
    }

    // ──────────────────────────────────────────────
    // TABLE: testimonials
    // ──────────────────────────────────────────────
    echo "Creating 'testimonials' table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `testimonials` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `role` VARCHAR(100) DEFAULT '',
        `rating` INT NOT NULL DEFAULT 5,
        `comment` TEXT NOT NULL,
        `is_active` TINYINT(1) DEFAULT 1,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Seed default testimonials
    $stmt = $pdo->query("SELECT COUNT(*) FROM `testimonials`");
    if ($stmt->fetchColumn() == 0) {
        $insert = $pdo->prepare("INSERT INTO `testimonials` (name, role, rating, comment) VALUES (?, ?, ?, ?)");
        
        $defaultTestimonials = [
            [
                'Aditya Pratama',
                'Sneakerhead, Surabaya',
                5,
                'Unyellowing treatment-nya juara! Midsole Jordan 1 saya yang tadinya kuning parah sekarang bersih lagi kayak baru keluar box. Manual hand-wash-nya bener-bener presisi dan aman buat bahan suede.'
            ],
            [
                'Sarah Amelia',
                'Mahasiswi UNAIR',
                5,
                'Fast Clean-nya penyelamat banget pas mau ada acara kampus dadakan. Cuma nunggu sebentar, sepatu lari saya langsung kinclong bagian midsole & upper-nya. Harganya bersahabat banget buat kantong mahasiswa!'
            ],
            [
                'Budi Santoso',
                'Pegawai Swasta, Tambaksari',
                5,
                'Repaint sol sepatu pantofel kulit saya rapi banget warnanya rata. Tempatnya bersih, pelayanannya ramah, dan nomor WhatsApp adminnya fast-response. Recommended banget cuci sepatu di Surabaya!'
            ]
        ];

        foreach ($defaultTestimonials as $tst) {
            $insert->execute($tst);
        }
        echo "  → Seeded " . count($defaultTestimonials) . " default testimonials successfully!\n";
    } else {
        echo "  → Testimonials table already populated.\n";
    }

    echo "\n════════════════════════════════════════════════\n";
    echo "✓ DATABASE SETUP COMPLETED SUCCESSFULLY!\n";
    echo "════════════════════════════════════════════════\n\n";
    echo "You can now login to the Admin Panel: <a href='login.php' style='color:#38bdf8;font-weight:bold;text-decoration:underline;'>Go to Login Page</a>\n";

} catch (PDOException $e) {
    echo "\n❌ DATABASE ERROR: " . $e->getMessage() . "\n";
    echo "Please check your database credentials in 'config.php' and host accessibility.\n";
}

echo "</pre>";
