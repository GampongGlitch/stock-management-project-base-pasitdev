<?php
    if ($_GET['status'] == 'sale') {
        include 'report/slip.php';
    } elseif ($_GET['status'] == 'import') {
        include 'report/slip-import.php';
    }
?>