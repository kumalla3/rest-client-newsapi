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

## Keamanan API Key

- Simpan API key di `config/apikey.php`.
- Jangan mengunggah API key asli ke GitHub.
- Pastikan `config/apikey.php` tercantum di `.gitignore`.

## Pengujian

| No. | Pengujian | Hasil yang Diharapkan |
| --- | --- | --- |
| 1 | Membuka halaman utama | Berita berhasil ditampilkan |
| 2 | Mengubah kategori | Daftar berita menyesuaikan kategori |
| 3 | Mengubah negara | Berita menyesuaikan negara |
| 4 | Melakukan pencarian | Berita sesuai kata kunci ditampilkan |
| 5 | Menggunakan API key yang salah | Pesan kesalahan ditampilkan |
| 6 | Membuka melalui HP | Tampilan tetap rapi dan responsif |

## Referensi

- [Dokumentasi NewsAPI](https://newsapi.org/docs/endpoints/top-headlines)
- [Tutorial Membuat Rest Client dengan cURL di PHP Bag 2](https://rudyekoprasetya.wordpress.com/2020/03/09/membuat-rest-client-dengan-curl-di-php-bag-2/)
- [Dokumentasi PHP cURL](https://www.php.net/manual/en/book.curl.php)
- [Dokumentasi Bootstrap 5](https://getbootstrap.com/docs/5.3/)

## Penulis

**Nabella Putri Kumalla**  
Program Studi Sistem Informasi, UNP Kediri
