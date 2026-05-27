<div class="content">
    <h1><i class="fa-solid fa-layer-group"></i> จัดการหมวดหมู่</h1>
    <hr>
    <form action="" method="post" id="form-category">
        <div class="row">
            <div class="col">
                <input type="text" name="category_name" placeholder="ชื่อหมวดหมู่" class="form-control">
            </div>
            <div class="col">
                <button type="button" class="btn btn-primary" id="btn-add"><i class="fa-solid fa-floppy-disk"></i> บันทึก</button>
            </div>
        </div>
        <input type="hidden" name="type" value="add-category">
    </form>
</div>
<hr>
<div class="table-responsive" style="overflow-x: hidden;">
    <table class="table table-bordered table-sm" id="dataTable" width="100%">
        <thead>
            <th>#</th>
            <th>ชื่อหมวดหมู่</th>
            <th>วันที่เพิ่มข้อมูล</th>
            <th>Action</th>
        </thead>
        <tbody id="fetch_data"></tbody>
    </table>
</div>
<script>
    $(document).ready(function() {

        function fetchData() {
            $.post('controller/category.controller.php', {
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

        $("#btn-add").click(function(e) {
            // ดึงค่าจาก input ที่ต้องการเช็ค
            let categoryName = $("input[name='category_name']").val(); // ปรับชื่อ name ให้ตรงกับฟอร์มคุณ

            // เช็คค่าว่าง (trim คือการตัดช่องว่างหน้า-หลังออก)
            if (categoryName.trim() === "") {
                alertify.error("กรุณากรอกชื่อหมวดหมู่ด้วยครับ");
                return; // หยุดการทำงานถ้าค่าว่าง
            }
            let params = $("#form-category").serializeArray();
            params.push({
                name: "type",
                value: "add-category"
            });
            $.post('controller/category.controller.php', params, (response) => {
                if (response.message == 'success') {
                    alertify.success("Saved");
                    $("#form-category").find('input, textarea').val('');
                    fetchData();
                } else {
                    alertify.error("บันทึกไม่สำเร็จหรือข้อมูลซ้ำกัน");
                }
            });
        });
        $(document).on('click', '#btn-delete', function(e) {
            let id = $(this).data('id');
            let type = $(this).data('type');
            $.post('controller/category.controller.php', {
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
        });
        $(document).on('click', '#btn-edit', function(e) {
            let params = {
                id: $(this).data('id'),
                category_name: $(this).data('category_name'),
                type: $(this).data('type')
            }
            let strhtml = `
                <form id="edit-form">
                    <input type="text" class="form-control" name="category_name" value="${params.category_name}">
                    <p></p>
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

                    // เช็คค่าว่างจากฟอร์มที่เพิ่งสร้างใน strhtml
                    let categoryName = $("#edit-form input[name='category_name']").val();
                    if (categoryName.trim() === "") {
                        alertify.error("ชื่อหมวดหมู่ห้ามเป็นค่าว่าง");
                        return;
                    }

                    $.post('controller/category.controller.php', formData, (response) => {
                        if (response.message == 'success') {
                            fetchData();
                            alertify.success("Edited");
                        } else {
                            alertify.error("Edit Failed");
                        }
                    })
                }, () => {}
            );
            $.post('controller/category.controller.php', {
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
        });

    });
</script>