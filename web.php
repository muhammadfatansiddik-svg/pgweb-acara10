<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wifi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT wilayah, kecamatan, nama, jenis, longitude, latitude FROM sebaranwifi";
$result = $conn->query($sql);

$data_wifi = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data_wifi[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pesebaran Wifi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        html,
        body,
        #map {
            width: 100%;
            height: 100%;
            margin: 0;
        }
    </style>
</head>
</head>

<body>
    <div id="map"></div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        
        var map = L.map("map").setView([-6.1860608,106.8267740], 13);

        var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });
        Esri_WorldImagery.addTo(map);

        var dataWifi = <?php echo json_encode($data_wifi); ?>;
        var markerWifiLayer = L.layerGroup().addTo(map);

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

        // Control Layer
        var baseMaps = {
            "Esri World Imagery": Esri_WorldImagery,
        };

        var overlayMaps = {
            "Titik WiFi": markerWifiLayer
        };

        var controllayer = L.control.layers(baseMaps, overlayMaps);
        controllayer.addTo(map);
        
        // Scale Bar
        L.control.scale({
            position: "bottomleft",
            imperial: false,
        }).addTo(map);

    </script>

</body>

</html>