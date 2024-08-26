<?php
// Création du fichier de configuration pour faciliter les changements des parametres facilement
return [
    "host" => $_ENV["DB_HOST"],
    "port" => "3306",
    "database" => $_ENV["DB_NAME"],
    "user" => $_ENV["DB_USER"],
    "password" => $_ENV["DB_PASSWORD"],
    "charset" => "utf8mb4",
    "options" => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
];
?>
