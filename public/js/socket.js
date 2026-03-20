const socket = io();

const markers = {};        // store markers per hive
let selectedHive = null;   // which hive is shown in chart

socket.on("connect", () => {
    console.log("Connecté au serveur");
});

socket.on("nouvelleDonnee", (data) => {
    console.log("Donnée reçue :", data);

    const { hiveId, temperature, humidite, lat, lng } = data;

    // creates a marker if it doesn't exist
    if (!markers[hiveId]) {
        const marker = L.marker([lat, lng]).addTo(map);

        marker.on("click", () => {
            selectedHive = hiveId;
            console.log("Ruche sélectionnée :", hiveId);
        });

        markers[hiveId] = marker;
    }

    // update le popup
    markers[hiveId].setPopupContent(
        `<b>${hiveId}</b><br>
        Température : ${temperature}°C<br>
        Humidité : ${humidite}%`
    );

    // Updates the chart only for selected hive
    if (selectedHive === hiveId) {
        updateChart(temperature, hiveId);
    }
});