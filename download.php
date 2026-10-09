<?php
require 'config.php';

$filename = isset($_GET['file']) ? $_GET['file'] : '';

if(!empty($filename)){
    // Menambahkan parameter ?download= pada URL Supabase untuk memaksa browser mengunduh file
    $download_url = SUPABASE_URL . '/storage/v1/object/public/' . SUPABASE_BUCKET . '/' . rawurlencode($filename) . '?download=';
    
    // Alihkan pengguna (redirect) ke tautan unduh Supabase
    header("Location: " . $download_url);
    exit;
} else {
    echo "Maaf, dokumen tidak ditemukan.";
}
?>