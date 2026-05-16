<?php
class ConnexionDB{
    private static $_dbname="hiki";
    private static $_user="root";
    private static $_pwd="golden ratio 1.618";
    private static $_host="localhost";

    private static $_bdd=null;

    private function __construct(){
        try{
            self::$_bdd=new PDO("mysql:host=".self::$_host.";dbname=".self::$_dbname.";charset=utf8",self::$_user,self::$_pwd,
                    [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
            );
        }catch (PDOExeption $e){
            die('Database connexion failed : '.$e->getMessage());
        }
    }

    public static function getInstance(): ?PDO{
        if (!self::$_bdd){
            new ConnexionDB();
        }
        return (self::$_bdd);
    }
}