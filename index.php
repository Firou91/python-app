<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique de Température</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="temperatureChart" width="800" height="400"></canvas>

    <form>
    	<label for="fanCheckbox">Activer le ventilateur</label>
    	<input type="checkbox" id="fanCheckbox" name="fanStatus" <?php echo> $fanStatus ? 'checked' : ''; ?>>
    	<input type="submit" value="Enregistrer">
    </form>

    <?php
    // Connexion à la base de données
    $connexion = new mysqli("localhost", "firou", "091344", "dht22");

    // Vérifier la connexion
    if ($connexion->connect_error) {
        die("Échec de la connexion à la base de données : " . $connexion->connect_error);
    }

    // Récupérer les données de la table
    $resultat = $connexion->query("SELECT * FROM temp");

    // Préparation des données pour le graphique
    $labels = [];
    $data = [];

    while ($row = $resultat->fetch_assoc()) {
        $labels[] = $row['date'];
        $data[] = $row['temp'];
    }

    // Fermeture de la connexion à la base de données
    $connexion->close();
    ?>

    <script>
        // Récupérer les données PHP dans JavaScript
        var labels = <?php echo json_encode($labels); ?>;
        var data = <?php echo json_encode($data); ?>;

        // Configuration du graphique
        var ctx = document.getElementById('temperatureChart').getContext('2d');
        var temperatureChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Température (°C)',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: [{
                        type: 'time',
                        time: {
                            unit: 'minute'
                        },
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }],
                    y: {
                        title: {
                            display: true,
                            text: 'Température (°C)'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
