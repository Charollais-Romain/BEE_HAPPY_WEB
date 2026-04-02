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

    // Add label once
    if (!chart.data.labels.includes(time)) {
        chart.data.labels.push(time);

        if (chart.data.labels.length > 20) {
            chart.data.labels.shift();
            chart.data.datasets.forEach(ds => ds.data.shift());
        }
    }

    // Find dataset
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

    chart.update();
}