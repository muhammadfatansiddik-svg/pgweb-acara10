<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $kecamatan = $_POST['kecamatan'];
    $wilayah = $_POST['wilayah'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "wifi";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update data menggunakan Prepared Statement
    $stmt = $conn->prepare("UPDATE sebaranwifi SET nama=?, jenis=?, longitude=?, latitude=?, kecamatan=?, wilayah=? WHERE id=?");
    $stmt->bind_param("ssssssi", $nama, $jenis, $longitude, $latitude, $kecamatan, $wilayah, $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Kembali ke index.php di folder utama (naik satu tingkat)
        header("Location: ../index.php");
        exit();
    } else {
        echo "Error mengupdate data: " . $conn->error;
    }
}
?>