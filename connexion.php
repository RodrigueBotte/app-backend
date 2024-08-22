<?php
session_start(["cookie_lifetime" => 3600]);

if (isset($_SESSION['logged']) && $_SESSION["logged"] === true) {
    header("Location: ./profil.php");
    exit;
}

$email = $pass = "";
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
    
    if (empty($_POST["email"])) {
        $error["email"] = "Veuillez entrer votre email";
    }
    else{
        $email = trim($_POST['email']);
    }
    if (empty($_POST['password'])) {
        $error['password'] = "Veuillez entrer votre mot de passe!";
    }
    else{
        $password = trim($_POST['password']);
    }
    if (empty($error)) {
        require "./service/pdo.php";
        $connexion = connexionPDO();

        $sql = $connexion->prepare("SELECT * FROM users WHERE email = :em");
        $sql->execute(["em"=>$email]);

        $user = $sql->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION["logged"] = true ;
                $_SESSION["username"] = $user["username"];
                $_SESSION["id"] = $user['id'];
                $_SESSION["expire"] = time() + 3600;
                header("Location: ./profil.php");
                exit;
            }
            else{
                $error["connexion"] = "Mot de Passe Incorrect";
            }
        }
        else{
            $error["connexion"] = "Email Incorrect";
        }
    }
}


$title = "Connexion";
require './template/header.php'
?>

<div class="connexion">
    <h2>Connexion</h2>
    <form action="" method="post">
        <label for="email">Email : </label>
        <input type="email" name="email" id="email">
        <span class="erreur" style="color: red;"><?php echo $error["email"]??"" ?></span>
        <label for="password">Mot de passe : </label>
        <input type="password" name="password" id="password">
        <span class="erreur" style="color: red;"><?php echo $error["password"]??"" ?></span>
        <input type="submit" value="connexion" name="connexion">
        <span class="erreur" style="color: red;"><?php echo $error["connexion"]??"" ?></span>
    </form>
    <div>
        <p>Je ne possède pas de compte</p>
        <a href="./inscription.php"><button>Inscription</button></a>
    </div>
</div>
<?php
require './template/footer.php'
?>