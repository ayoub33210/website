
<?php
// Démarre la session
session_start();

// Vérifie si l'utilisateur est connecté
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    // Redirige l'utilisateur vers la page de connexion
    header("Location: connexion.php");
    exit();
}

// Le reste du code de votre page ici...
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surveillance qualité de l'air</title>
</head>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Site</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto:400,300,100,500);
        body,
        html {
            margin: 0;
            height: 100%;
            font-family: 'Roboto', sans-serif;
        }

        nav {
            background-color: #2660d3;
            /* Bleu plus clair */
            padding: 10px;
            color: #fff;
            text-align: left;
            /* Centré à gauche */
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-weight: 500;
            font-size: 15px;
        }

        nav a:hover {
            border-bottom: 2px solid #8ceabb;
            padding-bottom: 3px;
        }
    </style>
</head>

<body>
    <nav>
        <a href="index.php">Données instantannées</a>
        <a href="#">Données sauvegardées</a>
        <a href="#">Mesures de temperatures corporelles</a>
        <a href="#">Temperature de la salle</a>
        <a href="#">Humidité</a>
        <a href="#">Mesures du nombres de particules(PM10/PM2.5)</a>

    </nav>
</body>

</html>

<body>

</body>

</html>


<?php
// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "test");

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("La connexion a échoué : " . $mysqli->connect_error);
}

// Récupérer les données de la base de données
$result = $mysqli->query("SELECT mesure_PPM, date_PPM FROM PPM");

// Créer une image
$largeur = 600;
$hauteur = 400;
$image = imagecreate($largeur, $hauteur);
$blanc = imagecolorallocate($image, 255, 255, 255);
$noir = imagecolorallocate($image, 0, 0, 0);

// Tracer le graphique
$x = 0;
while ($row = $result->fetch_assoc()) {
    $y = $hauteur - $row['mesure_PPM'];
    imagefilledrectangle($image, $x, $y, $x + 10, $hauteur, $noir); // Largeur des barres = 10 pixels
    $x += 15; // Espace entre les barres = 5 pixels
}

// En-tête pour indiquer que l'image est une image PNG
header('Content-type: image/png');

// Afficher l'image
imagepng($image);

// Libérer la mémoire
imagedestroy($image);

// Fermer la connexion à la base de données
$mysqli->close();
?>
