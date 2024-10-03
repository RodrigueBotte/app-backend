<?php
session_start(["cookie_lifetime" => 3600]);
require "./service/csrf.php";

$max_attempts = 4;       // Nombre de tentative possible
$block_duration = 30;   // Temps de blocage après avoir épuisé les tentatives

if (isset($_SESSION['logged']) && $_SESSION["logged"] === true) {
    header("Location: ./profil.php");
    exit;
}

// Initialiser le suivi des tentatives si non existant
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// Vérifier si l'utilisateur est bloqué
if ($_SESSION['attempts'] >= $max_attempts) {
    $time_since_last_attempt = time() - $_SESSION['last_attempt_time'];
    
    if ($time_since_last_attempt < $block_duration) {
        die("Vous avez atteint la limite des tentatives. Réessayez dans " . ($block_duration - $time_since_last_attempt) . " secondes.");
    } else {
        // Réinitialiser les tentatives après la période de blocage
        $_SESSION['attempts'] = 0;
    }
}

$email = $pass = "";
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
    
    if (empty($_POST["email"])) {
        $error["email"] = "Veuillez entrer votre email";
    }
    else{
        $email = cleanData($_POST['email']);
    }
    if (empty($_POST['password'])) {
        $error['password'] = "Veuillez entrer votre mot de passe!";
    }
    else{
        $password = cleanData($_POST['password']);
    }
    // verification of token in the formulary
    if (!isCSRFValid()) {
        $error['csrf'] = "La methode utilisée n'est pas autorisée";
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
                $_SESSION['attempts'] = 0;
                header("Location: ./profil.php");
                exit;
            }
            else{
                $error["connexion"] = "Identifiants Incorrects";
                $_SESSION['attempts']++;
                $_SESSION['last_attempt_time'] = time();
            }
        }
        else{
            $error["connexion"] = "Cet email n'est pas valide";
            $_SESSION['attempts']++;
            $_SESSION['last_attempt_time'] = time();
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
        <?php setCSRF(10); ?>
        <span class="erreur" style="color: red;"><?php echo $error["csrf"]??"" ?></span>
        <input type="submit" value="connexion" name="connexion">
        <span class="erreur" style="color: red;"><?php echo $error["connexion"]??"" ?></span>
    </form>
    <div>
        <p style="font-size: 1rem;">Je ne possède pas de compte</p>
        <a href="./inscription.php"><button>Inscription</button></a>
    </div>
</div>
<?php
require './template/footer.php'
?>