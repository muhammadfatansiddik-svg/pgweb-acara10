<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wifi";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$row = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM sebaranwifi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Sebaran WiFi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                Form Edit Lokasi WiFi Jakarta
            </div>
            <div class="card-body">
                <?php if ($row): ?>
                    <form action="edit.php" onsubmit="return validateForm()" method="post">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lokasi / WiFi</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis</label>
                            <input type="text" class="form-control" id="jenis" name="jenis" value="<?php echo htmlspecialchars($row['jenis']); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($row['longitude']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($row['latitude']); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="kecamatan" class="form-label">Kecamatan</label>
                            <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?php echo htmlspecialchars($row['kecamatan']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="wilayah" class="form-label">Wilayah</label>
                            <input type="text" class="form-control" id="wilayah" name="wilayah" value="<?php echo htmlspecialchars($row['wilayah']); ?>" required>
                        </div>

                        <p id="informasi" class="text-danger fw-bold small"></p>

                        <div class="d-flex justify-content-between">
                            <a href="../index.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">
                        Data tidak ditemukan. <a href="../index.php" class="alert-link">Kembali ke Peta</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    function validateForm() {
        let lon = document.getElementById("longitude").value;
        let lat = document.getElementById("latitude").value;
        let info = document.getElementById("informasi");

        if (isNaN(lon) || isNaN(lat)) {
            info.innerHTML = "Koordinat Longitude dan Latitude harus berupa angka desimal (contoh: 106.8267 atau -6.1860).";
            return false;
        }
        return true;
    }
    </script>
</body>
</html>