// src/routes/lecturerRoutes.js
const express = require('express');
const router = express.Router();
const lecturerController = require('../controllers/lecturerController');
const authMiddleware = require('../middlewares/authMiddleware');
// Cần middleware kiểm tra role giảng viên

router.use(authMiddleware);

// Lấy danh sách luận văn đã được phân công
router.get('/theses', lecturerController.getAssignedTheses);
// Phê duyệt và ký số một phiên bản đề cương
router.post('/versions/:versionId/approve', lecturerController.approveThesisVersion);

module.exports = router;