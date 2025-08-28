<p align="center">
  <a href="https://github.com" target="_blank">
    <img src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png" width="150" alt="GitHub Logo">
  </a>
</p>

<p align="center">
  <!-- Stars -->
  <a href="https://github.com/NguyenThoNhan/PTTKPM25-26_ClassNx_Nhom5/stargazers">
    <img src="https://img.shields.io/github/stars/your-username/your-repo" alt="GitHub Stars">
  </a>
  
  <!-- Forks -->
  <a href="https://github.com/your-username/your-repo/fork">
    <img src="https://img.shields.io/github/forks/your-username/your-repo" alt="GitHub Forks">
  </a>
  
  <!-- Issues -->
  <a href="https://github.com/your-username/your-repo/issues">
    <img src="https://img.shields.io/github/issues/your-username/your-repo" alt="GitHub Issues">
  </a>
  
  <!-- License -->
  <a href="https://github.com/your-username/your-repo/blob/main/LICENSE">
    <img src="https://img.shields.io/github/license/your-username/your-repo" alt="License">
  </a>
</p>

# ✍️ Project EduSign: Hệ Thống Quản Lý & Phê Duyệt Luận Văn Tốt Nghiệp

Một dự án ứng dụng web được xây dựng với **Node.js (Express.js)** và **MySQL**, mô phỏng một hệ sinh thái quản lý học thuật toàn diện. Dự án áp dụng các công nghệ hiện đại như **Chữ ký số** để đảm bảo tính pháp lý và **Xác thực Sinh trắc học** để nâng cao bảo mật.

| **Môn học:** | **Phân tích và Thiết kế Phần mềm** |
| :--- | :--- |
| **Nhóm:** | **05** |
| **Giảng viên Hướng dẫn:** | ThS. Vũ Quang Dũng |
| **Giảng viên Hướng dẫn:** | Ths. Nguyễn Xuân Quế |

### **Thành viên Nhóm (Team Members)**

| **STT** | **Họ và Tên** | **Mã Sinh Viên** | **Vai trò (Role)** |
| :---: | :--- | :---: | :--- |
| 1 | Nguyễn Thọ Nhân | 23010786 | Team Leader, Backend Developer |
| 2 | Nguyễn Xuân Chức| 23010452| Frontend Developer, UI/UX Designer |
| 3 | Phạm Anh Thái | 23010784 | Business Analyst, Tester |
| 4 | Lê Tuấn Anh | 21011577 | Database Engineer, DevOps |
---

## 🚀 Giới Thiệu Dự Án (Project Introduction)

**EduSign** không chỉ là một hệ thống nộp bài và chấm điểm đơn thuần. Dự án này được phát triển nhằm giải quyết các bài toán cốt lõi trong quy trình học thuật, đặc biệt là vấn đề **đảm bảo tính toàn vẹn, tính xác thực và chống chối bỏ** trong các khâu phê duyệt quan trọng. Bằng việc áp dụng thuật toán chữ ký số sử dụng cặp khóa Bất đối xứng (Public/Private Key), hệ thống có khả năng xác minh rằng các quyết định phê duyệt và điểm số của giảng viên là không thể bị thay đổi và được xác thực một cách chính xác.

Bên cạnh đó, dự án tập trung vào việc xây dựng một trải nghiệm người dùng liền mạch và an toàn. **EduSign** tích hợp phương thức xác thực sinh trắc học hiện đại (WebAuthn API), mang lại một lớp bảo mật mạnh mẽ nhưng vẫn thân thiện với người dùng. Với các tính năng hỗ trợ như kênh thảo luận trực tiếp, thư viện tham khảo và công cụ gợi ý đề tài bằng AI, EduSign hướng tới mục tiêu trở thành một nền tảng quản lý luận văn toàn diện, minh bạch và hiệu quả.

