<?php
session_start();
require "./service/pdo.php";

$pdo = connexionPDO();
$sql = $pdo->query("SELECT username, email, password FROM users");
$users = $sql->fetch();

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
    <a href="./deconnexion.php"><button>Deconnexion</button></a>
    <a href="./deleteProfil.php" onclick="confirmDelete(event)"><button>Supprimer son compte</button></a>
</div>


<?php
require "./template/footer.php";
?>