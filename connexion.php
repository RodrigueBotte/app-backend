<?php
session_start(["cookie_lifetime" => 3600]);

if (isset($_SESSION['logged']) && $_SESSION["logged"] === true) {
    header("Location: ./profile.php");
    exit;
}

$username = $pass = "";



$title = "Connexion";
require './template/header.php'
?>

<div class="connexion">
    <h2>Connexion</h2>
    <form action="" method="post">
        <label for="username">Pseudo</label>
        <input type="text" name="username" id="username">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password">
        <input type="submit" value="connexion" name="connexion">
    </form>
    <div>
        <p>Je ne possède pas de compte</p>
        <a href="./inscription.php"><button>Inscription</button></a>
    </div>
</div>
<?php
require './template/footer.php'
?>