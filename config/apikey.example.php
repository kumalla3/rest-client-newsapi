<?php
/**
 * Salin file ini menjadi config/apikey.php, lalu isi API key dari newsapi.org.
 * File apikey.php sudah dimasukkan ke .gitignore supaya key tidak terupload ke GitHub.
 */
define('NEWS_API_KEY', 'MASUKKAN_API_KEY_ANDA_DI_SINI');

// Biarkan true. Ubah ke false HANYA di komputer lokal jika muncul
// error "SSL certificate problem" (lihat README).
define('SSL_VERIFY', true);
