// src/controllers/authController.js
const { User } = require('../models');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

exports.register = async (req, res) => {
    try {
        const { fullName, email, password } = req.body;

        // Kiểm tra xem email đã tồn tại chưa
        const existingUser = await User.findOne({ where: { email } });
        if (existingUser) {
            return res.status(400).json({ message: 'Email đã tồn tại.' });
        }

        // Mã hóa mật khẩu
        const hashedPassword = await bcrypt.hash(password, 10);

        // Tạo người dùng mới, mặc định là sinh viên
        const newUser = await User.create({
            fullName,
            email,
            password: hashedPassword,
            role: 'student' // Mặc định khi đăng ký là sinh viên
        });

        res.status(201).json({ message: 'Đăng ký thành công!', userId: newUser.id });

    } catch (error) {
        console.error('Lỗi đăng ký:', error);
        res.status(500).json({ message: 'Đã có lỗi xảy ra ở máy chủ.' });
    }
};

exports.login = async (req, res) => {
    try {
        const { email, password } = req.body;

        // Tìm người dùng bằng email
        const user = await User.findOne({ where: { email } });
        if (!user) {
            return res.status(404).json({ message: 'Email hoặc mật khẩu không đúng.' });
        }

        // So sánh mật khẩu
        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(401).json({ message: 'Email hoặc mật khẩu không đúng.' });
        }

        // Tạo JWT token
        const token = jwt.sign(
            { id: user.id, role: user.role },
            process.env.JWT_SECRET,
            { expiresIn: '1h' } // Token hết hạn sau 1 giờ
        );

        res.status(200).json({ 
            message: 'Đăng nhập thành công!',
            token,
            user: {
                id: user.id,
                fullName: user.fullName,
                role: user.role
            }
        });

    } catch (error) {
        console.error('Lỗi đăng nhập:', error);
        res.status(500).json({ message: 'Đã có lỗi xảy ra ở máy chủ.' });
    }
};