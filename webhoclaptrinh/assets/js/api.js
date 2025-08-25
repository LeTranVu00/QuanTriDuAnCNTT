// API Service - Xử lý tất cả các request đến backend
class APIService {
    constructor() {
        this.baseURL = window.location.origin + '/webhoclaptrinh/';
    }

    // Helper function để xử lý response
    // Helper function để xử lý response
    async handleResponse(response) {
    console.log('Response status:', response.status);
    console.log('Response URL:', response.url);
    
    const text = await response.text();
    console.log('Raw response text:', text);
    
    if (!response.ok) {
        // Try to parse as JSON, else use text
        try {
            const errorData = JSON.parse(text);
            throw new Error(errorData.error || `HTTP ${response.status}: ${response.statusText}`);
        } catch {
            throw new Error(`HTTP ${response.status}: ${text || response.statusText}`);
        }
    }
    
    // Try to parse as JSON, else return text
    try {
        return JSON.parse(text);
    } catch (e) {
        console.warn('Response is not JSON, returning as text');
        return text;
    }
    }

    // GET request
    async get(url, params = {}) {
        try {
            const queryString = Object.keys(params).length 
                ? '?' + new URLSearchParams(params).toString() 
                : '';
            
            const response = await fetch(`${this.baseURL}${url}${queryString}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include'
            });

            return await this.handleResponse(response);
        } catch (error) {
            console.error('GET Request failed:', error);
            throw error;
        }
    }

    // POST request
    async post(url, data = {}) {
    try {
        console.log('🔄 API Request:', `${this.baseURL}${url}`, data);
        
        const response = await fetch(`${this.baseURL}${url}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
            credentials: 'include'
        });

        console.log('✅ API Response status:', response.status);
        const result = await this.handleResponse(response);
        console.log('✅ API Response data:', result);
        return result;
        
    } catch (error) {
        console.error('❌ API Request failed:', error);
        throw error;
    }
}
    // PUT request
    async put(url, data = {}) {
        try {
            const response = await fetch(`${this.baseURL}${url}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
                credentials: 'include'
            });

            return await this.handleResponse(response);
        } catch (error) {
            console.error('PUT Request failed:', error);
            throw error;
        }
    }

    // DELETE request
    async delete(url, data = {}) {
        try {
            const config = {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'include'
            };

            // Nếu có data, gửi trong body
            if (Object.keys(data).length > 0) {
                config.body = JSON.stringify(data);
            }

            const response = await fetch(`${this.baseURL}${url}`, config);
            return await this.handleResponse(response);
        } catch (error) {
            console.error('DELETE Request failed:', error);
            throw error;
        }
    }

    // Upload file
    async upload(url, formData) {
        try {
            const response = await fetch(`${this.baseURL}${url}`, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });

            return await this.handleResponse(response);
        } catch (error) {
            console.error('Upload failed:', error);
            throw error;
        }
    }

    // Helper để thêm authorization header
    getAuthHeaders() {
        const token = localStorage.getItem('auth_token');
        return token ? { 'Authorization': 'Bearer ' + token } : {};
    }
}

// Khởi tạo API service instance
const apiService = new APIService();

// Utility functions cho auth
const AuthUtils = {
    // Lưu thông tin user
    setUser(userData) {
        localStorage.setItem('user_data', JSON.stringify(userData));
        if (userData.token) {
            localStorage.setItem('auth_token', userData.token);
        }
    },

    // Lấy thông tin user
    getUser() {
        const userData = localStorage.getItem('user_data');
        return userData ? JSON.parse(userData) : null;
    },

    // Kiểm tra đăng nhập
    isLoggedIn() {
        return !!this.getUser();
    },

    // Kiểm tra admin
    isAdmin() {
        const user = this.getUser();
        return user && user.role === 'admin';
    },

    // Đăng xuất - SỬA ĐƯỜNG DẪN
    logout() {
        localStorage.removeItem('user_data');
        localStorage.removeItem('auth_token');
        window.location.href = '/webhoclaptrinh/pages/auth/login.html';
    },

    // Redirect based on role - SỬA ĐƯỜNG DẪN
   // Redirect based on role - SỬA LẠI LOGIC
    // Sửa trong api.js
    redirectBasedOnRole() {
    const user = this.getUser();
    if (!user) {
        window.location.href = '/webhoclaptrinh/pages/auth/login.html';
        return;
    }

    // DEBUG: Xem role thực tế trong localStorage
    console.log('🎯 User role trong redirect:', user.role);
    
    // Logic linh hoạt hơn
    const role = user.role.toLowerCase().trim();
    
    if (role === 'admin' || role === 'administrator' || role === 'quản trị') {
        window.location.href = '/webhoclaptrinh/pages/admin/dashboard.html';
    } else if (role === 'user' || role === 'member' || role === 'học viên' || role === 'student') {
        window.location.href = '/webhoclaptrinh/pages/user/dashboard.html';
    } else {
        // Mặc định cho user thường
        window.location.href = '/webhoclaptrinh/pages/user/dashboard.html';
    }
}
};

// Error handler chung
const ErrorHandler = {
    showError(message, elementId = 'message') {
        const messageElement = document.getElementById(elementId);
        if (messageElement) {
            messageElement.innerHTML = `
                <div class="message-error">
                    <strong>Lỗi:</strong> ${message}
                </div>
            `;
            messageElement.scrollIntoView({ behavior: 'smooth' });
        }
    },

    showSuccess(message, elementId = 'message') {
        const messageElement = document.getElementById(elementId);
        if (messageElement) {
            messageElement.innerHTML = `
                <div class="message-success">
                    <strong>Thành công:</strong> ${message}
                </div>
            `;
            messageElement.scrollIntoView({ behavior: 'smooth' });
        }
    },

    clearMessages(elementId = 'message') {
        const messageElement = document.getElementById(elementId);
        if (messageElement) {
            messageElement.innerHTML = '';
        }
    }
};

// Form validation
const FormValidator = {
    validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    validatePassword(password) {
        return password.length >= 6;
    },

    validateRequired(fields) {
        for (const field of fields) {
            if (!field.value.trim()) {
                return false;
            }
        }
        return true;
    }
};