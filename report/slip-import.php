<?php
    // header("Content-type:application/pdf");
    include './model/import.model.php';
    date_default_timezone_set("Asia/Bangkok");
    $id = $_GET['id'] ?? 0;
    if ($id == 0) {
        echo "<h1>ไม่มีเลขที่บิล</h1>";
        exit();
    }
    $result = ImportModel::getSlip($id);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Noto Sans Thai", serif;
            font-style: normal;
            font-weight: 400;
        }
    </style>
    <title>ใบนำเข้าสินค้า</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-size: 12px;
            width: 58mm; /* กำหนดความกว้าง */
            font-family: Arial, sans-serif; /* เลือกฟอนต์ที่พิมพ์ง่าย */
        }
        @media print {
            body {
                width: 58mm;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-2" id="container">
        <div align="center">
            <h3>ใบนำเข้าสินค้า</h3>
            <hr>
        </div>
        <p>
            เลขที่บิล: <?=$result[1]['bill_id']?><br>
            วันที่ออกบิล: <?=$result[1]['datetime']?><br>
            ผู้ขาย: <?=$result[1]['username']?>
        </p>
        <hr>
        <table class="table table-sm">
            <tr>
                <th>สินค้า</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <?php foreach ($result[0] as $v) { ?>
                <tr>
                    <td>
                        <?=$v['product_name']?><br>
                        <i>ส่วนลด <?= number_format($v['discount'], 2) ?></i>
                    </td>
                    <td align="right">(<?=$v['qty']?>)</td>
                    <td align="right"><?= number_format($v['price'], 2) ?></td>
                    <td align="right"><?= number_format($v['total'], 2) ?></td>
                </tr>
            <?php } ?>
        </table>
        <hr>
        <h6>ยอดสุทธิ <u><?= number_format($result[2]['total'], 2) ?></u></h6>
    </div>
</body>
</html>