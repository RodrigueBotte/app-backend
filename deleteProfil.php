<?php
session_start();
require "./service/shouldBeLogged.php";
shouldBeLogged(true, "./connexion.php");

require "./service/pdo.php";

$connexion = connexionPDO();
$sql = $connexion->prepare("DELETE FROM users WHERE id = ?");
if ($sql->execute([(int)$_GET['id']])) {
    unset($_SESSION['id']);
    setcookie("PHPSESSID", "", time()-3600, "/");
    header("refresh:5;url=./index.php");
} else {
    echo "Une erreur s'est produite lors de la suppression du compte.";
}

$title = "Suppression de compte";
require "./template/header.php"
?>
<p>Votre compte a bien été supprimé. Redirection vers la page d'accueil dans quelques instants.</p>
<?php
require "./template/footer.php";
?>