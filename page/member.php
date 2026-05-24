<div class="content">
    <h1><i class="fa-solid fa-person-circle-plus"></i> จัดการสมาชิก</h1>
    <hr>
    <form action="" method="post" id="form-member">
        <div class="row">
            <div class="col">
                <input type="text" name="member_name" placeholder="ชื่อสมาชิก" class="form-control">
            </div>
            <div class="col">
                <input type="text" name="phone" placeholder="เบอร์โทรศัพท์" class="form-control">
            </div>
            <div class="col">
                <button type="button" class="btn btn-primary" id="btn-add"><i class="fa-solid fa-floppy-disk"></i> บันทึก</button>
            </div>
        </div>
        <input type="hidden" name="type" value="add-member">
    </form>
</div>
<hr>
<div class="table-responsive" style="overflow-x: hidden;">
    <table class="table table-bordered table-sm" id="dataTable" width="100%">
        <thead>
            <th>#</th>
            <th>ชื่อลูกค้า</th>
            <th>โทรศัพท์</th>
            <th>วันที่เพิ่มข้อมูล</th>
            <th>Action</th>
        </thead>
        <tbody id="fetch_data"></tbody>
    </table>
</div>
<script>
    $(document).ready(function() {

        function fetchData() {
            $.post('controller/member.controller.php', {
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
            // ดึงค่ามาเช็ค
            let memberName = $("input[name='member_name']").val();
            let phone = $("input[name='phone']").val();

            if (memberName.trim() === "" || phone.trim() === "") {
                alertify.error("กรุณากรอกข้อมูลให้ครบถ้วน");
                return;
            }

            let params = $("#form-member").serializeArray();
            params.push({
                name: "type",
                value: "add-member"
            });
            $.post('controller/member.controller.php', params, (response) => {
                if (response.message == 'success') {
                    alertify.success("Saved");
                    $("#form-member").find('input, textarea').val('');
                    fetchData();
                } else {
                    alertify.error("บันทึกไม่สำเร็จหรือข้อมูลซ้ำกัน");
                }
            });
        });
        $(document).on('click', '#btn-delete', function(e) {
            let id = $(this).data('id');
            let type = $(this).data('type');
            $.post('controller/member.controller.php', {
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
                member_name: $(this).data('member_name'),
                phone: $(this).data('phone'),
                type: $(this).data('type')
            }
            let strhtml = `
                <form id="edit-form">
                    <input type="text" class="form-control" name="member_name" value="${params.member_name}">
                    <p></p>
                    <input type="text" class="form-control" name="phone" value="${params.phone}">
                    <input type="hidden" name="id" value="${params.id}">
                    <input type="hidden" name="type" value="${params.type}">
                </form>
            `;
            alertify.confirm().destroy();
            alertify.confirm(
                "แก้ไข",
                strhtml,
                () => {
                    // เช็คค่าว่างจากฟอร์มที่เพิ่งสร้างใน strhtml
                    let editName = $("#edit-form input[name='member_name']").val();
                    let editPhone = $("#edit-form input[name='phone']").val();

                    if (editName.trim() === "" || editPhone.trim() === "") {
                        alertify.error("ข้อมูลห้ามเป็นค่าว่าง");
                        return;
                    }

                    let formData = $("#edit-form").serializeArray();
                    $.post('controller/member.controller.php', formData, (response) => {
                        if (response.message == 'success') {
                            fetchData();
                            alertify.success("Updated");
                        } else {
                            alertify.error("Update Failed");
                        }
                    })
                }, () => {}
            );
            $.post('controller/member.controller.php', {
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