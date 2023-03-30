<?php
include('../config.php');

if (isset($_SESSION['user_id']) && isset($_SESSION['admin'])) {
    header("Location: ../admin/index.php");
}

//if post request is login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = login($username, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['admin'] = 1;

        // add username to session
        $_SESSION['username'] = $user['username'];

        header("Location: index.php");
    } else {
        $message = 'Xin lỗi, những thông tin đăng nhập không khớp';
    }
}

//write function login use quote
function login($username, $password)
{
    global $conn;

    $password = md5($password);

    $sql = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}


include("../components/header.php");


?>
<!-- auth-page wrapper -->
<div class="auth-page-wrapper py-5 d-flex justify-content-center align-items-center min-vh-100">
    <!-- auth-page content -->
    <div class="auth-page-content overflow-hidden pt-lg-5">
        <div class="container">
                                <div class="p-lg-5 p-4">
                                    <div class="gradient-catagory pb-2">
                                        Trang quản trị dành cho Admin EMO
                                    </div>

                                    <div class="mt-4">
                                        <form action="/admin/login.php" method="post">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="username" name="username" placeholder="Tên đăng nhập" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>" />
                                            </div>

                                            <div class="mb-3">
                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                    <input type="password" name="password" class="form-control pe-5" placeholder="Mật khẩu" id="password-input" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>" />
                                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted shadow-none" type="button" id="password-addon">
                                                        <i class="ri-eye-fill align-middle"></i>
                                                    </button>
                                                </div>
                                            </div>


                                            <div class="mt-4">
                                                <button class="btn w-100" type="submit" id="click-submit" style="background: #ce0000; border: 0; font-weight: 700; font-size: 20px;color:#fff">
                                                    Đăng nhập
                                                </button>
                                            </div>

                                        </form>
                                    </div>


                                </div>
            </div>
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <?php include '../components/footer.php'; ?>
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->

<?php

//include scripts in components
include '../components/scripts.php';

//check if error then show error with swal.fire
if (isset($error)) {
    echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Lỗi',
        text: '$message',
      })
    </script>";
}




?>

<script>
// Nhấn Enter để click nút Login
var input = document.getElementById("password");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    document.getElementById("click-submit").click();
  }
});
</script>
</body>

</html>