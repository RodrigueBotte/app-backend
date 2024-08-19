<?php
require "./service/shouldBeLogged.php";
shouldBeLogged(false, "./connexion.php");

$username = $email = $password = "";
$error = [];
$regex = "/^(?=.*[!?@#$%^&*+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscription'])) {
    
    require "./service/pdo.php";
    require "./service/csrf.php";

    $pdo = connexionPDO();

    if (empty($_POST['pseudo'])) {
        $error['pseudo']= "Veuillez saisir un pseudo!";
    }
    else{
        $username = cleanData($_POST['pseudo']);
        if(!preg_match("/^[a-zA-Z'\s-]{2,25}$/", $username)){
            $error['pseudo'] = "Veuillez saisir un pseudo valide!";
        }
    }


    if(empty($_POST['email'])){
        $error['email'] = "Veuillez saisir un email!";
    }
    else{
        $email = cleanData($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "Veillez saisir un email valide!";
        }
        $sql = $pdo->prepare("SELECT * FROM users WHERE email=:em");
        $sql->execute(["em"=>$email]);
        $resultat = $sql->fetch();
        if ($resultat) {
            $error['email'] = "Cet email est déjà enregistré!";
        }
    }

    if(empty($_POST['password'])){
        $error['password'] = "Veuillez entrer un email!";
    }
    else{
        $password = trim($_POST['password']);
        if (!preg_match($regex, $password)) {
            $error['password'] = 'Veuillez saisir un email plus complexe';
        }
        $password = password_hash($password, PASSWORD_DEFAULT);
    }

    if (empty($_POST['passwordBis'])) {
        $error['passwordBis'] = 'Veuillez saisir à nouveau votre mot de passe!';
    }elseif ($_POST['password'] != $_POST['passwordBis']) {
        $error['passwordBis'] = "Veuillez entrer le même mot de passe";
    }
    // setCSRF();
    // isCSRFValid();

    if (empty($error)) {
        $sql = $pdo->prepare("INSERT INTO users(username, email, password) value(?,?,?)");
        $sql->execute([$username, $email, $password]);
        $_SESSION["flash"] = "Inscription prise en compte!!!";
        header('Location: ./index.php');
        exit;
    }
}

$title = "Création d'un compte";
require './template/header.php'
?>
<div class="inscription">
    <h2>Inscription</h2>
    <form action="" method="post">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" required>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required>
        <label for="passwordBis">Confirmation du mot de passe</label>
        <input type="password" name="passwordBis" id="passwordBis" required>
        <input type="submit" value="Incription">
    </form>
</div>

<?php
require './template/footer.php'
?>