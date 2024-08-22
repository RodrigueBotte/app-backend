<?php
session_start();
require "./service/pdo.php";

$pdo = connexionPDO();
$sql = $pdo->prepare("SELECT id, username, email FROM users WHERE id = :id");
$sql->execute(['id' => $_SESSION['id']]);
$users = $sql->fetch(PDO::FETCH_ASSOC);
var_dump($users['id'], $_SESSION['id']);

$sql = $pdo->prepare("SELECT inscription.deck_name FROM inscription WHERE user_id = :id");
$sql->execute(['id' => $_SESSION['id']]);
$decks = $sql->fetchAll(PDO::FETCH_ASSOC);


$title = "Page de profil";
require "./template/header.php";
?>
<script>
    function confirmDelete(event) {
        event.preventDefault;
        var useConfirm = confirm("Etes vous sur de vouloir supprimer votre compte?")

        if (useConfirm) {
            window.location.href = event.target.href;
        }
    }
</script>
<div class="profil">
    <h2>Page de profil</h2>
    <p>Pseudo : <?php echo $users['username'] ?></p>
    <p>Email : <?php echo $users['email'] ?></p>
    <?php if (!empty($decks)): ?>
        <p>Deck(s) :</p>
        <?php foreach ($decks as $deck): ?>
            <p><?php echo htmlspecialchars($deck['deck_name']); ?></p>
        <?php endforeach; ?>
    <?php else: ?>
        <p></p>
    <?php endif; ?>
    <a href="./deconnexion.php"><button>Deconnexion</button></a>
    <a href="./deleteProfil.php?id=<?php echo $users["id"] ?>" onclick="confirmDelete(event)"><button>Supprimer son compte</button></a>
</div>


<?php
require "./template/footer.php";
?>