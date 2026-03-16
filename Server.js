const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const path = require("path");

const app = express();
const server = http.createServer(app);
const io = new Server(server);

// dossier contenant ton dashboard
app.use(express.static("public"));

app.get("/dashboard", (req, res) => {

    const token = req.query.token;

    if (!token) {
        return res.redirect("http://localhost/login.php");
    }

    const decoded = Buffer.from(token, "base64").toString("utf8");
    const parts = decoded.split("|");

    const login = parts[0];

    console.log("Utilisateur connecté :", login);

    // charger ton dashboard
    res.sendFile(path.join(__dirname, "public", "index.html"));
});

io.on("connection",(socket)=>{
    console.log("client connecté");
});

setInterval(()=>{

    const data = {
        temperature: (34 + Math.random()).toFixed(2),
        humidite: (60 + Math.random()*10).toFixed(2)
    };

    io.emit("nouvelleDonnee", data);

},3000);

server.listen(3000, ()=>{
    console.log("Serveur lancé sur http://localhost:3000");
});