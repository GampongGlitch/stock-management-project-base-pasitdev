<?php
require 'model/sale.model.php';
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<div class="content">
    <div class="d-flex justify-content-between">
        <div>
            <h1><i class="fa-brands fa-stack-overflow"></i> เบิกสินค้า</h1>
        </div>
        <style>
            .xxcc {
                width: 350px; 
                height: 70px; 
                font-size: 50px; 
                pointer-events: none; 
                color: yellow; 
                background-color: black; 
                font-weight: bold;
                text-align: right;
            }
        </style>
        <div>
            <p 
                id="txt-sum-total" 
                class="xxcc">
            </p>
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-between">
        <div></div>
        <div>
            <button type="button" class="btn btn-info" id="btn-last-bill">
                <i class="fa-solid fa-print"></i> พิมพ์บิลล่าสุด
            </button>
            <button type="button" class="btn btn-success" id="btn-new">
                <i class="fa-solid fa-rotate"></i> เปิดบิลใหม่
            </button>
            <button type="button" class="btn btn-danger" id="btn-end-sale">
                <i class="fa-solid fa-dollar-sign"></i> จบการขาย
            </button>
        </div>
    </div>
    <form method="post" id="form-sale">
        <div class="row">
            <div class="col">
                <label for="">สมาชิก</label>
                <input list="member_list" name="member_id" id="txt-member" class="form-control" placeholder="สมาชิก"
                    value="1">
                <datalist id="member_list">
                    <?= SaleModel::getMember() ?>
                </datalist>
            </div>
            <div class="col">
                <label for="">วันที่ทำรายการ</label>
                <input type="datetime-local" name="date" id="txt-date" class="form-control" placeholder="วันที่ทำรายการ"
                    value="<?= date('Y-m-d H:i:s') ?>">
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-12">
                <label for="">จำนวน</label>
                <input type="number" name="product_qty" id="txt-product-qty" class="form-control"
                    placeholder="จำนวนที่เบิก" value="1">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="">รหัสสินค้า</label>
                <input type="text" name="product_code" id="txt-product-code" class="form-control"
                    placeholder="รหัสสินค้า / ชื่อสินค้า">
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <label for="" hidden>ราคาขาย</label>
                <input type="number" name="product_price" id="txt-product-price" class="form-control"
                    placeholder="ราคาขาย" hidden>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <label for="" hidden>รวมทั้งสิ้น</label>
                <input type="number" name="total" id="txt-total" class="form-control" placeholder="รวมทั้งสิ้น" hidden>
            </div>
        </div>
    </form>
    <hr>
    <div class="table-responsive" style="overflow-x: scroll;">
        <table class="table table-bordered table-sm" id="dataTable" width="100%">
            <thead>
                <th>#</th>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>หมวดหมู่</th>
                <th>ราคาขาย</th>
                <th>จำนวน</th>
                <th>ส่วนลด</th>
                <th>รวมทั้งสิ้น</th>
                <th>Action</th>
            </thead>
            <tbody id="fetch_data"></tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    $(document).ready(function () {
        // Init Loaded
        function getFormattedDateTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }
        function fetchData() {
            $.post('controller/sale.controller.php', { type: 'get-temp' }, (response) => {
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
                        let formattedNumber = response.total.toLocaleString("en-US", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                        localStorage.setItem("money", response.total);
                        $("#txt-sum-total").text(formattedNumber);
                    } else {
                        $("#fetch_data").empty();
                        $("#txt-sum-total").text(0);
                    }
                }
            });
        }

        fetchData();
        function appendToTemp() {
            var params = {
                is_type: 'เบิก',
                member_id: $("#txt-member").val(),
                date: $("#txt-date").val(),
                product_code: $("#txt-product-code").val(),
                product_price: $("#txt-product-price").val(),
                product_qty: $("#txt-product-qty").val(),
                total: $("#txt-total").val(),
                user_id: <?= $_SESSION['userid'] ?? 0 ?>,
                discount: 0,
                type: 'add-temp'
            }
            $.post("controller/sale.controller.php", params, (response) => {
                if (response.message == 'success') {
                    fetchData();
                    alertify.success("เพิ่มสินค้าสำเร็จ");
                } else if (response.message == 'out') {
                    alertify.warning("ไม่สามารถเบิกได้ สินค้าหมดสต๊อก");
                } else {
                    alertify.error("เพิ่มสินค้าไม่สำเร็จ");
                }
            })

        }
        $(function () {

            const products = <?= SaleModel::getItem() ?>;

            $("#txt-product-code").autocomplete({
                source: products.map(item => ({
                    label: `${item.product_code} - ${item.product_name}`,
                    value: item.product_code,
                    product_code: item.product_code
                })),
                select: function (event, ui) {
                    $(this).val(ui.item.product_code);
                    $.post("controller/sale.controller.php", {
                        product_code: ui.item.product_code,
                        type: 'get-detail'
                    }, (response) => {
                        if (response.data.length > 0) {
                            $.each(response.data, function (k, v) {
                                $("#txt-product-price").val(parseFloat(v.product_price));
                                const total = v.product_price * parseInt($("#txt-product-qty").val());
                                $("#txt-total").val(total);
                            });
                            appendToTemp();
                            $("#txt-product-code").val(null);
                            $("#txt-product-price").val(null);
                            $("#txt-product-qty").val(1);
                            $("#txt-total").val(null);
                        } else {
                            alertify.error("ไม่พบสินค้าในระบบ");
                            $("#txt-product-code").val(null);
                        }
                    });
                }
            });
            $("#txt-product-code").keypress(function (e) {
                if (e.keyCode === 13) {
                    let code = $(this).val();
                    $.post("controller/sale.controller.php", {
                        product_code: code,
                        type: 'get-detail'
                    }, (response) => {
                        if (response.data.length > 0) {
                            $.each(response.data, function (k, v) {
                                $("#txt-product-price").val(parseFloat(v.product_price));
                                const total = v.product_price * parseInt($("#txt-product-qty").val());
                                $("#txt-total").val(total);
                            });
                            appendToTemp();
                            $("#txt-product-code").val(null);
                            $("#txt-product-price").val(null);
                            $("#txt-product-qty").val(1);
                            $("#txt-total").val(null);
                        } else {
                            alertify.error("ไม่พบสินค้าในระบบ");
                            $("#txt-product-code").val(null);
                        }
                    });
                }
            });
            $("#txt-product-qty").keyup(function () {
                const price = $("#txt-product-price").val();
                const qty = $("#txt-product-qty").val();
                const total = price * qty
                $("#txt-total").val(total);
            });
        });
        $(document).on('change', '#btn-qty-edit', function (e) {
            e.preventDefault();
            let params = {
                product_code: $(this).data('id'),
                product_qty: $(this).val() || 0,
                type: 'edit-qty'
            }
            $.post('controller/sale.controller.php', params, (response) => {
                if (response.message == 'success') {
                    fetchData();
                    alertify.success("แก้ไขจำนวนแล้ว");
                } else {
                    alertify.error("Error to fetch");
                }
            })

        });
        $(document).on('change', '#btn-discount-edit', function (e) {
            e.preventDefault();
            let params = {
                product_code: $(this).data('id'),
                discount: $(this).val() || 0,
                type: 'edit-discount'
            }
            $.post('controller/sale.controller.php', params, (response) => {
                if (response.message == 'success') {
                    fetchData();
                    alertify.success("แก้ไขส่วนลด");
                } else {
                    alertify.error("Error to fetch");
                }
            })

        });
        $(document).on('click', '#btn-delete', function (e) {
            e.preventDefault();
            let product_code = $(this).data('product_code');
            $.post('controller/sale.controller.php', { type: 'remove-temp-one', product_code: product_code }, (response) => {
                if (response.message == 'success') {
                    fetchData();
                    alertify.success("ลบข้อมูลแล้ว");
                } else {
                    alertify.error("Error to fetch");
                }
            })
        });
        $("#btn-new").click(function(e) {
            e.preventDefault();
            $.post('controller/sale.controller.php', {type: 'remove-temp'}, (response) => {
                if (response.message == 'success') {
                    $("#txt-date").val(getFormattedDateTime());
                    $("#txt-product-code").val(null);
                    $("#txt-product-price").val(null);
                    $("#txt-product-qty").val(1);
                    $("#txt-total").val(null);
                    $("#txt-sum-total").text(0);
                    fetchData();
                    alertify.success("ล้างรายการแล้ว");
                }
            });
        });
        $("#btn-end-sale").click(function(e) {
            e.preventDefault();
            var unixTimestamp = Math.floor(Date.now() / 1000);
            var strHtml = `
                <a href="#" class="btn btn-success btn-icon-split" id="btn-get-money">
                    <span class="icon text-white-50">
                        <i class="fas fa-check"></i>
                    </span>
                    <span class="text">รับมาพอดี</span>
                </a>
                <hr>
                <h2>รับเงิน</h2>
                <input 
                    type="text" 
                    class="form-control" 
                    id="txt-customer-money" 
                    placeholder="รับเงินจากลูกค้า"
                    style="text-align: right; height: 80px; font-size: 40px; color: yellow; background-color: black; font-weight: bold;"
                />
                <h2>เงินทอน</h2>
                <input 
                    type="text" 
                    class="form-control" 
                    id="txt-return-money" 
                    placeholder="รับเงินจากลูกค้า"
                    style="text-align: right; height: 80px; font-size: 40px; color: red; background-color: black; font-weight: bold;"
                />
            `;
            alertify.confirm().destroy();
            alertify.confirm(
                "จบการขาย",
                strHtml,
                () => {
                    var params = {
                        type: 'end-sale',
                        bill_id: Math.floor(Date.now() / 1000),
                        money: $("#txt-customer-money").val(),
                        return: $("#txt-return-money").val()
                    }
                    localStorage.setItem("bill_id", params.bill_id);
                    $.post('controller/sale.controller.php', params, (response) => {
                        if (response.message == 'success') {
                            $("#txt-date").val(getFormattedDateTime());
                            $("#txt-product-code").val(null);
                            $("#txt-product-price").val(null);
                            $("#txt-product-qty").val(1);
                            $("#txt-total").val(null);
                            $("#txt-sum-total").text(null);
                            fetchData();
                            if (localStorage.getItem("print") === 'true') {
                                window.open(`print.php?id=${params.bill_id}&status=sale`, "popupWindow", "width=290, height=589, scrollbars=yes");
                            }
                            alertify.success("จบการขายแล้ว");
                        } else {
                            alertify.error("ไม่สามารถจบการขายได้");
                        }
                    });
                },
                () => {}
            );
        });
        
        $(document).on('click', '#btn-get-money', function(e) {
            let sum = $("#txt-sum-total").text();
            let floatNumber = parseFloat(sum.replace(/,/g, ""));
            $("#txt-customer-money").val(floatNumber);
            $("#txt-return-money").val(0);
        });
        $(document).on('keyup', '#txt-customer-money', function(e) {
            const total = localStorage.getItem("money");
            const receipt = $("#txt-customer-money").val()
            const summery = receipt - total
            $("#txt-return-money").val(summery || 0);
            
        });
        $("#btn-last-bill").click(function(e) {
            e.preventDefault();
            const bill = localStorage.getItem("bill_id");
            window.open(`print.php?id=${bill}&status=sale`, "popupWindow", "width=290, height=589, scrollbars=yes");
        });
        // Init Loaded 
    });
</script>