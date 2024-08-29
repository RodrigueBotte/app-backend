<?php
session_start();
require "./service/pdo.php";

// récupération des informations de la personne connecté 
$pdo = connexionPDO();
$sql = $pdo->prepare("SELECT id, username, email FROM users WHERE id = :id");
$sql->execute(['id' => $_SESSION['id']]);
$users = $sql->fetch(PDO::FETCH_ASSOC);

// on cherche les informations du nom du deck en vérifiant que les deux ID correspondent
$sql = $pdo->prepare("SELECT inscription.deck_name FROM inscription WHERE user_id = :id");
$sql->execute(['id' => $_SESSION['id']]);
$decks = $sql->fetchAll(PDO::FETCH_ASSOC);
var_dump("./deleProfil.php?id=".$users['id']);


$title = "Page de profil";
require "./template/header.php";
?>
<div class="profil">
    <h2>Page de profil</h2>
    <!-- On affiche les données de la bdd avec htmlspecialchars pour éviter le XSS -->
    <p>Pseudo : <?php echo htmlspecialchars($users['username']) ?></p>
    <p>Email : <?php echo htmlspecialchars($users['email']) ?></p>
    <div>
        <?php if (!empty($decks)): ?>
            <p>Deck pour le tournoi :</p>
            <?php foreach ($decks as $deck): ?>
                <p><?php echo htmlspecialchars($deck['deck_name']); ?></p>
                <!-- lien permettant de modifier les informations du deck -->
                <a href="./update/updateDeck.php?id=<?php echo $users['id'] ?>">Changer de deck pour le tournoi</a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <!-- lien vers la deconnexion et la suppresion du compte -->
    <a href="./deconnexion.php"><button>Deconnexion</button></a>
    <a href="./deleteProfil.php?id=<?php echo $users["id"]; ?>" onclick="confirmDelete(event)" ><button>Supprimer son compte</button></a>
</div>
<!-- mise en place d'une fonction contenant une alerte à confirmer si l'on veut supprimer son compte -->
<script>
    function confirmDelete(event) {
        event.preventDefault();
        var useConfirm = confirm("Etes vous sur de vouloir supprimer votre compte?")

        if (useConfirm) {
            window.location.href = event.currentTarget.href;
        }
    }
</script>

<?php
require "./template/footer.php";
?>