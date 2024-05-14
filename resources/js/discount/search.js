$('#search').on('input', function () {
    var searchTerm = $(this).val().trim().toLowerCase();
    var $discounts = $('.discount');

    $discounts.each(function () {
        var $discountElement = $(this);
        var discountName = $discountElement.find('.discount-name').text().toLowerCase();

        if (discountName.includes(searchTerm)) {
            $discountElement.css('display', 'flex');
        } else {
            $discountElement.css('display', 'none');
        }
    });
});