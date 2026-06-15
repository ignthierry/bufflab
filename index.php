<?php
/**
 * Bufflab Clean Shoes Surabaya - Dynamic Landing Page
 */
require_once __DIR__ . '/cms/config.php';

// Set default fallback values in case the database is not initialized or connection fails
$settings = [
    'hero_title' => 'Step Up Your Game with Clean Kicks',
    'hero_subtitle' => 'Kembalikan kesegaran sepatu favorit Anda dengan layanan pembersihan profesional yang mendalam dan presisi.',
    'about_heading' => 'Tentang Kami',
    'about_p1' => 'Bufflab Clean Shoes adalah penyedia layanan perawatan dan pembersihan sepatu profesional (shoecare) yang berbasis di Surabaya. Kami lahir dari pemahaman bahwa sepatu bukan sekadar alas kaki, melainkan bagian dari identitas, rasa percaya diri, dan investasi penampilan Anda.',
    'about_p2' => 'Dengan menggunakan teknik pencucian manual (hand-wash) yang presisi, cairan pembersih khusus (premium cleaner) yang aman untuk berbagai jenis bahan, serta tenaga kerja yang terlatih, kami berkomitmen untuk mengembalikan kesegaran sepatu kesayangan Anda tanpa merusak serat materialnya.',
    'whatsapp_number' => '6287776666680',
    'whatsapp_message' => 'Halo Bufflab Clean Shoes Surabaya! Saya tertarik dengan layanan shoecare Anda dan ingin bertanya lebih lanjut.',
    'address' => 'Jl. Bronggalan 2G No.51, Pacar Kembang, Kec. Tambaksari, Surabaya, Jawa Timur 60132, Indonesia',
    'schedule' => 'Senin - Sabtu: 09:00 - 18:00 WIB',
    'instagram_url' => 'https://www.instagram.com/bufflabcleanshoes',
    'tiktok_url' => 'https://tiktok.com/@bufflab.clean',
    'tos_url' => '#'
];

$services = [
    [
        'id' => 1,
        'name' => 'Fast Clean',
        'subname' => 'Quick Wash',
        'description' => 'Pembersihan cepat pada area midsole dan upper, cocok untuk Anda yang memiliki mobilitas tinggi.',
        'price' => 30000,
        'icon_name' => 'bolt',
        'is_best_seller' => 0
    ],
    [
        'id' => 2,
        'name' => 'Deep Clean',
        'subname' => 'Kebersihan Total',
        'description' => 'Pembersihan menyeluruh secara intensif mulai dari outsole, midsole, upper, insole, hingga tali sepatu.',
        'price' => 50000,
        'icon_name' => 'sanitizer',
        'is_best_seller' => 1
    ],
    [
        'id' => 3,
        'name' => 'Premium Treatment',
        'subname' => 'Suede, Leather, Nubuck',
        'description' => 'Perawatan khusus untuk sepatu berbahan sensitif (Suede, Leather, Nubuck) menggunakan formula premium.',
        'price' => 75000,
        'icon_name' => 'diamond',
        'is_best_seller' => 0
    ],
    [
        'id' => 4,
        'name' => 'Unyellowing',
        'subname' => 'Anti-Oxidation',
        'description' => 'Treatment khusus untuk menghilangkan efek menguning pada midsole sepatu akibat oksidasi.',
        'price' => 45000,
        'icon_name' => 'wb_sunny',
        'is_best_seller' => 0
    ],
    [
        'id' => 5,
        'name' => 'Repaint / Recolor',
        'subname' => 'Restore Color',
        'description' => 'Mengembalikan warna sepatu yang sudah pudar agar tampak seperti baru kembali.',
        'price' => 120000,
        'icon_name' => 'format_paint',
        'is_best_seller' => 0
    ]
];

$testimonials = [
    [
        'id' => 1,
        'name' => 'Aditya Pratama',
        'role' => 'Sneakerhead, Surabaya',
        'rating' => 5,
        'comment' => 'Unyellowing treatment-nya juara! Midsole Jordan 1 saya yang tadinya kuning parah sekarang bersih lagi kayak baru keluar box. Manual hand-wash-nya bener-bener presisi dan aman buat bahan suede.'
    ],
    [
        'id' => 2,
        'name' => 'Sarah Amelia',
        'role' => 'Mahasiswi UNAIR',
        'rating' => 5,
        'comment' => 'Fast Clean-nya penyelamat banget pas mau ada acara kampus dadakan. Cuma nunggu sebentar, sepatu lari saya langsung kinclong bagian midsole & upper-nya. Harganya bersahabat banget buat kantong mahasiswa!'
    ],
    [
        'id' => 3,
        'name' => 'Budi Santoso',
        'role' => 'Pegawai Swasta, Tambaksari',
        'rating' => 5,
        'comment' => 'Repaint sol sepatu pantofel kulit saya rapi banget warnanya rata. Tempatnya bersih, pelayanannya ramah, dan nomor WhatsApp adminnya fast-response. Recommended banget cuci sepatu di Surabaya!'
    ]
];

$dbConnected = false;

