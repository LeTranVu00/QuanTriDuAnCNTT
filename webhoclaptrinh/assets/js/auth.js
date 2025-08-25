
// Auth Service - Xử lý đăng nhập và đăng ký
class AuthService {
    constructor() {
        this.api = apiService;
        this.init();
    }

    init() {
        // Auto redirect nếu đã đăng nhập
        if (AuthUtils.isLoggedIn()) {
            AuthUtils.redirectBasedOnRole();
        }
    }

// Đăng nhập
async login(email, password) {
    try {
        ErrorHandler.clearMessages();

        // Validation
        if (!FormValidator.validateEmail(email)) {
            throw new Error('Email không hợp lệ');
        }

        if (!FormValidator.validatePassword(password)) {
            throw new Error('Mật khẩu phải có ít nhất 6 ký tự');
        }

        console.log('🔵 Đang gửi request đăng nhập...', { email, password });

        // DEBUG: Log URL đầy đủ
        const fullUrl = API_CONFIG.BASE_URL + 'modules/auth/login.php';
        console.log('🔵 API URL:', fullUrl);

        // Gọi API với debug chi tiết
        const response = await fetch(fullUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        });

        console.log('🔵 Response status:', response.status);
        console.log('🔵 Response headers:', Object.fromEntries(response.headers.entries()));

        const responseText = await response.text();
        console.log('🔵 Raw response text:', responseText);

        // Parse JSON
        let data;
        try {
            data = JSON.parse(responseText);
            console.log('🟢 Parsed JSON data:', data);
        } catch (parseError) {
            console.error('🔴 JSON parse error:', parseError, 'Raw text:', responseText);
            throw new Error('Invalid JSON response from server');
        }

        // Kiểm tra response
        if (data.success && data.user) {
            // DEBUG: Kiểm tra user data từ server
            console.log('Server response user:', data.user);
            console.log('User role from server:', data.user.role);
            console.log('Role type:', typeof data.user.role);
            AuthUtils.setUser(data.user);
            ErrorHandler.showSuccess(data.message || 'Đăng nhập thành công!');
            
            // Redirect sau 1 giây
            setTimeout(() => {
                AuthUtils.redirectBasedOnRole();
            }, 1000);
            
            return data;
        } else {
            throw new Error(data.error || 'Đăng nhập thất bại');
        }

    } catch (error) {
        console.error('🔴 Login error:', error);
        ErrorHandler.showError(error.message || 'Lỗi kết nối server');
        throw error;
    }
}

    // Đăng ký
    async register(fullname, email, password, confirmPassword) {
        try {
            ErrorHandler.clearMessages();

            // Validation
            if (!fullname.trim()) {
                throw new Error('Vui lòng nhập họ tên');
            }

            if (!FormValidator.validateEmail(email)) {
                throw new Error('Email không hợp lệ');
            }

            if (!FormValidator.validatePassword(password)) {
                throw new Error('Mật khẩu phải có ít nhất 6 ký tự');
            }

            if (password !== confirmPassword) {
                throw new Error('Mật khẩu xác nhận không khớp');
            }

            // Gọi API
            const data = await this.api.post('modules/auth/register.php', {
            fullname: fullname,
            email: email,
            password: password,
            role: 'user'
        });

            ErrorHandler.showSuccess('Đăng ký thành công! Vui lòng đăng nhập.');

            // Redirect đến trang login sau 2 giây
            // Redirect đến trang login sau 2 giây
            setTimeout(() => {
                window.location.href = '/webhoclaptrinh/pages/auth/login.html';
            }, 2000);

            return data;

        } catch (error) {
            console.error('Register error:', error);
            ErrorHandler.showError(error.message || 'Đăng ký thất bại');
            throw error;
        }
    }

    // Xử lý form đăng nhập
    handleLoginForm() {
        const form = document.getElementById('loginForm');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const loginText = document.getElementById('loginText');
            const loginLoading = document.getElementById('loginLoading');

            // Hiển thị loading
            if (loginText && loginLoading) {
                loginText.style.display = 'none';
                loginLoading.style.display = 'inline-block';
            }
            submitBtn.disabled = true;

            try {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                await this.login(email, password);
            } catch (error) {
                // Error đã được xử lý trong login()
            } finally {
                // Ẩn loading
                if (loginText && loginLoading) {
                    loginText.style.display = 'inline-block';
                    loginLoading.style.display = 'none';
                }
                submitBtn.disabled = false;
            }
        });
    }

    // Xử lý form đăng ký
    handleRegisterForm() {
        const form = document.getElementById('registerForm');
        if (!form) return;

        // Real-time password validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        
        if (passwordInput && confirmPasswordInput) {
            confirmPasswordInput.addEventListener('input', () => {
                this.validatePasswordMatch();
            });
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const registerText = document.getElementById('registerText');
            const registerLoading = document.getElementById('registerLoading');

            // Hiển thị loading
            if (registerText && registerLoading) {
                registerText.style.display = 'none';
                registerLoading.style.display = 'inline-block';
            }
            submitBtn.disabled = true;

            try {
                const fullname = document.getElementById('fullname').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                
                await this.register(fullname, email, password, confirmPassword);
            } catch (error) {
                // Error đã được xử lý trong register()
            } finally {
                // Ẩn loading
                if (registerText && registerLoading) {
                    registerText.style.display = 'inline-block';
                    registerLoading.style.display = 'none';
                }
                submitBtn.disabled = false;
            }
        });
    }

    // Validate password match
    validatePasswordMatch() {
        const password = document.getElementById('password')?.value;
        const confirmPassword = document.getElementById('confirmPassword')?.value;
        const messageElement = document.getElementById('passwordMatchMessage');

        if (!password || !confirmPassword) return;

        if (password !== confirmPassword) {
            if (!messageElement) {
                const confirmPasswordGroup = document.getElementById('confirmPassword').closest('.form-group');
                if (confirmPasswordGroup) {
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'passwordMatchMessage';
                    errorDiv.className = 'message-error';
                    errorDiv.style.marginTop = '8px';
                    errorDiv.style.fontSize = '14px';
                    errorDiv.innerHTML = 'Mật khẩu xác nhận không khớp';
                    confirmPasswordGroup.appendChild(errorDiv);
                }
            }
        } else if (messageElement) {
            messageElement.remove();
        }
    }
}

// Khởi tạo auth service khi trang load
document.addEventListener('DOMContentLoaded', function() {
    const authService = new AuthService();

    // Kiểm tra xem đang ở trang nào
    if (document.getElementById('loginForm')) {
        authService.handleLoginForm();
    }

    if (document.getElementById('registerForm')) {
        authService.handleRegisterForm();
    }

    // Auto-focus vào input đầu tiên
    const firstInput = document.querySelector('form input');
    if (firstInput) {
        firstInput.focus();
    }
});

// Global logout function
window.logout = function() {
    AuthUtils.logout();
};

// Check auth status on page load
window.checkAuth = function() {
    if (!AuthUtils.isLoggedIn()) {
        window.location.href = '/webhoclaptrinh/pages/auth/login.html';
    }
};

// Check admin权限
window.checkAdmin = function() {
    if (!AuthUtils.isAdmin()) {
        window.location.href = '/webhoclaptrinh/index.html';
    }
};