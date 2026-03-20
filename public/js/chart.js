const ctx = document.getElementById("temperatureChart").getContext("2d");

const chart = new Chart(ctx, {
    type: "line",
    data: {
        labels: [],
        datasets: [{
            label: "Température ruche",
            data: [],
            borderWidth: 2,
            tension: 0.3 // smooth curve
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false, // smoother real-time updates
        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});

function updateChart(temperature, hiveId) {
    chart.data.labels.push(new Date().toLocaleTimeString());
    chart.data.datasets[0].data.push(temperature);

    // Update label dynamically
    chart.data.datasets[0].label = `Température ${hiveId}`;

    if (chart.data.labels.length > 20) {
        chart.data.labels.shift();
        chart.data.datasets[0].data.shift();
    }

    chart.update();
}