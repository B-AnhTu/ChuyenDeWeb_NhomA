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

$(document).ready(function () {
    $("form").on("submit", function (e) {
        e.preventDefault();

        // Disable nút submit để tránh gửi nhiều lần
        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop("disabled", true);

        // Thêm loading state
        submitButton.html("<span>Đang gửi...</span>");

        let formData = {
            name: $('input[placeholder="Họ và tên"]').val().trim(),
            email: $('input[placeholder="Email của bạn"]').val().trim(),
            message: $("textarea").val().trim(),
            _token: $('meta[name="csrf-token"]').attr("content"),
        };

        // Kiểm tra dữ liệu trước khi gửi
        if (!formData.name || !formData.email || !formData.message) {
            alert("Vui lòng điền đầy đủ thông tin");
            submitButton.prop("disabled", false);
            submitButton.html("Gửi tin nhắn");
            return;
        }

        $.ajax({
            type: "POST",
            url: "/send-contact",
            data: formData,
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    $("form")[0].reset();
                } else {
                    alert(
                        response.message ||
                            "Có lỗi xảy ra, vui lòng thử lại sau."
                    );
                }
            },
            error: function (xhr) {
                console.error("Error:", xhr);

                if (xhr.status === 422) {
                    // Lỗi validation
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = "";
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + "\n";
                    });
                    alert(errorMessage);
                } else {
                    // Lỗi server hoặc lỗi khác
                    alert(
                        "Có lỗi xảy ra khi gửi tin nhắn. Mã lỗi: " + xhr.status
                    );
                }
            },
            complete: function () {
                // Reset trạng thái nút submit
                submitButton.prop("disabled", false);
                submitButton.html("Gửi tin nhắn");
            },
        });
    });
});
