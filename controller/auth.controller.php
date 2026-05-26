<?php
require_once '../model/config.model.php';
date_default_timezone_set("Asia/Bangkok");
if (!isset($_SESSION)) { session_start(); }

class Auth_controller {
    public static function handler ($postData) {
        switch ($postData['type']) {
            case 'login': self::logIn($postData);  break;
            case 'reset-password': self::resetPassword($postData);  break;
            case 'change-password': self::changePassword($postData);  break;
            case 'register': self::Register($postData);  break;
        }
    }
    public static function logIn($postData) {
        $password = base64_encode($postData['password']);
        $sql = "SELECT
                *
            FROM
                users
            WHERE
                username = '$postData[username]'
                AND `password` = '$password'
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $_SESSION['userid'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['product_id'] = 0;
            $_SESSION['member_id'] = 0;
            echo json_encode([
                'message' => 'success'
            ]);
        } else {
            echo json_encode([
                'message' => 'error' 
            ]); 
        }
    }
    public static function resetPassword ($postData) {
        $password = base64_encode($postData['password']);
        $sql = "UPDATE users SET `password` = '$password' WHERE username = '$postData[username]'";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            echo json_encode([
                'message' => 'success'
            ]);
        } else {
            echo json_encode([
                'message' => 'error'
            ]);
        }
    }
    public static function changePassword ($postData) {
        $password = base64_encode($postData['pass2']);
        $sql = "UPDATE users SET `password` = '$password' WHERE id = '$postData[id]'";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            echo json_encode([
                'message' => 'success'
            ]);
        } else {
            echo json_encode([
                'message' => 'error'
            ]);
        }
    }
    public static function Register ($postData) {
        $password = base64_encode($postData['password']);
        $sql = "INSERT INTO
                users
            SET username = '$postData[username]',
                password = '$password'
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            echo json_encode([
                'message' => 'success'
            ]);
        } else {
            echo json_encode([
                'message' => 'error'
            ]);
        }
    }
}

// Main Controller 
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Auth_controller::handler($_POST);
} else {
    echo json_encode([
        "message" => "Not allow methods"
    ]);
}