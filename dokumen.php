<?php
require 'config.php';

$nama_file = isset($_GET['file']) ? $_GET['file'] : '';
$judul_bersih = isset($_GET['judul']) ? $_GET['judul'] : 'Detail Dokumen';

if (empty($nama_file)) {
    header("Location: index.php");
    exit;
}

$file_url = SUPABASE_URL . '/storage/v1/object/public/' . SUPABASE_BUCKET . '/' . rawurlencode($nama_file);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($judul_bersih); ?> - BPJS Ketenagakerjaan</title>
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

    <!-- HEADER / NAVBAR SAMA PERSIS DENGAN INDEX.PHP -->
    <header class="bg-bpjs-blue text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Kiri: Logo Area -->
                <div class="flex items-center gap-3">
                    <img src="img/logobpjstk.png" alt="Logo BP Jamsostek" class="h-12 w-auto p-1 rounded">
                </div>
                
                <!-- Kanan: Desktop Nav & Search Bar (Nge-link ke index.php) -->
                <nav class="hidden md:flex items-center gap-6 font-medium">
                    <a href="index.php" class="hover:text-bpjs-yellow transition-colors flex items-center gap-2">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    
                    <form action="index.php" method="GET" class="relative">
                        <input type="text" name="search" placeholder="Cari dokumen..." 
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
        
<!-- Breadcrumbs -->
        <div class="text-xs text-gray-500 mb-6 uppercase tracking-wider font-semibold">
            <a href="index.php" class="hover:text-bpjs-blue transition-colors">STANDAR PELAYANAN</a> &gt; 
            <span class="text-bpjs-blue"><?php echo htmlspecialchars($judul_bersih); ?></span>
        </div>

        <!-- Judul Halaman -->
        <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($judul_bersih); ?></h2>
                <p class="text-gray-500 mt-1">Dokumen Resmi Panduan Pelayanan BPJS Ketenagakerjaan</p>
            </div>
            
            <!-- Tombol Download -->
            <a href="download.php?file=<?php echo urlencode($nama_file); ?>" 
            class="inline-flex items-center justify-center gap-2 bg-bpjs-blue hover:bg-blue-800 text-white font-semibold py-2.5 px-6 rounded-lg shadow transition-colors">
            <i class="fas fa-download"></i>
    Unduh PDF
            </a>
        </div>

        <!-- AREA BACA DOKUMEN (PDF VIEWER) -->
        <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <iframe 
                src="<?php echo htmlspecialchars($file_url); ?>" 
                class="w-full h-[65vh] rounded-lg"
                style="border: none;"
                title="Viewer Dokumen Pedoman"
            >
                Browser Anda tidak mendukung iframe atau tidak dapat menampilkan PDF. 
                Silakan gunakan tombol unduh di atas.
            </iframe>
        </div>

    </main>

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