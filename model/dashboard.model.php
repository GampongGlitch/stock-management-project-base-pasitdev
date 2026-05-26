<?php 
require './model/config.model.php';
date_default_timezone_set("Asia/Bangkok");
class DashboardModel {
    public static function Product() {
        $sql = "SELECT id FROM product";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            return $query->num_rows;
        }
    }
    public static function Member() {
        $sql = "SELECT id FROM member";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            return $query->num_rows;
        }
    }
    public static function Category() {
        $sql = "SELECT id FROM category";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            return $query->num_rows;
        }
    }
    public static function ProductOut() {
        $sql = "SELECT id FROM product WHERE product_qty = 0";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            return $query->num_rows;
        }
    }
    public static function SumByMonth() {
        $sql = "SELECT 
                CASE MONTH(transaction_date)
                    WHEN 1 THEN 'มกราคม'
                    WHEN 2 THEN 'กุมภาพันธ์'
                    WHEN 3 THEN 'มีนาคม'
                    WHEN 4 THEN 'เมษายน'
                    WHEN 5 THEN 'พฤษภาคม'
                    WHEN 6 THEN 'มิถุนายน'
                    WHEN 7 THEN 'กรกฎาคม'
                    WHEN 8 THEN 'สิงหาคม'
                    WHEN 9 THEN 'กันยายน'
                    WHEN 10 THEN 'ตุลาคม'
                    WHEN 11 THEN 'พฤศจิกายน'
                    WHEN 12 THEN 'ธันวาคม'
                END AS month,
                SUM(total) AS total
            FROM 
            `transaction`
            GROUP BY
                month
        ";
        $result = Backend::MySQL()->query($sql);
        $count = $result->num_rows;
        $status_work = array();
        $total = array();
        while($rs = mysqli_fetch_array($result)){
            $status_work[] = "\"".$rs['month']."\"";
            $total[] = $rs['total'];
        }
        $status_work = implode(",", $status_work);
        $total = implode(",", $total);
        return [$status_work, $total];
    }

}