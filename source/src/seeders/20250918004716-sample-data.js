'use strict';
const bcrypt = require('bcryptjs');
const crypto = require('crypto');
const cryptoService = require('../services/cryptoService'); // Import service của chúng ta

module.exports = {
  up: async (queryInterface, Sequelize) => {
    // ---- 1. TẠO NGƯỜI DÙNG ----
    const hashedPassword = await bcrypt.hash('password123', 10);
    
    // Tạo khóa cho giảng viên
    const { publicKey, privateKey } = cryptoService.generateKeyPair();
    const encryptedPrivateKey = cryptoService.encrypt(privateKey);

    const users = await queryInterface.bulkInsert('Users', [
      {
        fullName: 'Nguyễn Văn An',
        email: 'student@edusign.com',
        password: hashedPassword,
        role: 'student',
        createdAt: new Date(),
        updatedAt: new Date()
      },
      {
        fullName: 'Trần Thị Bích',
        email: 'lecturer@edusign.com',
        password: hashedPassword,
        role: 'lecturer',
        publicKey: publicKey,
        privateKey: JSON.stringify(encryptedPrivateKey), // Lưu dưới dạng chuỗi JSON
        createdAt: new Date(),
        updatedAt: new Date()
      },
      {
        fullName: 'Admin Quản Trị',
        email: 'admin@edusign.com',
        password: hashedPassword,
        role: 'admin',
        createdAt: new Date(),
        updatedAt: new Date()
      }
    ], { returning: true }); // returning: true để lấy lại ID đã tạo

    const studentId = users[0].id;
    const lecturerId = users[1].id;

    // ---- 2. TẠO LUẬN VĂN ----
    const theses = await queryInterface.bulkInsert('Theses', [
      {
        title: 'Xây dựng hệ thống EduSign quản lý luận văn',
        description: 'Nghiên cứu và áp dụng chữ ký số vào quy trình phê duyệt luận văn tốt nghiệp.',
        status: 'pending_approval', // Trạng thái chờ duyệt
        studentId: studentId,
        lecturerId: lecturerId, // Gán luôn giảng viên
        createdAt: new Date(),
        updatedAt: new Date()
      }
    ], { returning: true });

    const thesisId = theses[0].id;

    // ---- 3. TẠO PHIÊN BẢN ĐỀ CƯƠNG ----
    // Giả lập hash của một file
    const sampleFileContent = 'Nội dung file đề cương mẫu';
    const fileHash = crypto.createHash('sha256').update(sampleFileContent).digest('hex');

    await queryInterface.bulkInsert('ThesisVersions', [
      {
        thesisId: thesisId,
        filePath: 'public/uploads/theses/sample-de-cuong.pdf', // Đường dẫn giả lập
        versionName: 'Đề cương sơ bộ',
        fileHash: fileHash,
        submissionDate: new Date(),
        createdAt: new Date(),
        updatedAt: new Date()
      }
    ]);
  },

  down: async (queryInterface, Sequelize) => {
    // Xóa theo thứ tự ngược lại để tránh lỗi khóa ngoại
    await queryInterface.bulkDelete('ThesisVersions', null, {});
    await queryInterface.bulkDelete('Theses', null, {});
    await queryInterface.bulkDelete('Users', null, {});
    await queryInterface.bulkDelete('Approvals', null, {});
  }
};