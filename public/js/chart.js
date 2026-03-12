const ctx = document.getElementById("temperatureChart").getContext("2d");

const chart = new Chart(ctx, {
type: "line",
data: {
labels: [],
datasets: [{
label: "Température ruche",
data: [],
borderWidth: 2
}]
},
options: {
responsive: true
}
});