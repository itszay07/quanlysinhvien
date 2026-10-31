# HỆ THỐNG QUẢN LÝ SINH VIÊN

## 1. Giới thiệu dự án

Hệ thống quản lý sinh viên là dự án xây dựng website phục vụ việc quản lý thông tin sinh viên, lớp học, khoa và thông báo.

Dự án được phát triển bằng PHP và MySQL, sử dụng Tailwind CSS để thiết kế giao diện. Hệ thống có hai nhóm người dùng chính: Quản trị viên (Admin) và Sinh viên.

## 2. Công nghệ sử dụng

- **Frontend:** HTML, CSS, Tailwind CSS.
- **Backend:** PHP.
- **Database:** MySQL.
- **Kết nối cơ sở dữ liệu:** PDO.
- **Môi trường phát triển:** XAMPP.
- **Quản lý mã nguồn:** Git và GitHub.

## 3. Các chức năng đã xây dựng

### 3.1. Chức năng đăng nhập

- Đăng nhập bằng tài khoản Admin.
- Đăng nhập bằng mã số sinh viên (MSSV).
- Kiểm tra mật khẩu bằng `password_verify()`.
- Phân quyền Admin và Sinh viên bằng Session.
- Đăng xuất khỏi hệ thống.

### 3.2. Chức năng quản trị viên

- Quản lý sinh viên: thêm, sửa, xóa và xem danh sách.
- Quản lý lớp học: thêm, sửa, xóa và xem danh sách.
- Quản lý khoa: thêm, sửa, xóa và xem danh sách.
- Quản lý thông báo: thêm, sửa, xóa và xem danh sách.
- Tìm kiếm dữ liệu trong các danh sách quản lý.
- Thống kê tổng số sinh viên, lớp học, khoa và thông báo.
- Thống kê số lớp và số sinh viên theo từng khoa.

### 3.3. Chức năng sinh viên

- Xem trang thông tin cá nhân.
- Xem thông báo chung của hệ thống.
- Đổi mật khẩu.

### 3.4. Chức năng quên mật khẩu

- Nhập email đã đăng ký.
- Kiểm tra email trong cơ sở dữ liệu.
- Nhập mật khẩu mới và xác nhận mật khẩu.
- Cập nhật mật khẩu đã mã hóa vào cơ sở dữ liệu.

**Lưu ý:** Chức năng quên mật khẩu hiện đang ở mức mô phỏng bằng PHP Session, chưa triển khai xác thực email thực tế.

## 4. Cấu trúc cơ sở dữ liệu

Database: `quanlysinhvien`

Hệ thống gồm 5 bảng chính:

| Tên bảng | Chức năng |
|---|---|
| `khoa` | Lưu thông tin khoa |
| `lop` | Lưu thông tin lớp học |
| `sinh_vien` | Lưu thông tin sinh viên |
| `quan_tri_vien` | Lưu tài khoản quản trị viên |
| `thong_bao` | Lưu thông báo hệ thống |

## 5. Cấu trúc thư mục dự án

```text
quanly_sinhvien/
│
├── admin/             # Chức năng quản trị viên
├── sinhvien/          # Chức năng sinh viên
├── auth/              # Đăng nhập, đăng xuất, quên mật khẩu
├── config/            # Kết nối cơ sở dữ liệu
├── database.sql         # File SQL tạo cơ sở dữ liệu
├── includes/          # Các thành phần dùng chung
├── css/               # File CSS
│
├── index.php          # Trang chính
└── README.md          # Tài liệu dự án
```

## 6. Hướng dẫn cài đặt

1. Cài đặt XAMPP.
2. Khởi động Apache và MySQL.
3. Sao chép thư mục dự án vào `htdocs`.
4. Mở phpMyAdmin.
5. Tạo database `quanlysinhvien`.
6. Import file `database/quanlysinhvien.sql`.
7. Cấu hình thông tin kết nối trong `config/ketnoi.php`.
8. Truy cập website:

```text
http://localhost/quanly_sinhvien/
```

## 7. Bảo mật

- Sử dụng `password_hash()` để mã hóa mật khẩu trước khi lưu.
- Sử dụng `password_verify()` để kiểm tra mật khẩu.
- Sử dụng PDO Prepared Statements để truy vấn cơ sở dữ liệu.
- Sử dụng Session để quản lý trạng thái đăng nhập.
- Phân quyền truy cập giữa Admin và Sinh viên.
- Không công khai thông tin kết nối database trên GitHub.

## 8. Trạng thái dự án

- [x] Thiết kế cơ sở dữ liệu gồm 5 bảng.
- [x] Kết nối PHP với MySQL.
- [x] Xây dựng chức năng đăng nhập và đăng xuất.
- [x] Phân quyền Admin và Sinh viên.
- [x] Xây dựng giao diện quản trị viên.
- [x] CRUD sinh viên.
- [x] CRUD lớp học.
- [x] CRUD khoa.
- [x] CRUD thông báo.
- [x] Tìm kiếm dữ liệu.
- [x] Thống kê dữ liệu trên Dashboard.
- [x] Xem thông tin cá nhân sinh viên.
- [x] Đổi mật khẩu.
- [x] Mô phỏng chức năng quên mật khẩu.
- [ ] Hoàn thiện xác thực email thực tế cho chức năng quên mật khẩu.
- [ ] Kiểm thử và hoàn thiện toàn bộ hệ thống.

## 9. Tác giả

**Zay (trvu)**

Admin

Tên đăng nhập: admin

Mật khẩu: Admin@12345

Lưu ý: Đây là tài khoản mẫu dùng để đăng nhập và kiểm tra chức năng quản trị viên. Mật khẩu trong cơ sở dữ liệu được mã hóa bằng password_hash().

Sinh viên

Sử dụng MSSV và mật khẩu đã được tạo trong cơ sở dữ liệu để đăng nhập có thể dùng tk admin để tạo tại tôi làm biến.

Không thì sử dụng tk tôi đã tạo sẵn

Tên đăng nhập: 001

Mật khẩu: TranVu
