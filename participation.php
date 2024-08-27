<?php
session_start();
// pour s'incrire à l'evénement, on vérifie si la personne est connecté
require "./service/shouldBeLogged.php";
require "./service/pdo.php";
require "./service/csrf.php";
shouldBeLogged(true, './connexion.php');

$error = [];

$pdo = connexionPDO();
$sql = $pdo->prepare("SELECT id, username FROM users WHERE id=?");
$sql->execute([(int)$_SESSION['id']]);
// on récupère ses informations en fonction de son id de session
$participant = $sql->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['deck'])) {
        $error['deck'] = "Veuillez rentrer le nom de votre deck pour participer <br>";
    }
    if (!isCSRFValid()) {
        $error['csrf'] = "La methode utilisée n'est pas autorisée";
    }

    if (empty($error)) {
        $deck = cleanData($_POST['deck']);

        $sql = $pdo->prepare("INSERT INTO inscription (user_id, deck_name) VALUES (?, ?)");
        // On enregistre sa participation en mettant son id de session en tant qu'id d'inscription
        // ce qui va permettre de récupérer le nom de son deck en fonction de son compte
        $sql->execute([$participant['id'], $deck]);

        header('Location: ./profil.php');
        exit;
    }
}


require "./template/header.php"
?>
<div class="participation">
    <form action="" method="post">
        <p>Nom du participant: <?php echo htmlspecialchars($participant['username']) ?></p>
        <label for="deck">Nom du deck et ses couleurs :</label>
        <input type="text" name="deck" id="deck"><br>
        <span class="erreur" style="color: red;"><?php echo $error["deck"] ?? "" ?></span>
        <?php setCSRF(10); ?>
        <span class="erreur" style="color: red;"><?php echo $error["csrf"] ?? "" ?></span>
        <input type="submit" value="Valider ma participation">
    </form>
</div>


<?php
require "./template/footer.php"
?>