// INITIALIZE MAP (default view: UEP Zone 2)
var map = L.map('map').setView([12.5076, 124.6370], 15);

// Add tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 20,
}).addTo(map);

// Dorm / BH locations (sample)
const listings = [
    { name: "University Homes", lat: 12.50791, lng: 124.63690 },
    { name: "HL Enterprises", lat: 12.50830, lng: 124.63650 },
    { name: "KKB’s Grill & Restaurant", lat: 12.50910, lng: 124.63750 },
    { name: "Capul District", lat: 12.50330, lng: 124.63620 },
    { name: "TESDA Provincial Training Center", lat: 12.50280, lng: 124.63500 }
];

// Add markers to map
listings.forEach(place => {
    L.marker([place.lat, place.lng])
        .addTo(map)
        .bindPopup(`<b>${place.name}</b><br>Click for details`)
        .on("click", () => {
            window.location.href = "bh_details.html";
        });
});
