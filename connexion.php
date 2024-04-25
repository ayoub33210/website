<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <title>Connexion</title>
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles.css">

</head>
<body class="align">

  <div class='bold-line'></div>
  <div class='container'>
  <div class='window'>
    <div class='overlay'></div>
    <div class='content'>
      <div class='welcome'>Connexion</div>
      <div class='input-fields'>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <input type='email' name='email' placeholder='Mail' class='input-line full-width'></input>
          <input type='password' name='password' placeholder='Mot de passe' class='input-line full-width'></input>
          <div class='spacing' onclick="window.location.href='inscription.php'">Vous n'avez pas de compte ? <span class='highlight'>Inscrivez-vous</span></div>
          <div><button type='submit' class='ghost-round full-width'> Se connecter</button></div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "test");

// Vérifier la connexion
if ($mysqli->connect_error) {
    die("La connexion a échoué : " . $mysqli->connect_error);
}

// Traitement du formulaire de connexion
// Traitement du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Valider et sécuriser les données
  $email_Utilisateur = secureInput($_POST['email']);
  $password_Utilisateur = secureInput($_POST['password']);

  // Vérifier l'existence de l'utilisateur dans la base de données
  $query = "SELECT * FROM utilisateur WHERE mail_Utilisateur = '$email_Utilisateur'";
  $result = $mysqli->query($query);

  if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      // Vérifier le mot de passe
      if (password_verify($password_Utilisateur, $row['password_Utilisateur'])) {
          // Démarre la session
          session_start();
          // Définit la variable de session
          $_SESSION['loggedin'] = true;
          // Rediriger l'utilisateur vers la page index.php
          header("Location: index.php");
          exit(); // Assurez-vous d'arrêter l'exécution du script après la redirection
      } else {
          echo "Mot de passe incorrect";
      }
  } else {
      echo "Utilisateur non trouvé";
  }
}


// Fonction pour sécuriser les chaînes avant de les utiliser dans une requête SQL
function secureInput($input) {
    global $mysqli;
    return $mysqli->real_escape_string($input);
}

// Fermer la connexion à la base de données
$mysqli->close();

?>


</body>
</html>