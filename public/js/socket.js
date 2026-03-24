const socket = io();

const markers = {};
let selectedHive = null;

socket.on("connect", () => {
    console.log("Connecté au serveur");
});

socket.on("toutesDonnees", (hives) => {
    hives.forEach(addOrUpdateHive);
});

socket.on("nouvelleDonnee", addOrUpdateHive);

// 👇 THIS is your old code moved into a function
function addOrUpdateHive(data) {
    const { hiveId, temperature, humidite, lat, lng } = data;

    if (!lat || !lng) return;

    if (!selectedHive) {
        selectedHive = hiveId;
    }

    if (!markers[hiveId]) {
        const marker = L.marker([lat, lng]).addTo(map);
    
        // 👇 TRÈS IMPORTANT
        marker.bindPopup("");
    
        marker.on("click", () => {
            selectedHive = hiveId;
            marker.openPopup(); // optionnel mais utile
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
}