<?php

class Database
{

    private static $pdo = null;

    public static function getConnection()
    {

        if (self::$pdo === null) {

            $host = Config::get('database', 'host');
            $dbname = Config::get('database', 'dbname');
            $port = Config::get('database', 'port');
            $user = Config::get('database', 'user');
            $password = Config::get('database', 'password');
            $charset = Config::get('database', 'charset');

            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

            try {
                self::$pdo = new PDO($dsn, $user, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                error_log("Erreur de connexion BDD : " . $e->getMessage());
                throw new RuntimeException("Le service est temporairement indisponible. Veuillez réessayer plus tard.");
            }
        }

        return self::$pdo;
    }
}
