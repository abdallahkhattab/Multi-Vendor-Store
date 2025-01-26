$('.item-quantity').on('change', function () {
    const id = $(this).data('id');
    const quantity = $(this).val();

    $.ajax({
        url: `/cart/${id}`,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': csrf_token,
        },
        data: {
            quantity: quantity,
        },
        success: function (response) {
            console.log('Cart updated:', response);
        },
        error: function (xhr) {
            console.error('Error updating cart:', xhr.responseJSON);
        }
    });
});

(function ($) {
    $(document).on('click', '.remove-item', function (e) {
        e.preventDefault();

        const itemId = $(this).data('id'); // Ensure the `data-id` attribute is present and correct
        const row = $(this).closest('.cart-single-list');

        if (!itemId) {
            alert('Item ID is missing. Please try again.');
            return;
        }

        if (confirm('Are you sure you want to remove this item from your cart?')) {
            $.ajax({
                type: "DELETE",
                url: `/cart/${itemId}`,
                data: {
                    _token: csrf_token,
                },
                success: function (response) {
                    alert(response.message);
                    row.remove(); // Remove the item's row from the DOM
                    // Optionally update totals dynamically here
                },
                error: function (xhr) {
                    alert('Failed to remove item from cart.');
                }
            });
        }
    });
})(jQuery);

(function ($) {
    $(document).ready(function () {
        $('.add-to-cart-btn').click(function (e) {
            e.preventDefault();

            let productId = $(this).data('id');

            $.ajax({
                url: "{{ route('cart.store') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    quantity: $('#quantity').val()
                },
                success: function (response) {
                    alert('Product added to cart!');
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Failed to add product to cart. Please try again.');
                }
        });
        });
    });
})(jQuery);
