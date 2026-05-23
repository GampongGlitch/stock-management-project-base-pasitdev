<?php include './model/product.model.php'; ?>
<div class="content">
    <h1><i class="fa-solid fa-boxes-stacked"></i> รายงานสต๊อกคงเหลือ</h1>
    <hr>
    <div class="table-responsive" style="overflow-x: hidden;">
        <table class="table table-bordered table-sm" id="dataTable" width="100%">
            <thead>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>หมวดหมู่</th>
                <th>ราคาขาย</th>
                <th>จำนวนคงเหลือ</th>
            </thead>
            <tbody id="fetch_data"></tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {

        function fetchData() {
            $.post('controller/stock.controller.php', { type: 'get' }, (response) => {
                if (response.message == 'success') {
                    if (response.data.length > 0) {
                        $('#dataTable').DataTable().destroy();
                        $("#fetch_data").empty();
                        $("#fetch_data").html(response.data).promise().done(() => {
                            $("#dataTable").DataTable({
                                initComplete: () => {
                                    var searchInput = $(
                                        'div.dataTables_filter input');
                                    searchInput.attr('placeholder',
                                        'Search here...');
                                },
                                responsive: true,
                                bLengthChange: true,
                                ordering: false,
                                // bFilter: false,
                                bInfo: false,
                                pageLength: 10
                            });
                        });
                    } else {
                        $("#fetch_data").empty();
                    }
                }
            });
        }
        fetchData();
    });
</script>