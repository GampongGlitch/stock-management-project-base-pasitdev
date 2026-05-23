<?php
require_once '../model/config.model.php';
date_default_timezone_set("Asia/Bangkok");
if (!isset($_SESSION)) { session_start(); }

class Stock_controller {
    public static function handler ($postData) {
        switch ($postData['type']) {
            case 'get': self::get(); break;
        }
    }
    public static function get() {
        $sql = "
            SELECT
                t1.id AS id,
                t1.product_code AS product_code,
                t1.product_name AS product_name,
                t1.product_price AS product_price,
                t1.product_qty AS product_qty,
                t1.category_id AS category_id,
                t2.category_name AS category_name
            FROM
                product t1
            LEFT JOIN
                category t2 ON t1.category_id = t2.id
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            $html = "";
            $color = "";
            foreach ($query as $v) {
                if ($v['product_qty'] === 0) {
                    $color = "text-danger";
                } else {
                    $color = "text-gray";
                }
                $html .= "
                    <tr>
                        <td>$v[product_code]</td>
                        <td>$v[product_name]</td>
                        <td>$v[category_name]</td>
                        <td>".number_format($v['product_price'], 2)."</td>
                        <td class='$color'>".number_format($v['product_qty'], 0)."</td>
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
}

// Main Controller 
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Stock_controller::handler($_POST);
} else {
    echo json_encode([
        "message" => "Not allow methods"
    ]);
}