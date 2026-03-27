(function () {
    "use strict";

    const errEl = document.getElementById("map-error");
    const mapEl = document.getElementById("map");
    if (!mapEl || typeof L === "undefined") return;

    const map = L.map(mapEl).setView([46.5, 2.5], 6);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: "&copy; OpenStreetMap",
    }).addTo(map);

    const rucherIcon = L.divIcon({
        className: "",
        html: '<div class="bee-marker-rucher" role="img" aria-label="Rucher"></div>',
        iconSize: [28, 28],
        iconAnchor: [14, 14],
        popupAnchor: [0, -12],
    });

    function showError(msg) {
        if (errEl) {
            errEl.textContent = msg;
            errEl.style.display = "block";
        }
    }

    async function loadRuchers() {
        try {
            const res = await fetch("api/ruchers.php", { headers: { Accept: "application/json" } });
            const data = await res.json();
            if (!data.ok) {
                showError(data.error || "Impossible de charger les ruchers.");
                return;
            }

            const ruchers = data.ruchers || [];
            if (ruchers.length === 0) {
                showError("Aucun rucher en base. Importez sql/schema.sql et exécutez scripts/seed_mesures.php.");
                return;
            }

            const bounds = [];
            ruchers.forEach(function (r) {
                const lat = parseFloat(r.lat);
                const lng = parseFloat(r.lng);
                if (Number.isNaN(lat) || Number.isNaN(lng)) return;

                bounds.push([lat, lng]);
                const marker = L.marker([lat, lng], { icon: rucherIcon }).addTo(map);

                const nb = r.nb_ruches ?? 0;
                const desc = r.description
                    ? "<div class=\"bee-popup-meta\">" + escapeHtml(r.description) + "</div>"
                    : "";

                marker.bindPopup(
                    "<div class=\"bee-popup-title\">" + escapeHtml(r.nom) + "</div>" +
                    "<div class=\"bee-popup-meta\">" + nb + " ruche" + (nb > 1 ? "s" : "") + "</div>" +
                    desc +
                    "<a class=\"bee-btn\" href=\"rucher.php?id=" + encodeURIComponent(r.id) + "\">Voir le rucher</a>"
                );
            });

            if (bounds.length) {
                map.fitBounds(bounds, { padding: [40, 40], maxZoom: 14 });
            }
        } catch (e) {
            console.error(e);
            showError("Erreur réseau ou serveur. Vérifiez PHP / MySQL et l’URL de la page.");
        }
    }

    function escapeHtml(s) {
        const d = document.createElement("div");
        d.textContent = s;
        return d.innerHTML;
    }

    loadRuchers();
})();
