// public/js/auth.js
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    // Xử lý thông báo
    const errorMessageDiv = document.getElementById('errorMessage');
    const successMessageDiv = document.getElementById('successMessage');

    const showMessage = (element, message, isError = true) => {
        if (element) {
            element.textContent = message;
            element.className = isError ? 'error-message' : 'success-message';
            element.style.display = 'block';
        }
    };

    const hideMessages = () => {
        if (errorMessageDiv) errorMessageDiv.style.display = 'none';
        if (successMessageDiv) successMessageDiv.style.display = 'none';
    };

    // Xử lý Form Đăng nhập
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideMessages();
            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Đã có lỗi xảy ra.');
                }

                // Lưu token và thông tin người dùng vào localStorage
                localStorage.setItem('token', result.token);
                localStorage.setItem('user', JSON.stringify(result.user));

                // <<< PHẦN CẬP NHẬT QUAN TRỌNG >>>
                // Dựa vào vai trò (role) để chuyển hướng đến đúng dashboard
                switch (result.user.role) {
                    case 'student':
                        window.location.href = '/student/dashboard';
                        break;
                    case 'lecturer':
                        window.location.href = '/lecturer/dashboard';
                        break;
                    case 'admin':
                        // Sẽ thêm '/admin/dashboard' ở giai đoạn sau
                        alert('Chức năng cho Admin sẽ được phát triển!');
                        break;
                    default:
                        window.location.href = '/login';
                }

            } catch (error) {
                showMessage(errorMessageDiv, error.message);
            }
        });
    }

    // Xử lý Form Đăng ký
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideMessages();
            const formData = new FormData(registerForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Đã có lỗi xảy ra.');
                }
                
                showMessage(successMessageDiv, 'Đăng ký thành công! Đang chuyển đến trang đăng nhập...', false);
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);

            } catch (error) {
                showMessage(errorMessageDiv, error.message);
            }
        });
    }
});