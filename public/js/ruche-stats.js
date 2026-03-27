(function () {
    "use strict";

    const rucheId = window.BEE_RUCHE_ID;
    if (!rucheId || typeof Chart === "undefined") return;

    const errEl = document.getElementById("stats-error");
    const summaryEl = document.getElementById("stats-summary");
    const periodEl = document.getElementById("period-select");
    const titleEl = document.getElementById("ruche-title");
    const bcRucher = document.getElementById("bc-rucher-link");
    const bcRuche = document.getElementById("bc-ruche-name");

    const honey = "#f5b800";
    const honeyDeep = "#e8a317";
    const ink = "#2d2a26";

    let chartPoids = null;
    let chartTemp = null;

    function showError(msg) {
        if (errEl) {
            errEl.textContent = msg;
            errEl.style.display = "block";
        }
    }

    function clearError() {
        if (errEl) errEl.style.display = "none";
    }

    function statBlock(label, value) {
        const wrap = document.createElement("div");
        wrap.className = "bee-stat";
        wrap.innerHTML =
            "<div class=\"bee-stat-label\">" + label + "</div>" +
            "<div class=\"bee-stat-value\">" + value + "</div>";
        return wrap;
    }

    function renderSummary(s) {
        if (!summaryEl) return;
        summaryEl.innerHTML = "";
        if (!s || s.nb_mesures === 0) {
            summaryEl.appendChild(statBlock("Mesures", "0"));
            return;
        }

        if (s.dernier_poids_kg != null) {
            summaryEl.appendChild(statBlock("Dernier poids", s.dernier_poids_kg + " kg"));
        }
        if (s.poids_moyen_kg != null) {
            summaryEl.appendChild(statBlock("Poids moyen", s.poids_moyen_kg + " kg"));
        }
        if (s.poids_min_kg != null && s.poids_max_kg != null) {
            summaryEl.appendChild(statBlock("Min / max", s.poids_min_kg + " — " + s.poids_max_kg + " kg"));
        }
        if (s.derniere_temperature_c != null) {
            summaryEl.appendChild(statBlock("Temp. récente", s.derniere_temperature_c + " °C"));
        }
        summaryEl.appendChild(statBlock("Points sur la période", String(s.nb_mesures)));
    }

    function destroyCharts() {
        if (chartPoids) {
            chartPoids.destroy();
            chartPoids = null;
        }
        if (chartTemp) {
            chartTemp.destroy();
            chartTemp = null;
        }
    }

    function buildCharts(labels, poids, temps) {
        destroyCharts();

        const ctxP = document.getElementById("chart-poids");
        const ctxT = document.getElementById("chart-temp");
        if (!ctxP || !ctxT) return;

        const commonOpts = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: "index", intersect: false },
            plugins: {
                legend: { labels: { color: ink, font: { family: "'Plus Jakarta Sans', sans-serif" } } },
            },
            scales: {
                x: {
                    ticks: { maxTicksLimit: 10, color: ink },
                    grid: { color: "rgba(45,42,38,0.08)" },
                },
                y: {
                    ticks: { color: ink },
                    grid: { color: "rgba(45,42,38,0.08)" },
                },
            },
        };

        chartPoids = new Chart(ctxP, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Poids (kg)",
                        data: poids,
                        borderColor: honeyDeep,
                        backgroundColor: "rgba(232, 163, 23, 0.15)",
                        fill: true,
                        tension: 0.25,
                        borderWidth: 2,
                        pointRadius: 0,
                    },
                ],
            },
            options: commonOpts,
        });

        chartTemp = new Chart(ctxT, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Température (°C)",
                        data: temps,
                        borderColor: ink,
                        backgroundColor: "rgba(26, 26, 26, 0.06)",
                        fill: true,
                        tension: 0.25,
                        borderWidth: 2,
                        pointRadius: 0,
                    },
                ],
            },
            options: commonOpts,
        });
    }

    async function loadMeta() {
        const res = await fetch("api/ruche.php?id=" + encodeURIComponent(rucheId), {
            headers: { Accept: "application/json" },
        });
        const data = await res.json();
        if (!data.ok) {
            showError(data.error || "Ruche introuvable.");
            return false;
        }
        const r = data.ruche;
        if (titleEl) titleEl.textContent = r.nom;
        if (bcRuche) bcRuche.textContent = r.nom;
        if (bcRucher) {
            bcRucher.textContent = r.rucher_nom;
            bcRucher.setAttribute("href", "rucher.php?id=" + encodeURIComponent(r.rucher_id));
        }
        document.title = r.nom + " — Bee Happy";
        return true;
    }

    async function loadStats() {
        clearError();
        const days = periodEl ? periodEl.value : "30";
        try {
            const res = await fetch(
                "api/ruche-stats.php?id=" + encodeURIComponent(rucheId) + "&days=" + encodeURIComponent(days),
                { headers: { Accept: "application/json" } }
            );
            const data = await res.json();
            if (!data.ok) {
                showError(data.error || "Impossible de charger les statistiques.");
                return;
            }

            const s = data.series;
            renderSummary(data.summary || {});
            buildCharts(s.labels || [], s.poids_kg || [], s.temperature_c || []);
        } catch (e) {
            console.error(e);
            showError("Erreur réseau ou serveur.");
        }
    }

    async function init() {
        const ok = await loadMeta();
        if (ok) await loadStats();
    }

    if (periodEl) {
        periodEl.addEventListener("change", function () {
            loadStats();
        });
    }

    init();
})();
