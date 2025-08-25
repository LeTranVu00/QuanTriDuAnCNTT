// Dashboard functionality
class Dashboard {
    constructor() {
        this.user = AuthUtils.getUser();
        this.init();
    }

    init() {
        if (!this.user) {
            window.location.href = '../../pages/auth/login.html';
            return;
        }

        this.loadUserInfo();
        this.loadDashboardData();
        this.setupEventListeners();
    }

    loadUserInfo() {
        // Hiển thị thông tin user
        document.getElementById('userName').textContent = this.user.fullname || this.user.email;
        document.getElementById('userRole').textContent = this.user.role === 'admin' ? 'Quản trị viên' : 'Học viên';
        
        // Tạo avatar từ tên
        if (this.user.fullname) {
            const initials = this.user.fullname.split(' ').map(n => n[0]).join('').toUpperCase();
            document.getElementById('userAvatar').textContent = initials.substring(0, 2);
        }
    }

    async loadDashboardData() {
        try {
            // Load courses count
            const courses = await apiService.get('modules/courses/list.php');
            document.getElementById('totalCourses').textContent = courses.length;

            // Load user courses
            const userCourses = await apiService.get('modules/user_courses/read.php');
            document.getElementById('myCourses').textContent = userCourses.length;

            // Load recent courses
            this.displayRecentCourses(userCourses.slice(0, 5));

            // Giả lập dữ liệu khác
            document.getElementById('completedLessons').textContent = '12';
            document.getElementById('learningTime').textContent = '24h';

        } catch (error) {
            console.error('Error loading dashboard data:', error);
            ErrorHandler.showError('Không thể tải dữ liệu dashboard');
        }
    }

    displayRecentCourses(courses) {
        const tbody = document.getElementById('recentCourses');
        
        if (courses.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">
                        Bạn chưa đăng ký khóa học nào. 
                        <a href="../courses/index.html" style="color: #2563eb; text-decoration: none;">
                            Khám phá khóa học ngay!
                        </a>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = courses.map(course => `
            <tr>
                <td>
                    <strong>${course.title || course.course_title}</strong>
                    <br>
                    <small class="text-muted">${course.category_name || 'No category'}</small>
                </td>
                <td>
                    <div style="background: #e5e7eb; height: 8px; border-radius: 4px; width: 100%; margin: 5px 0;">
                        <div style="background: #2563eb; height: 100%; border-radius: 4px; width: ${Math.random() * 100}%;"></div>
                    </div>
                    <small>${Math.floor(Math.random() * 100)}% hoàn thành</small>
                </td>
                <td>
                    <span class="badge badge-success">Đang học</span>
                </td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="window.location.href='../courses/detail.html?id=${course.course_id || course.id}'">
                        Tiếp tục học
                    </button>
                </td>
            </tr>
        `).join('');
    }

    setupEventListeners() {
        // Mobile menu toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const sidebar = document.querySelector('.sidebar');
        
        if (mobileMenuBtn && sidebar) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }

        // Click outside to close sidebar on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !mobileMenuBtn.contains(e.target) &&
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });
    }
}

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', function() {
    new Dashboard();
});

// Global logout function
window.logout = function() {
    if (confirm('Bạn có chắc muốn đăng xuất?')) {
        AuthUtils.logout();
    }
};