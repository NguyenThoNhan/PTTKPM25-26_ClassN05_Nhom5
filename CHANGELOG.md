
# 📜 CHANGELOG - EduSign Project

## 📅 Tuần 1: Phân tích Yêu cầu - Xác định Use Case và Actor
### 🎯 Mục tiêu
- Hiểu rõ hệ thống và xác định các yêu cầu cơ bản của EduSign.  

### 📝 Công việc đã thực hiện
1. 🔍 Phân tích đề bài:
   - Xác định **Actor**:  
     - 🎓 Sinh viên (Student)  
     - 👨‍🏫 Giảng viên (Lecturer)  
     - 👑 Quản trị viên (Admin)  
     - 🖥️ Hệ thống (EduSign Platform)  
   - Xác định **Use Case ban đầu**:
     - 🎓 Sinh viên: Nộp đề cương, Nộp bản thảo, Theo dõi trạng thái, Thảo luận với giảng viên, Tìm luận văn tham khảo, Nhận gợi ý AI, Tải template.  
     - 👨‍🏫 Giảng viên: Xem danh sách sinh viên, Tải & xem luận văn, Gửi nhận xét, Ký số phê duyệt đề cương, Ký số chấm điểm cuối.  
     - 👑 Admin: Quản lý người dùng (CRUD), Quản lý thư viện luận văn, Phân công giảng viên, Xem báo cáo Dashboard, Theo dõi audit log.  
2. 📄 Viết tài liệu yêu cầu:
   - ✅ Yêu cầu chức năng (FR): Hệ thống phải cho phép nộp luận văn nhiều giai đoạn, phê duyệt bằng chữ ký số, thảo luận trực tuyến.  
   - 🔐 Yêu cầu phi chức năng (NFR): Đảm bảo bảo mật (HTTPS, mã hóa, chữ ký số), hiệu năng (<2s phản hồi), tính toàn vẹn dữ liệu, giao diện thân thiện.  

### 📦 Sản phẩm cuối tuần
- Tài liệu mô tả yêu cầu hệ thống (FR & NFR).  
- Danh sách Actor và Use Case ban đầu.  

---

## 📅 Tuần 2: Mô hình hóa Use Case và Kịch bản
### 🎯 Mục tiêu
- Trực quan hóa các Use Case và mô tả chi tiết luồng hoạt động trong EduSign.  

### 📝 Công việc đã thực hiện
1. 🎨 Vẽ Biểu đồ Use Case:
   - Biểu diễn các Actor (Sinh viên, Giảng viên, Admin).  
   - Thể hiện quan hệ giữa Actor và Use Case (nộp luận văn, ký số phê duyệt, tìm kiếm thư viện…).  
   - Sử dụng <<include>> và <<extend>> khi cần (VD: "Nộp bản thảo" <<include>> "Xác minh chữ ký số").  
2. 📖 Viết kịch bản (Scenario):
   - **Primary Scenario**:  
     - Sinh viên nộp luận văn → Hệ thống xác thực → Giảng viên xem → Giảng viên ký số phê duyệt.  
   - **Alternative Scenario**:  
     - Sinh viên nộp file không hợp lệ → Hệ thống từ chối và gửi thông báo lỗi.  
     - Giảng viên từ chối phê duyệt → Hệ thống ghi nhận lý do và trả lại cho sinh viên chỉnh sửa.  

### 📦 Sản phẩm cuối tuần
- File Biểu đồ Use Case (EduSign Use Case Diagram).  
- Tài liệu mô tả kịch bản chi tiết cho các Use Case chính (Submit Thesis, Approve Proposal, Sign Final Grade).  

---

## 📅 Tuần 3: Thiết kế Lớp và Tạo cơ sở code
### 🎯 Mục tiêu
- Thiết kế cấu trúc dữ liệu và các đối tượng chính của EduSign.  

### 📝 Công việc đã thực hiện
1. 📊 Thiết kế Biểu đồ Lớp:
   - Các lớp chính:  
     - `SinhVien` (maSV, hoTen, email, khoa…)  
     - `GiangVien` (maGV, hoTen, boMon…)  
     - `LuanVan` (maLV, tieuDe, trangThai, fileUpload…)  
     - `PhienPhanBien` (ngay, giangVien, nhanXet…)  
     - `ChuKySo` (publicKey, privateKey, signature)  
     - `Admin` (maAdmin, quyenHan).  
   - Quan hệ:  
     - SinhVien **nộp** LuanVan.  
     - GiangVien **phê duyệt & chấm điểm** LuanVan.  
     - Admin **quản lý** SinhVien, GiangVien, LuanVan.  
2. 💻 Lập trình cơ bản:
   - Tạo các file lớp trong Java/C#:  
     - `SinhVien.java`, `GiangVien.java`, `LuanVan.java`, `ChuKySo.java`, `Admin.java`.  
   - Khai báo thuộc tính và phương thức rỗng (skeleton code).  

### 📦 Sản phẩm cuối tuần
- File Biểu đồ Lớp UML (EduSign Class Diagram).  
- Bộ mã nguồn cơ bản (.java hoặc .cs) cho các lớp chính.  

