<?php include("../../include/header.php"); ?>
<link rel="stylesheet" href="../../assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
<link rel="stylesheet" href="../../assets/plugins/toastr/toastr.min.css">
<link rel="stylesheet" href="../../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="../../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="../../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<style>
    .dataTables_filter {
        display: none;
    }

    .dataTables_length {
        float: left !important;
    }
</style>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include("../../include/checkmenu.php"); ?>
            <div class="layout-page">
                <?php include("../../include/navbar.php"); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="py-3 mb-0">ข้อมูลลูกค้า</h4>

                        <div class="row">

                            <!-- <div class="col-lg-12 mb-0 order-0 d-flex justify-content-end">
                                <a href="data.php" class="btn btn-primary text-white account-image-reset mb-4">
                                    <i class="bx bx-plus d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">
                                        <i class="bx bx-plus me-1"></i>

                                        เพิ่มข้อมูล</span>
                                </a>
                            </div> -->

                            <div class="col-lg-12 mb-4 order-0">
                                <div class="card">
                                    <h5 class="card-header">

                                        <div class="input-group">
                                            <span class="input-group-text bg-primary text-white" id="basic-addon11">ค้นหาข้อมูล </span>
                                            <input type="text" autocomplete="yes" id="search" name="search" class="form-control" placeholder="ค้นหาข้อมูลจากตาราง" />
                                        </div>

                                    </h5>
                                    <div class="card-body">
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-bordered" id="tableCustomers">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">รหัสลูกค้า</th>
                                                        <th class="text-center">ชื่อลูกค้า</th>
                                                        <th class="text-center">ที่อยู่</th>
                                                        <th class="text-center">เบอร์โทรศัพท์</th>
                                                        <th class="text-center">สถานะ</th>
                                                        <th class="text-center">จํานวนออเดอร์ที่ลูกค้าสั่ง</th>
                                                        <th class="text-center">จัดการข้อมูล</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbCustomers">


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="modal_confirm_del" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-warning ">
                                    <h4 class="modal-title text-white" id="exampleModalLabel2">แจ้งเตือน</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h5 class="">ยืนยันการลบข้อมูล ?</h5>
                                </div>
                                <div class="modal-footer text-center">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        ยกเลิก
                                    </button>
                                    <button type="button" id="btnIdCustomer" onclick="confirmDel_customer()" class="btn btn-warning">ยืนยัน</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include("../../include/footer.php"); ?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
</body>
<?php include("../../include/script.php"); ?>
<script src="../../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../../assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../../assets/plugins/toastr/toastr.min.js"></script>
<script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script>
    var table; 
    $(document).ready(function() {
        dataCustomer();




        if (sessionStorage.getItem('toastrShown') === 'edit') {
            toastr.success("แก้ไขข้อมูลแล้วค่ะ !");
            sessionStorage.removeItem('toastrShown');
        }
        if (sessionStorage.getItem('toastrShown') === 'save') {
            toastr.success("บันทึกข้อมูลแล้วค่ะ !");
            sessionStorage.removeItem('toastrShown');
        }
        if (sessionStorage.getItem('toastrShown') === 'no') {
            toastr.error("ข้อมูลลูกค้านี้ถูกลบไปแล้วค่ะ !");
            sessionStorage.removeItem('toastrShown');
        }
    });
    $('#search').on('input', function() {
        var value = $(this).val().trim();
        table.search(value).draw();
    });


    function dataCustomer() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                $('#tableCustomers').DataTable().destroy();
                document.getElementById("tbCustomers").innerHTML = this.responseText;
                $('#tableCustomers').DataTable({
                    "language": {
                        "lengthMenu": "แสดง _MENU_ รายการ",
                        "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                        "infoEmpty": "ไม่พบรายการ",
                        "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                        "search": "ค้นหา:",
                        "paginate": {
                            "first": "หน้าแรก",
                            "last": "หน้าสุดท้าย",
                            "next": "ถัดไป",
                            "previous": "ก่อนหน้า"
                        }
                    },
                    "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "ทั้งหมด"] ]
                });
                table = $('#tableCustomers').DataTable();
            }
        };

        xhttp.open("GET", "../../services/customer/tableCustomer.php", true);
        xhttp.send();
    }

    function updateCustomerStatus(objId) {
        $('#modal_confirm_del').modal('show');
        $('#btnIdCustomer').val(objId)


    }

    function confirmDel_customer() {
        let objId = $('#btnIdCustomer').val();
        $.ajax({
            type: 'GET',
            url: "../../services/customer/data.php?v=customerStatus&id=" + objId,
            success: function(response) {
                if (response.result == 1) {
                    dataCustomer();
                    toastr.success("ลบข้อมูลลูกค้าแล้วค่ะ !");
                    $('#modal_confirm_del').modal('hide');
                } else {
                    toastr.error("ข้อมูลลูกค้านี้ถูกลบไปแล้วค่ะ !");
                    $('#modal_confirm_del').modal('hide');
                }
            },
            error: function(error) {
                console.log(error)
            }
        });
    }
</script>