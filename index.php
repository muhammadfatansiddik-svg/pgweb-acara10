<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wifi";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sebaranwifi";
$result = $conn->query($sql);
$total_data = $result->num_rows;

$data_wifi = array();
if ($total_data > 0) {
    while($row = $result->fetch_assoc()) {
        $data_wifi[] = $row;
    }
}
$result->data_seek(0); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WebGIS - Peta Sebaran WiFi Jakarta</title>
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; 
        }
        
        #map {
            height: 100vh;
            width: 100vw;
            z-index: 1; 
        }

        .btn-show-table {
            position: absolute;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999; 
            box-shadow: 0px 4px 10px rgba(0,0,0,0.4);
            border-radius: 20px;
            padding: 8px 20px;
        }

        .offcanvas-bottom {
            height: 45vh !important; /* Tinggi tabel 45% dari layar */
            z-index: 1050;
        }

        .table-gis-container {
            height: 100%;
            overflow: auto; 
        }
        .table-gis {
            font-size: 12px;
            white-space: nowrap;
            margin-bottom: 0;
        }
        .table-gis thead th {
            background-color: #f4f4f4;
            position: sticky;
            top: 0;
            z-index: 2;
            border-bottom: 1px solid #ccc;
        }
        .table-gis tbody td {
            vertical-align: middle;
            padding: 4px 12px;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <button class="btn btn-primary btn-show-table fw-bold" type="button" data-bs-toggle="offcanvas" data-bs-target="#tabelAtribut">
        <i class="bi bi-table"></i> Buka Tabel Atribut
    </button>

    <div class="offcanvas offcanvas-bottom shadow" tabindex="-1" id="tabelAtribut" aria-labelledby="tabelAtributLabel">
        
        <div class="offcanvas-header bg-light border-bottom py-2">
            <h6 class="offcanvas-title text-primary fw-bold" id="tabelAtributLabel">
                <i class="bi bi-list-columns-reverse"></i> Lokasi WiFi DKI Jakarta - Layer 0 (Total: <?php echo $total_data; ?>)
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        
        <div class="offcanvas-body p-0 table-gis-container">
            <table class="table table-bordered table-hover table-gis">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Kecamatan</th>
                        <th>Wilayah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Looping data untuk mengisi baris tabel HTML
                    if ($total_data > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["nama"] . "</td>
                                <td>" . $row["jenis"] . "</td>
                                <td>" . $row["longitude"] . "</td>
                                <td>" . $row["latitude"] . "</td>
                                <td>" . $row["kecamatan"] . "</td>
                                <td>" . $row["wilayah"] . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>0 results</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    
    <script>
        var map = L.map("map").setView([-6.1860608,106.8267740], 13);

        var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        }).addTo(map);

        var dataWifi = <?php echo json_encode($data_wifi); ?>;

        var markerWifiLayer = L.layerGroup().addTo(map);

        // Looping data JSON untuk membuat marker di peta
        dataWifi.forEach(function(item) {
            if (item.latitude && item.longitude) {
                var lat = parseFloat(item.latitude);
                var lng = parseFloat(item.longitude);

                var popupContent = "<b>Nama: </b>" + item.nama + "<br>" +
                                "<b>Jenis: </b>" + item.jenis + "<br>" +
                                "<b>Kecamatan: </b>" + item.kecamatan + "<br>" +
                                "<b>Wilayah: </b>" + item.wilayah;

                var marker = L.marker([lat, lng]).bindPopup(popupContent);
                markerWifiLayer.addLayer(marker);
            }
        });

        // Layer Control (Pojok Kanan Atas)
        var baseMaps = {
            "Esri World Imagery": Esri_WorldImagery
        };
        var overlayMaps = {
            "Lokasi WiFi": markerWifiLayer
        };
        L.control.layers(baseMaps, overlayMaps).addTo(map);

        // Scale Bar
        L.control.scale({
            position: "bottomleft",
            imperial: false,
        }).addTo(map);

    </script>
</body>
</html>

<?php 
// Tutup koneksi database di akhir halaman
$conn->close(); 
?>