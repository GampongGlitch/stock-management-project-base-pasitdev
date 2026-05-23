<?php 
require './model/config.model.php';
class StockModel {

    public static function getCategoryOption() {
        $sql = "SELECT * FROM category";
        $query = Backend::MySQL()->query($sql);
        if ($query) {
            $html = "";
            foreach ($query as $v) {
                $html .= "
                    <option value='$v[id]'>$v[category_name]</option>
                ";
            }
            return $html;
        } else {
            return [];
        }
    }
}

?> 