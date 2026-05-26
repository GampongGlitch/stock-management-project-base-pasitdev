<?php
require_once '../model/config.model.php';
date_default_timezone_set("Asia/Bangkok");
if (!isset($_SESSION)) {
    session_start();
}

class Import_controller
{
    public static function handler($postData)
    {
        switch ($postData['type']) {
            case 'get-detail':
                self::getDetail($postData);
                break;
            case 'add-temp':
                self::addTemp($postData);
                break;
            case 'get-temp':
                self::getTemp();
                break;
            case 'get-sum':
                self::getSum();
                break;
            case 'edit-qty':
                self::editQty($postData);
                break;
            case 'edit-discount':
                self::editDiscount($postData);
                break;
            case 'end-sale':
                self::endSale($postData);
                break;
            case 'remove-temp':
                self::removeTemp();
                break;
            case 'remove-temp-one':
                self::removeTempOne($postData);
                break;
        }
    }
    public static function getDetail($postData)
    {
        $sql = "SELECT
                t1.id AS id,
                t1.product_code AS product_code,
                t1.product_name AS product_name,
                t1.product_price AS product_price,
                t1.product_qty AS product_qty,
                t2.category_name
            FROM
                product t1
            LEFT JOIN
                category t2 ON t1.category_id = t2.id
            WHERE
                t1.product_code = '$postData[product_code]'
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            $data = [];
            foreach ($query as $v) {
                $data[] = $v;
            }
            echo json_encode([
                'message' => 'success',
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'message' => 'success',
                'data' => []
            ]);
        }
    }
    public static function findExists($product_code)
    {
        $sql = "SELECT product_code FROM transaction_temp WHERE product_code = '$product_code'";
        $query = Backend::MySQL()->query($sql);
        if ($query->num_rows == 0) {
            return true;
        } else {
            return false;
        }
    }
    public static function getTemp()
    {
        $user_id = $_SESSION['userid'];
        $sql = "SELECT
                t1.product_code AS product_code,
                t2.product_name AS product_name,
                t3.category_name AS category_name,
                t1.price AS product_price,
                t1.qty AS product_qty,
                t1.discount AS discount,
                t1.total AS total
            FROM
                transaction_temp t1
            LEFT JOIN
                product t2 ON t1.product_code = t2.product_code
            LEFT JOIN
                category t3 ON t2.category_id = t3.id
            WHERE
                t1.user_id = $user_id
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            $html = "";
            $i = 0;
            $sum = 0;
            foreach ($query as $v) {
                $i++;
                $sum = $sum + $v['total'];
                $html .= "
                    <tr>
                        <td>$i</td>
                        <td>$v[product_code]</td>
                        <td>$v[product_name]</td>
                        <td>$v[category_name]</td>
                        <td width='15%'>
                            <input type='number' class='form-control' value='$v[product_price]' id='btn-price-edit' data-id='$v[product_price]'>
                        </td>
                        <td width='8%'>
                            <input type='text' class='form-control' value='$v[product_qty]' id='btn-qty-edit' data-id='$v[product_code]'>
                        </td>
                        <td width='20%'>
                            <input type='text' class='form-control' value='$v[discount]' id='btn-discount-edit' data-id='$v[product_code]'>
                        </td>
                        <td><b>" . number_format($v['total'], 2) . "</b></td>
                        <td>
                            <a
                                class='btn btn-danger'
                                id='btn-delete'
                                data-product_code='$v[product_code]'
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
                'data' => $html,
                'total' => $sum
            ]);
        } else {
            echo json_encode([
                'message' => 'error',
                'data' => ''
            ]);
        }
    }
    public static function addTemp($postData)
    {
        if (self::findExists($postData['product_code'])) {
            $sql = "INSERT INTO
                    transaction_temp
                SET
                    is_type = 'นำเข้า',
                    product_code = '$postData[product_code]',
                    member_id = 0,
                    price = '$postData[product_price]',
                    qty = '$postData[product_qty]',
                    total = '$postData[total]',
                    discount = 0,
                    transaction_date = '$postData[date]',
                    user_id = '$postData[user_id]'
            ";
            $query = Backend::MySQL()->query($sql);
            if ($query) {
                echo json_encode([
                    'message' => 'success'
                ]);
            } else {
                echo json_encode([
                    'message' => 'success'
                ]);
            }
        } else {
            Backend::MySQL()->query("UPDATE transaction_temp SET qty = qty + 1 WHERE product_code = '$postData[product_code]'");
            Backend::MySQL()->query("UPDATE transaction_temp SET total = (price * qty) - discount WHERE product_code = '$postData[product_code]'");
            echo json_encode([
                'message' => 'success'
            ]);
        }
    }
    public static function getSum()
    {
        $user_id = $_SESSION['userid'];
        $sql = "SELECT
                SUM(total) AS total
            FROM
                transaction_temp
            WHERE
                user_id = $user_id
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            $total = $query->fetch_assoc();
            echo json_encode([
                'message' => 'success',
                'total' => $total['total']
            ]);
        } else {
            echo json_encode([
                'message' => 'error',
                'total' => 0
            ]);
        }
    }
    public static function editQty($postData)
    {
        $user_id = $_SESSION['userid'];
        $sql = "UPDATE
                transaction_temp
            SET
                qty = '$postData[product_qty]'
            WHERE
                product_code = '$postData[product_code]'
                AND user_id = '$user_id'
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            Backend::MySQL()->query("UPDATE transaction_temp SET total = price * qty WHERE product_code = '$postData[product_code]'");
            echo json_encode([
                'message' => 'success',
            ]);
        } else {
            echo json_encode([
                'message' => 'error'
            ]);
        }
    }
    public static function editDiscount($postData)
    {
        $user_id = $_SESSION['userid'];
        $sql = "UPDATE
                transaction_temp
            SET
                discount = '$postData[discount]'
            WHERE
                product_code = '$postData[product_code]'
                AND user_id = '$user_id'
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            Backend::MySQL()->query("UPDATE transaction_temp SET total = (price * qty) - discount WHERE product_code = '$postData[product_code]'");
            echo json_encode([
                'message' => 'success',
            ]);
        } else {
            echo json_encode([
                'message' => 'error',
            ]);
        }
    }
    public static function removeTemp()
    {
        $user_id = $_SESSION['userid'];
        $sql = "DELETE FROM
                transaction_temp
            WHERE
                user_id = $user_id
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            echo json_encode([
                'message' => 'success',
            ]);
        } else {
            echo json_encode([
                'message' => 'error',
            ]);
        }
    }
    public static function removeTempOne($postData)
    {
        $user_id = $_SESSION['userid'];
        $sql = "DELETE FROM
                transaction_temp
            WHERE
                product_code = '$postData[product_code]'
                AND user_id = $user_id
                AND is_type = 'นำเข้า'
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            echo json_encode([
                'message' => 'success',
            ]);
        } else {
            echo json_encode([
                'message' => 'error',
            ]);
        }
    }
    public static function endSale($postData)
    {
        $user_id = $_SESSION['userid'];
        try {
            $sql_temp = "SELECT * FROM `transaction_temp` WHERE user_id = $user_id";
            $query_temp = Backend::MySQL()->query($sql_temp);
            foreach ($query_temp as $row) {
                $sql_insert = "INSERT INTO
                        `transaction`
                    SET
                        bill_id = '$postData[bill_id]',
                        is_type = '$row[is_type]',
                        product_code = '$row[product_code]',
                        member_id = 0,
                        price = '$row[price]',
                        qty = '$row[qty]',
                        total = '$row[total]',
                        discount = '$row[discount]',
                        transaction_date = '$row[transaction_date]',
                        user_id = '$row[user_id]'
                ";
                Backend::MySQL()->query($sql_insert);
                $str_update_qty = "UPDATE product SET product_qty = product_qty + $row[qty] WHERE product_code = '$row[product_code]'";
                Backend::MySQL()->query($str_update_qty);
            }
            Backend::MySQL()->query("DELETE FROM transaction_temp");
            echo json_encode([
                'message' => 'success',
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'message' => $e,
            ]);
        }
    }
}

// Main Controller 
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Import_controller::handler($_POST);
} else {
    echo json_encode([
        "message" => "Not allow methods"
    ]);
}
