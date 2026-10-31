
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Thống kê tổng số sinh viên
$tongSinhVien = $conn->query(
    "SELECT COUNT(*) FROM sinh_vien"
)->fetchColumn();

// Thống kê tổng số lớp
$tongLop = $conn->query(
    "SELECT COUNT(*) FROM lop"
)->fetchColumn();

// Thống kê tổng số khoa
$tongKhoa = $conn->query(
    "SELECT COUNT(*) FROM khoa"
)->fetchColumn();

// Thống kê tổng số thông báo
$tongThongBao = $conn->query(
    "SELECT COUNT(*) FROM thong_bao"
)->fetchColumn();

// Thống kê số lớp và số sinh viên theo từng khoa
$sql = "SELECT
            k.makhoa,
            k.tenkhoa,
            COUNT(DISTINCT l.malop) AS so_lop,
            COUNT(DISTINCT sv.mssv) AS so_sinh_vien
        FROM khoa k
        LEFT JOIN lop l ON k.makhoa = l.makhoa
        LEFT JOIN sinh_vien sv ON k.makhoa = sv.makhoa
        GROUP BY k.makhoa, k.tenkhoa
        ORDER BY k.makhoa";

$stmt = $conn->prepare($sql);
$stmt->execute();

$thongKeKhoa = $stmt->fetchAll(PDO::FETCH_ASSOC);

$hoten = $_SESSION["hoten"] ?? "Quản trị viên";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ quản trị</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<script>
function toggleMenu() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("hidden");
}
</script>

<body class="bg-gray-100 min-h-screen">
<!-- HEADER -->
<header class="w-full bg-blue-700 text-white shadow">

    <div class="w-full px-4 sm:px-6 py-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">

        <div>
            <h1 class="text-xl sm:text-2xl font-bold">
                QUẢN LÝ SINH VIÊN
            </h1>

            <p class="text-blue-100 text-sm mt-1">
                Trang quản trị hệ thống
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 text-sm">

            <span>
                Xin chào,
                <strong><?php echo htmlspecialchars($hoten); ?></strong>
            </span>

            <a href="../auth/logout.php"
               class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg">
                Đăng xuất
            </a>

        </div>

    </div>

</header>

