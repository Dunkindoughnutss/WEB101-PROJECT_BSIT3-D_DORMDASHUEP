<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Leaflet CSS library -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

    <title>MAP</title>

    <style>
        html, body {
            margin: 0;
            height: 100%;
            width: 100%;
        }

        /* Map container */
        #map {
            width: 75%;
            height: 75vh;
            position: relative;
        }

        /* Buttons container inside map (right side) */
        #mapControls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: rgba(255,255,255,0.9);
            padding: 5px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 50px; /* ensure container fits buttons */
        }

        #mapControls button {
            padding: 8px;
            margin: 3px 0;
            cursor: pointer;
            font-size: 20px; /* symbol size */
            width: 40px;     /* consistent button width */
            height: 40px;    /* consistent button height */
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
            transition: background 0.2s;
        }

        #mapControls button:hover {
            background-color: #f0f0f0;
        }
    </style>

    <!-- Leaflet JS library -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin="">
    </script>
</head>

<body>

    <!-- Map container -->
    <div id="map">
        <!-- Buttons inside map (top-right) -->
        <div id="mapControls">
            <button id="resetBtn">🔄</button>
            <button id="trackBtn">📍</button>
        </div>
    </div>

    <script>
        // Initialize map
        var map = L.map('map').setView([12.509775, 124.665596], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        let marker = null, circle = null;
        let tracking = true; // Whether the map should follow the user

        // Watch user position with high accuracy
        navigator.geolocation.watchPosition(success, error, {
            enableHighAccuracy: true,
            maximumAge: 0,
            timeout: 5000
        });

        function success(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            let radius = position.coords.accuracy;

            // Clamp radius between 20 and 100 meters
            if (!radius || radius > 100) radius = 100;
            if (radius < 20) radius = 20;

            if (!marker) {
                // First GPS fix: create marker and circle
                marker = L.marker([lat, lng]).addTo(map);
                circle = L.circle([lat, lng], { radius: radius }).addTo(map);
                map.fitBounds(circle.getBounds());
            } else {
                // Update marker and circle position
                marker.setLatLng([lat, lng]);
                circle.setLatLng([lat, lng]);
                circle.setRadius(radius);
            }

            // Pan map if tracking is enabled
            if (tracking) {
                map.setView([lat, lng]);
            }
        }

        function error(err) {
            if (err.code === 1) {
                alert("Please allow geolocation access");
            } else {
                alert("Cannot get current location");
            }
        }

        // Reset map view button
        document.getElementById('resetBtn').addEventListener('click', function() {
            map.setView([12.509775, 124.665596], 18);
            tracking = false; // stop following temporarily
        });

        // Track user button
        document.getElementById('trackBtn').addEventListener('click', function() {
            tracking = true; // resume following user
            if (marker) {
                map.setView(marker.getLatLng()); // jump immediately to last known location
            }
        });

    </script>

</body>
</html>
