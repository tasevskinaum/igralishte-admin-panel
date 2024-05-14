$(document).ready(function () {
    $('.size-inputs').each(function () {
        const quantityInput = $(this).find('input[type="number"]');
        const quantity = parseInt(quantityInput.val());
        $(this).find('.quantity-display').text(quantity);
    });

    $('.quantity-control').click(function (event) {
        event.preventDefault();
        const size = $(this).data('size');
        const change = parseInt($(this).data('change'));
        const quantityInput = $(`[name="quantity${size}"]`);
        let quantity = parseInt(quantityInput.val()) + change;
        if (quantity < 0) {
            quantity = 0;
        }
        quantityInput.val(quantity);
        $(this).siblings('.quantity-display').text(quantity);
    });

    $('#sizes').on('click', 'span', function (event) {
        $('#sizes span').removeClass('active');

        $(this).addClass('active');

        const size = $(this).text();
        $('.size-inputs').hide();
        $(`#size-${size}`).css('display', 'flex');
    });
});