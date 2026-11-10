# News Rest Client (PHP + cURL + NewsAPI)

Aplikasi web sederhana untuk menampilkan berita terbaru dari [NewsAPI.org](https://newsapi.org) menggunakan **PHP cURL** dan **Bootstrap 5**.

Proyek ini dibuat untuk Tugas 7 mata kuliah Pengujian Perangkat Lunak, Program Studi Sistem Informasi, UNP Kediri.

## Tampilan Aplikasi

**Halaman utama**

![Halaman utama](screenshots/halaman-utama.png)

**Daftar berita**

![Daftar berita](screenshots/daftar-berita.png)

**Pilihan kategori dan negara**

![Kategori dan negara](screenshots/kategori-dan-negara.png)

## Fitur

- Menampilkan berita dari NewsAPI.
- Memilih kategori berita: umum, teknologi, bisnis, sains, kesehatan, olahraga, dan hiburan.
- Memilih negara: Amerika Serikat, Indonesia, Inggris, dan Australia.
- Mencari berita berdasarkan kata kunci.
- Menampilkan detail request, status HTTP, waktu respons, dan jumlah hasil.
- Menangani kesalahan API key dan koneksi.
- Tampilan responsif untuk komputer dan HP.

## Teknologi

| Teknologi | Kegunaan |
| --- | --- |
| PHP | Bahasa pemrograman sisi server |
| cURL | Mengirim request ke NewsAPI |
| NewsAPI.org | Sumber data berita |
| Bootstrap 5 | Tampilan dan tata letak |
| HTML dan CSS | Struktur dan desain halaman |
| XAMPP | Menjalankan aplikasi secara lokal |

## Struktur Folder

```text
rest-client-newsapi
├── config
│   ├── config.php
│   └── apikey.example.php
├── css
│   └── style.css
├── screenshots
├── index.php
├── .gitignore
└── README.md
```

## Persyaratan

- PHP 7.4 atau lebih baru dengan ekstensi **cURL** aktif (sudah aktif di XAMPP).
- Koneksi internet.
- API key gratis dari NewsAPI.org. Paket gratis (Developer) hanya berjalan dari `localhost`, bukan dari server publik.

## Cara Menjalankan

1. Unduh atau salin repository ini ke folder `htdocs`.
2. Jalankan **Apache** melalui XAMPP.
3. Daftar di [NewsAPI.org](https://newsapi.org) untuk mendapatkan API key.
4. Salin `config/apikey.example.php` menjadi `config/apikey.php`.
5. Masukkan API key ke dalam `config/apikey.php`:

```php
define('NEWS_API_KEY', 'ISI_API_KEY_ANDA');
define('SSL_VERIFY', true);
```

6. Buka aplikasi melalui browser:

```text
http://localhost/rest-client-newsapi/
```

Jika berhasil, berita akan tampil pada halaman utama.

## Mengatasi Error SSL di XAMPP

Jika muncul pesan `SSL certificate problem: unable to get local issuer certificate`:

1. Unduh `cacert.pem` dari <https://curl.se/ca/cacert.pem>.
2. Simpan di `C:\xampp\php\extras\ssl\cacert.pem`.
3. Buka `C:\xampp\php\php.ini`, lalu isi atau ubah baris berikut:

```ini
curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"
```

4. Restart Apache di XAMPP, lalu muat ulang halaman.

Hanya jika cara di atas gagal dan aplikasi dijalankan **di komputer lokal**, ubah `SSL_VERIFY` menjadi `false` di `config/apikey.php`. Jangan dipakai di server sebenarnya.

## Keamanan API Key

- Simpan API key di `config/apikey.php`.
- Jangan mengunggah API key asli ke GitHub.
- Pastikan `config/apikey.php` tercantum di `.gitignore`.

## Pengujian

Pengujian dilakukan secara manual pada aplikasi yang berjalan di XAMPP (`http://localhost/rest-client-newsapi/`).

| No. | Pengujian | Langkah | Hasil yang Diharapkan | Hasil Aktual | Status |
| --- | --- | --- | --- | --- | --- |
| 1 | Membuka halaman utama | Buka `index.php` | Berita teknologi AS tampil, status 200 OK | Berita tampil, 200 OK, 54 berita (lihat `screenshots/halaman-utama.png`) | Lulus |
| 2 | Mengubah kategori | Klik tombol "Bisnis" | Request memakai `category=business`, daftar berita menyesuaikan | Request menjadi `category=business`, 200 OK, 55 berita (lihat `screenshots/kategori-dan-negara.png`) | Lulus |
| 3 | Mengubah negara | Pilih "Indonesia" pada dropdown | Request memakai `country=id` | Request menjadi `country=id&category=technology`, 200 OK, 0 berita. Muncul pesan "Tidak ada berita untuk pilihan ini" | Lulus |
| 4 | Melakukan pencarian | Ketik kata kunci "Indonesia" pada kategori Teknologi AS, klik Cari | Request memuat parameter `q` | Request menjadi `country=us&category=technology&q=Indonesia`, 200 OK, 0 berita. Muncul pesan "Tidak ada berita untuk pilihan ini" | Lulus |
| 5 | API key salah | Isi key asal (`abc123`) di `config/apikey.php` | Pesan kesalahan "NewsAPI: ... (HTTP 401)" | Muncul pesan merah "NewsAPI: Your API key is invalid or incorrect. Check your key, or go to https://newsapi.org to create a free API key. (HTTP 401)". API key pada Detail Request tetap tersamar (`********`) | Lulus |
| 6 | API key belum diisi | Ubah nama `config/apikey.php` menjadi `apikey_backup.php`, lalu muat ulang halaman | Muncul pesan "API key belum diisi" | Muncul pesan merah "API key belum diisi. Salin config/apikey.example.php menjadi config/apikey.php, lalu isi API key Anda." Halaman tidak error dan tidak ada request yang dikirim ke NewsAPI | Lulus |

Catatan: hasil 0 berita pada pengujian 3 dan 4 berasal dari API (tidak ada headline yang cocok), bukan kesalahan aplikasi. Request tetap berstatus 200 OK dan aplikasi menampilkan pesan, bukan halaman kosong.

## Referensi

- [Dokumentasi NewsAPI](https://newsapi.org/docs/endpoints/top-headlines)
- [Tutorial Membuat Rest Client dengan cURL di PHP Bag 2](https://rudyekoprasetya.wordpress.com/2020/03/09/membuat-rest-client-dengan-curl-di-php-bag-2/)
- [Dokumentasi PHP cURL](https://www.php.net/manual/en/book.curl.php)
- [Dokumentasi Bootstrap 5](https://getbootstrap.com/docs/5.3/)

## Penulis

**Nabella Putri Kumalla**  
Program Studi Sistem Informasi, UNP Kediri
