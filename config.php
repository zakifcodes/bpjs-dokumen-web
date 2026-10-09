<?php
// 1. Kredensial Utama Supabase 
define('SUPABASE_URL', 'https://bcupruovjpnjnqydkjbr.supabase.co');
define('SUPABASE_ANON_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImJjdXBydW92anBuam5xeWRramJyIiwicm9sZSI6ImFub24iLCJpYXQiOjE3OTE1MjE3MTgsImV4cCI6MjEwNzA5NzcxOH0.-K1pHJIFipFRjQOssRpDlGZ9uVAHQDvLQCWNv5nWmNI'); 
define('SUPABASE_BUCKET', 'documents');

// 2. Fungsi Pengelola URL Storage (Digunakan untuk menampilkan dan mengunduh PDF)
function getStorageUrl($nama_file) {
    // Saat ini mengambil dari Supabase Storage
    return SUPABASE_URL . '/storage/v1/object/public/' . SUPABASE_BUCKET . '/' . rawurlencode($nama_file);
    
    // Jika suatu saat PDF dipindah ke tempat yang lebih besar cukup mengubah menjadi
    // return 'https://bucket-baru-s3.amazonaws.com/documents/' . rawurlencode($nama_file);
}
?>