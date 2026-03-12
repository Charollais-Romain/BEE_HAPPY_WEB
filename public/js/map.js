const map = L.map('map').setView([45.83, 1.26], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
maxZoom:19
}).addTo(map);

const marker = L.marker([45.83,1.26]).addTo(map);

marker.bindPopup("Ruche 1");