<?php include './model/product.model.php'; ?>
<div class="content">
    <h1>จัดการสินค้า</h1>
    <hr>
    <form method="post" id="form-product">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <input type="text" name="product_code" placeholder="รหัสสินค้า" id="txt-product-code"
                    class="form-control">
            </div>
            <div class="col-md-5 col-sm-12">
                <button type="button" class="btn btn-info" id="btn-generate-code"><i class="fa-solid fa-wand-magic-sparkles"></i></button>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <input type="text" name="product_name" placeholder="ชื่อสินค้า" class="form-control">
            </div>
            <div class="col-md-2 col-sm-12">
                <select name="category_id" class="form-control">
                    <option value="" selected disabled>--เลือกหมวดหมู่--</option>
                    <?php
                    foreach (ProductModel::getCategory() as $v) {
                        echo "<option value='$v[id]'>$v[category_name]</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <input type="number" name="product_price" placeholder="ราคาขาย" class="form-control">
            </div>
            <div class="col-md-2 col-sm-12">
                <input type="number" name="product_qty" placeholder="จำนวนตั้งต้น" class="form-control" value="0">
            </div>
        </div>
        <p></p>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <button type="button" class="btn btn-primary" id="btn-add"><i class="fa-solid fa-plus"></i> เพิ่มสินค้า</button>
            </div>
        </div>
        <p></p>
    </form>
    <div class="table-responsive" style="overflow-x: hidden;">
        <table class="table table-bordered table-sm" id="dataTable" width="100%">
            <thead>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>หมวดหมู่</th>
                <th>ราคาขาย</th>
                <th>จำนวนคงเหลือ</th>
                <th>Action</th>
            </thead>
            <tbody id="fetch_data"></tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {

        function generateCode() {
            var unixTimestamp = Math.floor(Date.now() / 1000);
            $("#txt-product-code").val(unixTimestamp);
        }

        function fetchData() {
            $.post('controller/product.controller.php', {
                type: 'get'
            }, (response) => {
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

        generateCode();

        $("#btn-generate-code").click(function(e) {
            generateCode();
        });
        $("#btn-add").click(function(e) {
            e.preventDefault();

            // 1. ตรวจสอบค่าว่าง (Validation)
            // ดึงค่าจากฟอร์มโดยมองหา input ที่มีชื่อที่จำเป็น
            let productName = $("input[name='product_name']").val(); // ชื่อสินค้า

            if (productName.trim() === "") {
                alertify.error("กรุณากรอกข้อมูลให้ครบถ้วน");
                return; // หยุดการทำงานทันทีถ้าข้อมูลไม่ครบ
            }

            let params = $("#form-product").serializeArray();
            params.push({
                name: 'type',
                value: 'add'
            });
            $.post('controller/product.controller.php', params, (response) => {
                if (response.message == 'success') {
                    alertify.success("Saved");
                    $("#form-product").find('input, textarea').val('');
                    fetchData();
                    generateCode();
                } else {
                    alertify.error("บันทึกไม่สำเร็จหรือข้อมูลซ้ำกัน");
                }
            });
        });
        $(document).on('click', '#btn-delete', function(e) {
            let id = $(this).data('id');
            let type = $(this).data('type');
            alertify.confirm(
                "ลบสินค้า",
                "ยืนยันลบสินค้า",
                () => {
                    $.post('controller/product.controller.php', {
                        id: id,
                        type: type
                    }, (response) => {
                        if (response.message == 'success') {
                            fetchData();
                            alertify.success("Deleted");
                        } else {
                            alertify.error("Delete Failed");
                        }
                    });
                },
                () => {}
            );
        });
        $(document).on('click', '#btn-edit', function(e) {
            let params = {
                id: $(this).data('id'),
                product_code: $(this).data('product_code'),
                product_name: $(this).data('product_name'),
                product_price: $(this).data('product_price'),
                product_qty: $(this).data('product_qty'),
                category_id: $(this).data('category_id'),
                category_name: $(this).data('category_name'),
                type: 'edit'
            }
            let strhtml = `
                <form id="edit-form">
                    <input type="text" class="form-control" name="product_code" value="${params.product_code}">
                    <p></p>
                    <input type="text" class="form-control" name="product_name" value="${params.product_name}">
                    <p></p>
                    <input type="number" class="form-control" name="product_price" value="${params.product_price}">
                    <p></p>
                    <input type="number" class="form-control" name="product_qty" value="${params.product_qty}">
                    <p></p>
                    <select name="category_id" class="form-control">
                        <option value="${params.category_id}">${params.category_name}</option>
                        <?= ProductModel::getCategoryOption() ?>
                    </select>
                    <input type="hidden" name="id" value="${params.id}">
                    <input type="hidden" name="type" value="${params.type}">
                </form>
            `;
            alertify.confirm().destroy();
            alertify.confirm(
                "แก้ไข",
                strhtml,
                () => {
                    let formData = $("#edit-form").serializeArray();
                    $.post('controller/product.controller.php', formData, (response) => {
                        if (response.message == 'success') {
                            fetchData();
                            alertify.success("แก้ไขแล้ว");
                        } else {
                            alertify.error("Delete Failed");
                        }
                    })
                }, () => {}
            );
        });
        $(document).on('click', '#btn-edit-qty', function(e) {
            e.preventDefault();
            alertify.confirm().destroy();
            alertify.confirm(
                "แก้ไขจำนวน",
                `<form id='edit-form-qty'><input type='number' class='form-control' id='txt-edit-qty' value='${$(this).data('qty')}'></form>`,
                () => {
                    $.post("controller/product.controller.php", {
                        qty: $("#txt-edit-qty").val(),
                        type: 'edit-qty',
                        id: $(this).data('id')
                    }, (response) => {
                        if (response.message == 'success') {
                            fetchData();
                            alertify.success("Edited");
                        } else {
                            alertify.error("Delete Failed");
                        }
                    });
                },
                () => {}
            ).set('closable', false);
        });
        $(document).on('click', '#btn-edit-price', function(e) {
            e.preventDefault();
            alertify.confirm().destroy();
            alertify.confirm(
                "แก้ไขราคา",
                `<form id='edit-form-qty'><input type='number' class='form-control' id='txt-edit-price' value='${$(this).data('price')}'></form>`,
                () => {
                    $.post("controller/product.controller.php", {
                        price: $("#txt-edit-price").val(),
                        type: 'edit-price',
                        id: $(this).data('id')
                    }, (response) => {
                        if (response.message == 'success') {
                            fetchData();
                            alertify.success("Edited");
                        } else {
                            alertify.error("Delete Failed");
                        }
                    });
                },
                () => {}
            ).set('closable', false);
        });

    });
</script>