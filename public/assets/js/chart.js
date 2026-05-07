const ctx = document.getElementById("temperatureChart").getContext("2d");

const chart = new Chart(ctx, {
    type: "line",
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        plugins: {
            title: {
                display: true,
                text: "Temperature (°C)",
                align: "center"
            }
        },
        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});

function updateChart(temperature, hiveId) {
    const time = new Date().toLocaleTimeString();

    // Ajout label une fois
    if (!chart.data.labels.includes(time)) {
        chart.data.labels.push(time);

        if (chart.data.labels.length > 20) {
            chart.data.labels.shift();
            chart.data.datasets.forEach(ds => ds.data.shift());
        }
    }

    // Cherche dataset
    let dataset = chart.data.datasets.find(ds => ds.label === hiveId);

    
    if (!dataset) {
        const colors = {
            "Ruche 1": "red",
            "Ruche 2": "blue",
            "Ruche 3": "green",
            "Ruche 4": "orange"
        };

        dataset = {
            label: hiveId,
            data: [],
            borderWidth: 2,
            borderColor: colors[hiveId] || "black",
            fill: false
        };

        chart.data.datasets.push(dataset);
    }

    dataset.data.push(temperature);
    if (chart.data.labels.length > 10) {
                 chart.data.labels.shift();
                 chart.data.datasets[0].data.shift();
             }

    chart.update();
}






/****************************************************************************************************************************************/

// const ctx = document.getElementById("temperatureChart").getContext("2d");

// // Initialisation du chart
// const chart = new Chart(ctx, {
//     type: "line",
//     data: {
//         labels: [],
//         datasets: [{
//             label: "Température (°C)",
//             data: [],
//             borderWidth: 2,
//             tension: 0.3
//         }]
//     },
//     options: {
//         responsive: true,
//         maintainAspectRatio: false,
//         animation: false,
//         scales: {
//             x: {
//                 ticks: {
//                     maxTicksLimit: 6 // évite que ça explose visuellement
//                 }
//             },
//             y: {
//                 beginAtZero: false
//             }
//         }
//     }
// });


// //  CHARGEMENT HISTORIQUE
// fetch("http://localhost:3000/api/mesures")
//     .then(res => res.json())
//     .then(data => {

//         // on inverse pour ordre chronologique
//         data.reverse();

//         const labels = data.map(d => {
//             const date = new Date(d.date_heure);
//             return date.toLocaleTimeString();
//         });

//         const temperatures = data.map(d => d.temp);

//         chart.data.labels = labels;
//         chart.data.datasets[0].data = temperatures;

//         chart.update();
//     })
//     .catch(err => console.error("Erreur fetch :", err));


// // 2. TEMPS RÉEL (Socket.io)
// const socket = io("http://localhost:3000");

// socket.on("toutesDonnees", (dataArray) => {

//     // ici on reçois plusieurs ruches
//     // on prend la première (tu pourras améliorer plus tard)
//     const data = dataArray[0];

//     const now = new Date().toLocaleTimeString();

//     chart.data.labels.push(now);
//     chart.data.datasets[0].data.push(data.temperature);

//     // limite à 50 points pour éviter bug/perf
//     if (chart.data.labels.length > 50) {
//         chart.data.labels.shift();
//         chart.data.datasets[0].data.shift();
//     }

//     chart.update();
// });

/*****************************************************************************************************************************************/