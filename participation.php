<?php
session_start();

require "./service/shouldBeLogged.php";
require "./service/pdo.php";
shouldBeLogged(true, './connexion.php');

$pdo = connexionPDO(); 
$sql = $pdo->prepare("SELECT id, username FROM users WHERE id=?");
$sql->execute([(int)$_SESSION['id']]);
$participant = $sql->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deck = $_POST['deck'];

    $sql= $pdo->prepare("INSERT INTO inscription (user_id, deck_name) VALUES (?, ?)");
    $sql->execute([$participant['id'], $deck]);

    header('Location: ./profil.php');
    exit;
}


require "./template/header.php"
?>
<div class="participation">
    <form action="" method="post">
        <p>Nom du participant: <?php echo $participant['username'] ?></p>
    <label for="deck">Nom du deck et ses couleurs :</label>
    <input type="text" name="deck" id="deck">
    <input type="submit" value="Valider ma participation">
    </form>
</div>


<?php
require "./template/footer.php"
?>