// ===== DỮ LIỆU GIẢ LẬP =====
const blogPosts = [
  {
    title: 'Thành tích học viên tại 28Tech năm 2025',
    desc: 'Bài viết này ghi nhận lại những thành tích của học viên trong nửa cuối năm 2025...',
    date: '08/02/2025',
    image: 'https://via.placeholder.com/400x200',
    category: 'Blogs'
  },
  {
    title: 'Học lập trình Java từ con số 0',
    desc: 'Khóa học Java cơ bản dành cho người mới bắt đầu...',
    date: '10/06/2025',
    image: 'https://via.placeholder.com/400x200',
    category: 'Java'
  },
  {
    title: 'Tự học VueJS trong 7 ngày',
    desc: 'Tìm hiểu VueJS một cách bài bản qua các ví dụ dễ hiểu...',
    date: '01/05/2025',
    image: 'https://via.placeholder.com/400x200',
    category: 'VueJS'
  },
  {
    title: 'Giới thiệu cấu trúc dữ liệu cây',
    desc: 'Bài học về cây nhị phân, cây tổng quát trong giải thuật...',
    date: '22/04/2025',
    image: 'https://via.placeholder.com/400x200',
    category: 'CTDL & Giải Thuật'
  }
];
// Gộp dữ liệu người dùng đã thêm
let userPosts = JSON.parse(localStorage.getItem('userCourses') || '[]');

// Nếu chưa có dữ liệu nào thì thêm 5 khóa học mẫu
if (userPosts.length === 0) {
  userPosts = [
    {
      title: 'Lập trình C cơ bản',
      desc: 'Khóa học giúp bạn bắt đầu với ngôn ngữ lập trình C từ những kiến thức nền tảng nhất.',
      image: 'https://via.placeholder.com/400x200',
      date: '15/07/2025',
      category: 'C'
    },
    {
      title: 'Lập trình hướng đối tượng với C++',
      desc: 'Tìm hiểu về class, object, kế thừa, đa hình trong C++ một cách trực quan.',
      image: 'https://via.placeholder.com/400x200',
      date: '16/07/2025',
      category: 'C++'
    },
    {
      title: 'Java nâng cao: JDBC & Spring Boot',
      desc: 'Kết nối cơ sở dữ liệu và xây dựng ứng dụng web hiện đại với Java Spring Boot.',
      image: 'https://via.placeholder.com/400x200',
      date: '18/07/2025',
      category: 'Java'
    },
    {
      title: 'VueJS thực chiến',
      desc: 'Xây dựng ứng dụng web thực tế với VueJS 3 Composition API.',
      image: 'https://via.placeholder.com/400x200',
      date: '20/07/2025',
      category: 'VueJS'
    },
    {
      title: 'CTDL: Danh sách liên kết & Stack',
      desc: 'Hiểu rõ cách hoạt động của các cấu trúc dữ liệu nền tảng trong lập trình.',
      image: 'https://via.placeholder.com/400x200',
      date: '21/07/2025',
      category: 'CTDL & Giải Thuật'
    }
  ];
  localStorage.setItem('userCourses', JSON.stringify(userPosts));
}

blogPosts.push(...userPosts);

// ===== RENDER BLOG & DANH MỤC =====
const blogListEl = document.getElementById('blog-list');
const sidebarEl = document.getElementById('sidebar');

function renderBlogs(categoryFilter = null) {
  blogListEl.innerHTML = '';
  blogPosts.forEach(post => {
    if (!categoryFilter || post.category === categoryFilter) {
      const article = document.createElement('article');
      article.className = 'blog-card';
      const index = blogPosts.indexOf(post);
      article.innerHTML = `
        <img src="${post.image}" alt="${post.title}">
        <div class="blog-content">
          <span class="badge">${post.category}</span>
          <h3>${post.title}</h3>
          <p>${post.desc}</p>
          <span class="date">${post.date}</span>
          <button onclick="window.location.href='course.html?id=${index}'">Xem chi tiết</button>
        </div>
      `;
      blogListEl.appendChild(article);
    }
  });
}


function renderSidebar() {
  const categories = [...new Set(blogPosts.map(p => p.category))];
  sidebarEl.innerHTML = '';
  categories.forEach(cat => {
    const div = document.createElement('div');
    div.className = 'category-card';
    div.textContent = cat;
    div.addEventListener('click', () => renderBlogs(cat));
    sidebarEl.appendChild(div);
  });

  // Thêm nút "Tất cả"
  const allBtn = document.createElement('div');
  allBtn.className = 'category-card';
  allBtn.textContent = 'Tất cả';
  allBtn.style.fontWeight = 'bold';
  allBtn.style.backgroundColor = '#eee';
  allBtn.addEventListener('click', () => renderBlogs(null));
  sidebarEl.prepend(allBtn);
}

// ===== TÌM KIẾM =====
const searchInput = document.getElementById('search-input');
if (searchInput) {
  searchInput.addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    const blogCards = document.querySelectorAll('.blog-card');
    blogCards.forEach(card => {
      const text = card.innerText.toLowerCase();
      card.style.display = text.includes(keyword) ? 'block' : 'none';
    });
  });
}

// ===== ĐĂNG NHẬP & MUA KHÓA HỌC =====
window.addEventListener('DOMContentLoaded', () => {
  renderSidebar();
  renderBlogs();

  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
  const userArea = document.getElementById('user-area');

  if (userArea) {
    if (isLoggedIn) {
      userArea.innerHTML = `
        <span style="color: #fff; margin-right: 10px">👤 Đã đăng nhập</span>
        <a href="#" id="logout-link" style="color:#ffdddd">Đăng xuất</a>
      `;
      document.getElementById('logout-link').addEventListener('click', () => {
        localStorage.removeItem('isLoggedIn');
        location.reload();
      });
    } else {
      userArea.innerHTML = `<a href="login.html" id="login-link">Đăng nhập</a>`;
    }
  }
});
