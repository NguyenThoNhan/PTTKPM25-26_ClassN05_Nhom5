// src/routes/thesisRoutes.js
const express = require('express');
const router = express.Router();
const thesisController = require('../controllers/thesisController');
const authMiddleware = require('../middlewares/authMiddleware');
const upload = require('../middlewares/uploadMiddleware');

// Tất cả các route trong file này đều yêu cầu xác thực
router.use(authMiddleware);

// Lấy tất cả luận văn của sinh viên đang đăng nhập
router.get('/', thesisController.getAllThesesForStudent);

// Sinh viên tạo một đề tài luận văn mới
router.post('/', thesisController.createThesis);

// Sinh viên nộp một phiên bản file cho luận văn (ví dụ: /api/theses/1/versions)
router.post(
    '/:thesisId/versions', 
    upload.single('thesisFile'), // 'thesisFile' là tên của field trong form-data
    thesisController.submitVersion
);

module.exports = router;