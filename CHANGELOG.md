# 📖 Changelog

Tất cả thay đổi quan trọng của **BookHaven** sẽ được ghi lại trong file này.

Định dạng dựa theo [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)  
Và phiên bản theo [Semantic Versioning](https://semver.org/spec/v2.0.0.html)

---

## [1.3.0] - 2025-09-24
### ✅ Hoàn thành Giai Đoạn 3: Modern Features
- Thêm **QR Code System** (tạo & quét QR code, mượn/trả nhanh).
- Tích hợp **PWA**: cài đặt như app, hỗ trợ offline reading, service worker, sync dữ liệu.
- Hoàn tất full responsive design và animations hiện đại.
- Production Ready: deploy với logging, monitoring, performance optimization.

---

## [1.2.0] - 2025-08-15
### ✅ Hoàn thành Giai Đoạn 2: Social & Notifications
- Thêm **Hệ thống thông báo** (8 loại, email + in-app, user preferences).
- Tích hợp **Social features**:
  - User profiles, social feed.
  - Reading groups, discussions, replies, likes.
- Console command `notifications:process` để xử lý thông báo tự động.

---

## [1.1.0] - 2025-07-10
### ✅ Hoàn thành Giai Đoạn 1: Core Features
- Triển khai **Book Review & Rating System** (5 sao, review text & images, helpful votes).
- **Gamification System**: XP, level, badges, reading streaks, daily challenges, leaderboard.
- Cải thiện UI/UX với animations, responsive design.
- Thêm 2 bảng mới (`reviews`, `notifications`) và 2 cột mới (`qr_code`, `qr_code_path` trong `books`).

---

## [1.0.0] - 2025-06-01
### 🎉 Phiên bản đầu tiên
- Hoàn thành hệ thống quản lý thư viện cơ bản:
  - CRUD sách, danh mục, người dùng, sự kiện.
  - Quản lý mượn/trả (có chữ ký số RSA-SHA256).
  - Authentication (Laravel Breeze + Sanctum).
  - Phân quyền Admin/User.
- Database khởi tạo với 11 bảng core.
- UI/UX cơ bản với Tailwind CSS & Blade Templates.

---

## 🚀 Roadmap
- [ ] **Phase 4**: AI-powered recommendations, advanced analytics, multi-language, mobile API, advanced search.
- [ ] **Phase 5**: Multi-tenant, advanced reporting, external integrations, enterprise security & optimization.
