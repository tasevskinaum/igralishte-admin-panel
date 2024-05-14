$('#search').on('input', function () {
    var searchTerm = $(this).val().trim().toLowerCase();
    var $listProductElements = $('#product-list-view .product');
    var $galleryProductElements = $('#product-gallery-view .product');

    $listProductElements.each(function () {
        var $productElement = $(this);
        var productName = $productElement.find('.product-name').text().toLowerCase();

        if (productName.includes(searchTerm)) {
            $productElement.css('display', 'flex');
        } else {
            $productElement.css('display', 'none');
        }
    });

    $galleryProductElements.each(function () {
        var $productElement = $(this);
        var productName = $productElement.find('.product-name').text().toLowerCase();

        if (productName.includes(searchTerm)) {
            $productElement.css('display', 'block');
        } else {
            $productElement.css('display', 'none');
        }
    });
});