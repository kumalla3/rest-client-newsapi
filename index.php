<?php
// load config.php (fungsi cURL + API key)
require_once __DIR__ . '/config/config.php';

// fungsi bantu: amankan teks sebelum ditampilkan di HTML
function e($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

// fungsi bantu: potong teks panjang
function potong($text, $panjang) {
    $text = trim((string)$text);
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($text, 0, $panjang, '...', 'UTF-8');
    }
    return strlen($text) > $panjang ? substr($text, 0, $panjang) . '...' : $text;
}

// pilihan kategori dan negara yang didukung NewsAPI (endpoint top-headlines)
$categories = array(
    'general'       => 'Umum',
    'technology'    => 'Teknologi',
    'business'      => 'Bisnis',
    'science'       => 'Sains',
    'health'        => 'Kesehatan',
    'sports'        => 'Olahraga',
    'entertainment' => 'Hiburan',
);
$countries = array(
    'us' => 'Amerika Serikat',
    'id' => 'Indonesia',
    'gb' => 'Inggris',
    'au' => 'Australia',
);

// ambil pilihan pengguna dari URL, default: technology + us (sesuai Tugas 6)
$category = isset($_GET['category']) ? $_GET['category'] : 'technology';
$country  = isset($_GET['country'])  ? $_GET['country']  : 'us';
$q        = isset($_GET['q']) ? trim($_GET['q']) : '';
if (!array_key_exists($category, $categories)) { $category = 'technology'; }
if (!array_key_exists($country, $countries))   { $country  = 'us'; }
if (strlen($q) > 100) { $q = substr($q, 0, 100); }

// parameter request (tanpa API key, aman untuk ditampilkan di halaman)
$params = array('country' => $country, 'category' => $category);
if ($q !== '') { $params['q'] = $q; }
$param_tampil = http_build_query($params);

$articles = array();
$total    = 0;
$error    = '';
$response = null;

