
<?php
session_start();
require_once "../config/ketnoi.php";

$error = "";
$success = "";

// Kiểm tra quyền đặt lại mật khẩu
if (
    !isset($_SESSION["reset_role"]) ||
    !isset($_SESSION["reset_id"])
) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // Kiểm tra mật khẩu
    if (strlen($password) < 6) {

        $error = "Mật khẩu phải có ít nhất 6 ký tự!";

    } elseif ($password != $confirm_password) {

        $error = "Mật khẩu xác nhận không khớp!";

    } else {

        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($_SESSION["reset_role"] == "admin") {

            $sql = "UPDATE quan_tri_vien SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $hashed_password,
                $_SESSION["reset_id"]
            ]);

        } elseif ($_SESSION["reset_role"] == "sinhvien") {

            $sql = "UPDATE sinh_vien SET password = ? WHERE mssv = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $hashed_password,
                $_SESSION["reset_id"]
            ]);

        } else {

            $error = "Thông tin đặt lại mật khẩu không hợp lệ!";
        }

        if ($error == "") {

            // Xóa phiên đặt lại mật khẩu
            unset($_SESSION["reset_role"]);
            unset($_SESSION["reset_id"]);

            $success = "Đổi mật khẩu thành công! Bạn có thể đăng nhập.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <h1 class="text-2xl font-bold text-center text-slate-800 mb-3">
            ĐẶT LẠI MẬT KHẨU
        </h1>

        <p class="text-center text-slate-500 mb-6">
            Nhập mật khẩu mới của bạn.
        </p>

        <?php if ($error != "") { ?>
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-5 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <?php if ($success != "") { ?>

            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-5 text-sm">
                <?php echo htmlspecialchars($success); ?>
            </div>

            <a href="login.php"
               class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg">
                Quay lại đăng nhập
            </a>

        <?php } else { ?>

            <form method="POST" class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Mật khẩu mới
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        minlength="6"
                        placeholder="Nhập mật khẩu mới"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Xác nhận mật khẩu
                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        required
                        minlength="6"
                        placeholder="Nhập lại mật khẩu mới"
                        class="w-full border border-slate-300 rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg">
                    Xác nhận đổi mật khẩu
                </button>

            </form>

        <?php } ?>

    </div>

</body>
</html>
