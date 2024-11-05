// public/js/send-email.js
document.getElementById("subscribeBtn").addEventListener("click", function () {
    const form = document.getElementById("newsletterForm");
    const messageDiv = document.getElementById("newsletterMessage");
    const submitBtn = this;
    const btnText = submitBtn.querySelector(".btn-text");
    const spinner = submitBtn.querySelector(".spinner-border");

    // Validate email
    const emailInput = form.querySelector('input[name="email"]');
    if (!emailInput.value) {
        messageDiv.innerHTML = `
            <div class="alert alert-danger">
                Vui lòng nhập email của bạn.
            </div>`;
        return;
    }

    // Show loading state
    form.classList.add("loading");
    btnText.textContent = "Đang xử lý...";
    spinner.classList.remove("d-none");

    // Get form data
    const formData = new FormData(form);

    // Create request payload
    const payload = {
        name: formData.get("name"),
        email: formData.get("email"),
        _token: formData.get("_token"), // Include CSRF token
    };

    fetch("/newsletter/subscribe", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-CSRF-TOKEN": formData.get("_token"),
        },
        body: JSON.stringify(payload),
    })
        .then((response) => {
            if (!response.ok) {
                return response.json().then((err) => Promise.reject(err));
            }
            return response.json();
        })
        .then((data) => {
            const alertClass =
                data.status === "success" ? "alert-success" : "alert-danger";
            messageDiv.innerHTML = `<div class="alert ${alertClass}">${data.message}</div>`;

            if (data.status === "success") {
                form.reset();
            }
        })
        .catch((error) => {
            let errorMessage = "Có lỗi xảy ra, vui lòng thử lại sau.";
            if (error.message) {
                errorMessage = error.message;
            }
            messageDiv.innerHTML = `
            <div class="alert alert-danger">
                ${errorMessage}
            </div>`;
        })
        .finally(() => {
            // Reset loading state
            form.classList.remove("loading");
            btnText.textContent = "Đăng ký";
            spinner.classList.add("d-none");

            // Auto hide message after 5 seconds
            setTimeout(() => {
                messageDiv.innerHTML = "";
            }, 5000);
        });
});
