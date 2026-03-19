const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const path = require("path");

const app = express();
const server = http.createServer(app);
const io = new Server(server);

<<<<<<< Updated upstream
// dossier contenant ton dashboard
=======
// static folder for dashboard
>>>>>>> Stashed changes
app.use(express.static("public"));

app.get("/dashboard", (req, res) => {

    const token = req.query.token;

<<<<<<< Updated upstream
=======
    // If no token → back to login
>>>>>>> Stashed changes
    if (!token) {
        return res.redirect("http://localhost/login.php");
    }

<<<<<<< Updated upstream
    const decoded = Buffer.from(token, "base64").toString("utf8");
    const parts = decoded.split("|");

    const login = parts[0];

    console.log("Utilisateur connecté :", login);

    // charger ton dashboard
    res.sendFile(path.join(__dirname, "public", "index.html"));
});

io.on("connection",(socket)=>{
    console.log("client connecté");
=======
    try {

        // Decode token
        const decoded = Buffer.from(token, "base64").toString("utf8");
        const parts = decoded.split("|");

        const login = parts[0];
        const timestamp = parts[1];

        console.log("Utilisateur connecté :", login);

        // You could add expiration here
        const now = Math.floor(Date.now() / 1000);
        if (now - timestamp > 3600) { // 1 hour
            return res.redirect("http://localhost/login.php");
        }

        // Load dashboard
        res.sendFile(path.join(__dirname, "public", "index.html"));

    } catch (err) {
        console.log("Token invalide");
        res.redirect("http://localhost/login.php");
    }

>>>>>>> Stashed changes
});

// Socket connection
io.on("connection", (socket) => {

<<<<<<< Updated upstream
    const data = {
        temperature: (34 + Math.random()).toFixed(2),
        humidite: (60 + Math.random()*10).toFixed(2)
    };

    io.emit("nouvelleDonnee", data);
=======
    console.log("Client connecté");

});
>>>>>>> Stashed changes

// Example sensor data simulation
setInterval(() => {

<<<<<<< Updated upstream
server.listen(3000, ()=>{
=======
    const data = {
        temperature: (34 + Math.random()).toFixed(2),
        humidite: (60 + Math.random() * 10).toFixed(2)
    };

    io.emit("nouvelleDonnee", data);

}, 3000);

server.listen(3000, () => {
>>>>>>> Stashed changes
    console.log("Serveur lancé sur http://localhost:3000");
});