// src/middlewares/authMiddleware.js
const jwt = require('jsonwebtoken');

module.exports = (req, res, next) => {
    try {
        const authHeader = req.headers.authorization;
        if (!authHeader) {
            return res.status(401).json({ message: 'Yêu cầu xác thực không hợp lệ.' });
        }

        const token = authHeader.split(' ')[1]; // Bearer TOKEN
        if (!token) {
            return res.status(401).json({ message: 'Không tìm thấy token.' });
        }

        const decodedToken = jwt.verify(token, process.env.JWT_SECRET);
        req.user = { id: decodedToken.id, role: decodedToken.role }; // Gắn thông tin user vào request
        next();

    } catch (error) {
        res.status(401).json({ message: 'Token không hợp lệ hoặc đã hết hạn.' });
    }
};