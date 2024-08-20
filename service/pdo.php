<?php
function connexionPDO(): \PDO {
    $config = require __DIR__."/../config/config.php";

    // $_ENV["DB_HOST"] = "mysql";
    // $_ENV["DB_NAME"] = "tournoi_mtg";
    // $_ENV["DB_USER"] = "root";
    // $_ENV["DB_PASSWORD"] = "root";


    $dsn = 
        "mysql:host=".$config["host"].
        ";port=".$config["port"].
        ";dbname=".$config["database"].
        ";charset=".$config["charset"];

    try{
        $pdo = new \PDO($dsn, $config["user"], $config["password"], $config["options"]);
        return $pdo;
    }
    catch(\PDOException $e){
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

?>