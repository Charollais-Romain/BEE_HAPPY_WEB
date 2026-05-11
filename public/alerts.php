<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Bee Alertes</title>
<link rel="stylesheet" href="../public/assets/css/alertes.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div style="text-align: center;">
    <h2>Alertes</h2>
</div>
<div class="container">

    <h1>Alertes</h1>

    <div id="alertes-container"></div>

</div>

<script>

const alertes = [
    {
        titre:"Température trop élevée",
        message:"La ruche dépasse 40°C",
        date:"2026-05-12 14:22",
        niveau:"critical",
        lu:false
    },

    {
        titre:"Choc Critique",
        message:"Choc de la ruche",
        date:"2026-05-12 11:03",
        niveau:"warning",
        lu:true
    },

    {
        titre:"Connexion rétablie",
        message:"La ruche est reconnectée",
        date:"2026-05-11 18:44",
        niveau:"info",
        lu:true
    }
];

const container = document.getElementById('alertes-container');

alertes.forEach(alert => {

    let badge = '';
    
    if(alert.niveau === 'critical'){
        badge = '<span class="badge badge-critical">Critique</span>';
    }
    else if(alert.niveau === 'warning'){
        badge = '<span class="badge badge-warning">Attention</span>';
    }
    else{
        badge = '<span class="badge badge-info">Info</span>';
    }

    container.innerHTML += `
    
    <div class="alert-card ${alert.niveau} ${alert.lu ? '' : 'unread'}">

        <h3>
            ${alert.titre}
            ${badge}
        </h3>

        <p>${alert.message}</p>

        <div class="alert-date">
            ${alert.date}
        </div>

    </div>
    
    `;
});

</script>
</body>
</html>