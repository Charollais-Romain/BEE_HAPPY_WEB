const express = require("express");
const http = require("http");
const { Server } = require("socket.io");

const app = express();
const server = http.createServer(app);
const io = new Server(server);

app.use(express.static("public"));

io.on("connection", (socket) => {
    console.log("Client connecté");

    socket.on("disconnect", () => {
        console.log("Client déconnecté");
    });
});

// Simulation de données capteurs
setInterval(() => {
    const sensorData = {
        temperature: (20 + Math.random() * 5).toFixed(2),
        humidity: (40 + Math.random() * 20).toFixed(2)
    };

    io.emit("sensorData", sensorData);

}, 1000);

server.listen(3000, () => {
    console.log("Serveur lancé sur http://localhost:3000");
});