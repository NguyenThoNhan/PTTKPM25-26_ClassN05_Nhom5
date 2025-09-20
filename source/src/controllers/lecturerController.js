// src/controllers/lecturerController.js
const { Thesis, ThesisVersion, User, Approval } = require('../models');
const cryptoService = require('../services/cryptoService');

exports.getAssignedTheses = async (req, res) => {
    try {
        const theses = await Thesis.findAll({
            where: { lecturerId: req.user.id },
            include: [
                { 
                    model: User, 
                    as: 'Student', 
                    attributes: ['fullName', 'email'] 
                },
                {
                    model: ThesisVersion,
                    include: [{ model: Approval }]
                }
            ],
            order: [['createdAt', 'DESC']]
        });
        res.status(200).json(theses);
    } catch (error) {
        res.status(500).json({ message: 'Lỗi server: ' + error.message });
    }
};

exports.approveThesisVersion = async (req, res) => {
    const { versionId } = req.params;
    const lecturerId = req.user.id;
    try {
        const version = await ThesisVersion.findByPk(versionId, { include: Thesis });
        if (!version) return res.status(404).json({ message: 'Không tìm thấy phiên bản luận văn.' });

        // Kiểm tra giảng viên này có được phân công cho luận văn này không
        if (version.Thesi.lecturerId !== lecturerId) {
            return res.status(403).json({ message: 'Bạn không có quyền phê duyệt luận văn này.' });
        }

        // Lấy khóa bí mật của giảng viên
        const lecturer = await User.findByPk(lecturerId);
        const decryptedPrivateKey = cryptoService.decrypt(JSON.parse(lecturer.privateKey));
        
        // Ký lên "dấu vân tay" của file
        const signature = cryptoService.sign(version.fileHash, decryptedPrivateKey);

        // Lưu chữ ký vào bảng Approvals
        await Approval.create({
            thesisVersionId: versionId,
            lecturerId: lecturerId,
            signature: signature
        });

        // Cập nhật trạng thái luận văn
        version.Thesi.status = 'approved';
        await version.Thesi.save();

        res.status(200).json({ message: 'Phê duyệt đề cương thành công!' });
    } catch (error) {
        res.status(500).json({ message: 'Lỗi server: ' + error.message });
    }
};