<?php
session_start();

require "../service/shouldBeLogged.php";
shouldBeLogged(true, "../connexion.php");

// if (!isset($_SESSION['user_id'], $_GET['id']) || $_SESSION['user_id'] != $_GET['id']) {
//     header("Location: ../profil.php");
//     exit;
// }

require "../service/pdo.php";
$connexion = connexionPDO();
$sql = $connexion->prepare("SELECT * FROM inscription WHERE user_id = ? ");
$sql->execute([$_GET['id']]);
$decks = $sql->fetch();

$deck = "";
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    require "../service/csrf.php";

    if (empty($_POST['deck'])) {
        $deck = $decks['deck_name'];
    } else {
        $deck = $_POST['deck'];
    }
    $sql = $connexion->prepare("UPDATE inscription SET deck_name = ? WHERE user_id = ?");
    $sql->execute([$deck, $decks['user_id']]);

    header("Location: ../profil.php");
    exit;
}

require "../template/header.php"
?>
<div class="update">
    <h2>Changement de deck pour le tournoi : </h2>
    <p><?php echo $decks['deck_name'] ?> remplacé par : </p>
    <form action="" method="post">
        <input type="text" name="deck" id="deck">
        <input type="submit" value="Valider">
    </form>
</div>



<?php
require "../template/footer.php"
?>