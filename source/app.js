// app.js
require('dotenv').config();
const express = require('express');
const path = require('path');
const authRoutes = require('./src/routes/authRoutes');
const { sequelize } = require('./src/models');
const thesisRoutes = require('./src/routes/thesisRoutes');
const adminRoutes = require('./src/routes/adminRoutes');
const lecturerRoutes = require('./src/routes/lecturerRoutes'); 
const jwt = require('jsonwebtoken');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(express.json()); // Để xử lý JSON body
app.use(express.urlencoded({ extended: true })); // Để xử lý form data
app.use(express.static(path.join(__dirname, 'public'))); // Phục vụ các file tĩnh từ thư mục public

// API Routes
app.use('/api/auth', authRoutes);
app.use('/api/theses', thesisRoutes);
app.use('/api/admin', adminRoutes);
app.use('/api/lecturer', lecturerRoutes);

// Page Serving Routes
app.get('/login', (req, res) => {
    res.sendFile(path.join(__dirname, 'src', 'views', 'login.html'));
});

app.get('/register', (req, res) => {
    res.sendFile(path.join(__dirname, 'src', 'views', 'register.html'));
});

app.get('/dashboard', (req, res) => {
    // Route này không còn sử dụng để hiển thị dashboard trực tiếp
    res.redirect('/login');
});

app.get('/student/dashboard', (req, res) => {
    res.sendFile(path.join(__dirname, 'src', 'views', 'dashboard-student.html'));
});

app.get('/lecturer/dashboard', (req, res) => {
    res.sendFile(path.join(__dirname, 'src', 'views', 'dashboard-lecturer.html'));
});

// Route gốc chuyển hướng đến trang đăng nhập
app.get('/', (req, res) => {
    res.redirect('/login');
});


// Khởi động server
app.listen(PORT, async () => {
    console.log(`Server đang chạy trên cổng ${PORT}`);
    try {
        await sequelize.authenticate();
        console.log('Kết nối cơ sở dữ liệu thành công.');
    } catch (error) {
        console.error('Không thể kết nối đến cơ sở dữ liệu:', error);
    }
});