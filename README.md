# E-Learning Platform (LMS)

![Status](https://img.shields.io/badge/Status-Active-success)
![Laravel](https://img.shields.io/badge/Laravel-10-red)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)
![Tailwind](https://img.shields.io/badge/Tailwind-CSS-38B2AC)
![License](https://img.shields.io/badge/License-MIT-green)

**E-Learning Platform** adalah sistem manajemen pembelajaran (LMS) modern yang dirancang untuk memfasilitasi interaksi antara Administrator, Guru, dan Siswa secara efisien. Dibangun dengan fokus pada kemudahan penggunaan, performa cepat, dan fitur kolaboratif yang lengkap.

<img width="2513" height="1412" alt="image" src="https://github.com/user-attachments/assets/965393ad-35c3-4c80-8774-f65d26fbbde3" />

<img width="2543" height="1399" alt="image" src="https://github.com/user-attachments/assets/89f24188-84a7-49db-b528-db70e6d64899" />

<img width="2543" height="1401" alt="image" src="https://github.com/user-attachments/assets/29c8be95-2ca4-47fe-966c-f23936d9fa21" />

---

## Fitur Utama Berdasarkan Peran

### Administrator (Control Center)

* **Dashboard Overview**: Ringkasan statistik pengguna dan kursus aktif.
* **User Management**: Kendali penuh untuk mengelola akun Guru dan Siswa.
* **Course Monitoring**: Pengawasan seluruh aktivitas kursus di platform.
* **Data Analytics**: Ekspor data pengguna dalam format Excel/CSV untuk pelaporan.

### Guru (Creative Learning)

* **Course Mastery**: Kelola materi, tugas, dan ujian dengan antarmuka intuitif.
* **Smart Engagement**: Fitur *Pin/Unpin* komentar pada materi untuk menyoroti diskusi penting.
* **Automated Gradebook**: Pelacakan nilai otomatis yang bisa diekspor langsung ke Excel.
* **Dynamic Leaderboard**: Visualisasi peringkat siswa berdasarkan performa akademik.
* **Attendance Tracker**: Manajemen kehadiran siswa di setiap sesi materi.

### Siswa (Interactive Study)

* **Course Explorer**: Telusuri dan daftar ke berbagai kursus yang tersedia.
* **Progress Tracking**: Visualisasi kemajuan belajar secara *realtime* untuk setiap materi.
* **Realtime Collaboration**: Fitur chat langsung dengan guru dan teman sekelas.
* **Assessment Center**: Kerjakan tugas dan kuis interaktif dengan umpan balik cepat.
* **Smart Notifications**: Pemberitahuan otomatis untuk tugas baru dan pengumuman kursus.

---

## Tech Stack & Dependencies

* **Framework**: [Laravel 10](https://laravel.com/)
* **Frontend**: [Tailwind CSS](https://tailwindcss.com/) & Blade Templating
* **Starter Kit**: [Laravel Breeze](https://laravel.com/docs/10.x/starter-kits#laravel-breeze) (for Auth)
* **Excel Module**: [Maatwebsite Excel](https://laravel-excel.com/) (for Reporting)
* **Database**: MySQL / PostgreSQL
* **Realtime**: Pusher / WebSocket Integration Ready

---

## Prasyarat Sistem

Pastikan environment Anda sudah memiliki:

1. **PHP v8.1+**
2. **Composer** (Dependency Manager)
3. **Node.js & npm** (Frontend Build Tool)
4. **MySQL Database Server**

---

## Cara Instalasi (Langkah Demi Langkah)

Ikuti langkah sukses ini untuk menjalankan proyek di lokal:

### 1. Clone Repository

```bash
git clone https://github.com/SinggihHakim/e-learning.git
cd e-learning
```

### 2. Setup Backend (PHP)

```bash
composer install
```

* Copy file `.env.example` ke `.env`
* Konfigurasi database di `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
* Generate App Key:

```bash
php artisan key:generate
```

### 3. Setup Frontend (JavaScript)

```bash
npm install
npm run build
```

### 4. Migrasi Database & Seeding

Jalankan perintah ini untuk membuat struktur tabel dan mengisi data contoh:

```bash
php artisan migrate --seed
```

### 5. Jalankan Aplikasi

```bash
php artisan serve
```

Akses di: `http://localhost:8000`

---

## Troubleshooting (Masalah Umum)

**Q: Database tidak terdeteksi saat `migrate`?**

* **Solusi**: Pastikan database server (XAMPP/MySQL) sudah menyala dan nama database di `.env` sudah sesuai (default: `lms`).

**Q: Gambar atau file tidak muncul?**

* **Solusi**: Jalankan perintah symlink storage:

```bash
php artisan storage:link
```

**Q: CSS atau JS tidak terbaca?**

* **Solusi**: Pastikan Anda sudah menjalankan `npm run build` atau gunakan `npm run dev` saat pengembangan.

---

## Kontribusi

Selamat berkontribusi! Fork repo ini dan berikan Pull Request terbaik Anda.

Dibuat oleh **Singgih Hakim**

