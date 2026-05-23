<?php

class Backend {
    private static $conn = null;

    public static function MySQL(): mysqli|Throwable {
        try {
            $db = new mysqli(
                "localhost",
                "root",
                "",
                "stock"
            );
            $db->set_charset("utf8");
            self::$conn = $db;
            return self::$conn;
        } catch (\Throwable $th) {
            return $th;
        }
    }
} 

?>