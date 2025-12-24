// Initialize Map (Center on UEP area)
const uepCoords = [12.509775, 124.665596];
var map = L.map('map').setView(uepCoords, 16);

// Set up the Map Tiles
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

// Property Markers (Available for Renters)
const properties = [
    { name: "Parcon Dormitory", coords: [12.5100, 124.6655], price: "₱1,700/mo" },
    { name: "Cruz Bhouse", coords: [12.5115, 124.6630], price: "₱1,700/mo" },
    { name: "University Homes", coords: [12.5125, 124.6680], price: "₱3,000/mo" }
];

// Add properties to map
properties.forEach(prop => {
    L.circleMarker(prop.coords, {
        radius: 10,
        fillColor: "#4A90E2",
        color: "#fff",
        weight: 2,
        fillOpacity: 0.9
    }).addTo(map).bindPopup(`<b>${prop.name}</b><br>${prop.price}<br><a href="renter_search.html">View Details</a>`);
});

/**
 * Enables "Pin Mode" 
 * For renters to mark places they visited or are interested in
 */
function enableAddPinMode() {
    alert("Click on the map to mark a location of interest.");
    map.once('click', (e) => {
        L.marker([e.latlng.lat, e.latlng.lng]).addTo(map)
        .bindPopup("<b>Marked Interest</b><br>Saved for your session").openPopup();
    });
}

// Reset View
document.getElementById('resetBtn').onclick = () => map.setView(uepCoords, 16);

// Track Location (Geolocation)
document.getElementById('trackBtn').onclick = () => {
    map.locate({setView: true, maxZoom: 18});
};

map.on('locationfound', (e) => {
    L.marker(e.latlng).addTo(map).bindPopup("You are here").openPopup();
    document.getElementById('gps-status').innerText = "GPS: Located";
    document.getElementById('gps-status').style.color = "green";
});

map.on('locationerror', () => {
    alert("Could not find your location.");
    document.getElementById('gps-status').innerText = "GPS: Access Denied";
});