try {
    $pdo = getDB();
    
    // Fetch settings
    $dbSettings = $pdo->query("SELECT * FROM `landing_settings` WHERE id = 1")->fetch();
    if ($dbSettings) {
        $settings = array_merge($settings, $dbSettings);
    }
    
    // Fetch active services
    $dbServices = $pdo->query("SELECT * FROM `services` WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll();
    if (!empty($dbServices)) {
        $services = $dbServices;
    }
    
    // Fetch active testimonials
    $dbTestimonials = $pdo->query("SELECT * FROM `testimonials` WHERE is_active = 1 ORDER BY id DESC")->fetchAll();
    if (!empty($dbTestimonials)) {
        $testimonials = $dbTestimonials;
    }
    
    $dbConnected = true;
} catch (Exception $e) {
    // Fail silently, falls back to default values
}
?>
<!DOCTYPE html>
<html class="light" lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Bufflab Clean Shoes | Professional Shoecare Surabaya - <?= htmlspecialchars($settings['hero_title']) ?></title>
<meta name="description" content="Bufflab Clean Shoes adalah penyedia layanan perawatan dan pembersihan sepatu profesional (shoecare) terbaik di Surabaya dengan teknik manual (hand-wash) presisi."/>
<meta name="keywords" content="shoecare surabaya, cuci sepatu surabaya, pembersihan sepatu, repaint sepatu surabaya, unyellowing surabaya, premium cleaner, cuci sneakers"/>
<!-- Open Graph / Facebook -->
<meta property="og:type" content="website"/>
<meta property="og:title" content="Bufflab Clean Shoes | Professional Shoecare Surabaya"/>
<meta property="og:description" content="Layanan cuci dan perawatan sepatu premium berbasis di Surabaya. Bersih, presisi, dan aman untuk segala jenis bahan."/>
<meta property="og:image" content="https://lh3.googleusercontent.com/aida-public/AB6AXuCnT4TlspmSiCPtUfbxLCKJrgNnYyJn4xWoM9nGDNEIuO6ltmhjrnS7mJtGV1vlCUdgUCOEsHrJBuIpcte9RnT4YlgqX_R3GdHzIiphOU4tx_YxJhaENYUGOZyeRvObFPpUrQh4wemq2Pg-ecbbBwoynFxh8ggzbbevFaLD5rGxbHWGa5s-OrhLyoyUq91eFFJHLMkx_VOb6sRIidc_xPI8QJRZza9A-TubseoCQ7VbNv81yb_f2irlYSOll7HT6PdSeLQEbEaXJItB"/>
<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image"/>
<meta property="twitter:title" content="Bufflab Clean Shoes | Professional Shoecare Surabaya"/>
<meta property="twitter:description" content="Layanan cuci dan perawatan sepatu premium berbasis di Surabaya. Bersih, presisi, dan aman untuk segala jenis bahan."/>

<!-- Tailwind Play CDN -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Google Fonts & Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&amp;family=Inter:wght@400;500;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>

<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-secondary-fixed-variant": "#0038b6",
                    "on-secondary-fixed": "#001452",
                    "secondary-container": "#0052fe",
                    "error": "#ba1a1a",
                    "secondary-fixed": "#dde1ff",
                    "on-error": "#ffffff",
                    "tertiary-container": "#1d1b1a",
                    "on-secondary-container": "#dfe3ff",
                    "surface-container-high": "#e5e9eb",
                    "surface-container-low": "#f1f4f6",
                    "surface-dim": "#d7dadc",
                    "on-tertiary-fixed": "#1d1b1a",
                    "secondary-fixed-dim": "#b7c4ff",
                    "primary": "#000000",
                    "surface-container-lowest": "#ffffff",
                    "surface-tint": "#5f5e5e",
                    "primary-fixed-dim": "#c9c6c5",
                    "on-tertiary": "#ffffff",
                    "tertiary": "#000000",
                    "inverse-surface": "#2d3133",
                    "on-background": "#181c1e",
                    "outline-variant": "#c4c7c7",
                    "primary-container": "#1c1b1b",
                    "on-primary-container": "#858383",
                    "secondary": "#003ec6",
                    "tertiary-fixed": "#e6e1df",
                    "surface-variant": "#e0e3e5",
                    "error-container": "#ffdad6",
                    "surface-bright": "#f7fafc",
                    "surface-container-highest": "#e0e3e5",
                    "surface-container": "#ebeef0",
                    "inverse-on-surface": "#eef1f3",
                    "on-tertiary-container": "#868381",
                    "background": "#f7fafc",
                    "outline": "#747878",
                    "on-primary-fixed": "#1c1b1b",
                    "on-primary-fixed-variant": "#474646",
                    "inverse-primary": "#c9c6c5",
                    "on-surface-variant": "#444748",
                    "primary-fixed": "#e5e2e1",
                    "tertiary-fixed-dim": "#cac6c3",
                    "on-tertiary-fixed-variant": "#484645",
                    "on-secondary": "#ffffff",
                    "surface": "#f7fafc",
                    "on-primary": "#ffffff",
                    "on-error-container": "#93000a",
                    "on-surface": "#181c1e"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "gutter": "24px",
                    "sm": "12px",
                    "md": "24px",
                    "lg": "48px",
                    "margin-desktop": "64px",
                    "base": "8px",
                    "margin-mobile": "16px",
                    "xs": "4px",
                    "xl": "80px"
            },
            "fontFamily": {
                    "headline-lg-mobile": ["Montserrat"],
                    "body-md": ["Inter"],
                    "headline-xl": ["Montserrat"],
                    "headline-md": ["Montserrat"],
                    "headline-lg": ["Montserrat"],
                    "label-sm": ["Inter"],
                    "body-lg": ["Inter"]
            },
            "fontSize": {
                    "headline-lg-mobile": ["28px", {"lineHeight": "36px", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "headline-xl": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "800"}],
                    "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                    "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                    "label-sm": ["14px", {"lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                    "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
    
    <!-- Split CSS -->
    <link rel="stylesheet" href="style.css" />
</head>
<body class="bg-background text-on-surface font-body-md selection:bg-secondary-fixed selection:text-on-secondary-fixed">

<!-- Announcement Promo Bar -->
<div class="bg-secondary text-on-secondary py-2.5 px-gutter text-center text-xs font-bold font-headline select-none relative z-50 flex items-center justify-center gap-2">
  <span class="flex items-center gap-1">
    <span class="material-symbols-outlined text-sm animate-pulse">local_fire_department</span>
    PROMO SPESIAL: Cuci 3 Pasang Cuma 50K! (15-30 Juni 2026)
  </span>
  <a href="#promo" class="underline hover:text-secondary-fixed transition-colors font-medium ml-2">Klaim Promo &rarr;</a>
</div>

<!-- TopNavBar -->
<header class="bg-surface-container-lowest/90 backdrop-blur-md shadow-sm docked full-width top-0 sticky z-50 transition-all duration-300">
<nav class="flex justify-between items-center px-gutter py-md max-w-7xl mx-auto relative">
<a href="#" class="flex items-center gap-3 select-none hover:opacity-90 transition-opacity">
  <img src="logo.png" alt="Bufflab Clean Shoes Logo" class="w-10 h-10 object-contain rounded-xl shadow-sm bg-black/5 p-0.5" />
  <div class="flex flex-col leading-none">
    <span class="font-headline-md text-lg font-black tracking-tight text-primary">BUFFLAB</span>
    <span class="font-label-sm text-[9px] tracking-[0.2em] font-bold text-secondary uppercase mt-0.5">Clean Shoes</span>
  </div>
</a>
<div class="hidden md:flex items-center gap-lg">
<a class="text-on-surface-variant font-medium hover:text-secondary transition-colors font-label-sm text-label-sm" href="#services">Services</a>
<a class="text-on-surface-variant font-medium hover:text-secondary transition-colors font-label-sm text-label-sm" href="#about">About Us</a>
<a class="text-on-surface-variant font-medium hover:text-secondary transition-colors font-label-sm text-label-sm" href="#testimonials">Reviews</a>
<a class="text-on-surface-variant font-medium hover:text-secondary transition-colors font-label-sm text-label-sm" href="#location">Location</a>
</div>
<div class="hidden md:block">
<button class="whatsapp-btn bg-secondary text-on-secondary px-6 py-3 rounded-full font-label-sm text-label-sm font-bold btn-interact shadow-md hover:bg-on-secondary-fixed-variant">
                WhatsApp Us
</button>
</div>
<!-- Hamburger button (visible on mobile, hidden on desktop) -->
<button id="mobile-menu-toggle" class="md:hidden flex items-center justify-center p-2 text-on-surface hover:text-secondary focus:outline-none transition-colors" aria-label="Toggle Menu">
  <span class="material-symbols-outlined !text-3xl" id="menu-icon">menu</span>
</button>
<!-- Mobile Nav Menu Overlay -->
<div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 w-full bg-surface-container-lowest shadow-lg border-b border-outline-variant/30 py-6 px-gutter flex flex-col gap-6 transition-all duration-300 transform origin-top scale-y-95 opacity-0 z-40">
  <a class="text-on-surface font-semibold hover:text-secondary transition-colors font-label-sm text-lg py-2 border-b border-outline-variant/10" href="#services">Services</a>
  <a class="text-on-surface font-semibold hover:text-secondary transition-colors font-label-sm text-lg py-2 border-b border-outline-variant/10" href="#about">About Us</a>
  <a class="text-on-surface font-semibold hover:text-secondary transition-colors font-label-sm text-lg py-2 border-b border-outline-variant/10" href="#testimonials">Reviews</a>
  <a class="text-on-surface font-semibold hover:text-secondary transition-colors font-label-sm text-lg py-2 border-b border-outline-variant/10" href="#location">Location</a>
  <button class="whatsapp-btn w-full bg-secondary text-on-secondary py-4 rounded-xl font-label-sm text-base font-bold btn-interact shadow-md text-center mt-2 flex items-center justify-center gap-2">
    <span class="material-symbols-outlined">chat</span> WhatsApp Us
  </button>
</div>
</nav>
</header>

<!-- Hero Section -->
<section class="relative overflow-hidden mesh-bg py-xl">
<div class="max-w-7xl mx-auto px-gutter grid grid-cols-1 md:grid-cols-2 gap-lg items-center">
<div class="z-10">
<span class="bg-secondary-fixed text-on-secondary-fixed px-4 py-2 rounded-full font-label-sm text-label-sm mb-md inline-block animate-fade-up">PREMIUM SHOECARE SURABAYA</span>
<h1 class="font-headline-xl text-headline-xl text-primary mb-md leading-tight animate-fade-up delay-100">
                    <?= htmlspecialchars($settings['hero_title']) ?>
                </h1>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-lg max-w-lg animate-fade-up delay-200">
                    <?= htmlspecialchars($settings['hero_subtitle']) ?>
                </p>
<div class="flex flex-col sm:flex-row gap-md animate-fade-up delay-300">
<a href="#services" class="bg-secondary text-on-secondary px-8 py-4 rounded-xl font-headline-md text-headline-md btn-interact hover:brightness-110 transition-all flex items-center justify-center gap-sm shadow-lg">
                        Cek Layanan <span class="material-symbols-outlined">arrow_forward</span>
</a>
<button class="whatsapp-btn border-2 border-secondary text-secondary px-8 py-4 rounded-xl font-headline-md text-headline-md btn-interact hover:bg-secondary-fixed transition-all flex items-center justify-center gap-sm">
                        Konsultasi Gratis
</button>
</div>
</div>
<div class="relative animate-fade-up delay-300">
<div class="absolute -top-10 -right-10 w-64 h-64 bg-secondary-container/10 rounded-full blur-3xl"></div>
<div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl animate-float">
<img alt="Premium Sneakers" class="w-full h-[500px] object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCnT4TlspmSiCPtUfbxLCKJrgNnYyJn4xWoM9nGDNEIuO6ltmhjrnS7mJtGV1vlCUdgUCOEsHrJBuIpcte9RnT4YlgqX_R3GdHzIiphOU4tx_YxJhaENYUGOZyeRvObFPpUrQh4wemq2Pg-ecbbBwoynFxh8ggzbbevFaLD5rGxbHWGa5s-OrhLyoyUq91eFFJHLMkx_VOb6sRIidc_xPI8QJRZza9A-TubseoCQ7VbNv81yb_f2irlYSOll7HT6PdSeLQEbEaXJItB"/>
</div>
<div class="absolute -bottom-6 -left-6 bg-surface-container-lowest p-md rounded-2xl shadow-xl z-20 flex items-center gap-md border border-outline-variant transform hover:scale-110 transition-transform cursor-default">
<div class="w-12 h-12 bg-secondary rounded-full flex items-center justify-center text-on-secondary">
<span class="material-symbols-outlined">verified</span>
</div>
<div>
<p class="font-headline-md text-headline-md text-primary leading-none">100%</p>
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Garansi Bersih</p>
</div>
</div>
</div>
</div>
</section>

<!-- Promo Section -->
<section class="py-xl bg-surface-container-lowest reveal" id="promo">
<div class="max-w-7xl mx-auto px-gutter">
<div class="bg-gradient-to-br from-secondary/10 to-secondary-container/5 rounded-[2rem] p-md md:p-xl border border-secondary/20 relative overflow-hidden flex flex-col lg:flex-row gap-lg items-center shadow-md">
  <!-- Decorative background elements -->
  <div class="absolute top-0 right-0 w-64 h-64 bg-secondary/5 rounded-full blur-3xl -z-10"></div>
  <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary-container/5 rounded-full blur-3xl -z-10"></div>

  <!-- Left: Promo Image -->
  <div class="w-full lg:w-1/2 flex justify-center">
    <div class="relative rounded-3xl overflow-hidden shadow-2xl hover-lift border border-outline-variant/30 max-w-md w-full">
      <img alt="Promo Spesial Bufflab Clean Shoes" class="w-full h-auto object-cover" src="promo%20(2).jpg" />
    </div>
  </div>

  <!-- Right: Promo Details -->
  <div class="w-full lg:w-1/2 flex flex-col justify-center">
    <span class="bg-error text-on-error px-4 py-1.5 rounded-full font-label-sm text-xs mb-sm inline-block font-bold self-start uppercase tracking-wider animate-bounce">Promo Terbatas!</span>
    <h2 class="font-headline-lg text-headline-lg text-primary mb-md leading-tight">
      Promo Spesial Cuci Sepatu <br/>
      <span class="text-secondary font-black">3 Pasang Cuma 50K</span>
    </h2>
    <p class="font-body-lg text-body-lg text-on-surface-variant mb-lg leading-relaxed">
      Dapatkan perawatan cuci sepatu premium dengan harga super hemat. Hanya dengan <strong>Rp 50.000</strong>, Anda sudah bisa mengembalikan kesegaran <strong>3 pasang sepatu</strong> kesayangan Anda! <br/>
      <span class="text-sm font-semibold text-secondary mt-1 inline-block">Periode Promo: 15-30 Juni 2026</span>
    </p>
    
    <div class="space-y-sm mb-lg">
      <div class="flex items-center gap-sm group">
        <span class="material-symbols-outlined text-secondary group-hover:scale-125 transition-transform duration-300">verified</span>
        <span class="font-body-md text-body-md text-slate-700">Teknik Hand-wash Presisi &amp; Aman</span>
      </div>
      <div class="flex items-center gap-sm group">
        <span class="material-symbols-outlined text-secondary group-hover:scale-125 transition-transform duration-300">verified</span>
        <span class="font-body-md text-body-md text-slate-700">Menggunakan Premium Cleaner Khusus Bahan Sepatu</span>
      </div>
      <div class="flex items-center gap-sm group">
        <span class="material-symbols-outlined text-secondary group-hover:scale-125 transition-transform duration-300">verified</span>
        <span class="font-body-md text-body-md text-slate-700">Berlaku untuk Layanan Fast Clean Pilihan</span>
      </div>
      <div class="flex items-center gap-sm group">
        <span class="material-symbols-outlined text-secondary group-hover:scale-125 transition-transform duration-300">local_shipping</span>
        <span class="font-body-md text-body-md text-slate-700">Gratis pickup dan delivery S&amp;K, max 4km dari lokasi Bufflab</span>
      </div>
    </div>
    
    <button id="promo-claim-btn" class="bg-secondary text-on-secondary px-8 py-4 rounded-xl font-headline-md text-headline-md btn-interact hover:brightness-110 transition-all flex items-center justify-center gap-sm shadow-lg self-start">
      <span class="material-symbols-outlined">chat</span> Ambil Promo Sekarang
    </button>
  </div>
</div>
</div>
</section>

<!-- About Section -->
<section class="py-xl bg-surface-container-low reveal" id="about">
<div class="max-w-7xl mx-auto px-gutter">
<div class="flex flex-col md:flex-row gap-xl items-center">
<div class="md:w-1/2 order-2 md:order-1">
<div class="grid grid-cols-2 gap-md">
<div class="overflow-hidden rounded-2xl shadow-md">
<img alt="Cleaning process" class="h-64 w-full object-cover hover:scale-110 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZf4Qgqs5HBMG4SraOpmJnnruJidt_RPVhMi7i3bkpSG4DW5auIm3-lKAnuHktXcaAQO2XFrq7jGrArAe54qz7I_HhmKPgqd6Ew57_lg8fsQe0RYfLgij_T0R4ffBb8x2UACPEH2hOI7RlOOnXUS4B-eB_ku7j3D_Tj7g1dOidnSKJfKOvwCXszRqXfJe81KFSxdewrC5LybDxB1HxbqyqULXAZQ7CJOFXnw9KCCQ1Ch303sZXVclavslFWWIWD4vSISHoDekHjMEo"/>
</div>
<div class="overflow-hidden rounded-2xl shadow-md mt-lg">
<img alt="Deep detailing" class="h-64 w-full object-cover hover:scale-110 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDW4ba2jhHZJjVr3NupUZnYek3BWNlwSJo5QeXLvQv8o7-FRlsg8chGHNgz6LXawS3oUiLbC9GtrnaRk6shBPICV6zJ8l1ahP8xnrbKoeBZdGtML4TPmpdRyC3yeuXynI893zlAfG-h5umY3meqt2wLmp53_ffUHdfqIPewjMMuH-bmTrwrWx1rPaLJPLXD9vvPJNJz2fT6TD8mkUF42YrH0olhM0jD0dvXKIH8EI6AQlXHCIeIn_4p698BNcUoxkvJ9zta22Oqeun8"/>
</div>
</div>
</div>
<div class="md:w-1/2 order-1 md:order-2">
<h2 class="font-headline-lg text-headline-lg text-primary mb-md"><?= htmlspecialchars($settings['about_heading']) ?></h2>
<div class="font-body-lg text-body-lg text-on-surface-variant mb-lg space-y-4 leading-relaxed">
    <p>
        <?= htmlspecialchars($settings['about_p1']) ?>
    </p>
    <p>
        <?= htmlspecialchars($settings['about_p2']) ?>
    </p>
</div>
<ul class="space-y-sm">
<li class="flex items-center gap-sm group">
<span class="material-symbols-outlined text-secondary group-hover:scale-125 transition-transform duration-300">check_circle</span>
<span class="font-body-md text-body-md">Premium Cleaner Ramah Lingkungan</span>
</li>
<li class="flex items-center gap-sm group">
<span class="material-symbols-outlined text-secondary group-hover:scale-125 transition-transform duration-300">check_circle</span>
<span class="font-body-md text-body-md">Teknik Cuci Tangan Manual (Hand-wash)</span>
</li>
<li class="flex items-center gap-sm group">
<span class="material-symbols-outlined text-secondary group-hover:scale-125 transition-transform duration-300">check_circle</span>
<span class="font-body-md text-body-md">Penjemuran Tanpa Sinar Matahari Langsung</span>
</li>
</ul>
</div>
</div>
</div>
</section>

<!-- Services Bento Grid -->
<section class="py-xl bg-surface-container-lowest reveal" id="services">
<div class="max-w-7xl mx-auto px-gutter">
<div class="text-center mb-xl">
<h2 class="font-headline-lg text-headline-lg text-primary mb-xs">Layanan Unggulan Kami</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Solusi lengkap untuk berbagai jenis dan kondisi sepatu.</p>
</div>

<div class="bento-grid">
    <?php foreach ($services as $index => $srv): ?>
        <?php if ($srv['is_best_seller']): ?>
            <!-- Best Seller Style Card (Dark Theme) -->
            <div class="col-span-1 md:col-span-2 row-span-1 bg-primary text-on-primary rounded-3xl p-lg hover-lift shimmer-glow flex flex-col justify-between overflow-hidden relative cursor-pointer group">
              <div class="z-10">
                <div class="w-12 h-12 bg-secondary text-on-secondary rounded-xl flex items-center justify-center mb-md group-hover:scale-110 transition-transform duration-300">
                  <span class="material-symbols-outlined"><?= htmlspecialchars($srv['icon_name']) ?></span>
                </div>
                <h3 class="font-headline-md text-headline-md mb-sm"><?= htmlspecialchars($srv['name']) ?></h3>
                <p class="font-body-md text-body-md text-on-primary/80 mb-md"><?= htmlspecialchars($srv['description']) ?></p>
              </div>
              <div class="flex justify-between items-center z-10">
                <span class="bg-secondary-container text-on-secondary-container px-4 py-1 rounded-full font-label-sm text-label-sm">Best Seller</span>
                <span class="font-label-sm text-label-sm">Mulai Rp <?= number_format($srv['price'], 0, ',', '.') ?></span>
              </div>
              <div class="absolute right-[-10%] bottom-[-10%] opacity-20 transform rotate-12 transition-transform group-hover:rotate-[30deg] duration-700">
                <span class="material-symbols-outlined !text-[180px]">flare</span>
              </div>
            </div>
        <?php elseif ($srv['name'] === 'Repaint / Recolor'): ?>
            <!-- Repaint Style Card (with image overlay) -->
            <div class="col-span-1 md:col-span-2 bg-secondary-container/5 rounded-3xl p-lg hover-lift border border-secondary/10 flex flex-col md:flex-row gap-lg items-center cursor-pointer group">
              <div class="flex-1">
                <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center mb-md group-hover:scale-110 transition-transform duration-300">
                  <span class="material-symbols-outlined"><?= htmlspecialchars($srv['icon_name']) ?></span>
                </div>
                <h3 class="font-headline-md text-headline-md text-primary mb-sm"><?= htmlspecialchars($srv['name']) ?></h3>
                <p class="font-body-md text-body-md text-on-surface-variant"><?= htmlspecialchars($srv['description']) ?></p>
              </div>
              <div class="w-full md:w-1/3 aspect-square rounded-2xl overflow-hidden shadow-inner bg-surface-variant">
                <img alt="Repaint Service" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC8FxxMZL5uNzNE4Z3OQDU0yZlG7h8D3TEaVK7s2dM3ctFSeFicKmRNmzIL5PWIESF65yYTj4xRZgVDAHqCaXfoiWrZs3mbdByTRn4-EuQkrbbanzxBTzZHZO7p6P6CB0uhMOXK8oEyGCvvy0Tpr8RgL_hknt0VYDPY1qGohZOcMSe_pZ-KTrH4bjPRqV2WZSPGdxaHIx3uf8FgoTHeZzX56fTBgxb31fYkaplE_HAUrdI5RxtUd3tB8hSrQM_DF9DHA7vm2HoXlqL4"/>
              </div>
            </div>
        <?php elseif ($index === 0 || $index === 4): ?>
            <!-- Wide Card Style (2-col width, light bg) -->
            <div class="col-span-1 md:col-span-2 row-span-1 bg-surface-container-low rounded-3xl p-lg hover-lift flex flex-col justify-between border border-outline-variant/30 cursor-pointer">
              <div>
                <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-xl flex items-center justify-center mb-md transition-transform duration-300">
                  <span class="material-symbols-outlined"><?= htmlspecialchars($srv['icon_name']) ?></span>
                </div>
                <h3 class="font-headline-md text-headline-md text-primary mb-sm"><?= htmlspecialchars($srv['name']) ?></h3>
                <p class="font-body-md text-body-md text-on-surface-variant mb-md"><?= htmlspecialchars($srv['description']) ?></p>
              </div>
              <div class="flex justify-between items-center">
                <span class="bg-secondary text-on-secondary px-4 py-1 rounded-full font-label-sm text-label-sm">Rp <?= number_format($srv['price'], 0, ',', '.') ?>+</span>
                <span class="material-symbols-outlined text-secondary-fixed-dim transition-transform duration-300">arrow_outward</span>
              </div>
            </div>
        <?php else: ?>
            <!-- Standard Single Column Card -->
            <div class="col-span-1 bg-surface-container-lowest border-2 border-secondary/20 rounded-3xl p-md hover-lift flex flex-col text-center items-center cursor-pointer">
              <div class="w-14 h-14 bg-secondary-fixed text-on-secondary-fixed rounded-full flex items-center justify-center mb-md shadow-sm transition-all duration-300">
                <span class="material-symbols-outlined"><?= htmlspecialchars($srv['icon_name']) ?></span>
              </div>
              <h3 class="font-headline-md text-headline-md text-primary mb-xs"><?= htmlspecialchars($srv['name']) ?></h3>
              <?php if ($srv['subname']): ?>
                  <p class="font-label-sm text-label-sm text-on-surface-variant mb-md"><?= htmlspecialchars($srv['subname']) ?></p>
              <?php endif; ?>
              <p class="font-body-md text-body-md text-on-surface-variant flex-grow"><?= htmlspecialchars($srv['description']) ?></p>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<!-- Price Calculator Section -->
<div class="mt-xl bg-surface-container-low rounded-[2rem] p-md md:p-xl border border-outline-variant/30 relative overflow-hidden reveal">
  <div class="absolute -top-10 -left-10 w-64 h-64 bg-secondary-container/5 rounded-full blur-3xl"></div>
  <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-secondary-container/5 rounded-full blur-3xl"></div>
  
  <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-lg">
    <!-- Selection Area -->
    <div class="lg:col-span-7 flex flex-col justify-between">
      <div>
        <span class="bg-secondary-fixed text-on-secondary-fixed px-4 py-1.5 rounded-full font-label-sm text-xs mb-sm inline-block font-bold">Kalkulator Estimasi</span>
        <h3 class="font-headline-md text-headline-md text-primary mb-sm">Estimasi Biaya Perawatan</h3>
        <p class="font-body-md text-body-md text-on-surface-variant mb-lg">Pilih layanan perawatan dan jumlah sepatu untuk menghitung estimasi biaya total Anda secara langsung.</p>
        
        <!-- Services List -->
        <div class="space-y-sm" id="calc-services">
          <?php foreach ($services as $srv): ?>
              <label class="calc-card flex items-center justify-between p-md bg-surface-container-lowest rounded-2xl border-2 border-transparent hover:border-secondary/20 cursor-pointer transition-all duration-300 select-none group">
                <div class="flex items-center gap-md">
                  <div class="checkbox-box w-5 h-5 rounded border border-outline flex items-center justify-center text-white transition-all group-hover:border-secondary">
                    <input type="checkbox" value="<?= $srv['price'] ?>" data-name="<?= htmlspecialchars($srv['name']) ?>" class="hidden calc-checkbox" id="calc-service-<?= $srv['id'] ?>" />
                    <span class="material-symbols-outlined !text-xs font-bold check-icon hidden">check</span>
                  </div>
                  <div>
                    <p class="font-label-sm text-primary font-bold"><?= htmlspecialchars($srv['name']) ?></p>
                    <p class="text-xs text-on-surface-variant"><?= htmlspecialchars($srv['subname'] ?: 'Perawatan khusus sepatu') ?></p>
                  </div>
                </div>
                <span class="font-label-sm text-secondary font-extrabold">Rp <?= number_format($srv['price'], 0, ',', '.') ?></span>
              </label>
          <?php endforeach; ?>
        </div>
      </div>
      
      <!-- Quantity Selector -->
      <div class="mt-lg flex items-center justify-between p-md bg-surface-container-lowest rounded-2xl border border-outline-variant/30">
        <div>
          <p class="font-label-sm text-primary font-bold">Jumlah Sepatu</p>
          <p class="text-xs text-on-surface-variant">Jumlah pasang sepatu yang akan dirawat</p>
        </div>
        <div class="flex items-center gap-md">
          <button id="qty-minus" class="w-10 h-10 rounded-xl bg-surface-container-low text-primary font-bold flex items-center justify-center hover:bg-secondary hover:text-white transition-all duration-300 focus:outline-none select-none">-</button>
          <span id="qty-value" class="font-headline-md text-headline-md text-primary w-6 text-center select-none">1</span>
          <button id="qty-plus" class="w-10 h-10 rounded-xl bg-surface-container-low text-primary font-bold flex items-center justify-center hover:bg-secondary hover:text-white transition-all duration-300 focus:outline-none select-none">+</button>
        </div>
      </div>
    </div>
    
    <!-- Summary Area -->
    <div class="lg:col-span-5">
      <div class="bg-surface-container-lowest p-md md:p-lg rounded-2xl border border-outline-variant/30 flex flex-col justify-between h-full min-h-[350px]">
        <div>
          <h4 class="font-headline-md text-lg text-primary mb-md pb-xs border-b border-outline-variant/30 flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary">shopping_basket</span> Detail Layanan
          </h4>
          <div id="calc-summary-items" class="space-y-sm max-h-[220px] overflow-y-auto pr-xs">
            <p class="text-on-surface-variant text-sm italic py-md text-center">Belum ada layanan terpilih</p>
          </div>
        </div>
        
        <div class="mt-lg pt-md border-t border-outline-variant/30">
          <div class="flex justify-between items-end mb-md">
            <div>
              <p class="text-xs text-on-surface-variant uppercase tracking-wider font-bold">Total Estimasi</p>
              <p id="calc-summary-qty" class="text-xs text-on-surface-variant">1 pasang sepatu</p>
            </div>
            <span id="calc-total-price" class="font-headline-lg text-headline-lg text-secondary font-black leading-none">Rp 0</span>
          </div>
          
          <button id="calc-book-btn" class="w-full bg-secondary text-on-secondary py-4 rounded-xl font-headline-md text-base btn-interact hover:brightness-110 transition-all flex items-center justify-center gap-sm shadow-lg text-center font-bold">
            <span class="material-symbols-outlined">chat</span> Pesan via WhatsApp
          </button>
          <p class="text-[10px] text-on-surface-variant text-center mt-sm">Estimasi biaya belum termasuk ongkir antar-jemput.</p>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</section>

<!-- Testimonials Section -->
<section class="py-xl bg-surface-container-low reveal" id="testimonials">
  <div class="max-w-7xl mx-auto px-gutter">
    <div class="text-center mb-xl">
      <span class="bg-secondary-fixed text-on-secondary-fixed px-4 py-1.5 rounded-full font-label-sm text-xs mb-sm inline-block font-bold">Testimoni Pelanggan</span>
      <h2 class="font-headline-lg text-headline-lg text-primary mb-xs">Apa Kata Mereka?</h2>
      <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl mx-auto">
        Kepuasan Anda adalah prioritas kami. Berikut adalah ulasan jujur dari pelanggan setia Bufflab Clean Shoes yang diambil langsung dari Instagram kami.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
      <?php foreach ($testimonials as $tst): ?>
        <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-3xl p-8 hover-lift shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between h-full group">
          <div>
            <!-- Rating Stars -->
            <div class="flex gap-1 text-amber-500 mb-6">
              <?php for ($i = 0; $i < 5; $i++): ?>
                <span class="material-symbols-outlined !text-xl" style="<?= $i < $tst['rating'] ? "font-variation-settings: 'FILL' 1;" : "" ?>">star</span>
              <?php endfor; ?>
            </div>
            <!-- Comment -->
            <p class="font-body-md text-body-md text-on-surface-variant italic mb-6 leading-relaxed">
              "<?= htmlspecialchars($tst['comment']) ?>"
            </p>
          </div>
          
          <!-- User Info -->
          <div class="flex items-center gap-4 pt-6 border-t border-outline-variant/20">
            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-secondary to-secondary-container text-on-secondary flex items-center justify-center font-bold font-headline text-lg shadow-inner group-hover:scale-105 transition-transform duration-300 select-none">
              <?= strtoupper(substr(htmlspecialchars($tst['name']), 0, 1)) ?>
            </div>
            <div>
              <h4 class="font-headline-md text-base text-primary font-bold leading-none mb-1"><?= htmlspecialchars($tst['name']) ?></h4>
              <p class="text-xs text-on-surface-variant"><?= htmlspecialchars($tst['role']) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Link to Instagram Reviews -->
    <div class="mt-lg text-center">
      <a href="<?= htmlspecialchars($settings['instagram_url']) ?>" target="_blank" class="inline-flex items-center gap-sm bg-gradient-to-r from-purple-600 to-pink-500 text-white px-8 py-3.5 rounded-xl font-headline-md text-sm font-bold shadow-lg hover:shadow-xl hover:brightness-110 active:scale-95 transition-all duration-300">
        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">
          <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
        </svg>
        Lihat Review Lainnya di Instagram
      </a>
    </div>
  </div>
</section>

<!-- Location & CTA Section -->
<section class="py-xl bg-surface reveal" id="location">
<div class="max-w-7xl mx-auto px-gutter">
<div class="bg-primary-container rounded-[2rem] overflow-hidden flex flex-col md:flex-row shadow-2xl">
<div class="p-lg md:w-1/2 flex flex-col justify-center">
<h2 class="font-headline-lg text-headline-lg text-on-primary-container mb-md">Kunjungi Lab Kami di Surabaya</h2>
<p class="font-body-md text-body-md text-on-primary-container/80 mb-lg">
                        Kami siap memberikan perawatan terbaik untuk koleksi sneaker Anda. Lokasi strategis dengan pelayanan yang ramah dan profesional.
                    </p>
<div class="space-y-md mb-lg">
<div class="flex items-start gap-md group cursor-pointer">
<span class="material-symbols-outlined text-secondary-fixed group-hover:scale-125 transition-transform duration-300">location_on</span>
<p class="font-body-md text-body-md text-on-primary-container group-hover:text-white transition-colors duration-300"><?= htmlspecialchars($settings['address']) ?></p>
</div>
<div class="flex items-start gap-md group cursor-pointer">
<span class="material-symbols-outlined text-secondary-fixed group-hover:rotate-12 transition-transform duration-300">schedule</span>
<p class="font-body-md text-body-md text-on-primary-container group-hover:text-white transition-colors duration-300"><?= htmlspecialchars($settings['schedule']) ?></p>
</div>
</div>
<button class="whatsapp-btn bg-secondary-container text-on-secondary-container px-8 py-4 rounded-xl font-headline-md text-headline-md btn-interact hover:brightness-110 transition-all flex items-center justify-center gap-sm self-start shadow-lg">
<span class="material-symbols-outlined">chat</span> Konsultasi Lewat WhatsApp
                    </button>
</div>
<div class="md:w-1/2 min-h-[400px] bg-surface-dim relative overflow-hidden group">
<!-- Map Placeholder -->
<div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-1000" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB3WuSbqKCYpRxNy5AF4PoyBbUpFLsela0aPjl9TvNZuD_XVDje0CN3EolVxq2uATsZYiwYLcFMe65-8hmkp1JuYibVBgSwtE0_lb27Kx12Yz_5yUtSh4adtzwESa9XpGpHQp-3e05NnEyLh8pnTtQGFEBUPxgd0eCGnqeZX8YiPmZ5PEU1jGMhGoqc6sj2uzI2RxJR7yZ3h_6Q9J3Gh-dSWoXXFQrw_IrK6gYkmvQ-xpSl7S-RwgIessQQjHlklaCMBAM1-QKsGXUH')">
<div class="absolute inset-0 bg-secondary/10 flex items-center justify-center">
<div class="bg-surface-container-lowest p-sm rounded-lg shadow-lg flex items-center gap-sm transform animate-bounce">
<div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center text-on-secondary">
<span class="material-symbols-outlined text-sm">home_repair_service</span>
</div>
<span class="font-label-sm text-label-sm text-primary font-bold">Bufflab Surabaya</span>
</div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Footer -->
<footer class="bg-primary-container text-on-primary-container full-width">
<div class="flex flex-col md:flex-row justify-between items-center px-margin-desktop py-lg w-full max-w-7xl mx-auto">
<div class="mb-lg md:mb-0">
<div class="flex items-center gap-3 select-none mb-3">
  <img src="logo.png" alt="Bufflab Clean Shoes Logo" class="w-10 h-10 object-contain rounded-xl shadow-md bg-white/10 p-0.5" />
  <div class="flex flex-col leading-none">
    <span class="font-headline-md text-lg font-black tracking-tight text-white">BUFFLAB</span>
    <span class="font-label-sm text-[9px] tracking-[0.2em] font-bold text-secondary-fixed uppercase mt-0.5">Clean Shoes</span>
  </div>
</div>
<p class="font-label-sm text-label-sm text-on-primary-container/80">© <?= date('Y') ?> Bufflab Clean Shoes Surabaya. All rights reserved.</p>
</div>
<div class="flex flex-wrap justify-center gap-lg">
<a class="text-on-primary-container/80 hover:text-secondary-container hover:scale-110 transition-all font-label-sm text-label-sm font-medium duration-300" href="<?= htmlspecialchars($settings['instagram_url']) ?>" target="_blank">Instagram</a>
<a class="text-on-primary-container/80 hover:text-secondary-container hover:scale-110 transition-all font-label-sm text-label-sm font-medium duration-300" href="<?= htmlspecialchars($settings['tiktok_url']) ?>" target="_blank">TikTok</a>
<a class="whatsapp-btn text-on-primary-container/80 hover:text-secondary-container hover:scale-110 transition-all font-label-sm text-label-sm font-medium duration-300" href="#">WhatsApp</a>
<a class="text-on-primary-container/80 hover:text-secondary-container hover:scale-110 transition-all font-label-sm text-label-sm font-medium duration-300" href="<?= htmlspecialchars($settings['tos_url']) ?>">Terms of Service</a>
</div>
</div>
</footer>

<!-- FAB -->
<button class="whatsapp-btn fixed bottom-gutter right-gutter bg-secondary text-on-secondary w-16 h-16 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all z-40 group btn-interact">
<span class="material-symbols-outlined !text-3xl">chat_bubble</span>
<span class="absolute right-full mr-4 bg-primary text-on-primary px-4 py-2 rounded-lg text-sm font-bold opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap shadow-xl">Hubungi Kami</span>
</button>

<!-- Inject Dynamic WhatsApp Config for External JS -->
<script>
    window.whatsappNumber = "<?= htmlspecialchars($settings['whatsapp_number']) ?>";
    window.whatsappMessage = "<?= htmlspecialchars($settings['whatsapp_message']) ?>";
</script>

<!-- Split JavaScript -->
<script src="script.js" defer></script>
</body>
</html>
