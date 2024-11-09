// public/js/send-email.js

$(document).ready(function () {
    $("form").on("submit", function (e) {
        e.preventDefault();

        // Disable nút submit để tránh gửi nhiều lần
        const submitButton = $(this).find("button.contact-submit-btn");
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
