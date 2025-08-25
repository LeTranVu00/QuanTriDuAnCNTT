// Cấu hình API endpoints
const API_CONFIG = {
    BASE_URL: 'http://localhost:8000/',
    ENDPOINTS: {
        AUTH: {
            LOGIN: 'modules/auth/login.php',
            REGISTER: 'modules/auth/register.php'
        },
        USERS: {
            PROFILE: 'modules/users/get_user.php',
            LIST: 'modules/users/get_users.php'
        },
        COURSES: {
            LIST: 'modules/courses/list.php',
            DETAIL: 'modules/courses/get.php'
        }
    }
};

// Storage keys
const STORAGE_KEYS = {
    AUTH_TOKEN: 'auth_token',
    USER_DATA: 'user_data'
};

// User roles
const USER_ROLES = {
    ADMIN: 'admin',
    USER: 'user',
    GUEST: 'guest'
};
