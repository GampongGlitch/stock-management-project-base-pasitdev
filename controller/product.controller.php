<?php
require_once '../model/config.model.php';
date_default_timezone_set("Asia/Bangkok");
if (!isset($_SESSION)) { session_start(); }

class Product_controller {
    public static function handler ($postData) {
        switch ($postData['type']) {
            case 'add': self::Add($postData);  break;
            case 'get': self::get(); break;
            case 'edit': self::Edit($postData); break;
            case 'delete': self::Delete($postData); break;
            case 'edit-qty': self::edit_qty($postData); break;
            case 'edit-price': self::edit_price($postData); break;
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
            foreach ($query as $v) {
                $html .= "
                    <tr>
                        <td>$v[product_code]</td>
                        <td>$v[product_name]</td>
                        <td>$v[category_name]</td>
                        <td>
                            <button 
                                type='button' 
                                data-id='$v[id]'
                                id='btn-edit-price'
                                data-price='$v[product_price]'
                                class='btn btn-danger text-white btn-sm'>
                                ".number_format($v['product_price'], 2)."
                                <i class='fa-solid fa-plus'></i>
                            </button>
                            
                        </td>
                        <td>
                            <button 
                                type='button' 
                                data-id='$v[id]'
                                id='btn-edit-qty'
                                data-qty='$v[product_qty]'
                                class='btn btn-warning text-dark btn-sm'>
                                ".number_format($v['product_qty'], 0)."
                                <i class='fa-solid fa-plus'></i>
                            </button>
                        </td>
                        <td>
                            <a
                                class='btn btn-success btn-sm'
                                id='btn-edit'
                                data-product_code='$v[product_code]'
                                data-product_name='$v[product_name]'
                                data-product_price='$v[product_price]'
                                data-product_qty='$v[product_qty]'
                                data-category_id='$v[category_id]'
                                data-category_name='$v[category_name]'
                                data-id='$v[id]'
                                data-type='edit'
                            >
                            <i class='fa-solid fa-pencil'></i>
                            </a>
                            <a
                                class='btn btn-danger btn-sm'
                                id='btn-delete'
                                data-id='$v[id]'
                                data-type='delete'
                            >
                            <i class='fa-solid fa-trash'></i>
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
    public static function Add($postData) {
        $sql = "
            INSERT INTO
                product
            SET
                product_code = '$postData[product_code]',
                product_name = '$postData[product_name]',
                category_id = '$postData[category_id]',
                product_price = '$postData[product_price]',
                product_qty = '$postData[product_qty]'
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
        $sql = "
            UPDATE
                product
            SET
                product_code = '$postData[product_code]',
                product_name = '$postData[product_name]',
                category_id = '$postData[category_id]',
                product_price = '$postData[product_price]',
                product_qty = '$postData[product_qty]'
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
    public static function Delete($postData) {
        $sql = "
            DELETE FROM
                product
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
    public static function edit_qty($postData) {
        $sql = "
            UPDATE
                product
            SET
                product_qty = '$postData[qty]'
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
    public static function edit_price($postData) {
        $sql = "
            UPDATE
                product
            SET
                product_price = '$postData[price]'
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
    Product_controller::handler($_POST);
} else {
    echo json_encode([
        "message" => "Not allow methods"
    ]);
}