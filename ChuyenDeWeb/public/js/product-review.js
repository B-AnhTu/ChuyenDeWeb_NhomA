document.addEventListener('DOMContentLoaded', function() {
    // Lấy ID sản phẩm
    const productId = document.querySelector('[data-id]').dataset.id;
    const reviewForm = document.getElementById('review-form');
    const reviewMessage = document.getElementById('review-message');
    
    // Load reviews đã được duyệt
    function loadReviews() {
        fetch(`/product-review/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const reviewsContainer = document.getElementById('reviews');
                    reviewsContainer.innerHTML = '';
                    
                    if (data.data.length === 0) {
                        reviewsContainer.innerHTML = '<p class="text-muted">Chưa có đánh giá nào.</p>';
                        return;
                    }
                    
                    data.data.forEach(review => {
                        reviewsContainer.innerHTML += `
                            <div class="review-item mb-4">
                                <h5>
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    ${review.user_name}
                                </h5>
                                <div class="d-flex justify-content-between align-items-start">
                                    <p class="pe-5">${review.comment}</p>
                                    <small class="text-muted">${review.created_at}</small>
                                </div>
                                <hr>
                            </div>
                        `;
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Load reviews khi trang được tải
    loadReviews();

    // Xử lý submit form
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const comment = document.getElementById('review-text').value;
            if (!comment.trim()) {
                showMessage('Vui lòng nhập nội dung đánh giá', 'warning');
                return;
            }

            fetch('/product-review', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    comment: comment
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('review-text').value = '';
                    showMessage('Cảm ơn bạn đã đánh giá! Bình luận của bạn sẽ được hiển thị sau khi được duyệt.', 'success');
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Có lỗi xảy ra khi gửi đánh giá', 'error');
            });
        });
    }

    // Hiển thị thông báo
    function showMessage(message, type) {
        const messageDiv = document.getElementById('review-message');
        messageDiv.className = 'alert alert-' + (type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger');
        messageDiv.textContent = message;
        messageDiv.style.display = 'block';
        
        // Tự động ẩn sau 5 giây
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 5000);
    }
});