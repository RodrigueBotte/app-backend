<?php
session_start();
// on vérifie que la personne est connnectée
require "./service/shouldBeLogged.php";
shouldBeLogged(true, "./connexion.php");

require "./service/pdo.php";

$connexion = connexionPDO();

// on utilise beginTransaction() pour vérifier que chaque suppression est bien effectué 
// afin de supprimer l'utilisateur ainsi que tout ce qui lui ai lié par son ID dans l'autre partie de la bdd
// s'il n'y a pas d'erreur, commit() valide les suppressions
// s'il y a une erreur durant l'une des suppresions, on revient en arrière grâce à rollBack()
$connexion->beginTransaction();
try {
    $sql = $connexion->prepare("DELETE FROM users WHERE id = ?");
    $sql->execute([$_GET['id']]);

    $sql = $connexion->prepare("DELETE FROM inscription WHERE user_id = ?");
    $sql->execute([$_GET['id']]);

    $connexion->commit();
    unset($_SESSION['id']);
    session_destroy();
    setcookie("PHPSESSID", "", time()-3600, "/");
    header("refresh:5; url=./index.php");

} catch (\Exception $e) {
    $connexion->rollBack();
    echo "Une erreur s'est produite lors de la suppression du compte.";
}

$title = "Suppression de compte";
require "./template/header.php"
?>
<p>Votre compte a bien été supprimé. Redirection vers la page d'accueil dans quelques instants.</p>
<?php
require "./template/footer.php";
?>