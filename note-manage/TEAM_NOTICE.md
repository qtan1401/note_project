# 📢 Thông báo Nhóm: Cập nhật Kiến trúc Dự án Note-Manage

Chào mọi người,

Nhằm mục đích giữ cho mã nguồn (source code) của dự án phát triển đồng bộ, dễ bảo trì và có khả năng mở rộng tốt nhất, dự án `note-manage` của chúng ta hiện nay đã thống nhất được áp dụng theo các tiêu chuẩn kỹ thuật sau:

## 🏗️ 1. Mô hình cốt lõi: MVC (Model - View - Controller)
Hệ thống được thiết kế và vận hành theo mô hình MVC phân lớp rõ ràng:
- **M (Model):** Đảm nhận vai trò thiết kế dữ liệu và tương tác trực tiếp với Database.
- **V (View):** Chịu trách nhiệm về giao diện hiển thị cho người dùng cuối (Frontend nguyên bản với HTML/CSS/JS).
- **C (Controller):** Xử lý những logic nghiệp vụ (business logic) phức tạp, điều hướng dữ liệu từ Model trả về cho View.

## 🌐 2. Chuẩn giao tiếp: RESTful API
Thay vì kết xuất thẳng giao diện từ Backend, dự án đã được chuyển đổi để thực giao tiếp hoàn toàn qua chuẩn **RESTful API**:
- Client (View/Frontend) và Server (Model/Controller/Backend) được tách biệt hoàn toàn (Decoupled Architecture).
- Giao tiếp giữa 2 bộ phận thông qua các HTTP method chuẩn mực: `GET` (Đọc), `POST` (Tạo mới), `PUT/PATCH` (Cập nhật), `DELETE` (Xóa).
- Mọi dữ liệu trao đổi qua lại giữa Frontend và Backend đều dưới định dạng **JSON**. Thiết kế này cho phép hệ thống cực kì linh hoạt nếu chúng ta muốn mở rộng làm thêm Mobile App sau này.

## 🚀 3. Framework nền tảng: Laravel
- Phần Backend API của dự án được xây dựng 100% bằng **Laravel Framework** (PHP).
- Tận dụng sức mạnh của Laravel (Eloquent ORM, routing API thông minh qua `routes/api.php`, kiến trúc thư mục tối ưu) giúp đẩy nhanh tiến độ làm việc và tuân thủ các kĩ thuật bảo mật tốt nhất.

---
**📌 Lưu ý khi làm việc:**
Mong các thành viên trong cấu trúc công việc của mình (bất kể làm frontend hay backend) tuân thủ đúng vai trò phân tách của mô hình MVC này. Tuyệt đối không nhúng lẫn lộn logic API vào trong file giao diện HTML, cũng như Backend chỉ lo tính toán và trả về chuẩn JSON!

*Chúc team hoàn thành dự án thật tốt đẹp!* 🚀
