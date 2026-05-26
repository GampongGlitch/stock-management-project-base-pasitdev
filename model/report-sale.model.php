<?php 
require './model/config.model.php';
class ReportSaleModel {
    public static function _main($f, $t) {
        $sql = "SELECT
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
                DATE(t1.transaction_date) BETWEEN '$f' AND '$t'
                AND t1.is_type IN ('เบิก', 'ขาย')
        ";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            return $query;
        } else {
            return [[]];
        }
    }
}

?> 