# Panduan Penggunaan Aplikasi ISOC — Internet Society Indonesia Jakarta Chapter

## Daftar Isi

1. [Gambaran Umum](#gambaran-umum)
2. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
3. [Instalasi & Setup](#instalasi--setup)
4. [Struktur Halaman Publik](#struktur-halaman-publik)
5. [Panel Admin (Filament)](#panel-admin-filament)
   - [Login Admin](#login-admin)
   - [Dashboard](#dashboard)
   - [Manajemen Sections](#manajemen-sections)
   - [Manajemen Team Members](#manajemen-team-members)
   - [Manajemen Partners (Mitra)](#manajemen-partners-mitra)
   - [Manajemen Events](#manajemen-events)
   - [Manajemen Event Registrations](#manajemen-event-registrations)
   - [Manajemen Site Settings](#manajemen-site-settings)
   - [Manajemen Users](#manajemen-users)
6. [Portal Peserta](#portal-peserta)
7. [Fitur Registrasi Event](#fitur-registrasi-event)
8. [Fitur Kehadiran & Sertifikat](#fitur-kehadiran--sertifikat)
9. [Multi-Language (Terjemahan)](#multi-language-terjemahan)
10. [Role & Permission](#role--permission)

---

## Gambaran Umum

Aplikasi ini adalah website resmi **Internet Society Indonesia Jakarta Chapter (ISOC)**, dibangun dengan Laravel + Filament sebagai CMS admin panel. Aplikasi mengelola konten halaman publik, event, registrasi peserta, kehadiran, sertifikat, dan informasi organisasi.

**Fitur utama:**
- CMS berbasis Filament untuk seluruh konten website
- Manajemen event lengkap: registrasi, kehadiran, sertifikat
- Portal peserta (login dengan kode registrasi)
- Multi-language (Indonesia & English)
- Role-based access control (Spatie Permission)
- Verifikasi sertifikat publik via QR code

---

## Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| Framework | Laravel 11 |
| Admin Panel | Filament v3 |
| Database | MariaDB 10.11 |
| Web Server | Nginx (SSL) |
| Runtime | PHP-FPM (Docker) |
| Frontend | Tailwind CSS, Vite |
| Multi-lang | Spatie Translatable |
| Roles | Spatie Permission |
| PDF | DomPDF (Barryvdh) |
| Container | Docker Compose |

---

## Instalasi & Setup

### Prasyarat
- Docker & Docker Compose terinstal
- Port 80, 443, dan 13306 tersedia

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repo-url> isoc
cd isoc

# 2. Jalankan container Docker
docker compose up -d

# 3. Masuk ke container PHP
docker exec -it isoc_php bash

# 4. Install dependensi PHP
composer install

# 5. Salin file environment
cp .env.example .env

# 6. Generate application key
php artisan key:generate

# 7. Jalankan migrasi dan seeder
php artisan migrate --seed

# 8. Build aset frontend
npm install && npm run build

# 9. Buat symlink storage
php artisan storage:link
```

### Konfigurasi Environment (`.env`)

Variabel penting yang perlu diatur:

```env
APP_NAME="ISOC Jakarta"
APP_URL=https://isoc.test

DB_HOST=db
DB_DATABASE=isoc
DB_USERNAME=root
DB_PASSWORD=p455w0rd

FILESYSTEM_DISK=public
```

### Akses Aplikasi

| URL | Keterangan |
|---|---|
| `https://isoc.test/` | Website publik |
| `https://isoc.test/admin` | Panel admin Filament |
| `https://isoc.test/portal` | Portal peserta |

> **Catatan:** Tambahkan `127.0.0.1 isoc.test` ke file `/etc/hosts` jika menggunakan domain lokal.

---

## Struktur Halaman Publik

| URL | Halaman |
|---|---|
| `/` | Beranda (Home) |
| `/about` | Tentang Kami |
| `/programs` | Program |
| `/events` | Daftar Event |
| `/our-partner` | Mitra |
| `/resources` | Sumber Daya |
| `/events/{event}/register` | Form Pendaftaran Event |
| `/verify/{code}` | Verifikasi Sertifikat |
| `/portal` | Portal Peserta |

### Ganti Bahasa

Website mendukung dua bahasa: **Indonesia (id)** dan **English (en)**.

Untuk beralih bahasa, gunakan URL:
- `/lang/id` → mengatur bahasa ke Indonesia
- `/lang/en` → mengatur bahasa ke English

---

## Panel Admin (Filament)

### Login Admin

Akses panel admin di: `https://isoc.test/admin`

Masukkan **email** dan **password** akun admin yang sudah dibuat. Akun admin default dibuat melalui seeder atau perintah:

```bash
php artisan make:filament-user
```

---

### Dashboard

Halaman utama panel admin menampilkan:
- Widget **Latest Access Logs** — log aktivitas terbaru di sistem
- Ringkasan navigasi ke seluruh resource

---

### Manajemen Sections

**Menu:** `Content Management → Sections`

Section adalah blok konten yang ditampilkan di setiap halaman publik. Setiap section diidentifikasi dengan kombinasi **page** + **key**.

#### Halaman yang Tersedia

| Nilai `page` | Halaman Publik |
|---|---|
| `home` | Beranda |
| `about` | Tentang Kami |
| `programs` | Program |
| `events` | Event |
| `mitra` | Mitra |

#### Key Section Bawaan (Seeder)

| Page | Key | Keterangan |
|---|---|---|
| `home` | `hero` | Banner utama beranda |
| `home` | `mission` | Blok misi organisasi |
| `home` | `featured_programs` | Program unggulan |
| `home` | `impact_stats` | Statistik dampak |
| `about` | `hero` | Banner halaman About |
| `about` | `history` | Sejarah organisasi |
| `about` | `team` | Anggota tim |
| `programs` | `hero` | Banner halaman Programs |
| `events` | `hero` | Banner halaman Events |
| `mitra` | `hero` | Banner halaman Mitra |

#### Field Section

| Field | Keterangan |
|---|---|
| **Page** | Halaman tempat section ini ditampilkan |
| **Key** | Identifier unik section dalam satu halaman |
| **Title** | Judul section (multi-language) |
| **Subtitle** | Subjudul section (multi-language) |
| **Description** | Deskripsi section (multi-language) |
| **Image** | Gambar section (opsional) |
| **Button Text / URL** | Tombol aksi utama (opsional) |
| **Secondary Button** | Tombol aksi sekunder (opsional) |
| **Order** | Urutan tampil (angka kecil = tampil lebih atas) |
| **Is Active** | Aktif/nonaktif section |

#### Section Items

Setiap section dapat memiliki **item turunan** (sub-konten), misalnya item program, item statistik, dll. Kelola section items melalui tab **Items** di halaman edit section.

---

### Manajemen Team Members

**Menu:** `Content Management → Team Members`

Kelola daftar anggota tim yang ditampilkan di halaman About.

| Field | Keterangan |
|---|---|
| **Name** | Nama anggota tim |
| **Position** | Jabatan/posisi |
| **Photo** | Foto profil (upload gambar) |
| **Order** | Urutan tampil (drag & drop tersedia) |
| **Is Active** | Tampilkan/sembunyikan anggota |

> Urutan anggota tim dapat diatur dengan **drag and drop** di tampilan tabel.

---

### Manajemen Partners (Mitra)

**Menu:** `Content Management → Partners`

Kelola daftar mitra yang ditampilkan di halaman Mitra.

| Field | Keterangan |
|---|---|
| **Name** | Nama mitra |
| **Subtitle** | Tagline/subjudul mitra |
| **Description** | Deskripsi singkat |
| **Logo** | Upload logo mitra |
| **Logo URL** | URL logo eksternal (fallback jika tidak upload) |
| **URL** | Website mitra |
| **Type** | `international` atau `national` |
| **Order** | Urutan tampil (drag & drop tersedia) |
| **Is Active** | Tampilkan/sembunyikan mitra |

---

### Manajemen Events

**Menu:** `Content Management → Events`

Kelola seluruh event/kegiatan organisasi.

#### Field Event

**Event Info:**
| Field | Keterangan |
|---|---|
| **Title** | Judul event (multi-language) |
| **Category** | Kategori event (cth: Webinar, Workshop, Panel Diskusi) |
| **Description** | Deskripsi event (multi-language) |

**Schedule & Location:**
| Field | Keterangan |
|---|---|
| **Date** | Tanggal event |
| **Time Info** | Info waktu (cth: `14:00 - 16:00 WIB`) |
| **Location** | Lokasi event (multi-language) |
| **Location Type** | `Online`, `Offline`, atau `Hybrid` |
| **Capacity Info** | Info kapasitas (cth: `250+ Peserta`) |
| **Registration URL** | URL pendaftaran eksternal (opsional) |
| **Max Participants** | Batas maksimal peserta (kosongkan = tidak terbatas) |
| **Registration Open** | Buka/tutup pendaftaran internal via portal |
| **Attendance Code** | Kode kehadiran rahasia untuk verifikasi di lokasi |

**Media & Settings:**
| Field | Keterangan |
|---|---|
| **Image** | Gambar/banner event |
| **Order** | Urutan tampil |
| **Is Featured** | Tampilkan sebagai event unggulan di beranda |
| **Is Active** | Aktif/nonaktif event |

**Certificate Template:**
- Kolom HTML kustom untuk template sertifikat
- Kosongkan untuk menggunakan template default
- Placeholder yang tersedia:
  - `{{participant_name}}`, `{{participant_email}}`, `{{participant_organization}}`, `{{participant_position}}`
  - `{{registration_code}}`, `{{event_title}}`, `{{event_date}}`, `{{event_date_id}}`
  - `{{event_location}}`, `{{event_time}}`, `{{event_category}}`
  - `{{attended_at}}`, `{{current_year}}`

#### Aksi di Tabel Event

| Aksi | Keterangan |
|---|---|
| **Generate Kode Hadir** | Generate kode kehadiran otomatis (format: `ISOCddmmYYYY`) |
| **View Kode Hadir** | Tampilkan kode kehadiran aktif |
| **Edit** | Edit detail event |

---

### Manajemen Event Registrations

**Menu:** `Content Management → Event Registrations`

Kelola seluruh pendaftaran peserta event.

#### Field Registrasi

| Field | Keterangan |
|---|---|
| **Event** | Event yang didaftari |
| **Registration Code** | Kode unik registrasi (otomatis, format: `REG-XXXXXXXX`) |
| **Name** | Nama peserta |
| **Email** | Email peserta |
| **Phone** | Nomor telepon |
| **Organization** | Instansi/organisasi |
| **Position** | Jabatan/posisi |
| **Is Speaker** | Centang jika peserta adalah narasumber/pemateri |
| **Motivation** | Motivasi mengikuti event |
| **Status** | `Pending`, `Confirmed`, atau `Cancelled` |
| **Attended At** | Waktu kehadiran (diisi saat peserta hadir) |
| **Certificate** | File sertifikat PDF |

#### Aksi di Tabel Registrasi

| Aksi | Keterangan |
|---|---|
| **Confirm** | Konfirmasi registrasi (dari Pending → Confirmed) |
| **Cancel** | Batalkan registrasi |
| **Mark Attended** | Tandai peserta sebagai hadir |
| **Generate Speaker Cert** | Generate sertifikat PDF untuk narasumber |
| **Upload Cert** | Upload sertifikat PDF manual |
| **Preview Cert** | Pratinjau sertifikat peserta |
| **Edit** | Edit data registrasi |

#### Aksi Massal (Bulk Actions)

| Aksi | Keterangan |
|---|---|
| **Confirm Selected** | Konfirmasi beberapa registrasi sekaligus |
| **Cancel Selected** | Batalkan beberapa registrasi sekaligus |
| **Mark Attended** | Tandai kehadiran beberapa peserta |
| **Generate Speaker Certs** | Generate sertifikat untuk beberapa narasumber |

---

### Manajemen Site Settings

**Menu:** `Content Management → Site Settings`

Kelola pengaturan global situs yang dapat digunakan di berbagai halaman.

| Field | Keterangan |
|---|---|
| **Key** | Identifier unik pengaturan |
| **Group** | Kelompok: `general`, `footer`, `social`, `seo` |
| **Value** | Nilai pengaturan (multi-language) |

#### Contoh Key Setting

| Key | Group | Contoh Value |
|---|---|---|
| `site_name` | general | Internet Society Indonesia Jakarta |
| `footer_description` | footer | Kami mendukung internet yang aman... |
| `social_instagram` | social | https://instagram.com/isoc.id |
| `social_twitter` | social | https://twitter.com/isoc_id |
| `meta_description` | seo | Website ISOC Jakarta Chapter |

---

### Manajemen Users

**Menu:** `Administration → Users`

Kelola akun pengguna panel admin.

| Field | Keterangan |
|---|---|
| **Name** | Nama pengguna |
| **Avatar** | Foto profil (upload, dioptimasi ke WebP) |
| **Email** | Alamat email (unik) |
| **Password** | Password (dienkripsi, isi hanya jika ingin mengubah) |
| **Roles** | Role yang diberikan ke user |

> **Catatan:** Jumlah total user ditampilkan sebagai badge di menu navigasi.

---

## Portal Peserta

**URL:** `https://isoc.test/portal/login`

Portal peserta memungkinkan peserta event untuk:
- Melihat daftar event yang diikuti
- Mengunduh sertifikat kehadiran
- Melakukan verifikasi kehadiran dengan kode

### Cara Login Portal Peserta

1. Buka `https://isoc.test/portal/login`
2. Masukkan **email** dan **kode registrasi** (format: `REG-XXXXXXXX`)
3. Klik tombol **Login**

> Jika baru pertama kali login, peserta akan diarahkan untuk mengatur password.

### Fitur Portal Peserta

| Fitur | Keterangan |
|---|---|
| **Dashboard** | Ringkasan statistik: total event, dikonfirmasi, hadir, sertifikat |
| **Daftar Event** | Semua event yang pernah didaftarkan |
| **Detail Event** | Informasi lengkap event beserta status registrasi |
| **Verifikasi Kehadiran** | Masukkan kode kehadiran yang diumumkan saat event |
| **Download Sertifikat** | Unduh sertifikat dalam format PDF |
| **Preview Sertifikat** | Pratinjau sertifikat di browser |

---

## Fitur Registrasi Event

### Alur Pendaftaran Peserta

1. Pengunjung membuka halaman `/events`
2. Klik event yang ingin didaftari
3. Klik tombol **Daftar Sekarang**
4. Isi form pendaftaran:
   - Nama lengkap
   - Email
   - Nomor telepon
   - Instansi/organisasi
   - Jabatan
   - Motivasi mengikuti event
   - Verifikasi CAPTCHA
5. Klik **Submit** → status awal: **Pending**
6. Admin mengkonfirmasi pendaftaran → status: **Confirmed**
7. Peserta menerima kode registrasi (`REG-XXXXXXXX`)

### Batasan Pendaftaran

- Pendaftaran hanya bisa dilakukan jika:
  - Field **Registration Open** aktif (centang)
  - Event berstatus **Is Active**
  - Jumlah peserta belum mencapai **Max Participants**

---

## Fitur Kehadiran & Sertifikat

### Alur Verifikasi Kehadiran

#### Via Admin Panel

1. Buka menu **Event Registrations**
2. Cari peserta berdasarkan nama / email / kode registrasi
3. Klik aksi **Mark Attended** → waktu kehadiran otomatis direkam

#### Via Portal Peserta

1. Peserta login ke portal
2. Buka detail event
3. Masukkan **kode kehadiran** yang diumumkan oleh panitia di lokasi event
4. Klik **Verifikasi** → status kehadiran tercatat

#### Via QR Code (Admin)

Admin dapat memindai QR code peserta melalui:
`https://isoc.test/admin/verify-attendance/{kode_registrasi}`

### Alur Penerbitan Sertifikat

#### Sertifikat Peserta Umum
1. Admin menandai peserta sebagai hadir (**Attended**)
2. Admin mengupload PDF sertifikat via aksi **Upload Cert**, atau
3. Sistem menggunakan template HTML dari kolom **Certificate Template** event

#### Sertifikat Narasumber
1. Tandai registrasi sebagai **Is Speaker**
2. Klik aksi **Generate Speaker Cert**
3. Sertifikat otomatis dibuat dalam format PDF dan disimpan

### Verifikasi Sertifikat Publik

Sertifikat dapat diverifikasi oleh siapapun melalui:
```
https://isoc.test/verify/{kode_registrasi}
```

---

## Multi-Language (Terjemahan)

Aplikasi mendukung dua bahasa: **Bahasa Indonesia (id)** dan **English (en)**.

### Di Panel Admin

Saat mengedit konten yang mendukung multi-language (Section, Event, Partner, dll.), tersedia **tab bahasa** di bagian atas form untuk beralih antara versi Indonesia dan English.

Field yang mendukung terjemahan:
- **Section:** title, subtitle, description, button_text, secondary_button_text
- **Event:** title, description, category, time_info, location, capacity_info
- **Partner:** name, subtitle, description
- **Team Member:** position
- **Site Settings:** value

### Di Website Publik

Pengunjung dapat beralih bahasa via:
- Link `/lang/id` (Indonesia)
- Link `/lang/en` (English)

Pilihan bahasa disimpan dalam sesi browser.

---

## Role & Permission

Aplikasi menggunakan **Spatie Permission** untuk manajemen role dan hak akses.

### Cara Assign Role ke User

1. Buka **Administration → Users**
2. Edit user yang diinginkan
3. Di bagian **Roles**, pilih role yang sesuai
4. Simpan

### Membuat Role Baru

Gunakan Filament Shield atau via artisan:

```bash
php artisan shield:generate --all
```

### Akses Panel Admin

Hanya user yang memiliki akses Filament (`FilamentUser`) yang dapat login ke panel admin. Akses ditentukan oleh method `canAccessPanel()` pada model `User`.

---

## Perintah Artisan Berguna

```bash
# Jalankan migrasi terbaru
php artisan migrate

# Reset dan seed ulang database
php artisan migrate:fresh --seed

# Buat symlink storage
php artisan storage:link

# Bersihkan cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generate role & permission untuk Filament Shield
php artisan shield:generate --all

# Buat user admin baru
php artisan make:filament-user
```

---

*Panduan ini dibuat berdasarkan kode sumber aplikasi ISOC Jakarta Chapter. Versi terakhir diperbarui: April 2026.*
