const socket = io();

socket.on("nouvelleDonnee", (data) => {

console.log(data);


// mise à jour graphique
chart.data.labels.push(new Date().toLocaleTimeString());
chart.data.datasets[0].data.push(data.temperature);

if(chart.data.labels.length > 20){
chart.data.labels.shift();
chart.data.datasets[0].data.shift();
}

chart.update();


// mise à jour popup ruche
marker.setPopupContent(
"Température : " + data.temperature + "°C <br>" +
"Humidité : " + data.humidite + "%"
);

});