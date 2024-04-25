<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <title>Inscription</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">

</head>

<body>

    <div class='bold-line'></div>
    <div class='container'>
        <div class='window'>
            <div class='overlay'></div>
            <div class='content'>
                <div class='welcome'>Inscription</div>
                <div class='input-fields'>
                  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


                    <input type='Nom' name='Nom' placeholder='Nom' class='input-line full-width'>
                    <input type='Prenom' name='Prenom' placeholder='Prenom' class='input-line full-width'>
                    <input type='email' name='email' placeholder='Mail' class='input-line full-width'>
                    <input type='password' name='password' placeholder='Mot de passe' class='input-line full-width'>
                </div>
                <div><button class='ghost-round full-width' type='submit'>Créer un compte</button></div>
                  </form>

              </div>
        </div>
    </div>


</body>

<?php
// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "test");

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("La connexion a échoué : " . $mysqli->connect_error);
}

// Fonction pour sécuriser les chaînes avant de les utiliser dans une requête SQL
function secureInput($input) {
    global $mysqli;
    return $mysqli->real_escape_string($input);
}

// Initialiser les variables
$nom_Utilisateur = $prenom_Utilisateur = $mail_Utilisateur = $password_Utilisateur = "";
$nomErr = $prenomErr = $emailErr = $passwordErr = "";

// Traitement du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider et sécuriser les données
    $nom_Utilisateur = secureInput($_POST['Nom']);
    $prenom_Utilisateur = secureInput($_POST['Prenom']);
    $mail_Utilisateur = secureInput($_POST['email']);
    $password_Utilisateur = secureInput($_POST['password']);

    // Valider les champs (vous pouvez ajouter d'autres validations selon vos besoins)
    if (empty($nom_Utilisateur)) {
        $nomErr = "Le nom est requis";
    }

    if (empty($prenom_Utilisateur)) {
        $prenomErr = "Le prénom est requis";
    }

    if (empty($email_Utilisateur)) {
        $mailErr = "L'adresse e-mail est requise";
    } elseif (!filter_var($email_Utilisateur, FILTER_VALIDATE_EMAIL)) {
        $mailErr = "Format d'adresse e-mail invalide";
    }

    if (empty($password_Utilisateur)) {
        $passwordErr = "Le mot de passe est requis";
    }

    // Si toutes les validations sont passées, procéder à l'insertion dans la base de données
    if (empty($nomErr) && empty($prenomErr) && empty($emailErr) && empty($passwordErr)) {
        // Exemple : hachage du mot de passe avec l'algorithme bcrypt
        $hashedPassword = password_hash($password_Utilisateur, PASSWORD_BCRYPT);

        // Insérer les données dans la base de données
        $query = "INSERT INTO utilisateur (nom_Utilisateur, prenom_Utilisateur, mail_Utilisateur, password_Utilisateur) VALUES ('$nom_Utilisateur', '$prenom_Utilisateur', '$mail_Utilisateur', '$hashedPassword')";
        if ($mysqli->query($query)) {
            echo "Inscription réussie!";
        } else {
            echo "Erreur lors de l'inscription : " . $mysqli->error;
        }
            }
            // Vérifier si l'adresse e-mail de l'utilisateur est définie
        if (isset($mail_Utilisateur) && filter_var($mail_Utilisateur, FILTER_VALIDATE_EMAIL)) {
            $confirmationCode = uniqid();
            $to = $mail_Utilisateur;
            $subject = 'Confirmation d\'inscription';
            $message = 'Merci de vous être inscrit sur notre site. Veuillez confirmer votre adresse e-mail en cliquant sur le lien suivant : http://localhost/confirmation.php?code=6623bf780f5e8' . $confirmationCode;
            $headers = 'From: ayoub.foud.sn@gmail.com' . "\r\n" .
                'Reply-To: ayoub.foud.sn@gmail.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            // Envoyer l'e-mail de confirmation
            if (mail($to, $subject, $message, $headers)) {
                echo 'Un e-mail de confirmation a été envoyé à ' . $mail_Utilisateur;
            } else {
                echo 'Erreur : Impossible d\'envoyer l\'e-mail de confirmation.';
            }
        } else {
            echo 'Erreur : Adresse e-mail invalide.';
        }



}





// Fermer la connexion à la base de données
$mysqli->close();
?>
</html>
