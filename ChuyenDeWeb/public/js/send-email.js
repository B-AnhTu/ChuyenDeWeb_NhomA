document.getElementById('subscribeBtn').addEventListener('click', function() {
    const form = document.getElementById('newsletterForm');
    const messageDiv = document.getElementById('newsletterMessage');
    const submitBtn = this;
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    // Show loading state
    form.classList.add('loading');
    btnText.textContent = 'Đang xử lý...';
    spinner.classList.remove('d-none');
    
    const formData = new FormData(form);
    
    fetch('/newsletter/subscribe', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: formData.get('name'),
            email: formData.get('email')
        })
    })
    .then(response => response.json())
    .then(data => {
        // Create alert message
        const alertClass = data.status === 'success' ? 'alert-success' : 'alert-danger';
        messageDiv.innerHTML = `<div class="alert ${alertClass}">${data.message}</div>`;
        
        if(data.status === 'success') {
            form.reset();
        }
    })
    .catch(error => {
        messageDiv.innerHTML = `
            <div class="alert alert-danger">
                Có lỗi xảy ra, vui lòng thử lại sau.
            </div>`;
    })
    .finally(() => {
        // Reset loading state
        form.classList.remove('loading');
        btnText.textContent = 'Đăng ký';
        spinner.classList.add('d-none');
        
        // Auto hide message after 5 seconds
        setTimeout(() => {
            messageDiv.innerHTML = '';
        }, 5000);
    });
});
