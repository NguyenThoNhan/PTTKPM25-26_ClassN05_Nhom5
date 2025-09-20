// public/js/dashboard-lecturer.js
document.addEventListener('DOMContentLoaded', () => {
    // ===== KIỂM TRA XÁC THỰC & LẤY THÔNG TIN USER =====
    const token = localStorage.getItem('token');
    const user = JSON.parse(localStorage.getItem('user'));

    if (!token || !user || user.role !== 'lecturer') {
        localStorage.clear();
        window.location.href = '/login';
        return;
    }

    // Điền thông tin user
    document.getElementById('userFullName').textContent = user.fullName;
    document.getElementById('logoutButton').addEventListener('click', () => {
        localStorage.clear();
        window.location.href = '/login';
    });
    
    // ===== API SERVICE (Helper để gọi API) =====
    const api = {
        get: (endpoint) => fetch(endpoint, {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(res => res.json()),
        
        post: (endpoint, body = {}) => fetch(endpoint, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}` 
            },
            body: JSON.stringify(body)
        }).then(res => res.json())
    };

    // ===== QUẢN LÝ MODAL =====
    const detailsModal = document.getElementById('thesisDetailsModal');
    document.getElementById('closeDetailsModalBtn').onclick = () => detailsModal.style.display = 'none';
    window.onclick = (event) => {
        if (event.target == detailsModal) detailsModal.style.display = 'none';
    };

    // ===== HIỂN THỊ DANH SÁCH LUẬN VĂN =====
    const thesisListContainer = document.getElementById('thesis-list-container');
    const statusMap = {
        pending_approval: { text: 'Chờ duyệt đề cương', class: 'pending_approval' },
        approved: { text: 'Đã duyệt đề cương', class: 'approved' },
        // Thêm các trạng thái khác
    };

    let currentTheses = []; // Cache lại danh sách luận văn

    async function loadAssignedTheses() {
        try {
            const theses = await api.get('/api/lecturer/theses');
            currentTheses = theses; // Lưu vào cache
            
            if (theses.length === 0) {
                thesisListContainer.innerHTML = '<p>Bạn chưa được phân công hướng dẫn luận văn nào.</p>';
                return;
            }

            thesisListContainer.innerHTML = theses.map(thesis => `
                <div class="lecturer-thesis-card">
                    <div class="thesis-info-lecturer">
                        <span class="student-name">SV: ${thesis.Student.fullName}</span>
                        <h3 class="thesis-title-lecturer">${thesis.title}</h3>
                    </div>
                    <div class="thesis-actions-lecturer">
                        <span class="status-badge ${statusMap[thesis.status]?.class || ''}">${statusMap[thesis.status]?.text || thesis.status}</span>
                        <button class="btn btn-primary btn-view-details" data-id="${thesis.id}">
                            <i class="fas fa-search-plus"></i> Xem & Duyệt
                        </button>
                    </div>
                </div>
            `).join('');
        } catch (error) {
            thesisListContainer.innerHTML = '<p>Lỗi khi tải danh sách luận văn.</p>';
        }
    }

    // ===== XỬ LÝ SỰ KIỆN =====

    // 1. Mở modal chi tiết
    thesisListContainer.addEventListener('click', (e) => {
        const viewButton = e.target.closest('.btn-view-details');
        if (viewButton) {
            const thesisId = viewButton.dataset.id;
            const thesis = currentTheses.find(t => t.id == thesisId);
            if (thesis) {
                openDetailsModal(thesis);
            }
        }
    });

    function openDetailsModal(thesis) {
        document.getElementById('detailsThesisTitle').textContent = thesis.title;
        document.getElementById('detailsStudentName').textContent = thesis.Student.fullName;
        document.getElementById('detailsStudentEmail').textContent = thesis.Student.email;
        document.getElementById('detailsThesisStatus').textContent = statusMap[thesis.status]?.text || thesis.status;
        document.getElementById('detailsThesisStatus').className = `status-badge ${statusMap[thesis.status]?.class || ''}`;

        const versionContainer = document.getElementById('version-list-container');
        if (thesis.ThesisVersions.length > 0) {
            versionContainer.innerHTML = thesis.ThesisVersions.map(version => {
                const isApproved = version.Approval !== null;
                const canApprove = thesis.status === 'pending_approval' && !isApproved;

                return `
                <div class="version-item">
                    <div class="version-info">
                        <strong>${version.versionName}</strong>
                        <span>Nộp ngày: ${new Date(version.submissionDate).toLocaleDateString('vi-VN')}</span>
                    </div>
                    <div class="version-actions">
                        ${isApproved ? 
                            `<div class="approval-status"><i class="fas fa-check-circle"></i> Đã ký duyệt</div>` : 
                            (canApprove ? `<button class="btn btn-approve" data-version-id="${version.id}">
                                <i class="fas fa-signature"></i> Phê duyệt và Ký số
                            </button>` : `<button class="btn" disabled>Chờ xử lý</button>`)
                        }
                    </div>
                </div>
                `;
            }).join('');
        } else {
            versionContainer.innerHTML = '<p>Sinh viên chưa nộp phiên bản nào.</p>';
        }

        detailsModal.style.display = 'block';
    }

    // 2. Xử lý sự kiện ký số
    document.getElementById('thesisDetailsModal').addEventListener('click', async (e) => {
        const approveButton = e.target.closest('.btn-approve');
        if (approveButton) {
            const versionId = approveButton.dataset.versionId;
            if (confirm('Bạn có chắc chắn muốn ký số phê duyệt đề cương này? Hành động này không thể hoàn tác.')) {
                try {
                    const result = await api.post(`/api/lecturer/versions/${versionId}/approve`);
                    if (result.message) {
                        alert(result.message);
                        detailsModal.style.display = 'none';
                        loadAssignedTheses(); // Tải lại danh sách để cập nhật trạng thái
                    } else {
                        throw new Error(result.message || 'Phê duyệt thất bại.');
                    }
                } catch (error) {
                    alert('Lỗi: ' + error.message);
                }
            }
        }
    });

    // ===== TẢI DỮ LIỆU BAN ĐẦU =====
    loadAssignedTheses();
});