# 📜 CHANGELOG - EduSign Project

## 📅 Tuần 1: Phân tích Yêu cầu - Xác định Use Case và Actor
### 🎯 Mục tiêu
- Hiểu rõ hệ thống và xác định các yêu cầu cơ bản của EduSign.  

### 📝 Công việc đã thực hiện
1. 🔍 Phân tích đề bài:
   - Xác định **Actor**:  
     - 🎓 Sinh viên (Student)  
     - 👨‍🏫 Giảng viên (Lecturer)  
     - 👑 Quản trị viên (Admin)  
     - 🖥️ Hệ thống (EduSign Platform)  
   - Xác định **Use Case ban đầu**:
     - 🎓 Sinh viên: Nộp đề cương, Nộp bản thảo, Theo dõi trạng thái, Thảo luận với giảng viên, Tìm luận văn tham khảo, Nhận gợi ý AI, Tải template.  
     - 👨‍🏫 Giảng viên: Xem danh sách sinh viên, Tải & xem luận văn, Gửi nhận xét, Ký số phê duyệt đề cương, Ký số chấm điểm cuối.  
     - 👑 Admin: Quản lý người dùng (CRUD), Quản lý thư viện luận văn, Phân công giảng viên, Xem báo cáo Dashboard, Theo dõi audit log.  
2. 📄 Viết tài liệu yêu cầu:
   - ✅ Yêu cầu chức năng (FR): Hệ thống phải cho phép nộp luận văn nhiều giai đoạn, phê duyệt bằng chữ ký số, thảo luận trực tuyến.  
   - 🔐 Yêu cầu phi chức năng (NFR): Đảm bảo bảo mật (HTTPS, mã hóa, chữ ký số), hiệu năng (<2s phản hồi), tính toàn vẹn dữ liệu, giao diện thân thiện.  

### 📦 Sản phẩm cuối tuần
- Tài liệu mô tả yêu cầu hệ thống (FR & NFR).  
- Danh sách Actor và Use Case ban đầu.  

---

## 📅 Tuần 2: Mô hình hóa Use Case và Kịch bản
### 🎯 Mục tiêu
- Trực quan hóa các Use Case và mô tả chi tiết luồng hoạt động trong EduSign.  

### 📝 Công việc đã thực hiện
1. 🎨 Vẽ Biểu đồ Use Case:
   - Biểu diễn các Actor (Sinh viên, Giảng viên, Admin).  
   - Thể hiện quan hệ giữa Actor và Use Case (nộp luận văn, ký số phê duyệt, tìm kiếm thư viện…).  
   - Sử dụng <<include>> và <<extend>> khi cần (VD: "Nộp bản thảo" <<include>> "Xác minh chữ ký số").  
2. 📖 Viết kịch bản (Scenario):
   - **Primary Scenario**:  
     - Sinh viên nộp luận văn → Hệ thống xác thực → Giảng viên xem → Giảng viên ký số phê duyệt.  
   - **Alternative Scenario**:  
     - Sinh viên nộp file không hợp lệ → Hệ thống từ chối và gửi thông báo lỗi.  
     - Giảng viên từ chối phê duyệt → Hệ thống ghi nhận lý do và trả lại cho sinh viên chỉnh sửa.  

### 📦 Sản phẩm cuối tuần
- File Biểu đồ Use Case (EduSign Use Case Diagram).  
- Tài liệu mô tả kịch bản chi tiết cho các Use Case chính (Submit Thesis, Approve Proposal, Sign Final Grade).  

---

## 📅 Tuần 3: Thiết kế Lớp và Tạo cơ sở code
### 🎯 Mục tiêu
- Thiết kế cấu trúc dữ liệu và các đối tượng chính của EduSign.  

### 📝 Công việc đã thực hiện
1. 📊 Thiết kế Biểu đồ Lớp:
   - Các lớp chính:  
     - `SinhVien` (maSV, hoTen, email, khoa…)  
     - `GiangVien` (maGV, hoTen, boMon…)  
     - `LuanVan` (maLV, tieuDe, trangThai, fileUpload…)  
     - `PhienPhanBien` (ngay, giangVien, nhanXet…)  
     - `ChuKySo` (publicKey, privateKey, signature)  
     - `Admin` (maAdmin, quyenHan).  
   - Quan hệ:  
     - SinhVien **nộp** LuanVan.  
     - GiangVien **phê duyệt & chấm điểm** LuanVan.  
     - Admin **quản lý** SinhVien, GiangVien, LuanVan.  
2. 💻 Lập trình cơ bản:
   - Tạo các file lớp trong Java/C#:  
     - `SinhVien.java`, `GiangVien.java`, `LuanVan.java`, `ChuKySo.java`, `Admin.java`.  
   - Khai báo thuộc tính và phương thức rỗng (skeleton code).  

### 📦 Sản phẩm cuối tuần
- File Biểu đồ Lớp UML (EduSign Class Diagram).  
- Bộ mã nguồn cơ bản (.java hoặc .cs) cho các lớp chính.  

