<?php 
$bulan_tes = array(
    '01' => "Januari",
    '02' => "Februari",
    '03' => "Maret",
    '04' => "April",
    '05' => "Mei",
    '06' => "Juni",
    '07' => "Juli",
    '08' => "Agustus",
    '09' => "September",
    '10' => "Oktober",
    '11' => "November",
    '12' => "Desember"
);
date_default_timezone_set("Asia/Jakarta"); // Sesuaikan dengan zona waktu Anda

// Cek apakah pengguna adalah admin
if (!empty($_SESSION['admin'])) {
    // Cek tanggal reset terakhir di database
    $sql = "SELECT tanggal_reset FROM last_reset ORDER BY id DESC LIMIT 1";
    $stmt = $config->query($sql);
    $lastResetDate = $stmt->fetchColumn();

    // Dapatkan tanggal dan waktu saat ini
    $today = date("Y-m-d H:i:s");
    $currentDate = date("Y-m-d");
    $currentTime = date("H:i:s");

    // Cek apakah hari ini adalah 1 Januari dan waktu adalah 00:00
    if ($currentDate == date('Y') . '-01-01' && $currentTime == '00:00:00') {
        // Cek apakah reset sudah dilakukan tahun ini
        if ($lastResetDate === false || date('Y', strtotime($lastResetDate)) < date('Y')) {
            // Hapus semua data di tabel `nota`
            $sql = 'DELETE FROM nota';
            $stmt = $config->prepare($sql);
            $stmt->execute();

            // Simpan tanggal reset terbaru
            $sql = 'INSERT INTO last_reset (tanggal_reset) VALUES (?)';
            $stmt = $config->prepare($sql);
            $stmt->execute([$currentDate]);

            echo '<script>alert("Data laporan telah dihapus otomatis.");</script>';
        }
    }
}
?>
 <?php 
// Ambil password_laporan dari database
$sql = "SELECT password_laporan FROM toko WHERE id_toko = 1"; // Ganti dengan ID toko yang sesuai
$stmt = $config->query($sql);
$passwordLaporan = $stmt->fetchColumn();

?>

<div class="row">
    <div class="col-md-12">
        <h4>
            <button class="btn btn-danger" id="resetButton" style="display:none;" onclick="resetLaporan()">RESET</button>
            <button class="btn btn-warning" id="showResetButton" onclick="showResetButton()">Tampilkan Reset</button>
            Data Laporan Penjualan <?= $bulan_tes[date('m')]; ?> <?= date('Y'); ?>
        </h4>
        <br />
        <div id="laporanContainer">
            <!-- Data laporan akan diperbarui di sini -->
        </div>
    </div>
