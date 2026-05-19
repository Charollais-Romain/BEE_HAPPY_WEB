<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Bee Alertes</title>
<link rel="stylesheet" href="../public/assets/css/alertes.css">
</head>
<body>

<?php include '../includes/header.php'; ?>
<div class="container">

    <h1>Alertes</h1>

    <div id="alertes-container"></div>

</div>

<script>

const container = document.getElementById('alertes-container');

let alertesConnues = [];

async function chargerAlertes(){

    try{

        const response = await fetch('get_alertes.php');

        const alertes = await response.json();

        container.innerHTML = '';

        alertes.forEach(alert => {

            const criticite = alert.criticite.toLowerCase();

            let badgeClass = '';
            let badgeText = '';

            switch(alert.criticite){

                case 'LOW':
                    badgeClass = 'badge-low';
                    badgeText = 'Faible';
                    break;

                case 'MEDIUM':
                    badgeClass = 'badge-medium';
                    badgeText = 'Moyenne';
                    break;

                case 'HIGH':
                    badgeClass = 'badge-high';
                    badgeText = 'Haute';
                    break;

                case 'CRITICAL':
                    badgeClass = 'badge-critical';
                    badgeText = 'Critique';
                    break;
            }

            let nouvelleClasse = '';

            if(!alertesConnues.includes(alert.id_alerte)){

                alertesConnues.push(alert.id_alerte);

                nouvelleClasse = 'new-alert';

                // Popup uniquement pour critique
                if(alert.criticite === 'CRITICAL'){

                    afficherPopup(alert);
                }
            }

            container.innerHTML += `
            
            <div class="alert-card ${criticite} ${nouvelleClasse}">

                <div class="alert-header">

                    <h3>${alert.nom}</h3>

                    <span class="badge ${badgeClass}">
                        ${badgeText}
                    </span>

                </div>

                <p>${alert.message}</p>

                <div class="alert-date">
                    ${formatDate(alert.date_heure)}
                </div>

            </div>
            
            `;
        });

    }
    catch(error){

        console.error("Erreur chargement alertes :", error);
    }
}

function formatDate(dateString){

    const date = new Date(dateString);

    return date.toLocaleString('fr-FR');
}

function afficherPopup(alert){

    const popup = document.createElement('div');

    popup.style.position = 'fixed';
    popup.style.top = '20px';
    popup.style.right = '20px';
    popup.style.background = '#b71c1c';
    popup.style.color = 'white';
    popup.style.padding = '15px';
    popup.style.borderRadius = '10px';
    popup.style.boxShadow = '0 0 10px rgba(0,0,0,0.3)';
    popup.style.zIndex = '9999';

    popup.innerHTML = `
        <strong>ALERTE CRITIQUE</strong><br>
        ${alert.nom}
    `;

    document.body.appendChild(popup);

    setTimeout(() => {
        popup.remove();
    }, 5000);
}

chargerAlertes();

setInterval(chargerAlertes, 5000);

</script>
</body>
</html>