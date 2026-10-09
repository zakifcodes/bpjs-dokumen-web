<?php
require 'config.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$dokumen_list = [];

// Membangun URL API Supabase
$api_url = SUPABASE_URL . '/rest/v1/dokumen?select=*';

// Jika ada pencarian, gunakan operator 'ilike' untuk pencarian case-insensitive[cite: 3]
if (!empty($search)) {
    $api_url .= '&judul=ilike.*' . urlencode($search) . '*';
}

// Urutkan berdasarkan waktu penambahan terbaru
$api_url .= '&order=created_at.desc';

// Eksekusi API via cURL
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . SUPABASE_ANON_KEY,
    'Authorization: Bearer ' . SUPABASE_ANON_KEY
]);
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $dokumen_list = json_decode($response, true);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Standar Pelayanan Kesehatan BPJS Ketenagakerjaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bpjs: {
                            blue: '#0056A6', 
                            white: '#ffffff', 
                            dark: '#1e293b',
                            yellow: '#FDB813'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { overflow-x: hidden; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- HEADER / NAVBAR DENGAN SEARCH BAR -->
    <header class="bg-bpjs-blue text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Kiri: Logo Area -->
                <div class="flex items-center gap-3">
                    <img src="img/logobpjstk.png" alt="Logo BP Jamsostek" class="h-12 w-auto p-1 rounded">
                </div>
                
                <!-- Kanan: Desktop Nav & Search Bar -->
                <nav class="hidden md:flex items-center gap-6 font-medium">
                    <a href="index.php" class="hover:text-bpjs-yellow transition-colors flex items-center gap-2">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    
                    <!-- Search Bar -->
                    <form action="index.php" method="GET" class="relative">
                        <input type="text" name="search" placeholder="Cari dokumen..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                               class="pl-10 pr-4 py-2 rounded-full text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-bpjs-yellow w-64 shadow-inner">
                        <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-bpjs-blue">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </nav>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white hover:text-bpjs-yellow focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Breadcrumbs & Judul -->
        <div class="mb-8">
            <div class="text-xs text-gray-500 mb-6 uppercase tracking-wider font-semibold">
                STANDAR PELAYANAN
            </div>
            <h1 class="text-2xl font-bold text-gray-800 uppercase mb-2">Standar Pelayanan Kesehatan BPJS Ketenagakerjaan</h1>
            <p class="text-sm text-gray-500">Pedoman standar pelayanan dalam penyelenggaraan jaminan kesehatan BPJS Ketenagakerjaan.</p>
        </div>

        <!-- Indikator Jika Sedang Mencari -->
        <?php if(isset($_GET['search']) && !empty(trim($_GET['search']))): ?>
            <div class="mb-4 text-sm text-gray-600 bg-blue-50 border border-blue-200 p-3 rounded">
                Menampilkan hasil pencarian untuk: <strong>"<?php echo htmlspecialchars($_GET['search']); ?>"</strong>
                <a href="index.php" class="text-red-500 hover:underline ml-2">(Hapus Pencarian)</a>
            </div>
        <?php endif; ?>

        <!-- GRID DOKUMEN OTOMATIS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            
            <?php
            $folder_dokumen = 'documents/';
            $kata_kunci = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

            if (is_dir($folder_dokumen)) {
                $files = glob($folder_dokumen . "*.pdf");

                // Urutkan file berdasarkan nama secara menurun (Tahun terbaru di atas)
                rsort($files);

                // Filter berdasarkan pencarian (jika ada)
                if (!empty($kata_kunci)) {
                    $filtered_files = [];
                    foreach ($files as $file) {
                        $nama_file = basename($file);
                        $judul_bersih = strtolower(str_replace(['.pdf', '_', '-'], [' ', ' ', ' '], $nama_file));
                        
                        // Cek apakah kata kunci ada di dalam judul
                        if (strpos($judul_bersih, $kata_kunci) !== false || strpos(strtolower($nama_file), $kata_kunci) !== false) {
                            $filtered_files[] = $file;
                        }
                    }
                    $files = $filtered_files; // Ganti array file dengan hasil filter
                }

                if (count($files) > 0) {
                    foreach ($files as $filepath) {
                        $nama_file = basename($filepath);
                        $judul_bersih = str_replace(['.pdf', '_', '-'], [' ', ' ', ' '], $nama_file);
                        $judul_bersih = ucwords($judul_bersih); 
                        
                        echo '
                        <a href="dokumen.php?file=' . urlencode($nama_file) . '" class="bg-white border border-gray-200 rounded shadow-sm hover:shadow-md transition-shadow p-5 flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="text-teal-600 border border-teal-600 p-3 rounded bg-teal-50 group-hover:bg-teal-100 transition-colors">
                                    <i class="fas fa-file-medical text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm mb-1">' . htmlspecialchars($judul_bersih) . '</h3>
                                    <p class="text-xs text-gray-500">Dokumen ' . htmlspecialchars($judul_bersih) . ' BPJS Ketenagakerjaan...</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-bpjs-blue transition-colors"></i>
                        </a>
                        ';
                    }
                } else {
                    echo "<p class='col-span-full text-gray-500'>Dokumen tidak ditemukan.</p>";
                }
            } else {
                echo "<p class='col-span-full text-red-500'>Folder 'documents' tidak ditemukan. Harap buat folder tersebut.</p>";
            }
            ?>

        </div>
    </main>
    <!-- GRID DOKUMEN OTOMATIS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            if (isset($dokumen_list['error']) || empty($dokumen_list)) {
                echo "<p class='col-span-full text-gray-500'>Dokumen tidak ditemukan.</p>";
            } else {
                foreach ($dokumen_list as $doc) {
                    $nama_file = $doc['nama_file'];
                    $judul_bersih = $doc['judul'];
                    
                    echo '
                    <a href="dokumen.php?file=' . urlencode($nama_file) . '&judul=' . urlencode($judul_bersih) . '" class="bg-white border border-gray-200 rounded shadow-sm hover:shadow-md transition-shadow p-5 flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="text-teal-600 border border-teal-600 p-3 rounded bg-teal-50 group-hover:bg-teal-100 transition-colors">
                                <i class="fas fa-file-medical text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm mb-1">' . htmlspecialchars($judul_bersih) . '</h3>
                                <p class="text-xs text-gray-500">Dokumen ' . htmlspecialchars($judul_bersih) . ' BPJS Ketenagakerjaan...</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-bpjs-blue transition-colors"></i>
                    </a>
                    ';
                }
            }
            ?>
        </div>
        
    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-300 pt-12 pb-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                <!-- Kolom 1: Alamat -->
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4 border-b border-gray-700 pb-2">Kantor Pusat</h3>
                    <p class="text-sm leading-relaxed">
                        <strong>Grha BPJAMSOSTEK</strong><br>
                        Jl. Gatot Subroto No. 79<br>
                        Karet Semanggi, Kec. Setiabudi<br>
                        Jakarta Selatan 12930<br>
                        Indonesia
                    </p>
                </div>
                <!-- Kolom 2: Kontak -->
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4 border-b border-gray-700 pb-2">Hubungi Kami</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-3">
                            <div class="bg-gray-800 p-2 rounded-full text-bpjs-yellow">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Call Center</p>
                                <p class="font-bold text-white text-lg">175</p>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="bg-gray-800 p-2 rounded-full text-bpjs-yellow">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Email Bantuan</p>
                                <p class="text-white">care@bpjsketenagakerjaan.go.id</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Kolom 3: Media Sosial -->
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4 border-b border-gray-700 pb-2">Media Sosial</h3>
                    <p class="text-sm mb-4">Ikuti kami untuk mendapatkan informasi terbaru.</p>
                    <div class="flex gap-4">
                        <a href="https://id-id.facebook.com/bpjstkinfo/" target="_blank" class="bg-gray-800 hover:bg-bpjs-blue transition-colors text-white w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://x.com/bpjstkinfo" target="_blank" class="bg-gray-800 hover:bg-bpjs-blue transition-colors text-white w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="https://www.youtube.com/user/bpjstkinfo" target="_blank" class="bg-gray-800 hover:bg-bpjs-blue transition-colors text-white w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://www.instagram.com/bpjs.ketenagakerjaan/" target="_blank" class="bg-gray-800 hover:bg-bpjs-blue transition-colors text-white w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.threads.net/@bpjs.ketenagakerjaan" target="_blank" class="bg-gray-800 hover:bg-bpjs-blue transition-colors text-white w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fab fa-threads"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-6 text-center text-xs text-gray-500">
                <p>&copy; 2026 BPJS Ketenagakerjaan. Hak Cipta Dilindungi Undang-Undang.</p>
            </div>
        </div>
    </footer>
</body>
</html>