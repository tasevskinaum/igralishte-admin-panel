$(document).ready(function () {
    $("#set_discount_on").on("focus", function () {
        if (!$(this).val().includes('#')) {
            $(this).val('#' + $(this).val());
        }
    });

    $("#set_discount_on").on("keypress", function (event) {
        if (event.key === "," && !$(this).val().endsWith(' #')) {
            event.preventDefault();
            $(this).val($(this).val() + ',#');
        }
    });
});