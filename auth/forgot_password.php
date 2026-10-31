
<?php
session_start();
require_once "../config/ketnoi.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");

    // Kiểm tra email Admin
    $sql = "SELECT id FROM quan_tri_vien WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        $_SESSION["reset_role"] = "admin";
        $_SESSION["reset_id"] = $user["id"];

        header("Location: reset_password.php");
        exit();

    } else {

        // Kiểm tra email Sinh viên
        $sql = "SELECT mssv FROM sinh_vien WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            $_SESSION["reset_role"] = "sinhvien";
            $_SESSION["reset_id"] = $user["mssv"];

            header("Location: reset_password.php");
            exit();

        } else {
            $error = "Email không tồn tại trong hệ thống!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <h1 class="text-2xl font-bold text-center text-slate-800 mb-3">
            QUÊN MẬT KHẨU
        </h1>

        <p class="text-center text-slate-500 mb-6">
            Nhập email đã đăng ký để tiếp tục.
        </p>

        <?php if ($error != "") { ?>
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-5 text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <form method="POST" class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    required
                    placeholder="Nhập email của bạn"
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg">
                Tiếp tục
            </button>

        </form>

        <div class="text-center mt-6">
            <a href="login.php" class="text-sm text-blue-600 hover:underline">
                Quay lại đăng nhập
            </a>
        </div>

    </div>

</body>
</html>
