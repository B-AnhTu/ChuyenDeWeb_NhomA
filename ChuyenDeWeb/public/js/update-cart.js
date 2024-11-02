document.addEventListener("DOMContentLoaded", function () {
    const quantityInputs = document.querySelectorAll('.pro-qty input');
    const updateCartButton = document.querySelector('.cart-btn-right');

    quantityInputs.forEach(input => {
        input.addEventListener('change', function () {
            const productId = this.name.match(/\d+/)[0];
            const quantity = parseInt(this.value);

            if (quantity && quantity > 0) {
                updateCart(productId, quantity);
            }
        });
    });

    function updateCart(productId, quantity) {
        fetch(`/cart/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`.cart-total-${productId}`).innerText = `${data.total_price} vnđ`;
                document.querySelector('.cart-total').innerText = `${data.cart_total} vnđ`;
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
