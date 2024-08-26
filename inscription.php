<?php
// we require some folder
require "./service/shouldBeLogged.php";
require "./service/csrf.php";
// verification if user is not connect
shouldBeLogged(false, "./connexion.php");

$username = $email = $password = "";
$error = [];
$regex = "/^(?=.*[!?@#$%^&*+-])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{6,}$/";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscription'])) {

    // call of folder pdo to do the connexion with the bdd
    require "./service/pdo.php";

    $pdo = connexionPDO();

    // treatment of pseudo 
    if (empty($_POST['pseudo'])) {
        $error['pseudo'] = "Veuillez saisir un pseudo!";
    } else {
        // Cleandata transform $username with htmlspecialchars against XSS
        $username = cleanData($_POST['pseudo']);
        if (!preg_match("/^[a-zA-Z0-9@_-]{3,20}$/", $username)) {
            $error['pseudo'] = "Veuillez saisir un pseudo valide!";
        }
    }

    // Treatment of email
    if (empty($_POST['email'])) {
        $error['email'] = "Veuillez saisir un email!";
    } else {
        $email = cleanData($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "Veuillez saisir un email valide!";
        }
        // verification if $email is unique
        $sql = $pdo->prepare("SELECT * FROM users WHERE email=:em");
        $sql->execute(["em" => $email]);
        $resultat = $sql->fetch();
        if ($resultat) {
            $error['email'] = "Cet email est déjà enregistré!";
        }
    }

    // Treatment of password
    if (empty($_POST['password'])) {
        $error['password'] = "Veuillez entrer un mot de passe!";
    } else {
        $password = trim($_POST['password']);
        // verification if $password respect $regex 
        if (!preg_match($regex, $password)) {
            $error['password'] = "Veuillez saisir un email plus complexe";
        }
        // the password is hash to be illegible in the bdd
        $password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Treatment of passwordBis 
    if (empty($_POST['passwordBis'])) {
        $error['passwordBis'] = "Veuillez saisir à nouveau votre mot de passe!";
    } elseif ($_POST['password'] != $_POST['passwordBis']) {
        $error['passwordBis'] = "Veuillez entrer le même mot de passe";
    }
    // verification of token in the formulary
    if (!isCSRFValid()) {
        $error = "La methode utilisée n'est pas autorisée";
    }

    // if we don't have any $error, we send identifiant in the bdd
    if (empty($error)) {
        // we use prepare() and execute() for a better securisation against SQL injection
        $sql = $pdo->prepare("INSERT INTO users(username, email, password) VALUES (?, ?, ?)");
        $sql->execute([$username, $email, $password]);
        header('Location: ./connexion.php');
        exit;
    }
}

$title = "Création d'un compte";
require './template/header.php'
?>
<div class="inscription">
    <h2>Inscription</h2>
    <!-- Mise en place du formulaire d'inscription -->
    <form action="" method="post">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" required>
        <!-- affichage du message d'erreur en cas de problème -->
        <span class="erreur" style="color: red;"><?php echo $error["pseudo"] ?? "" ?></span>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <span class="erreur" style="color: red;"><?php echo $error["email"] ?? "" ?></span>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" required>
        <!-- Mise en place d'un token avec un timer de 10 min dans un input hidden -->
        <?php setCSRF(10); ?>
        <span class="erreur" style="color: red;"><?php echo $error["password"] ?? "" ?></span>
        <label for="passwordBis">Confirmation du mot de passe</label>
        <input type="password" name="passwordBis" id="passwordBis" required>
        <span class="erreur" style="color: red;"><?php echo $error["passwordBis"] ?? "" ?></span>
        <input type="submit" value="incription" name="inscription">
    </form>
</div>

<?php
require './template/footer.php'
?>