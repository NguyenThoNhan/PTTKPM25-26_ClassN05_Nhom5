// src/controllers/adminController.js
const { User, Thesis } = require('../models');
const cryptoService = require('../services/cryptoService');

// Tạo tài khoản giảng viên (bao gồm cả tạo khóa)
exports.createLecturer = async (req, res) => {
    // Tạm thời bỏ qua logic mã hóa mật khẩu để tập trung vào khóa
    const { fullName, email, password } = req.body;
    
    const { publicKey, privateKey } = cryptoService.generateKeyPair();
    const encryptedPrivateKey = cryptoService.encrypt(privateKey);

    try {
        const lecturer = await User.create({
            fullName, email, password, // Cần mã hóa password trong thực tế
            role: 'lecturer',
            publicKey: publicKey,
            privateKey: JSON.stringify(encryptedPrivateKey) // Lưu dưới dạng chuỗi JSON
        });
        res.status(201).json({ message: 'Tạo tài khoản giảng viên thành công.', lecturerId: lecturer.id });
    } catch (error) {
        res.status(500).json({ message: 'Lỗi server: ' + error.message });
    }
};

// Phân công giảng viên cho luận văn
exports.assignLecturer = async (req, res) => {
    try {
        const { thesisId } = req.params;
        const { lecturerId } = req.body;

        const thesis = await Thesis.findByPk(thesisId);
        const lecturer = await User.findOne({ where: { id: lecturerId, role: 'lecturer' } });

        if (!thesis || !lecturer) {
            return res.status(404).json({ message: 'Không tìm thấy luận văn hoặc giảng viên.' });
        }

        thesis.lecturerId = lecturerId;
        await thesis.save();

        res.status(200).json({ message: 'Phân công giảng viên thành công.', thesis });
    } catch (error) {
        res.status(500).json({ message: 'Lỗi server: ' + error.message });
    }
};