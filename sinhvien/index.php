
<?php
session_start();

// Kiểm tra quyền sinh viên
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "sinhvien") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

$hoten = $_SESSION["hoten"] ?? "Sinh viên";

// Lấy 5 thông báo mới nhất
$sql = "SELECT * FROM thong_bao
        ORDER BY created_at DESC
        LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->execute();

$thongBao = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ sinh viên</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- HEADER -->
    <header class="bg-blue-700 text-white shadow-md">
        <div class="max-w-6xl mx-auto px-6 py-5">
            <h1 class="text-2xl md:text-3xl font-bold">
                HỆ THỐNG QUẢN LÝ SINH VIÊN
            </h1>

            <p class="text-blue-100 mt-1">
                Khu vực dành cho sinh viên
            </p>
        </div>
    </header>

    <main class="max-w-6xl mx-auto p-6">

        <!-- LỜI CHÀO -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Xin chào,
                <span class="text-blue-600">
                    <?php echo htmlspecialchars($hoten); ?>
                </span>!
            </h2>

            <p class="text-gray-500 mt-2">
                Chào mừng bạn đến với khu vực sinh viên.
            </p>
        </div>

        <!-- CHỨC NĂNG SINH VIÊN -->
        <div class="mb-5">
            <h3 class="text-xl font-bold text-gray-800">
                Chức năng sinh viên
            </h3>

            <p class="text-gray-500 mt-1">
                Chọn chức năng bạn muốn sử dụng.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-10">

            <!-- THÔNG TIN CÁ NHÂN -->
            <a href="profile.php"
               class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">

                <div class="text-blue-600 text-3xl mb-3">👤</div>

                <h4 class="text-lg font-bold text-gray-800">
                    Thông tin cá nhân
                </h4>

                <p class="text-gray-500 mt-2">
                    Xem thông tin cá nhân của bạn.
                </p>
            </a>

            <!-- ĐỔI MẬT KHẨU -->
            <a href="change_password.php"
               class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">

                <div class="text-green-600 text-3xl mb-3">🔒</div>

                <h4 class="text-lg font-bold text-gray-800">
                    Đổi mật khẩu
                </h4>

                <p class="text-gray-500 mt-2">
                    Thay đổi mật khẩu tài khoản.
                </p>
            </a>

            <!-- THÔNG BÁO -->
            <a href="notifications.php"
               class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">

                <div class="text-orange-500 text-3xl mb-3">🔔</div>

                <h4 class="text-lg font-bold text-gray-800">
                    Xem thông báo
                </h4>

                <p class="text-gray-500 mt-2">
                    Theo dõi các thông báo từ nhà trường.
                </p>
            </a>

            <!-- ĐĂNG XUẤT -->
            <a href="../auth/logout.php"
               class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition">

                <div class="text-red-600 text-3xl mb-3">🚪</div>

                <h4 class="text-lg font-bold text-gray-800">
                    Đăng xuất
                </h4>

                <p class="text-gray-500 mt-2">
                    Thoát khỏi tài khoản sinh viên.
                </p>
            </a>

        </div>

        <!-- THANH THÔNG BÁO -->
        <section class="bg-white rounded-xl shadow-md p-5 sm:p-6">

            <div class="flex flex-wrap justify-between items-center gap-3 mb-5">

                <div>
                    <h3 class="text-xl font-bold text-gray-800">
                        🔔 Thông báo mới nhất
                    </h3>

                    <p class="text-gray-500 text-sm mt-1">
                        Cập nhật thông báo từ nhà trường.
                    </p>
                </div>

                <a href="notifications.php"
                   class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                    Xem tất cả →
                </a>

            </div>

            <?php if (count($thongBao) > 0) { ?>

                <div class="divide-y divide-gray-200">

                    <?php foreach ($thongBao as $tb) { ?>

                        <div class="py-4">

                            <h4 class="font-bold text-gray-800">
                                <?php echo htmlspecialchars($tb["tieude"]); ?>
                            </h4>

                            <p class="text-gray-600 mt-2 whitespace-pre-line">
                                <?php echo htmlspecialchars($tb["noidung"]); ?>
                            </p>

                            <p class="text-gray-400 text-sm mt-2">
                                Ngày đăng:
                                <?php echo date("d/m/Y H:i", strtotime($tb["created_at"])); ?>
                            </p>

                        </div>

                    <?php } ?>

                </div>

            <?php } else { ?>

                <p class="text-center text-gray-500 py-8">
                    Hiện chưa có thông báo nào.
                </p>

            <?php } ?>

        </section>

    </main>

    <!-- FOOTER -->
    <footer class="text-center text-gray-500 py-6">
        © Hệ thống quản lý sinh viên
        <p>Create By Zay(Trvu)</p>
    </footer>

</body>
</html>
