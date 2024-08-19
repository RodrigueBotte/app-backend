<?php
function connexionPDO(): \PDO {
    $config = require __DIR__."/../config/config.php";

    $dsn = 
        "mysql:host=".$config["host"].
        ";port=".$config["port"].
        ";dbname=".$config["dbname"].
        ";charset=".$config["charset"];

    try{
        $pdo = new \PDO(
            $dsn,
            $config["user"],
            $config["password"],
            $config["options"]
        );
        return $pdo;
    }
    catch(\PDOException $e){
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

?>