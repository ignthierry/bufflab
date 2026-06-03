Sistem ini dirancang untuk mengotomatisasi dan mencatat seluruh alur kerja operasional Bufflab Clean Shoes, mulai dari order intake (melalui customer langsung atau input admin), proses pelacakan di workshop, pembuatan invoice, hingga notifikasi WhatsApp otomatis saat sepatu selesai dikerjakan.

Tujuan Utama:

Mempercepat proses check-in sepatu di outlet.

Meminimalisir risiko kesalahan data (tertukar, salah jenis perawatan).

Memberikan transparansi status pengerjaan kepada pelanggan.

Otomatisasi pengiriman bukti transaksi dan pemberitahuan via WhatsApp.

2. Alur Kerja Sistem (User Journey)
[Customer Datang/Order] ➔ [Admin Input Data & Foto] ➔ [Sistem Cetak QR/Barcode & Kirim WA Invoice]
                                                                    │
[Customer Terima WA Selesai] ◀─ [Admin Ubah Status "Ready"] ◀─ [Staff Kerjakan Sepatu]
3. Spesifikasi Kebutuhan Fungsional (Fitur Utama)
M1: Manajemen Pengguna & Hak Akses (Auth)
Admin/Kasir: Menginput order, menerima pembayaran, mencetak invoice, mengubah status pengerjaan, mengirim notifikasi.

Workshop Staff (Opsional untuk fase berikutnya): Hanya melihat daftar antrean sepatu yang harus dicuci dan mengubah status dari "Queued" ke "In Progress".

Owner: Melihat laporan keuangan, statistik jenis cuci terlaris, dan performa tim.

M2: Manajemen Pelanggan (CRM Sederhana)
Mencatat data pelanggan berdasarkan Nomor WhatsApp (sebagai unique identifier).

Menyimpan riwayat order pelanggan untuk program loyalitas di masa depan (misal: 5x cuci gratis 1x).

M3: Modul Order Intake & Manajemen Item
Saat pelanggan memasukkan sepatu, admin menginput data dengan struktur sebagai berikut:

Detail Sepatu: Brand, Model, Warna, Ukuran, dan Bahan (Canvas, Suede, Leather, dll).

Kondisi Awal: Catatan minor (misal: lem mengelupas, noda darah, yellowing parah) + Fitur Upload Foto Kondisi Awal (sebagai bukti digital untuk menghindari komplain).

Jenis Layanan: Fast Clean, Deep Clean, Unyellowing, Repaint, Leather Care, dll.

M4: Sistem Transaksi & Invoice
Kalkulasi Otomatis: Sistem menghitung total biaya berdasarkan jenis layanan dan jumlah sepatu.

Metode Pembayaran: Tunai, Transfer Bank, atau QRIS (Bisa memilih Down Payment / DP, Lunas di awal, atau Bayar saat ambil).

Generate Invoice: Sistem otomatis membuat nomor invoice unik (Contoh: INV/202606/0001).

M5: Integrasi WhatsApp Gateway
Sistem terintegrasi dengan pihak ketiga (seperti Fonnte, Wablas, atau sejenisnya) untuk memicu pesan otomatis:

Trigger Order Masuk: Mengirimkan Link Invoice Digital dan detail sepatu segera setelah admin menyimpan data.

Trigger Selesai: Mengirimkan pesan pemberitahuan bahwa sepatu siap diambil beserta total sisa tagihan (jika ada).