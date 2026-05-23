<?php
    error_reporting(0);
    include './model/report-import.model.php'; 
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<div class="content">
    <h1><i class="fa-solid fa-boxes-stacked"></i> รายงานนำเข้าสินค้า</h1>
    <hr>
    <div class="table-responsive" style="overflow-x: hidden;">
        <table class="table table-bordered table-sm" id="dataTable" width="100%">
            <thead>
                <th>เลขที่บิล</th>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคานำเข้า</th>
                <th>จำนวนรับเข้า</th>
                <th>จำนวนทั้งสิ้น</th>
                <th>ส่วนลด</th>
                <th>วันที่รับเข้า</th>
                <th>ผู้ลงข้อมูล</th>
            </thead>
            <tbody>
                <?php foreach (ReportImportModel::_main($start_date, $end_date) as $v) { ?>
                    <tr>
                        <td><?=$v['bill_id']?></td>
                        <td><?=$v['product_code']?></td>
                        <td><?=$v['product_name']?></td>
                        <td><?= number_format($v['price'], 2) ?></td>
                        <td><?=$v['qty']?></td>
                        <td><?= number_format($v['total'], 2) ?></td>
                        <td><?= number_format($v['discount'], 2) ?></td>
                        <td><?=$v['datetime']?></td>
                        <td><?=$v['username']?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            dom: 'Bfrtip', // Add the Buttons container
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    title: 'Exported_Data', 
                    className: 'btn btn-primary'
                }
            ]
        });
        var strhtml = `
        <form id='report'>
            <label>จากวันที่</label>
            <input type='date' class='form-control' name='f' placeholder='จากวันที่'>
            <label>ถึงวันที่</label>
            <input type='date' class='form-control' name='t' placeholder='ถึงวันที่'>
        </form>
        `;
        <?php if (empty($_GET['start_date']) && empty($_GET['end_date'])) { ?>
            alertify.confirm(
                'รายงานนำเข้าสินค้า',
                strhtml,
                () => {
                    var strFrom = $("#report").serializeArray();
                    window.location.href = `index?r=report-import&start_date=${strFrom[0].value}&end_date=${strFrom[1].value}`;
                }, () => {}
            );
        <?php } ?>
    });
</script>