<?php
class ConnexionDB{
    private static $_dbname = "hiki";
    private static $_user = "root";
    private static $_pwd = "";
    private static $_host = "localhost";

    private static $_bdd = null;

    private function __construct(){
        $dbname = getenv('DB_NAME') ?: self::$_dbname;
        $user = getenv('DB_USER') ?: self::$_user;
        $pwd = getenv('DB_PASS') ?: self::$_pwd;
        $host = getenv('DB_HOST') ?: self::$_host;

        try{
            self::$_bdd = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $user, $pwd, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e){
            die('Database connection failed: '.$e->getMessage());
        }
    }

    public static function getInstance(): ?PDO{
        if (!self::$_bdd){
            new ConnexionDB();
        }
        return (self::$_bdd);
    }
}