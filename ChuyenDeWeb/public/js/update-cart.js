document.addEventListener("DOMContentLoaded", function () {
    const quantityInputs = document.querySelectorAll(".pro-qty input");

    quantityInputs.forEach((input) => {
        const quantityInput = input;
        const productId = quantityInput.getAttribute("data-product-id");

        const increaseButton =
            quantityInput.parentElement.querySelector(".inc");
        const decreaseButton =
            quantityInput.parentElement.querySelector(".dec");

        increaseButton.addEventListener("click", function () {
            let quantity = parseInt(quantityInput.value);
            quantityInput.value = quantity + 1;
            updateCartQuantity(productId, quantity + 1);
        });

        decreaseButton.addEventListener("click", function () {
            let quantity = parseInt(quantityInput.value);
            if (quantity > 1) {
                quantityInput.value = quantity - 1;
                updateCartQuantity(productId, quantity - 1);
            }
        });
    });

    function updateCartQuantity(productId, quantity) {
        fetch("/update-cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (!data.success) {
                    console.error(
                        "Cập nhật giỏ hàng không thành công:",
                        data.message
                    );
                    return;
                }

                // Cập nhật tổng tiền nếu thành công
                const totalCell = document.querySelector(
                    `.cart-total-${productId}`
                );
                if (totalCell) {
                    totalCell.textContent =
                        new Intl.NumberFormat("vi-VN").format(data.itemTotal) +
                        " vnđ";
                }

                const cartTotal = document.querySelector(".cart-total");
                if (cartTotal) {
                    cartTotal.textContent =
                        new Intl.NumberFormat("vi-VN").format(data.cartTotal) +
                        " vnđ";
                }
            })
            .catch((error) => {
                console.error("Lỗi khi cập nhật giỏ hàng:", error);
            });
    }
    // Xử lý nút xóa sản phẩm
    const removeButtons = document.querySelectorAll(".icon_close");
    removeButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const productId = this.getAttribute("data-product-id");

            if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
                fetch("/cart/remove", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify({
                        product_id: productId,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Xóa dòng sản phẩm
                            const row = button.closest("tr");
                            row.remove();

                            // Cập nhật tổng tiền giỏ hàng
                            const cartTotal =
                                document.querySelector(".cart-total");
                            if (cartTotal) {
                                cartTotal.textContent =
                                    new Intl.NumberFormat("vi-VN").format(
                                        data.cartTotal
                                    ) + " vnđ";
                            }

                            // Nếu giỏ hàng trống, reload trang
                            if (data.cartTotal === 0) {
                                window.location.reload();
                            }
                        }
                    })
                    .catch((error) => {
                        console.error("Lỗi khi xóa sản phẩm:", error);
                    });
            }
        });
    });
//Xử lý thanh toán
    const checkoutButton = document.querySelector(
        ".shoping__checkout .primary-btn .thanh-toan"
    );
    checkoutButton.addEventListener("click", function (e) {
        e.preventDefault();
        const total = updateCartTotal();

        // Tạo form thanh toán
        const paymentForm = document.createElement("form");
        paymentForm.innerHTML = `
            <div class="modal fade" id="paymentModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thanh toán</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Số thẻ</label>
                                <input type="text" class="form-control" id="cardNumber" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Ngày hết hạn</label>
                                    <input type="text" class="form-control" id="expDate" placeholder="MM/YY" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>CVV</label>
                                    <input type="text" class="form-control" id="cvv" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tên trên thẻ</label>
                                <input type="text" class="form-control" id="cardName" required>
                            </div>
                            <p>Tổng thanh toán: <strong>${formatCurrency(
                                total
                            )}</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="primary-btn">Thanh toán</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(paymentForm);

        // Hiển thị modal thanh toán
        $("#paymentModal").modal("show");

        // Xử lý submit form thanh toán
        paymentForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const paymentData = {
                card_number: document.getElementById("cardNumber").value,
                exp_date: document.getElementById("expDate").value,
                cvv: document.getElementById("cvv").value,
                card_name: document.getElementById("cardName").value,
                amount: total,
            };

            fetch("/api/payment/process", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify(paymentData),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        window.location.href = "/payment/success";
                    } else {
                        alert("Thanh toán thất bại. Vui lòng thử lại.");
                    }
                });
        });
    });
});
