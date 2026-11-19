<!--
Nama    : Amanda Lisa Asriningsih
NIM     : 250202001
Kelas   : TI-2A
-->

<?php
//proses_anggota.php - Memvalidasi data dari form_anggota.php lalu menyimpannya dengan insert
//halaman ini hanya boleh dibuka melalui pengiriman form (method POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: form_anggota.php");
    exit;
}

//1. Ambil data dari form
$no_anggota = isset($_POST["no_anggota"])       ? trim($_POST["no_anggota"]) : "";
$nama_lengkap = isset($_POST["nama_lengkap"])   ? trim($_POST["nama_lengkap"]) : "";
$email = isset($_POST["email"])                 ? trim($_POST["email"]) : "";
$no_hp = isset($_POST["no_hp"])                 ? trim($_POST["no_hp"]) : "";
$jenis_anggota = isset($_POST["jenis_anggota"]) ? trim($_POST["jenis_anggota"]) : "";
$alamat = isset($_POST["alamat"])               ? trim($_POST["alamat"]) : "";

//2. Validasi data
$daftar_jenis = array("Mahasiswa", "Dosen", "Tenaga Kependidikan");
$error = array();
    
    //nomor anggota : wajib diisi, hanya angka, maksimal 15 digit
    if (empty($no_anggota)) {
        $errors[] = "Nomor anggota tidak boleh kosong.";
    } elseif (!ctype_digit($no_anggota)) {
        $errors[] = "Nomor anggota hanya boleh berisi angka.";
    } elseif (strlen($no_anggota) > 15){
        $errors[] = "Nomor anggota maksimal 15 karakter.";
    }

    //nama lengkap : wajib diisi, maksimal 100 karakter
    if (empty($nama_lengkap)) {
        $errors[] = "Nama lengkap tidak boleh kosong.";
    } elseif (strlen($nama_lengkap) > 100) {
        $errors[] = "Nama lengkap maksimal 100 karakter.";
    }
    
    //email : wajib diisi, format email valid, maksimal 100 karakter
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Format email tidak valid.";
    } elseif(strlen($email) > 100){
        $errors[] = "Email maksimal 100 karakter.";
    }

    //nomor hp : wajib diisi, hanya angka, 10 sampai 13 digit
    if (empty($no_hp)) {
        $errors[] = "Nomor HP tidak boleh kosong.";
    } elseif (!ctype_digit($no_hp)) {
        $errors[] = "Nomor HP hanya boleh berisi angka.";
    } elseif (strlen($no_hp) < 10 || strlen($no_hp) > 13) {
        $errors[] = "Nomor HP harus memiliki 10-13 digit.";
    }
    
    //jenis anggota : wajib dipilih : mahasiswa, dosen, atau tenaga kependidikan
    if (empty($jenis_anggota)) {
        $errors[] = "Jenis anggota wajib dipilih.";
    } elseif (!in_array($jenis_anggota, $daftar_jenis, true)) {
        $errors[] = "Jenis anggota tidak valid.";
    }
    
    //alamat : wajib diisi
    if (empty($alamat)) {
        $errors[] = "Alamat tidak boleh kosong.";
    }

    //3. Bila data valid, simpan ke database
    $berhasil = false;
    $pesan_db = "";

    if (empty($errors)) {
        require_once "koneksi.php";

        $sql = "INSERT INTO anggota
        (no_anggota, nama_lengkap, email, no_hp, jenis_anggota, alamat)"
        . "VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
    mysqli_stmt_bind_param(
        $stmt, "ssssss", $no_anggota, $nama_lengkap, $email, $no_hp,
        $jenis_anggota, $alamat
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $berhasil = true;
    } else {
        $pesan_db = mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    } else {
    $pesan_db = mysqli_error($koneksi);
    }
    mysqli_close($koneksi);

    //4. Bila berhasil, kembali ke form_anggota.php dengan status sukses
    if ($berhasil) {
        header("Location: form_anggota.php?status=sukses");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Penyimpanan Data</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { color: #1f3864;}
        .gagal {background: #fdecea; border-left: 5px solid #c0392b; padding: 10px;}
    </style>
</head>
<body>
    <?php if(!empty($errors)) { ?>
        <h1>Data Tidak Valid</h1>
        <p class="gagal">Data belum dapat disimpan. Perbaiki kesalahan :</p>
        <ul>
            <?php foreach ($errors as $pesan) {?>
            <li><?php echo htmlspecialchars($pesan); ?></li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <h1>Data Gagal Disimpan</h1>
        <p class="gagal">Terjadi kesalahan saat menyimpan data ke database.</p>
        <p>Pesan dari MySQL: <?php echo htmlspecialchars($pesan_db); ?></p>
    <?php } ?>

    <p><a href="javascript:history.back()">Kembali dan perbaiki data</a></p>
</body>
</html>
