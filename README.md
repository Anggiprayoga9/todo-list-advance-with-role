# Aplikasi Manajemen Tugas Sederhana (Laravel - Todo App)

Aplikasi ini adalah sistem manajemen tugas sederhana berbasis web yang dibangun menggunakan **Laravel**. Dirancang untuk membantu pengguna mengelola daftar tugas harian, dengan fitur khusus untuk membedakan peran (Admin dan User) dan memprioritaskan tugas berdasarkan batas waktu (deadline).

## Fitur Utama

### 1. Sistem Otentikasi dan Peran (Role-Based Access)

-   **Admin:** Memiliki hak akses penuh, dapat mengalokasikan (meng-assign) tugas ke User lain, dan melihat semua tugas di sistem.
-   **User Biasa:** Hanya dapat melihat, menyelesaikan, dan menghapus tugas yang dialokasikan kepadanya, bisa juga menambahkan tugas untuk diri sendiri.
-   Otorisasi (Authorization) menggunakan Laravel Gates/Policies memastikan hanya User yang berhak yang dapat memodifikasi tugas.

### 2. Logika Prioritas dan Pengurutan Cerdas

Daftar tugas diurutkan secara otomatis untuk memprioritaskan pekerjaan yang paling mendesak (sesuai Best Practice yang diterapkan):

1.  **Tugas Belum Selesai (Pending)** selalu berada di atas Tugas Selesai.
2.  Di antara yang belum selesai, tugas **Overdue** (Melewati Deadline) selalu berada di urutan teratas.
3.  Tugas Pending diurutkan berdasarkan **Deadline Terdekat**.
4.  Tugas Tanpa Deadline ditempatkan di bagian paling bawah kelompok Belum Selesai.

[Image of Task Prioritization Matrix (Urgent/Important)]

### 3. Pelaporan Status dan Progress yang Jelas

Halaman Progress (Laporan) memisahkan tugas ke dalam kategori yang jelas untuk analisis performa tim:

-   **Total Tugas**
-   **Selesai**
-   **Pending Murni** (Belum Overdue, Aktif)
-   **Overdue** (Belum Selesai, Lewat Deadline)

### 4. Integritas Data dan Validasi Ketat

-   **Anti-Backdating:** Tidak diizinkan membuat tugas dengan deadline di masa lalu (`after_or_equal:today`).
-   **Validasi Bersyarat:** Admin **wajib** mengisi deadline saat mengalokasikan tugas, sementara User biasa boleh mengosongkannya (untuk fleksibilitas to-do list pribadi).

## Persyaratan Sistem

-   PHP >= 8.1
-   Composer
-   Database (MySQL, PostgreSQL, SQLite, dsb.)
-   Laravel Framework ^10.x atau ^11.x

## Instalasi dan Deployment

Ikuti langkah-langkah berikut untuk menjalankan aplikasi ini secara lokal:

### 1. Clone Repositori

```bash
git clone [URL-REPOSITORI-ANDA]
cd nama-folder-proyek
```

### 2. Edit Environment
cp .env.example .env
php artisan key:generate

#### Edit .env databasase
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_todo
DB_USERNAME=root
DB_PASSWORD=

### 3. Instalasi Dependensi
composer install
php artisan migrate --seed

#### admin account:

username/email:
admin@example.com

password:
password

#### user account:
1. username:
anggiprayoga817@gmail.com
password:
password123

2. username:
anggi222@gmail.com
password:
anggi123

Import database terlebih dahulu untuk menggunakan data user.
Untuk user bisa juga resgistrasi akun terlebih dahulu, dengan catatan harus set up bagian berikut di .env seperti berikut:

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io (contoh menggunakan mailtrap untuk testing email)
MAIL_PORT=2525
MAIL_USERNAME=xxxxxxxxx
MAIL_PASSWORD=xxxxxxxx

jika sudah jalankan aplikasi dan mulai registrasi akun user.

#### Next Step for Start The App
npm install
npm run dev
php artisan serve


Aplikasi kini dapat diakses di http://127.0.0.1:8000
