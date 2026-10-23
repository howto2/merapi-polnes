"# merapi-polnes" 
# MERAPI POLNES

Website profil MERAPI POLNES (Mahasiswa Teknologi Informasi Pencinta Alam Indonesia), sebuah wadah mahasiswa di Jurusan Teknologi Informasi, Politeknik Negeri Samarinda. Website ini dikembangkan sebagai media informasi organisasi untuk memperkenalkan identitas, visi dan misi, divisi, serta informasi kegiatan kepada pengunjung.

## ✨ Fitur Utama

- **Profil Organisasi:** Menampilkan pengenalan dan identitas MERAPI POLNES.
- **Visi dan Misi:** Menyajikan tujuan serta arah organisasi.
- **Informasi Divisi:** Memperkenalkan divisi Lingkungan Hidup, Rock Climbing, dan Search and Rescue (SAR).
- **Informasi Berita dan Kegiatan:** Menyediakan bagian informasi berita serta dokumentasi kegiatan organisasi.
- **Akses Anggota dan Admin:** Menyediakan halaman login untuk anggota dan admin.
- **Pengelolaan Data:** Menggunakan PHP dan database MySQL untuk mendukung fungsi website yang terhubung dengan data.
- **Navigasi Interaktif:** Menggunakan JavaScript untuk mendukung interaksi pada antarmuka website.
- **Informasi Kontak:** Menampilkan informasi kontak organisasi.

## 🛠️ Teknologi yang Digunakan

- **HTML** — menyusun struktur halaman website.
- **CSS** — mengatur tampilan dan tata letak antarmuka.
- **JavaScript** — mendukung interaksi pada halaman website.
- **PHP** — menangani logika aplikasi sisi server.
- **MySQL** — menyimpan dan mengelola data aplikasi.
- **XAMPP** — dapat digunakan sebagai lingkungan server lokal untuk menjalankan PHP dan MySQL.

## 🚀 Instalasi dan Menjalankan Proyek

1. Clone repository ini:
   ```bash
   git clone https://github.com/howto2/merapi-polnes.git
   ```
2. Pindahkan folder proyek ke direktori `htdocs` jika menggunakan XAMPP.
3. Jalankan Apache dan MySQL melalui XAMPP Control Panel.
4. Buka phpMyAdmin dan buat database sesuai konfigurasi aplikasi.
5. Import file `dbweb_mrp.sql` ke database tersebut.
6. Sesuaikan konfigurasi koneksi database pada `koneksi.php` dengan pengaturan MySQL lokal.
7. Buka website melalui browser menggunakan alamat lokal yang sesuai dengan nama folder proyek.

> Catatan: Nama database dan konfigurasi koneksi harus disesuaikan dengan isi file SQL dan `koneksi.php`.

## 📁 Struktur Proyek

```text
merapi-polnes/
├── admin/             # Halaman atau modul admin
├── member/            # Halaman atau modul anggota
├── img/               # Aset gambar
├── uploads/           # Berkas yang diunggah
├── index.php          # Halaman utama website
├── koneksi.php        # Koneksi database
├── style.css          # Pengaturan tampilan
├── dbweb_mrp.sql      # Struktur dan data database
└── README.md          # Dokumentasi proyek
```

## 🎯 Tujuan Proyek

Mengembangkan website profil organisasi yang informatif dan mudah diakses, sekaligus menerapkan keterampilan pengembangan web, pengelolaan database, dan pembuatan antarmuka pengguna.

## 👨‍💻 Pengembang

**Olaf.Anchor.Tawon (OAT)**

GitHub: [howto2](https://github.com/howto2)

## 📌 Repository

[https://github.com/howto2/merapi-polnes](https://github.com/howto2/merapi-polnes)
