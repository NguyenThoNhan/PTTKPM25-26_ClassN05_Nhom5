// src/services/thesisService.js
const { Thesis, ThesisVersion, User } = require('../models');
const crypto = require('crypto');
const fs = require('fs');

// Hàm tạo mã hash cho file
const generateFileHash = (filePath) => {
    const fileBuffer = fs.readFileSync(filePath);
    const hashSum = crypto.createHash('sha256');
    hashSum.update(fileBuffer);
    return hashSum.digest('hex');
};

// Lấy danh sách luận văn của một sinh viên
exports.getThesesByStudent = async (studentId) => {
    return Thesis.findAll({
        where: { studentId },
        include: [{ model: ThesisVersion, attributes: ['id', 'versionName', 'submissionDate'] }],
        order: [['createdAt', 'DESC']]
    });
};

// Sinh viên tạo một đề tài luận văn mới
exports.createThesis = async (thesisData, studentId) => {
    return Thesis.create({
        ...thesisData,
        studentId,
        status: 'pending_approval' // Trạng thái ban đầu khi mới tạo
    });
};

// Sinh viên nộp một phiên bản luận văn
exports.submitVersion = async (file, thesisId, versionName, studentId) => {
    const thesis = await Thesis.findOne({ where: { id: thesisId, studentId } });
    if (!thesis) {
        throw new Error('Không tìm thấy luận văn hoặc bạn không có quyền truy cập.');
    }

    const fileHash = generateFileHash(file.path);

    return ThesisVersion.create({
        thesisId,
        versionName,
        filePath: file.path, // Lưu đường dẫn tương đối
        fileHash: fileHash,
        submissionDate: new Date()
    });
};