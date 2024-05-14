$('#search').on('input', function () {
    var searchTerm = $(this).val().trim().toLowerCase();
    var $brands = $('.brand');

    $brands.each(function () {
        var $brandElement = $(this);
        var brandName = $brandElement.find('.brand-name').text().toLowerCase();

        if (brandName.includes(searchTerm)) {
            $brandElement.css('display', 'flex');
        } else {
            $brandElement.css('display', 'none');
        }
    });
});