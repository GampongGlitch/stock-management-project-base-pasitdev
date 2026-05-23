<?php 
require './model/config.model.php';
date_default_timezone_set("Asia/Bangkok");
class SaleModel {
    public static function getMember() {
        $sql = "SELECT * FROM member";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            $html = '';
            foreach ($query as $v) {
                if ($v['member_name'] == 'ทั่วไป') {
                    $html .= "
                        <option value='$v[id]' selected>$v[member_name]</option>
                    ";
                } else {
                    $html .= "
                            <option value='$v[id]'>$v[member_name]</option>
                    ";
                }
            }
            return $html;
        } else {
            return [];
        }
    }
    public static function getItem() {
        $sql = "SELECT product_code, product_name FROM product";
        $query = Backend::MySQL()->query($sql);
        
        if ($query->num_rows) {
            $data = [];
            foreach ($query as $row) {
                $data[] = [
                    "product_code" => $row['product_code'],
                    "product_name" => $row['product_name']
                ];
            }
            return json_encode($data, true);
        }
    }
    public static function getSlip($id) {
        $sql = "
            SELECT
                t1.bill_id AS bill_id,
                t1.product_code AS product_code,
                t2.product_name AS product_name,
                t3.member_name AS member_name,
                t1.price AS price,
                t1.qty AS qty,
                t1.total AS total,
                t1.discount AS discount,
                t1.transaction_date AS `datetime`,
                t4.username AS username
            FROM 
                `transaction` t1
            LEFT JOIN
                product t2 ON t1.product_code = t2.product_code
            LEFT JOIN
                member t3 ON t1.member_id = t3.id
            LEFT JOIN
                users t4 ON t1.user_id = t4.id
            WHERE
                t1.bill_id = '$id' AND t1.is_type IN ('ขาย', 'เบิก')
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            $row = $query->fetch_assoc();
            $count = Backend::MySQL()->query("SELECT SUM(total) AS total FROM `transaction` WHERE bill_id = '$id'");
            $count = $count->fetch_assoc();
            return [$query, $row, $count];
        } else {
            return [[], [], 0];
        }
    }

}