---
## 📋 Tài liệu Mô tả Yêu cầu Hệ thống (System Requirements Document - SRD)
### I. Yêu cầu Chức năng (Functional Requirements)
👤 **Account & Security Management**
- Đăng ký/nhập đa vai trò (Student, Lecturer, Admin), hỗ trợ xác thực sinh trắc học.
- Quản lý hồ sơ cá nhân và tự động tạo/bảo vệ cặp khóa (Public/Private Key) cho việc ký số.
✍️ **Core Thesis Workflow**
- Quy trình nộp luận văn nhiều giai đoạn (đề cương, bản thảo, bản cuối).
- Giảng viên nhận xét, phê duyệt và chấm điểm bằng Chữ ký số để đảm bảo tính pháp lý và toàn vẹn.
- Theo dõi trạng thái và lịch sử phê duyệt của luận văn một cách minh bạch.
💬 **Interaction & Support Features**
- Kênh thảo luận riêng cho từng dự án, thư viện tham khảo với bộ lọc nâng cao.
- Hỗ trợ sinh viên bằng công cụ gợi ý đề tài (AI) và các mẫu luận văn (templates) có sẵn.
👑 **Administration & Reporting**
- Dashboard trực quan hiển thị các số liệu thống kê quan trọng cho Admin.
- Quản lý toàn diện (CRUD) tài khoản người dùng và các luận văn trong thư viện.
### II. Yêu cầu Phi chức năng (Non-Functional Requirements)
- 🔒 **Security**: Mã hóa toàn diện từ dữ liệu truyền tải (HTTPS) đến lưu trữ (hashed passwords, encrypted keys).
- ⚡ **Performance**: Phản hồi nhanh (<2s) và xử lý hiệu quả các tệp tin lớn.
- ✨ **Usability**: Giao diện trực quan, thân thiện và tương thích trên nhiều thiết bị (responsive).
- 🛡️ **Data Integrity**: Đảm bảo tính toàn vẹn của file gốc và các quyết định phê duyệt qua hashing và chữ ký số

---
## 👥 Actors & Use Cases
Actors (Các Tác nhân)
- 🎓 Sinh viên (Student): Người dùng chính, thực hiện luận văn và tương tác với giảng viên.
- 👨‍🏫 Giảng viên (Lecturer): Người hướng dẫn, phản biện, có trách nhiệm nhận xét, phê duyệt và chấm điểm luận văn.
- 👑 Admin (Administrator): Quản trị viên hệ thống (ví dụ: Trưởng bộ môn), có quyền quản lý toàn bộ người dùng, dữ liệu và quy trình.
## Use Case List
### 🎓 For Students:
- 📝 Nộp đề cương luận văn (Submit Proposal)
- 📂 Nộp các bản thảo (Submit Drafts)
- 📈 Theo dõi trạng thái (Track Thesis Status)
- 💬 Thảo luận với Giảng viên (Discuss with Lecturer)
- 🔍 Tìm kiếm luận văn tham khảo (Search in Library)
- 🤖 Nhận gợi ý đề tài từ AI (Get AI Suggestions)
- 📄 Tải mẫu luận văn (Download Templates)
### 👨‍🏫 For Lecturers:
- 📋 Xem danh sách sinh viên (View Assigned Students)
- 📥 Tải và xem luận văn (Download & View Thesis)
- 🗣️ Gửi nhận xét (Provide Feedback)
- ✍️ Ký số phê duyệt đề cương (Digitally Sign Proposal Approval)
- 💯 Ký số chấm điểm cuối cùng (Digitally Sign Final Grade)
### 👑 For Admins:
- 📊 Xem Dashboard báo cáo (View Dashboard)
- 👤 Quản lý người dùng (Manage Users - CRUD)
- 🔄 Phân công giảng viên (Assign Lecturers)
- 📚 Quản lý thư viện luận văn (Manage Library - CRUD)
- 📜 Theo dõi lịch sử hệ thống (View Audit Trail)

## 🛠️ Công nghệ Sử dụng (Technology Stack)
![alt text](https://img.shields.io/badge/Node.js-339933?style=for-the-badge&logo=nodedotjs&logoColor=white)
![alt text](https://img.shields.io/badge/Express.js-000000?style=for-the-badge&logo=express&logoColor=white)
![alt text](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![alt text](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![alt text](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![alt text](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
