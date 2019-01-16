<?php

namespace connection;
use PDO;

Class ConnectionFactory {

    static $pdo = null;

    static function makeConnection(array $conf) {
        try {
    $user = $conf["username"];
    $pass = $conf["password"];
    self::$pdo = new PDO('mysql:host=localhost; dbname=orm', $user, $pass,array(
    PDO::ATTR_PERSISTENT => true,PDO::ATTR_EMULATE_PREPARES=> false,PDO::ATTR_STRINGIFY_FETCHES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
 ));
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
return (self::$pdo);
    }

    static function getConnection() {
        if (isset(self::$pdo)) {
            return self::$pdo;

        }
        else {
            return "Connexion echouée";
        }
    }
}
