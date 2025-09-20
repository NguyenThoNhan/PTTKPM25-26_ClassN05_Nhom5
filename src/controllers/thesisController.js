// src/controllers/thesisController.js
const thesisService = require('../services/thesisService');

exports.getAllThesesForStudent = async (req, res) => {
    try {
        const studentId = req.user.id;
        const theses = await thesisService.getThesesByStudent(studentId);
        res.status(200).json(theses);
    } catch (error) {
        res.status(500).json({ message: 'Lỗi máy chủ: ' + error.message });
    }
};

exports.createThesis = async (req, res) => {
    try {
        const studentId = req.user.id;
        const newThesis = await thesisService.createThesis(req.body, studentId);
        res.status(201).json(newThesis);
    } catch (error) {
        res.status(400).json({ message: 'Lỗi tạo luận văn: ' + error.message });
    }
};

exports.submitVersion = async (req, res) => {
    try {
        if (!req.file) {
            return res.status(400).json({ message: 'Vui lòng chọn một file PDF để nộp.' });
        }
        const { thesisId } = req.params;
        const { versionName } = req.body;
        const studentId = req.user.id;

        const newVersion = await thesisService.submitVersion(req.file, thesisId, versionName, studentId);
        res.status(201).json({ message: 'Nộp phiên bản thành công!', version: newVersion });
    } catch (error) {
        res.status(400).json({ message: 'Lỗi nộp bài: ' + error.message });
    }
};