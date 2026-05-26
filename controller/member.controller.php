<?php
require_once '../model/config.model.php';
date_default_timezone_set("Asia/Bangkok");
if (!isset($_SESSION)) { session_start(); }

class Member_controller {
    public static function handler ($postData) {
        switch ($postData['type']) {
            case 'get': self::get();  break;
            case 'add-member': self::addMember($postData);  break;
            case 'edit': self::Edit($postData);  break;
            case 'delete': self::Delete($postData);  break;
        }
    }
    public static function get() {
        $sql = "SELECT * FROM member";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            $html = "";
            foreach ($query as $v) {
                $html .= "
                    <tr>
                        <td>$v[id]</td>
                        <td>$v[member_name]</td>
                        <td>$v[phone]</td>
                        <td>". date('d/m/Y H:i:s', strtotime($v['update_at'])) ."</td>
                        <td>
                            <a
                                class='btn btn-success'
                                id='btn-edit'
                                data-member_name='$v[member_name]'
                                data-phone='$v[phone]'
                                data-id='$v[id]'
                                data-type='edit'
                            >
                            <i class='fa-solid fa-pen'></i>
                            </a>
                            <a
                                class='btn btn-danger'
                                id='btn-delete'
                                data-id='$v[id]'
                                data-type='delete'
                            >
                            <i class='fa-regular fa-trash-can'></i>
                            </a>
                        </td>
                    </tr>
                ";
            }
            echo json_encode([
                'message' => 'success',
                'data' => $html
            ]);
        } else {
            echo json_encode([
                'message' => 'error',
                'data' => ''
            ]);
        }
    }
    public static function addMember($postData) {
        $sql = "INSERT INTO
                member
            SET
                member_name = '$postData[member_name]',
                phone = '$postData[phone]'
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
    public static function Edit($postData) {
        $dateNow = date("Y-m-d H:i:s");
        $sql = "UPDATE
                member
            SET
                member_name = '$postData[member_name]',
                phone = '$postData[phone]',
                update_at = '$dateNow'
            WHERE
                id = $postData[id]
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
    public static function DElete($postData) {
        $sql = "DELETE FROM
                member
            WHERE
                id = $postData[id]
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
    Member_controller::handler($_POST);
} else {
    echo json_encode([
        "message" => "Not allow methods"
    ]);
}