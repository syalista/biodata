<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "syalista31");

// Cek koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
}

// Tambah data siswa
if (isset($_POST['tambah'])) {
    // Mendapatkan nomor urut baru
    $result = mysqli_query($koneksi, "SELECT MAX(no) AS max_no FROM siswa");
    $data = mysqli_fetch_array($result);
    $NO = $data['max_no'] + 1; // Menambahkan 1 pada NO tertinggi

    $NISN = $_POST['NISN'];
    $NAMA = $_POST['NAMA'];
    $ADDRESS = $_POST['ADDRESS'];
    
    // Insert data ke database
    $query = "INSERT INTO siswa (no, nisn, nama, address) VALUES ('$NO', '$NISN', '$NAMA', '$ADDRESS')";
    mysqli_query($koneksi, $query);
    
    // Redirect ulang ke halaman utama
    header("Location: ".$_SERVER['PHP_SELF']);
}

// Update data siswa
if (isset($_POST['update'])) {
    $NO = $_POST['NO'];
    $NISN_lama = $_POST['NISN_lama']; // NISN yang asli (lama)
    $NISN_baru = $_POST['NISN'];      // NISN yang baru diinput
    $NAMA = $_POST['NAMA'];
    $ADDRESS = $_POST['ADDRESS'];
    
    // Update data di database dengan NISN baru
    $query = "UPDATE siswa SET NO='$NO', NISN='$NISN_baru', NAMA='$NAMA', ADDRESS='$ADDRESS' WHERE NISN='$NISN_lama'";
    
    // Jalankan query
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, tampilkan pesan sukses
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>alert('Data gagal diperbarui!');</script>";
    }
}

// Hapus data siswa
if (isset($_GET['hapus'])) {
    $NISN = $_GET['hapus'];
    
    // Hapus data dari database
    $query = "DELETE FROM siswa WHERE nisn='$NISN'";
    mysqli_query($koneksi, $query);
    
    // Redirect ulang ke halaman utama
    header("Location: ".$_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD SYALISTA</title>
</head>
<body>
    <h2>CRUD DATA SISWA</h2>

    <!-- Form Tambah/Edit Data -->
    <?php
    // Jika form edit, tampilkan data sesuai NISN
    if (isset($_GET['edit'])) {
        $NISN = $_GET['edit'];
        $result = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nisn='$NISN'");
        $d = mysqli_fetch_array($result);
    ?>
    <h3>EDIT DATA SISWA</h3>
    <form method="post" action="">
        <input type="hidden" name="NISN_lama" value="<?php echo $d['NISN']; ?>">

        <table>
            <tr>
                <td>NO</td>
                <td><input type="text" name="NO" value="<?php echo $d['NO']; ?>" readonly></td>
            </tr>
            <tr>
                <td>NISN</td>
                <td><input type="text" name="NISN" value="<?php echo $d['NISN']; ?>"></td>
            </tr>
            <tr>
                <td>NAMA</td>
                <td><input type="text" name="NAMA" value="<?php echo $d['NAMA']; ?>"></td>
            </tr>
            <tr>
                <td>ADDRESS</td>
                <td><input type="text" name="ADDRESS" value="<?php echo $d['ADDRESS']; ?>"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="update" value="UPDATE"></td>
            </tr>
        </table>
    </form>
    <?php
    } else {
    ?>
    <h3>TAMBAH DATA SISWA</h3>
    <form method="post" action="">
        <table>
            <tr>
                <td>NO</td>
                <td><input type="text" name="NO" value="<?php 
                    // Dapatkan nomor urut terbaru untuk menampilkan
                    $result = mysqli_query($koneksi, "SELECT MAX(no) AS max_no FROM siswa");
                    $data = mysqli_fetch_array($result);
                    echo $data['max_no'] + 1; // Menampilkan nomor urut berikutnya
                ?>" readonly></td>
            </tr>
            <tr>
                <td>NISN</td>
                <td><input type="text" name="NISN" required></td>
            </tr>
            <tr>
                <td>NAMA</td>
                <td><input type="text" name="NAMA" required></td>
            </tr>
            <tr>
                <td>ADDRESS</td>
                <td><input type="text" name="ADDRESS" required></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="tambah" value="TAMBAH"></td>
            </tr>
        </table>
    </form>
    <?php
    }
    ?>

    <br/>

    <!-- Tabel Tampil Data -->
    <table border="1">
        <tr>
            <th>NO</th>
            <th>NISN</th>
            <th>NAMA</th>
            <th>ADDRESS</th>
            <th>Opsi</th>
        </tr>
        <?php
        $data = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY no"); // Mengurutkan berdasarkan NO
        while($d = mysqli_fetch_array($data)) {
        ?>
        <tr>
            <td><?php echo $d['NO']; ?></td>
            <td><?php echo $d['NISN']; ?></td>
            <td><?php echo $d['NAMA']; ?></td>
            <td><?php echo $d['ADDRESS']; ?></td>
            <td>
                <a href="?edit=<?php echo $d['NISN']; ?>">EDIT</a> |
                <a href="?hapus=<?php echo $d['NISN']; ?>" onclick="return confirm('Ingin menghapus data ini?')">HAPUS</a>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>
