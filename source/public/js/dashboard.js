// public/js/dashboard.js
document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/login'; // Nếu không có token, quay về trang login
    }

    const user = JSON.parse(localStorage.getItem('user'));
    if (user) {
        document.getElementById('userInfo').textContent = `Xin chào, ${user.fullName} (${user.role})`;
    }

    document.getElementById('logoutButton').addEventListener('click', () => {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
    });
});