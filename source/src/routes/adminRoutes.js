// src/routes/adminRoutes.js
const express = require('express');
const router = express.Router();
const adminController = require('../controllers/adminController');
// Cần có middleware để kiểm tra role admin, nhưng tạm thời bỏ qua để đơn giản hóa

// Giả lập API tạo tài khoản GV
router.post('/lecturers', adminController.createLecturer); 
// API phân công
router.post('/theses/:thesisId/assign', adminController.assignLecturer);

module.exports = router;