</div>

            <?php if (!empty($_GET['cari'])) { ?>
                Data Laporan Penjualan <?= $bulan_tes[$_POST['bln']]; ?> <?= $_POST['thn']; ?>
            <?php } elseif (!empty($_GET['hari'])) { ?>
                Data Laporan Penjualan <?= $_POST['hari']; ?>
            <?php } else { ?>
                Data Laporan Penjualan <?= $bulan_tes[date('m')]; ?> <?= date('Y'); ?>
            <?php } ?>
        </h4>
        <br />
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mt-2">Cari Laporan Per Bulan</h5>
            </div>
            <div class="card-body p-0">
                <form method="post" action="index.php?page=laporan&cari=ok">
                    <table class="table table-striped">
                        <tr>
                            <th>Pilih Bulan</th>
                            <th>Pilih Tahun</th>
                            <th>Aksi</th>
                        </tr>
                        <tr>
                            <td>
                                <select name="bln" class="form-control">
                                    <option selected="selected">Bulan</option>
                                    <?php
                                    $bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                                    $jlh_bln = count($bulan);
                                    $bln1 = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
                                    for ($c = 0; $c < $jlh_bln; $c += 1) {
                                        echo "<option value='$bln1[$c]'> $bulan[$c] </option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <?php
                                $now = date('Y');
                                echo "<select name='thn' class='form-control'>";
                                echo '<option selected="selected">Tahun</option>';
                                for ($a = 2017; $a <= $now; $a++) {
                                    echo "<option value='$a'>$a</option>";
                                }
                                echo "</select>";
                                ?>
                            </td>
                            <td>
                                <input type="hidden" name="periode" value="ya">
                                <button class="btn btn-primary">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                                <a href="index.php?page=laporan" class="btn btn-success">
                                    <i class="fa fa-refresh"></i> Refresh</a>

                                <?php if (!empty($_GET['cari'])) { ?>
                                    <a href="excel.php?cari=yes&bln=<?= $_POST['bln']; ?>&thn=<?= $_POST['thn']; ?>" class="btn btn-info"><i class="fa fa-download"></i> Excel</a>
                                <?php } else { ?>
                                    <a href="excel.php" class="btn btn-info"><i class="fa fa-download"></i> Excel</a>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </form>
                <form method="post" action="index.php?page=laporan&hari=cek">
                    <table class="table table-striped">
                        <tr>
                            <th>Pilih Hari</th>
                            <th>Aksi</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="date" value="<?= date('Y-m-d'); ?>" class="form-control" name="hari">
                            </td>
                            <td>
                                <input type="hidden" name="periode" value="ya">
                                <button class="btn btn-primary">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                                <a href="index.php?page=laporan" class="btn btn-success">
                                    <i class="fa fa-refresh"></i> Refresh</a>

                                <?php if (!empty($_GET['hari'])) { ?>
                                    <a href="excel.php?hari=cek&tgl=<?= $_POST['hari']; ?>" class="btn btn-info"><i class="fa fa-download"></i> Excel</a>
                                <?php } else { ?>
                                    <a href="excel.php" class="btn btn-info"><i class="fa fa-download"></i> Excel</a>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <br />
        <br />
        <!-- view barang -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-100 table-sm" id="example1">
                        <thead>
                            <tr style="background:#DFF0D8;color:#333;">
                                <th>No</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th style="width:10%;">Jumlah</th>
                                <th style="width:10%;">Modal</th>
                                <th style="width:10%;">Total</th>
                                <th>Kasir</th>
                                <th>Tanggal Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            if (!empty($_GET['cari'])) {
                                $periode = $_POST['bln'] . '-' . $_POST['thn'];
                                $no = 1; 
                                $jumlah = 0;
                                $bayar = 0;
                                $hasil = $lihat->periode_jual($periode);
                            } elseif (!empty($_GET['hari'])) { // Perbaikan di sini
                                $hari = $_POST['hari'];
                                $no = 1; 
                                $jumlah = 0;
                                $bayar = 0;
                                $hasil = $lihat->hari_jual($hari);
                            } else {
                                $hasil = $lihat->jual();
                            }
                            ?>
                            <?php 
                            $bayar = 0;
                            $jumlah = 0;
                            $modal = 0;
                            foreach ($hasil as $isi) { 
                                $bayar += $isi['total'];
                                $modal += $isi['harga_beli'] * $isi['jumlah'];
                                $jumlah += $isi['jumlah'];
                            ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $isi['id_barang']; ?></td>
                                <td><?php echo $isi['nama_barang']; ?></td>
                                <td><?php echo $isi['jumlah']; ?> </td>
                                <td>Rp.<?php echo number_format($isi['harga_beli'] * $isi['jumlah']); ?>,-</td>
                                <td>Rp.<?php echo number_format($isi['total']); ?>,-</td>
                                <td><?php echo $isi['nm_member']; ?></td>
                                <td><?php echo $isi['tanggal_input']; ?></td>
                            </tr>
                            <?php $no++; } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total Terjual</th>
                                <th><?php echo $jumlah; ?></th>
                                <th>Rp.<?php echo number_format($modal); ?>,-</th>
                                <th>Rp.<?php echo number_format($bayar); ?>,-</th>
                                <th style="background:#0bb365;color:#fff;">Keuntungan</th>
                                <th style="background:#0bb365;color:#fff;">
                                    Rp.<?php echo number_format($bayar - $modal); ?>,-</th>
                            </tr>
                        </tfoot>
                    </table>
                </div </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function resetLaporan() {
        $.ajax({
            url: "fungsi/hapus/hapus.php?laporan=jual",
            method: "GET",
            success: function(response) {
                $("#laporanContainer").html(response); // Update tampilan real-time
                console.log("Laporan berhasil direset.");
            },
            error: function() {
                console.log("Gagal mereset laporan.");
            }
        });
    }
   


    function showResetButton() {
        var password = prompt("Masukkan password untuk menampilkan tombol reset:");
        // Bandingkan dengan password yang diambil dari database
        if (password === "<?php echo $passwordLaporan; ?>") {
            document.getElementById("resetButton").style.display = "block";
            alert("Tombol reset ditampilkan.");
        } else {
            alert("Password salah!");
        }
    }

    // Hapus interval reset setiap 20 detik
    // setInterval(resetLaporan, 20000); // Hapus atau ubah ini jika tidak diperlukan
</script>
