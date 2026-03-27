(function () {
    "use strict";

    var id = window.BEE_RUCHER_ID;
    if (!id || typeof L === "undefined") return;

    var errEl = document.getElementById("map-error");
    var titleEl = document.getElementById("rucher-title");
    var descEl = document.getElementById("rucher-desc");
    var bcEl = document.getElementById("rucher-breadcrumb-name");
    var mapEl = document.getElementById("map-rucher");
    if (!mapEl) return;

    var map = L.map(mapEl).setView([45.83, 1.26], 14);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "&copy; OpenStreetMap",
    }).addTo(map);

    var rucheIcon = L.divIcon({
        className: "",
        html: '<div class="bee-marker-ruche" role="img" aria-label="Ruche"></div>',
        iconSize: [22, 22],
        iconAnchor: [11, 11],
        popupAnchor: [0, -10],
    });

    function showError(msg) {
        if (errEl) {
            errEl.textContent = msg;
            errEl.style.display = "block";
        }
    }

    function escapeHtml(s) {
        var d = document.createElement("div");
        d.textContent = s;
        return d.innerHTML;
    }

    async function load() {
        try {
            var res = await fetch("api/rucher.php?id=" + encodeURIComponent(id), {
                headers: { Accept: "application/json" },
            });
            var data = await res.json();
            if (!data.ok) {
                showError(data.error || "Rucher introuvable.");
                return;
            }

            var rucher = data.rucher;
            var ruches = data.ruches || [];

            if (titleEl) titleEl.textContent = rucher.nom;
            if (bcEl) bcEl.textContent = rucher.nom;
            if (descEl) {
                descEl.textContent = rucher.description || "Position des ruches sur ce site.";
            }
            document.title = rucher.nom + " — Bee Happy";

            var bounds = [];
            var centerLat = parseFloat(rucher.lat);
            var centerLng = parseFloat(rucher.lng);
            if (!Number.isNaN(centerLat) && !Number.isNaN(centerLng)) {
                bounds.push([centerLat, centerLng]);
            }

            ruches.forEach(function (u) {
                var lat = parseFloat(u.lat);
                var lng = parseFloat(u.lng);
                if (Number.isNaN(lat) || Number.isNaN(lng)) return;
                bounds.push([lat, lng]);

                var marker = L.marker([lat, lng], { icon: rucheIcon }).addTo(map);
                marker.bindPopup(
                    "<div class=\"bee-popup-title\">" + escapeHtml(u.nom) + "</div>" +
                    "<a class=\"bee-btn\" href=\"ruche.php?id=" + encodeURIComponent(u.id) + "\">Statistiques</a>"
                );
            });

            if (bounds.length) {
                map.fitBounds(bounds, { padding: [48, 48], maxZoom: 17 });
            } else {
                showError("Aucune position de ruche valide.");
            }
        } catch (e) {
            console.error(e);
            showError("Erreur lors du chargement du rucher.");
        }
    }

    load();
})();
