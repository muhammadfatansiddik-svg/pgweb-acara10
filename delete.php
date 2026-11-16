<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "wifi";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Menggunakan Prepared Statement agar aman dari SQL Injection
    $stmt = $conn->prepare("DELETE FROM sebaranwifi WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Kembali ke halaman peta utama
        header("Location: index.php");
        exit();
    } else {
        echo "Error menghapus data: " . $conn->error;
    }
} else {
    echo "ID tidak valid atau tidak ditemukan. <a href='index.php'>Kembali ke Peta</a>";
}
?>