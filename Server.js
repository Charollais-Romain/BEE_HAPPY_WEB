const express = require("express");
const http = require("http");
const {Server} = require("socket.io");

const app = express();
const server = http.createServer(app);
const io = new Server(server);

app.use(express.static("public"));

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