// Initialize Map (Center on UEP area)
const uepCoords = [12.509775, 124.665596];
var map = L.map('map').setView(uepCoords, 16);

// Set up the Map Tiles
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

// Load Sample Property Markers (Matches Home Page Listings)
const properties = [
    { name: "Parcon Dormitory", coords: [12.5100, 124.6655], color: "#007bff" },
    { name: "Cruz Bhouse", coords: [12.5115, 124.6630], color: "#666" }
];

// Add properties to map
properties.forEach(prop => {
    L.circleMarker(prop.coords, {
        radius: 10,
        fillColor: prop.color,
        color: "#fff",
        weight: 2,
        fillOpacity: 0.9
    }).addTo(map).bindPopup(`<b>${prop.name}</b><br>UEP Zone Area`);
});

/**
 * Enables "Pin Mode"
 * Allows the user to click once on the map to drop a new marker
 */
function enableAddPinMode() {
    alert("Click anywhere on the map to pin a new site.");
    map.once('click', (e) => {
        L.marker([e.latlng.lat, e.latlng.lng]).addTo(map)
        .bindPopup("<b>New Property Site</b><br>Coordinate Saved").openPopup();
    });
}

// Reset Map View Button Logic
document.getElementById('resetBtn').onclick = () => map.setView(uepCoords, 16);