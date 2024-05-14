$(document).ready(function () {
    $("#tags").on("focus", function () {
        if (!$(this).val().includes('#')) {
            $(this).val('#' + $(this).val());
        }
    });

    $("#tags").on("keypress", function (event) {
        if (event.key === "," && !$(this).val().endsWith(' #')) {
            event.preventDefault();
            $(this).val($(this).val() + ', #');
        }
    });
});