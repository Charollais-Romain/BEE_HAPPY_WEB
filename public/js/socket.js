const socket = io();

const markers = {};
let selectedHive = null;

socket.on("connect", () => {
    console.log("Connecté au serveur");
});

socket.on("nouvelleDonnee", (data) => {
    console.log("Donnée reçue :", data);

    const { hiveId, temperature, humidite, lat, lng } = data;

    if (!lat || !lng) return;

    // Auto-select first hive
    if (!selectedHive) {
        selectedHive = hiveId;
    }

    if (!markers[hiveId]) {
        const marker = L.marker([lat, lng]).addTo(map);

        marker.on("click", () => {
            selectedHive = hiveId;
            console.log("Ruche sélectionnée :", hiveId);
        });

        markers[hiveId] = marker;
    }

    markers[hiveId].setPopupContent(
        `<b>${hiveId}</b><br>
        Température : ${temperature}°C<br>
        Humidité : ${humidite}%`
    );

    if (selectedHive === hiveId) {
        markers[hiveId].openPopup();
    }
});