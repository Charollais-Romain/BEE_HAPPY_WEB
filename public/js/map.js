const map = L.map('map').setView([45.83, 1.26], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
  maxZoom:19
}).addTo(map);

const hives = [
  {name:"Ruche 1", lat:45.83547, lng:1.2645},
  {name:"Ruche 2", lat:45.87676, lng:1.1360},
  {name:"Ruche 3", lat:45.82850, lng:1.2580},
  {name:"Ruche 4", lat:45.84000, lng:1.2500}
];

hives.forEach(hive => {
  L.marker([hive.lat, hive.lng])
    .addTo(map)
    .bindPopup(hive.name);
});
