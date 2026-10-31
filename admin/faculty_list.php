
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Nhận từ khóa tìm kiếm
$tuKhoa = trim($_GET["q"] ?? "");

// Lấy danh sách khoa
if ($tuKhoa != "") {
    $sql = "SELECT * FROM khoa
            WHERE makhoa LIKE :makhoa
            OR tenkhoa LIKE :tenkhoa
            ORDER BY makhoa";

    $stmt = $conn->prepare($sql);

    $tuKhoaTim = "%" . $tuKhoa . "%";

    $stmt->bindValue(":makhoa", $tuKhoaTim, PDO::PARAM_STR);
    $stmt->bindValue(":tenkhoa", $tuKhoaTim, PDO::PARAM_STR);
} else {
    $sql = "SELECT * FROM khoa ORDER BY makhoa";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();

$danhSachKhoa = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Đếm số kết quả
$tongSoKhoa = count($danhSachKhoa);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khoa</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto p-4 sm:p-6">

    <!-- Tiêu đề -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Quản lý khoa
            </h1>

            <p class="text-gray-500 mt-1">
                Danh sách các khoa trong hệ thống
            </p>
        </div>

        <a href="faculty_add.php"
           class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-medium">
            + Thêm khoa mới
        </a>

    </div>

    <!-- Thông báo -->
    <?php if (isset($_GET["msg"])) { ?>

        <?php if ($_GET["msg"] == "deleted") { ?>

            <div class="bg-green-100 text-green-700 border border-green-300 p-3 rounded-lg mb-4">
                Xóa khoa thành công!
            </div>

        <?php } elseif ($_GET["msg"] == "error") { ?>

            <div class="bg-red-100 text-red-700 border border-red-300 p-3 rounded-lg mb-4">
                Không thể xóa khoa vì đang có dữ liệu liên quan!
            </div>

        <?php } ?>

    <?php } ?>

    <!-- Bảng danh sách khoa -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">

        <div class="p-4 sm:p-5 border-b flex flex-col sm:flex-row justify-between sm:items-center gap-3">

            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-gray-700">
                    Danh sách khoa
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Tìm thấy
                    <strong><?php echo $tongSoKhoa; ?></strong>
                    khoa
                </p>
            </div>

            <a href="index.php"
               class="text-blue-600 hover:text-blue-800 font-medium">
                ← Trang chủ Admin
            </a>

        </div>
        
        <!-- Tìm kiếm -->
        <div class="p-4 sm:p-5 border-b">

            <form method="GET" action="faculty_list.php"
                class="flex flex-col sm:flex-row gap-3">

                <input
                    type="text"
                    name="q"
                    value="<?php echo htmlspecialchars($tuKhoa); ?>"
                    placeholder="Nhập mã khoa hoặc tên khoa..."
                    class="w-full min-w-0 flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">

                    <button
                        type="submit"
                        class="w-full sm:w-auto shrink-0 whitespace-nowrap bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg">
                        Tìm kiếm
                    </button>

                    <a href="faculty_list.php"
                    class="w-full sm:w-auto shrink-0 whitespace-nowrap text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-lg">
                        Làm mới
                    </a>

                </div>

            </form>

        </div>


        <!-- Bảng dữ liệu -->
        <div class="overflow-x-auto">

            <table class="w-full min-w-[650px] text-left border-collapse">

                <thead class="bg-gray-100 text-gray-700">

                    <tr>
                        <th class="px-6 py-4 border-b">Mã khoa</th>
                        <th class="px-6 py-4 border-b">Tên khoa</th>
                        <th class="px-6 py-4 border-b">Mô tả</th>
                        <th class="px-6 py-4 border-b text-center">Thao tác</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200">

                    <?php if ($tongSoKhoa > 0) { ?>

                        <?php foreach ($danhSachKhoa as $khoa) { ?>

                            <tr class="hover:bg-gray-50">

                                <td class="px-6 py-4 font-medium text-gray-800">
                                    <?php echo htmlspecialchars($khoa["makhoa"]); ?>
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    <?php echo htmlspecialchars($khoa["tenkhoa"]); ?>
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    <?php echo htmlspecialchars($khoa["mota"] ?? ""); ?>
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">

                                    <a href="faculty_edit.php?makhoa=<?php echo urlencode($khoa["makhoa"]); ?>"
                                       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-md text-sm">
                                        Sửa
                                    </a>

                                    <form action="faculty_delete.php"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa khoa này không?');">

                                        <input
                                            type="hidden"
                                            name="makhoa"
                                            value="<?php echo htmlspecialchars($khoa["makhoa"]); ?>">

                                        <button
                                            type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md text-sm">
                                            Xóa
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <?php if ($tuKhoa != "") { ?>
                                    Không tìm thấy khoa phù hợp.
                                <?php } else { ?>
                                    Chưa có khoa nào.
                                <?php } ?>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
