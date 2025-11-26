# 🏫 e-Rapor SDN Slumbung 1

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Vercel](https://img.shields.io/badge/Vercel-000000?style=for-the-badge&logo=vercel&logoColor=white)

Sistem Informasi Manajemen Penilaian dan Rapor Siswa berbasis Web untuk **SDN Slumbung 1**. Aplikasi ini dirancang untuk mendigitalkan proses penilaian manual menjadi sistem yang terintegrasi, aman, dan efisien bagi Wali Kelas dan Administrator.

---

## 🌐 Live Demo

Aplikasi ini telah di-deploy menggunakan arsitektur *Hybrid Cloud*:
- **Frontend/App:** Vercel
- **Database:** Railway (MySQL)
- **Storage:** Cloudinary

🔗 **Link:** [https://projek-p-web-e-rapor-kraj.vercel.app/]

---

## 🚀 Fitur Unggulan

Aplikasi ini dikembangkan dengan fitur-fitur tingkat lanjut (Advanced Features) melebihi standar CRUD biasa:

### 👨‍💼 Modul Administrator
1.  **Manajemen Data Master:** Pengelolaan data Siswa, Guru, Mata Pelajaran, dan Kelas dengan relasi database yang kompleks.
2.  **Import Data Massal (Excel/CSV):**
    - Fitur cerdas untuk menginput ratusan data siswa/guru sekaligus.
    - Validasi otomatis untuk mencegah duplikasi NIS/NIP.
    - Mendukung format `.xlsx` dan `.csv`.
3.  **Export Data Dinamis:**
    - Download data siswa dalam format Excel.
    - Mendukung filter export (Semua Siswa atau Per Kelas).
4.  **Cloud Storage Integration:**
    - Upload foto profil siswa terintegrasi langsung dengan **Cloudinary**.
    - Hemat penyimpanan server dan mendukung deployment di server *ephemeral* (seperti Vercel/Heroku).

### 👩‍🏫 Modul Guru (Wali Kelas)
1.  **Bulk Input Nilai (Input Massal):**
    - Guru dapat menilai seluruh siswa dalam satu kelas sekaligus untuk satu mata pelajaran (UX yang efisien).
2.  **Kalkulasi Otomatis:**
    - Sistem otomatis menghitung Nilai Akhir (Rata-rata Pengetahuan & Keterampilan).
    - Konversi otomatis ke Predikat (A, B, C, D) dan Deskripsi Capaian.
3.  **Manajemen Non-Akademik:**
    - Input Absensi Harian atau Rekap Semester.
    - Penilaian Sikap (Spiritual & Sosial) dengan bantuan **Template Deskripsi** (Tinggal klik, tidak perlu ketik manual).
4.  **Validasi & Cetak Rapor:**
    - **Validasi Sistem:** Mencegah pencetakan rapor jika data nilai/absensi belum lengkap.
    - **Generate PDF:** Mencetak rapor siswa ke format PDF standar Kurikulum (Kertas A4) siap cetak.

---

## 🛠️ Teknologi & Library

- **Framework:** Laravel 11
- **Language:** PHP 8.2
- **Database:** MySQL / MariaDB
- **Frontend:** Blade Templates, Tailwind CSS, Alpine.js (Interactivity)
- **Packages:**
  - `maatwebsite/excel`: Untuk Import & Export data.
  - `barryvdh/laravel-dompdf`: Untuk generate Rapor PDF.
  - `cloudinary-labs/cloudinary-laravel`: Untuk penyimpanan foto di cloud.
  - `laravel/breeze`: Untuk sistem autentikasi.

---

## 💻 Instalasi Lokal (Cara Menjalankan)

Jika ingin menjalankan proyek ini di komputer sendiri (Localhost):

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/ADAM-RIZQI-FIRDAUSI/erapor-sdn-slumbung.git](https://github.com/ADAM-RIZQI-FIRDAUSI/erapor-sdn-slumbung.git)
    cd erapor-sdn-slumbung
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    - Copy file `.env.example` menjadi `.env`.
    - Atur koneksi database (DB_DATABASE, DB_USERNAME, dll).
    - **PENTING:** Masukkan kredensial Cloudinary di `.env`:
      ```env
      CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
      CLOUDINARY_UPLOAD_PRESET=
      CLOUDINARY_NOTIFICATION_URL=
      ```

4.  **Setup Database & Assets**
    ```bash
    php artisan key:generate
    php artisan migrate:fresh --seed
    npm run build
    ```
    *(Perintah `--seed` akan membuat akun Admin dan Guru default).*

5.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

---

## 🔑 Akun Demo

Gunakan akun berikut untuk pengujian sistem:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@sdnslumbung1.sch.id` | `admin123` |
| **Guru (Wali Kelas)** | `sitiaminah@sdnslumbung1.sch.id` | `guru123` |

---

## 👨‍💻 Pengembang

**Adam Rizqi Firdausi**
* **NIM:** 242410103010
* **Prodi:** Informatika
* **Universitas:** Universitas Jember

---
*Dibuat untuk memenuhi Tugas Akhir Mata Kuliah Pemrograman Web (PWEB).*