if (NEWS_API_KEY === '' || NEWS_API_KEY === 'MASUKKAN_API_KEY_ANDA_DI_SINI') {
    $error = 'API key belum diisi. Salin config/apikey.example.php menjadi config/apikey.php, lalu isi API key Anda.';
} else {
    // url api untuk mengambil berita headline
    $url = 'https://newsapi.org/v2/top-headlines?' . http_build_query($params + array('apiKey' => NEWS_API_KEY));

    // kirim request dengan cURL
    $response = http_request_get($url);

    if ($response['body'] === false) {
        $error = 'Gagal terhubung ke NewsAPI: ' . $response['error'];
    } else {
        // konversi data json ke array
        $hasil = json_decode($response['body'], true);

        if (!is_array($hasil)) {
            $error = 'Response dari NewsAPI tidak dapat dibaca.';
        } elseif (!isset($hasil['status']) || $hasil['status'] !== 'ok') {
            $pesan = isset($hasil['message']) ? $hasil['message'] : 'terjadi kesalahan';
            $error = 'NewsAPI: ' . $pesan . ' (HTTP ' . $response['http_code'] . ')';
        } else {
            $articles = isset($hasil['articles']) ? $hasil['articles'] : array();
            $total    = isset($hasil['totalResults']) ? $hasil['totalResults'] : 0;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News Rest Client | NewsAPI + cURL</title>
    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- navbar -->
<nav class="navbar bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand brand" href="index.php"><span class="brand-dot"></span>RestClient <b>News</b></a>
        <span class="navbar-text small d-none d-sm-inline">Data dari NewsAPI.org</span>
    </div>
</nav>
<!-- navbar -->

<header class="hero">
    <div class="container">
        <h1>Berita Terkini</h1>
        <p class="hero-sub">
            Top headlines <?php echo e($countries[$country]); ?>, kategori <?php echo e($categories[$category]); ?>,
            diambil dari Public API NewsAPI.org menggunakan cURL di PHP.
        </p>

        <!-- form pencarian dan pilihan negara -->
        <form method="get" class="row g-2 search-form">
            <input type="hidden" name="category" value="<?php echo e($category); ?>">
            <div class="col-12 col-md">
                <input type="text" name="q" class="form-control" placeholder="Cari kata kunci, misalnya: AI"
                       value="<?php echo e($q); ?>">
            </div>
            <div class="col-8 col-md-3">
                <select name="country" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($countries as $kode => $nama): ?>
                        <option value="<?php echo e($kode); ?>" <?php echo $kode === $country ? 'selected' : ''; ?>><?php echo e($nama); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4 col-md-auto">
                <button type="submit" class="btn btn-primary w-100 px-4">Cari</button>
            </div>
        </form>

        <!-- pilihan kategori -->
        <div class="pills">
            <?php foreach ($categories as $kode => $nama): ?>
                <a class="pill <?php echo $kode === $category ? 'active' : ''; ?>"
                   href="?category=<?php echo e($kode); ?>&amp;country=<?php echo e($country); ?><?php echo $q !== '' ? '&amp;q=' . urlencode($q) : ''; ?>"><?php echo e($nama); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</header>

<main class="container pb-5">

    <!-- detail request: menampilkan endpoint dan hasil response -->
    <section class="req-card">
        <div class="req-title">Detail Request</div>
        <div class="req-url">
            <span class="badge-get">GET</span>
            <code>https://newsapi.org/v2/top-headlines?<?php echo e($param_tampil); ?>&amp;apiKey=********</code>
        </div>
        <?php if ($response !== null && $error === ''): ?>
            <div class="row req-stats text-center">
                <div class="col-4"><div class="stat-label">Status HTTP</div><div class="stat-value ok"><?php echo e($response['http_code']); ?> OK</div></div>
                <div class="col-4"><div class="stat-label">Waktu respons</div><div class="stat-value"><?php echo e($response['time_ms']); ?> ms</div></div>
                <div class="col-4"><div class="stat-label">Total hasil</div><div class="stat-value"><?php echo e($total); ?> berita</div></div>
            </div>
        <?php endif; ?>
    </section>

<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?php echo e($error); ?></div>
<?php elseif (count($articles) === 0): ?>
    <div class="alert alert-warning">Tidak ada berita untuk pilihan ini. Coba kata kunci, kategori, atau negara lain.</div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

    <!-- looping hasil data di array articles -->
    <?php foreach ($articles as $row):
        $judul = isset($row['title']) ? $row['title'] : '';
        if ($judul === '' || $judul === '[Removed]') { continue; }
        $gambar  = !empty($row['urlToImage']) ? $row['urlToImage'] : '';
        $sumber  = isset($row['source']['name']) ? $row['source']['name'] : 'Sumber tidak diketahui';
        $penulis = !empty($row['author']) ? potong($row['author'], 40) : '';
        $deskripsi = !empty($row['description']) ? potong($row['description'], 140) : '';
        $link    = isset($row['url']) ? $row['url'] : '#';
        $waktu   = (!empty($row['publishedAt']) && strtotime($row['publishedAt']))
                    ? date('d M Y, H:i', strtotime($row['publishedAt'])) : '';
    ?>
        <div class="col">
            <article class="news-card h-100">
                <a href="<?php echo e($link); ?>" target="_blank" rel="noopener noreferrer" class="news-img-wrap">
                    <?php if ($gambar !== ''): ?>
                        <img src="<?php echo e($gambar); ?>" class="news-img" alt="" loading="lazy"
                             onerror="this.outerHTML='<div class=\'news-placeholder\'>Gambar tidak tersedia</div>'">
                    <?php else: ?>
                        <div class="news-placeholder">Gambar tidak tersedia</div>
                    <?php endif; ?>
                </a>
                <div class="news-body">
                    <div class="news-source"><?php echo e($sumber); ?></div>
                    <h2 class="news-title">
                        <a href="<?php echo e($link); ?>" target="_blank" rel="noopener noreferrer"><?php echo e($judul); ?></a>
                    </h2>
                    <?php if ($deskripsi !== ''): ?>
                        <p class="news-desc"><?php echo e($deskripsi); ?></p>
                    <?php endif; ?>
                    <div class="news-foot">
                        <span class="news-meta">
                            <?php echo $penulis !== '' ? 'Oleh ' . e($penulis) . '<br>' : ''; ?>
                            <?php echo e($waktu); ?>
                        </span>
                        <a href="<?php echo e($link); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary">Selengkapnya</a>
                    </div>
                </div>
            </article>
        </div>
    <?php endforeach; ?>

    </div>
<?php endif; ?>

</main>

<footer class="site-footer">
    <div class="container text-center">
        Data berita dari <a href="https://newsapi.org" target="_blank" rel="noopener">NewsAPI.org</a>
        &middot; Dibuat dengan PHP, cURL, dan Bootstrap
    </div>
</footer>

<!-- JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
