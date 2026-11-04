<?php
/**
 * config/config.php
 * Berisi fungsi cURL untuk melakukan request GET ke Public API (NewsAPI.org)
 * dan memuat API key dari file config/apikey.php (file ini TIDAK ikut ke GitHub).
 */

$keyFile = __DIR__ . '/apikey.php';
if (file_exists($keyFile)) {
    require $keyFile;
}
if (!defined('NEWS_API_KEY')) {
    define('NEWS_API_KEY', '');
}
if (!defined('SSL_VERIFY')) {
    define('SSL_VERIFY', true);
}

/**
 * Mengirim request GET menggunakan cURL.
 *
 * @param string $url alamat endpoint lengkap beserta parameter
 * @return array ['body' => isi response / false, 'http_code' => kode HTTP,
 *                'time_ms' => lama request (ms), 'error' => pesan error cURL]
 */
function http_request_get($url) {
    // persiapkan curl
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, $url);

    // konversi hasil ke string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // deteksi user agent (NewsAPI mewajibkan User-Agent)
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36');

    // batas waktu request (detik)
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    // verifikasi sertifikat SSL (lihat README jika muncul error SSL di XAMPP)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, SSL_VERIFY);

    // eksekusi
    $body = curl_exec($ch);

    $result = array(
        'body'      => $body,
        'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
        'time_ms'   => round(curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000),
        'error'     => curl_error($ch),
    );

    // tutup curl (di PHP 8+ handle otomatis ditutup)
    if (PHP_VERSION_ID < 80000) {
        curl_close($ch);
    }

    // mengembalikan hasil curl
    return $result;
}
