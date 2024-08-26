<?php
// Fichier qui va permettre facilement l'appel à la bdd sous forme de fonction
function connexionPDO(): \PDO {
    // On appel le fichier config pour l'accées aux variables
    $config = require __DIR__."/../config/config.php";

    // Récupération des variables de la bdd
    $dsn = 
        "mysql:host=".$config["host"].
        ";port=".$config["port"].
        ";dbname=".$config["database"].
        ";charset=".$config["charset"];

    try{
        // On crée une instance de pdo en lui donnant les paramètres attendu,
        $pdo = new PDO($dsn, $config["user"], $config["password"], $config["options"]);
        return $pdo;
    }
    catch(PDOException $e){
        // Mise en place d'un message d'erreur en cas de problème
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

?>