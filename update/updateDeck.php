<?php
session_start();

require "../service/shouldBeLogged.php";
require "../service/csrf.php";
require "../service/pdo.php";
shouldBeLogged(true, "../connexion.php");

$connexion = connexionPDO();
$sql = $connexion->prepare("SELECT * FROM inscription WHERE user_id = ? ");
$sql->execute([(int)$_GET['id']]);
$decks = $sql->fetch();

$deck = "";
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isCSRFValid()) {
        $error['csrf'] = "La methode utilisée n'est pas autorisée";
    }
    if (empty($_POST['deck'])) {
        $deck = $decks['deck_name'];
    } else {
        $deck = cleanData($_POST['deck']);
    }
    if (empty($error)) {
        $sql = $connexion->prepare("UPDATE inscription SET deck_name = ? WHERE user_id = ?");
        $sql->execute([$deck, $decks['user_id']]);

        header("Location: ../profil.php");
        exit;
    }
}

require "../template/header.php"
?>
<div class="update">
    <h2>Changement de deck pour le tournoi : </h2>
    <p><?php echo htmlspecialchars($decks['deck_name']) ?> remplacé par : </p>
    <form action="" method="post">
        <input type="text" name="deck" id="deck">
        <?php setCSRF(10); ?>
        <span class="erreur" style="color: red;"><?php echo $error["csrf"] ?? "" ?></span>
        <input type="submit" value="Valider">
    </form>
</div>



<?php
require "../template/footer.php"
?>