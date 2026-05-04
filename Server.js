const express = require("express");
const http = require("http");
const path = require("path");
const { Server } = require("socket.io");

const app = express();
const server = http.createServer(app);
const io = new Server(server);

app.use(express.static("public"));

// Dashboard route
app.get("/dashboard", (req, res) => {
    const token = req.query.token;

    if (!token) {
        return res.redirect("http://10.187.52.4/~morganl/ProjetRuche/login.php");
    }

    try {
        const decoded = Buffer.from(token, "base64").toString("utf8");
        const parts = decoded.split("|");

        const login = parts[0];
        const timestamp = parseInt(parts[1]);

        console.log("Utilisateur connecté :", login);

        // Expiration check (1 hour)
        const now = Math.floor(Date.now() / 1000);
        if (now - timestamp > 3600) {
            return res.redirect("http://10.187.52.4/~morganl/ProjetRuche/login.php");
        }

        res.sendFile(path.join(__dirname, "public", "index.php"));
    } catch (err) {
        console.log("Token invalide");
        res.redirect("http://10.187.52.4/~morganl/ProjetRuche/login.php");
    }
});

// Socket connection
io.on("connection", (socket) => {
    console.log("Client connecté");

    const hives = [
        { id: "Ruche 1", lat: 45.83547, lng: 1.2645 },
        { id: "Ruche 2", lat: 45.87676, lng: 1.1360 },
        { id: "Ruche 3", lat: 45.82850, lng: 1.2580 },
        { id: "Ruche 4", lat: 45.84000, lng: 1.2500 }
    ];

    const sendData = () => {
        const hive = hives[Math.floor(Math.random() * hives.length)];

        const data = {
            hiveId: hive.id,
            lat: hive.lat,
            lng: hive.lng,
            temperature: parseFloat((34 + Math.random()).toFixed(2)),
            humidite: parseFloat((60 + Math.random() * 10).toFixed(2))
        };

        socket.emit("toutesDonnees", hives.map(hive => ({
            hiveId: hive.id,
            lat: hive.lat,
            lng: hive.lng,
            temperature: parseFloat((34 + Math.random()).toFixed(2)),
            humidite: parseFloat((60 + Math.random() * 10).toFixed(2))
        })));
    };

    sendData();

    // Sends data every 3 seconds
    const interval = setInterval(sendData, 3000);

    socket.on("disconnect", () => {
        console.log("Client déconnecté");
        clearInterval(interval);
    });
});

// Start server
server.listen(3000, () => {
    console.log("Serveur lancé sur http://10.187.52.4/~morganl/ProjetRuche/index.php");
});






/******************************************************************************************************************************************/

// const express = require("express");
// const http = require("http");
// const path = require("path");
// const { Server } = require("socket.io");
// const mysql = require("mysql2");

// const app = express();
// const server = http.createServer(app);
// const io = new Server(server);

// app.use(express.static("public"));
// app.use(express.json());

// // Connexion BDD
// const db = mysql.createConnection({
//     host: "localhost",
//     user: "morganl",
//     password: "morganl",
//     database: "morganl_b"
// });

// db.connect(err => {
//     if (err) {
//         console.error("Erreur BDD :", err);
//     } else {
//         console.log("BDD connectée");
//     }
// });


// // API pour récup les données
// app.get("/api/mesures", (req, res) => {
//     const sql = `
//         SELECT * 
//         FROM Ruche__mesure 
//         ORDER BY date_heure DESC 
//         LIMIT 100
//     `;

//     db.query(sql, (err, result) => {
//         if (err) {
//             console.error(err);
//             res.status(500).send("Erreur BDD");
//         } else {
//             res.json(result);
//         }
//     });
// });


// // Dashboard route
// app.get("/dashboard", (req, res) => {
//     const token = req.query.token;

//     if (!token) {
//         return res.redirect("http://localhost/login.php");
//     }

//     try {
//         const decoded = Buffer.from(token, "base64").toString("utf8");
//         const parts = decoded.split("|");

//         const timestamp = parseInt(parts[1]);

//         const now = Math.floor(Date.now() / 1000);
//         if (now - timestamp > 3600) {
//             return res.redirect("http://localhost/login.php");
//         }

//         res.sendFile(path.join(__dirname, "public", "index.html"));
//     } catch (err) {
//         res.redirect("http://localhost/login.php");
//     }
// });


// // Socket
// io.on("connection", (socket) => {
//     console.log("Client connecté");

//     const hives = [
//         { id: "Ruche 1", lat: 45.83547, lng: 1.2645 },
//         { id: "Ruche 2", lat: 45.87676, lng: 1.1360 },
//         { id: "Ruche 3", lat: 45.82850, lng: 1.2580 },
//         { id: "Ruche 4", lat: 45.84000, lng: 1.2500 }
//     ];

//     const sendData = () => {
//         const hive = hives[Math.floor(Math.random() * hives.length)];

//         const data = {
//             hiveId: hive.id,
//             lat: hive.lat,
//             lng: hive.lng,
//             temperature: parseFloat((34 + Math.random()).toFixed(2)),
//             humidite: parseFloat((60 + Math.random() * 10).toFixed(2))
//         };

//         // INSERT BDD
//         const sql = `
//             INSERT INTO Ruche__mesure (poids, temp, lat, lng, date_heure, id_capteur)
//             VALUES (?, ?, ?, ?, NOW(), ?)
//         `;

//         db.query(sql, [
//             50, // poids simulé
//             data.temperature,
//             data.lat,
//             data.lng,
//             1 // id capteur (à adapter)
//         ]);

//         // ENVOI FRONT
//         io.emit("toutesDonnees", hives.map(hive => ({
//             hiveId: hive.id,
//             lat: hive.lat,
//             lng: hive.lng,
//             temperature: parseFloat((34 + Math.random()).toFixed(2)),
//             humidite: parseFloat((60 + Math.random() * 10).toFixed(2))
//         })));
//     };

//     sendData();

//     const interval = setInterval(sendData, 3000);

//     socket.on("disconnect", () => {
//         console.log("Client déconnecté");
//         clearInterval(interval);
//     });
// });


// // Lancement
// server.listen(3000, () => {
//     console.log("Serveur lancé sur http://localhost:3000");
// });

/****************************************************************************************************************************************/