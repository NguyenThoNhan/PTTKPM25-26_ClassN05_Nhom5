// public/js/dashboard-student.js
document.addEventListener('DOMContentLoaded', () => {
    // ===== KIỂM TRA XÁC THỰC & LẤY THÔNG TIN USER =====
    const token = localStorage.getItem('token');
    const user = JSON.parse(localStorage.getItem('user'));

    if (!token || !user) {
        window.location.href = '/login';
        return;
    }

    // Điền thông tin user lên header
    document.getElementById('userFullName').textContent = user.fullName;
    document.getElementById('welcomeMessage').textContent = `Chào mừng trở lại, ${user.fullName}!`;
    document.getElementById('logoutButton').addEventListener('click', () => {
        localStorage.clear();
        window.location.href = '/login';
    });
    
    // ===== API SERVICE (Helper để gọi API) =====
    const api = {
        get: (endpoint) => fetch(endpoint, {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(res => res.json()),
        
        post: (endpoint, body) => fetch(endpoint, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}` 
            },
            body: JSON.stringify(body)
        }).then(res => res.json()),

        postForm: (endpoint, formData) => fetch(endpoint, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` },
            body: formData
        }).then(res => res.json())
    };

    // ===== QUẢN LÝ MODAL =====
    const newThesisModal = document.getElementById('newThesisModal');
    const detailsModal = document.getElementById('thesisDetailsModal');
    
    document.getElementById('openNewThesisModalBtn').onclick = () => newThesisModal.style.display = 'block';
    document.getElementById('closeNewThesisModalBtn').onclick = () => newThesisModal.style.display = 'none';
    document.getElementById('closeDetailsModalBtn').onclick = () => detailsModal.style.display = 'none';

    window.onclick = (event) => {
        if (event.target == newThesisModal) newThesisModal.style.display = 'none';
        if (event.target == detailsModal) detailsModal.style.display = 'none';
    };

    // ===== HIỂN THỊ DANH SÁCH LUẬN VĂN =====
    const thesisListContainer = document.getElementById('thesis-list-container');
    const statusMap = {
        pending_approval: { text: 'Chờ duyệt đề cương', class: 'pending_approval' },
        approved: { text: 'Đã duyệt', class: 'approved' },
        // Thêm các trạng thái khác ở đây
    };

    async function loadTheses() {
        try {
            const theses = await api.get('/api/theses');
            if (theses.length === 0) {
                thesisListContainer.innerHTML = '<p>Bạn chưa có luận văn nào. Hãy đăng ký đề tài mới!</p>';
                return;
            }
            thesisListContainer.innerHTML = theses.map(thesis => `
                <div class="thesis-card">
                    <div class="thesis-card-image">
                        <img src="/images/thesis.jpg" alt="Thesis Image">
                    </div>
                    <div class="thesis-card-content">
                        <span class="status-badge ${statusMap[thesis.status]?.class || ''}">${statusMap[thesis.status]?.text || thesis.status}</span>
                        <h3 class="thesis-card-title">${thesis.title}</h3>
                        <div class="thesis-card-actions">
                            <button class="btn btn-primary btn-view-details" data-id="${thesis.id}"><i class="fas fa-eye"></i> Xem chi tiết</button>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch (error) {
            thesisListContainer.innerHTML = '<p>Lỗi khi tải danh sách luận văn.</p>';
        }
    }

    // ===== XỬ LÝ SỰ KIỆN =====

    // 1. Đăng ký đề tài mới
    document.getElementById('newThesisForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const title = document.getElementById('thesisTitle').value;
        const description = document.getElementById('thesisDescription').value;
        
        try {
            await api.post('/api/theses', { title, description });
            newThesisModal.style.display = 'none';
            e.target.reset();
            loadTheses(); // Tải lại danh sách
        } catch (error) {
            alert('Lỗi khi tạo đề tài.');
        }
    });

    // 2. Mở modal chi tiết luận văn (sử dụng event delegation)
    thesisListContainer.addEventListener('click', async (e) => {
        if (e.target.classList.contains('btn-view-details')) {
            const thesisId = e.target.dataset.id;
            // API chưa có get-one, nên ta tìm trong list đã fetch
            const theses = await api.get('/api/theses'); 
            const thesis = theses.find(t => t.id == thesisId);

            if (thesis) {
                document.getElementById('detailsThesisTitle').textContent = thesis.title;
                document.getElementById('detailsThesisDescription').textContent = thesis.description;
                document.getElementById('detailsThesisStatus').textContent = statusMap[thesis.status]?.text || thesis.status;
                document.getElementById('detailsThesisStatus').className = `status-badge ${statusMap[thesis.status]?.class || ''}`;
                document.getElementById('detailsThesisId').value = thesis.id;
                
                const historyList = document.getElementById('version-history-list');
                if (thesis.ThesisVersions.length > 0) {
                    historyList.innerHTML = thesis.ThesisVersions.map(v => 
                        `<li><strong>${v.versionName}</strong> - Nộp ngày: ${new Date(v.submissionDate).toLocaleDateString('vi-VN')}</li>`
                    ).join('');
                } else {
                    historyList.innerHTML = '<li>Chưa có phiên bản nào được nộp.</li>';
                }

                detailsModal.style.display = 'block';
            }
        }
    });

    // 3. Nộp phiên bản mới
    document.getElementById('submitVersionForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const thesisId = document.getElementById('detailsThesisId').value;
        const formData = new FormData(e.target);
        
        try {
            const result = await api.postForm(`/api/theses/${thesisId}/versions`, formData);
            alert('Nộp bài thành công!');
            detailsModal.style.display = 'none';
            loadTheses(); // Tải lại để cập nhật
        } catch (error) {
            alert('Lỗi khi nộp bài: ' + error.message);
        }
    });

    // ===== TẢI DỮ LIỆU BAN ĐẦU =====
    loadTheses();
});