<!-- LAYOUT CHÍNH -->
<div class="w-full min-h-screen flex flex-col md:flex-row">

    <!-- NÚT MENU ĐIỆN THOẠI -->
    <div class="md:hidden bg-slate-800 p-4">
        <button id="menuButton"
                class="text-white text-2xl px-3 py-1 rounded hover:bg-slate-700"
                onclick="toggleMenu()">
            ☰
        </button>
        <span class="text-white ml-2 font-semibold">MENU</span>
    </div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
           class="hidden md:block w-full md:w-64 md:min-h-screen bg-slate-800 text-white p-5 shrink-0">

        <h2 class="text-lg font-bold mb-5 border-b border-slate-600 pb-4">
            QUẢN TRỊ VIÊN
        </h2>

        <nav class="flex flex-col gap-2">

            <a href="index.php"
               class="px-4 py-3 rounded-lg bg-blue-600 hover:bg-blue-700">
                Trang chủ
            </a>

            <a href="student_list.php"
               class="px-4 py-3 rounded-lg hover:bg-slate-700">
                Quản lý sinh viên
            </a>

            <a href="class_list.php"
               class="px-4 py-3 rounded-lg hover:bg-slate-700">
                Quản lý lớp
            </a>

            <a href="faculty_list.php"
               class="px-4 py-3 rounded-lg hover:bg-slate-700">
                Quản lý khoa
            </a>

            <a href="notification.php"
               class="px-4 py-3 rounded-lg hover:bg-slate-700">
                Quản lý thông báo
            </a>

        </nav>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 min-w-0 w-full px-4 sm:px-6 lg:px-8 py-8">


        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            Tổng quan hệ thống
        </h2>

        <p class="text-gray-500 mb-8">
            Thống kê dữ liệu quản lý sinh viên
        </p>

        <!-- THỐNG KÊ TỔNG QUAN -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            <!-- SINH VIÊN -->
            <a href="student_list.php"
               class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">

                <div class="flex justify-between items-center">

                    <div>
                        <p class="text-gray-500 text-sm">Tổng sinh viên</p>

                        <h3 class="text-3xl font-bold text-blue-600 mt-2">
                            <?php echo $tongSinhVien; ?>
                        </h3>
                    </div>

                    <div class="bg-blue-100 text-blue-600 p-4 rounded-full">
                        <span class="text-2xl">👨‍🎓</span>
                    </div>

                </div>

                <p class="text-blue-600 text-sm mt-4">
                    Quản lý sinh viên →
                </p>

            </a>

            <!-- LỚP -->
            <a href="class_list.php"
               class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">

                <div class="flex justify-between items-center">

                    <div>
                        <p class="text-gray-500 text-sm">Tổng số lớp</p>

                        <h3 class="text-3xl font-bold text-green-600 mt-2">
                            <?php echo $tongLop; ?>
                        </h3>
                    </div>

                    <div class="bg-green-100 text-green-600 p-4 rounded-full">
                        <span class="text-2xl">🏫</span>
                    </div>

                </div>

                <p class="text-green-600 text-sm mt-4">
                    Quản lý lớp học →
                </p>

            </a>

            <!-- KHOA -->
            <a href="faculty_list.php"
               class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">

                <div class="flex justify-between items-center">

                    <div>
                        <p class="text-gray-500 text-sm">Tổng số khoa</p>

                        <h3 class="text-3xl font-bold text-orange-600 mt-2">
                            <?php echo $tongKhoa; ?>
                        </h3>
                    </div>

                    <div class="bg-orange-100 text-orange-600 p-4 rounded-full">
                        <span class="text-2xl">🏢</span>
                    </div>

                </div>

                <p class="text-orange-600 text-sm mt-4">
                    Quản lý khoa →
                </p>

            </a>

            <!-- THÔNG BÁO -->
            <a href="notification.php"
               class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">

                <div class="flex justify-between items-center">

                    <div>
                        <p class="text-gray-500 text-sm">Tổng thông báo</p>

                        <h3 class="text-3xl font-bold text-purple-600 mt-2">
                            <?php echo $tongThongBao; ?>
                        </h3>
                    </div>

                    <div class="bg-purple-100 text-purple-600 p-4 rounded-full">
                        <span class="text-2xl">📢</span>
                    </div>

                </div>

                <p class="text-purple-600 text-sm mt-4">
                    Quản lý thông báo →
                </p>

            </a>

        </div>

        <!-- BẢNG THỐNG KÊ THEO KHOA -->
        <section class="mt-10 bg-white rounded-xl shadow p-5 sm:p-6">

            <div class="mb-5">

                <h3 class="text-lg font-bold text-gray-800">
                    Thống kê sinh viên theo khoa
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Thống kê số lớp và số sinh viên của từng khoa
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full min-w-[550px] text-left border-collapse">

                    <thead class="bg-gray-100 text-gray-700">

                        <tr>
                            <th class="px-4 py-3 border-b">STT</th>

                            <th class="px-4 py-3 border-b">
                                Tên khoa
                            </th>

                            <th class="px-4 py-3 border-b text-center">
                                Số lớp
                            </th>

                            <th class="px-4 py-3 border-b text-center">
                                Số sinh viên
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        <?php if (count($thongKeKhoa) > 0) { ?>

                            <?php foreach ($thongKeKhoa as $index => $khoa) { ?>

                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-3 text-gray-600">
                                        <?php echo $index + 1; ?>
                                    </td>

                                    <td class="px-4 py-3 font-medium text-gray-800">
                                        <?php echo htmlspecialchars($khoa["tenkhoa"]); ?>
                                    </td>

                                    <td class="px-4 py-3 text-center text-green-600 font-semibold">
                                        <?php echo $khoa["so_lop"]; ?>
                                    </td>

                                    <td class="px-4 py-3 text-center text-blue-600 font-semibold">
                                        <?php echo $khoa["so_sinh_vien"]; ?>
                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                    Chưa có dữ liệu khoa.
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </section>

        <!-- CHỨC NĂNG QUẢN LÝ -->
        <section class="mt-10 bg-white rounded-xl shadow p-5 sm:p-6">
        </section>

        <!-- FOOTER -->
        <footer class="text-between text-gray-500 text-sm py-6">
            © Hệ thống quản lý sinh viên 
            <p>by Zay(Trvu)</p>
        </footer>

    </main>

</div>

</body>
</html>
