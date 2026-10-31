
<?php
session_start();

// Kiểm tra quyền Admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/ketnoi.php";

// Lấy từ khóa tìm kiếm
$tuKhoa = trim($_GET["q"] ?? "");

// Lấy danh sách lớp
$sql = "SELECT
            l.malop,
            l.tenlop,
            k.tenkhoa
        FROM lop l
        LEFT JOIN khoa k ON l.makhoa = k.makhoa";

if ($tuKhoa != "") {
    $sql .= " WHERE
                l.malop LIKE :malop
                OR l.tenlop LIKE :tenlop";
}

$sql .= " ORDER BY l.malop ASC";

$stmt = $conn->prepare($sql);

if ($tuKhoa != "") {
    $timKiem = "%" . $tuKhoa . "%";

    $stmt->bindValue(":malop", $timKiem);
    $stmt->bindValue(":tenlop", $timKiem);
}

$stmt->execute();

$danhSachLop = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tổng số lớp tìm được
$tongLop = count($danhSachLop);

// Lấy thông báo
$msg = $_GET["msg"] ?? "";
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý lớp học</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">

        <!-- TIÊU ĐỀ -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">

            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    QUẢN LÝ LỚP HỌC
                </h1>

                <p class="text-gray-500 mt-1 text-sm">
                    Danh sách các lớp học trong hệ thống
                </p>
            </div>

            <a href="class_add.php"
               class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-medium transition">
                + Thêm lớp học
            </a>

        </div>

        <!-- ĐIỀU HƯỚNG -->
        <div class="mb-5">
            <a href="index.php"
               class="text-blue-600 hover:text-blue-800 font-medium">
                ← Trang chủ Admin
            </a>
        </div>

        <!-- THÔNG BÁO -->
        <?php if ($msg == "deleted") { ?>

            <div class="bg-green-100 text-green-700 border border-green-300 p-4 rounded-lg mb-5">
                Xóa lớp thành công!
            </div>

        <?php } elseif ($msg == "error") { ?>

            <div class="bg-red-100 text-red-700 border border-red-300 p-4 rounded-lg mb-5">
                Không thể xóa lớp vì đang có sinh viên liên kết!
            </div>

        <?php } ?>

        <!-- KHUNG DANH SÁCH -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">

            <!-- TIÊU ĐỀ DANH SÁCH -->
            <div class="p-4 sm:p-5 border-b">

                <h2 class="text-lg sm:text-xl font-semibold text-gray-700">
                    Danh sách lớp học
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Tìm thấy
                    <span class="font-bold text-blue-600">
                        <?php echo $tongLop; ?>
                    </span>
                    lớp học
                </p>

            </div>

            <!-- TÌM KIẾM -->
            <div class="p-4 sm:p-5 bg-gray-50 border-b">

                <form method="GET" action="class_list.php"
                      class="flex flex-col sm:flex-row gap-3">

                    <input
                        type="text"
                        name="q"
                        value="<?php echo htmlspecialchars($tuKhoa); ?>"
                        placeholder="Nhập mã lớp hoặc tên lớp..."
                        class="flex-1 min-w-0 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    <div class="flex gap-2">

                        <button
                            type="submit"
                            class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg transition">
                            Tìm kiếm
                        </button>

                        <a
                            href="class_list.php"
                            class="flex-1 sm:flex-none text-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-3 rounded-lg transition">
                            Làm mới
                        </a>

                    </div>

                </form>

            </div>

            <!-- BẢNG DỮ LIỆU -->
            <div class="overflow-x-auto">

                <table class="w-full min-w-[650px] text-left border-collapse">

                    <thead class="bg-gray-100 text-gray-700">

                        <tr>
                            <th class="px-5 sm:px-6 py-4 border-b">Mã lớp</th>
                            <th class="px-5 sm:px-6 py-4 border-b">Tên lớp</th>
                            <th class="px-5 sm:px-6 py-4 border-b">Khoa</th>
                            <th class="px-5 sm:px-6 py-4 border-b text-center">Thao tác</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200">

                        <?php if ($tongLop > 0) { ?>

                            <?php foreach ($danhSachLop as $lop) { ?>

                                <tr class="hover:bg-gray-50">

                                    <td class="px-5 sm:px-6 py-4 font-medium text-blue-700">
                                        <?php echo htmlspecialchars($lop["malop"]); ?>
                                    </td>

                                    <td class="px-5 sm:px-6 py-4 text-gray-700">
                                        <?php echo htmlspecialchars($lop["tenlop"]); ?>
                                    </td>

                                    <td class="px-5 sm:px-6 py-4 text-gray-700">
                                        <?php echo htmlspecialchars($lop["tenkhoa"] ?? "Chưa có"); ?>
                                    </td>

                                    <td class="px-5 sm:px-6 py-4 text-center whitespace-nowrap">

                                        <div class="flex justify-center items-center gap-2">

                                            <a
                                                href="class_edit.php?malop=<?php echo urlencode($lop["malop"]); ?>"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-md text-sm transition">
                                                Sửa
                                            </a>

                                            <form
                                                action="class_delete.php"
                                                method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa lớp này không?');">

                                                <input
                                                    type="hidden"
                                                    name="malop"
                                                    value="<?php echo htmlspecialchars($lop["malop"]); ?>">

                                                <button
                                                    type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm transition">
                                                    Xóa
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">

                                    <?php if ($tuKhoa != "") { ?>

                                        Không tìm thấy lớp học phù hợp với từ khóa
                                        "<?php echo htmlspecialchars($tuKhoa); ?>".

                                    <?php } else { ?>

                                        Chưa có lớp học nào.

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
