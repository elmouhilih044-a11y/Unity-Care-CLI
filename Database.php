<?php
// Database.php

class Database
{
    private static $host = "localhost";
    private static $user = "root";
    private static $password = "";
    private static $db_name = "unity_care_backend";
    private static $conn;

    public static function getConnection()
    {
        if (self::$conn == null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db_name,
                    self::$user,
                    self::$password
                );
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                die("Connection Error: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}