<?php 
    @ob_start();
    session_start();
    if (!empty($_SESSION['admin'])) { 
        // Admin sudah login
    } else {
        echo '<script>window.location="login.php";</script>';
        exit;
    }

    require 'config.php';
    include $view;
    $lihat = new view($config);
    $toko = $lihat->toko();
    $hsl = $lihat->penjualan();

    // Ambil nama pelanggan langsung dari parameter GET
    $pelanggan = isset($_GET['nm_member']) ? htmlentities($_GET['nm_member']) : "Tidak Diketahui";
    $bayar = isset($_GET['bayar']) ? htmlentities($_GET['bayar']) : 0;
    $kembali = isset($_GET['kembali']) ? htmlentities($_GET['kembali']) : 0;

    // Membuat kode transaksi otomatis
    $kode_transaksi = date("YmdHis"); // Format: YYYYMMDDHHMMSS
?>

<html>
<head>
    <title>Print Struk</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { width: 80mm; max-width: 100%; border: 1px solid #000; padding: 10px; box-sizing: border-box; }
        .header { display: flex; align-items: center; }
        .logo { width: 60px; margin-right: 10px; }
        .store-info { flex-grow: 1; }
        .text-center { text-align: center; }
        .table { width: 100%; border-collapse: collapse; }
        .table td, .table th { border-bottom: 1px dashed black; padding: 3px; text-align: left; }
        .footer { margin-top: 10px; text-align: center; font-size: 12px; }

        @media print {
            body { width: auto; }
            .container { border: none; margin: 0; padding: 0; }
            @page {
                size: auto; /* auto is the initial value */
                margin: 10mm; /* margin for the printed page */
            }
            .no-print { display: none; } /* Sembunyikan elemen yang tidak perlu saat dicetak */
        }
    </style>
</head>
<body>
    <script>window.print();</script>
    <div class="container">
        <div class="header">
            <img src="logo.jpg" alt="Logo Toko" class="logo">
            <div class="store-info">
                <p><strong><?php echo $toko['nama_toko']; ?></strong></p>
                <p>Alamat: <?php echo $toko['alamat_toko']; ?></p>
            </div>
        </div>
        <p>Tanggal: <?php echo date("d/m/Y H:i"); ?></p>
        <p>Pelanggan: <?php echo $pelanggan; ?></p> <!-- Nama pelanggan dari parameter GET -->
        <p>Kode Transaksi: <?php echo $kode_transaksi; ?></p> <!-- Kode transaksi otomatis -->
        
        <table class="table">
            <tr>
                <th>No</th>
                <th>Barang</th>
                <th>Jml</th>
                <th>Total</th>
            </tr>
            <?php $no=1; foreach ($hsl as $isi) { ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $isi['nama_barang']; ?></td>
                <td><?php echo $isi['jumlah']; ?></td>
                <td>Rp.<?php echo number_format($isi['total']); ?></td>
            </tr>
            <?php $no++; } ?>
        </table>

        <p><strong>Total: Rp.<?php echo number_format($lihat->jumlah()['bayar']); ?>,-</strong></p>
        <p>Bayar: Rp.<?php echo number_format($bayar); ?>,-</p>
        <p>Kembali: Rp.<?php echo number_format($kembali); ?>,-</p>

        <div class="footer">
            <p>Terima Kasih Telah Berbelanja di Toko Kami!</p>
        </div>
    </div>
</body>
</html>