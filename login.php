<?php

include 'config.php';
checkRememberCookie();



$title = "Đăng nhập";

// check if user is logged in
if (isset($_SESSION['user_id']) && !isset($_SESSION['admin'])) {
    header('location: dashboard.php');
}


include 'components/header.php';

//get username from url and check exist in db
$username = $_GET['username'] ?? "";

?>

<body>
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <!--<div class="bg-overlay"></div>-->
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="p-lg-5 p-4">
                    <div class="text-center w-100 mb-4">
                        <img src="/imgs/book.png" width="250">
                    </div>
                    <div class="gradient-catagory pb-1">
                        CHẠY ADS BÃO ĐƠN
                    </div>
                    <div class="gradient-catagory pb-2" style="font-size: 19px">
                        Chinh phục 3 nền tảng
                    </div>
                    <div class="mt-2">
                        <form action="index.html">

                            <input type="hidden" id="token" name="token">

                            <div class="mb-3">
                                <input type="text" class="form-control" id="username" value="<?= $username ?? "" ?>" autofocus placeholder="Nhập mã đọc sách"/>
                            </div>

                            <div class="mb-3">
                                <div class="position-relative auth-pass-inputgroup mb-3">
                                    <input type="number" class="form-control pe-5" placeholder="Nhập số điện thoại" autocomplete="on" id="phone" maxlength="10"/>

                                </div>
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-login w-100" type="button" id="btnSubmit" style="background: #01a1b9; border: 0; font-weight: 700; font-size: 20px;color:#fff">
                                    Bắt đầu học bài
                                </button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->

        <?php include 'components/footer.php'; ?>

        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->


    <!-- import axios from dns -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <!-- import jquery from dns -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <?php
    include 'components/scripts.php';
    ?>

    <script>
        // Initialize the agent at application startup.

        const fpPromise = import('https://openfpcdn.io/fingerprintjs/v3')
            .then(FingerprintJS => FingerprintJS.load())

        // Get the visitor identifier when you need it.
        fpPromise
            .then(fp => fp.get())
            .then(result => {
                // This is the visitor identifier:
                const visitorId = result.visitorId
                $("#token").val(visitorId)
            })
    </script>

    <script>
        // jquery document ready
        $(document).ready(function() {

            function swal(status, message) {
                if (status) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: message,
                    })

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Thất bại',
                        text: message,
                    })
                }

            }





            // click button submit
            $("#btnSubmit").click(function() {

                // get value from input
                var username = $("#username").val();
                var phone = $("#phone").val();
                var token = $("#token").val();

                console.log(token, "token")

                // check empty
                if (username == "" || phone == "") {


                    swal(false, "Vui lòng nhập đầy đủ thông tin");
                    return;
                }

                // check phone
                if (phone.length < 10 || phone.length > 11) {
                    swal(false, "Số điện thoại không hợp lệ");
                    return;
                }

                // check phone
                if (isNaN(phone)) {
                    swal(false, "Số điện thoại không hợp lệ");
                    return;
                }

                // send to server
                axios.post('api/auth.php', {
                        username: username,
                        phone: phone,
                        token: token
                    })
                    .then(function(response) {
                        console.log(response.data, "data");

                        // check response
                        if (response.data.status == true) {
                            // redirect to index
                            swal(true, response.data.message);

                            window.location.href = "/dashboard.php";
                        } else {
                            swal(false, response.data.message);
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });



            });
        });
    </script>
    
<script>
// Nhấn Enter để click nút Login
var input = document.getElementById("phone");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    document.getElementById("btnSubmit").click();
  }
});
</script>


</body>

</html>