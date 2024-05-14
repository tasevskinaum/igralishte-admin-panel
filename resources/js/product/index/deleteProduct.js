$(document).ready(function () {
    $(".delete-product-btn").click(function (e) {
        e.preventDefault();

        $("#delete-product-input").val($(this).val());

        $('#product-delete-modal').modal('show');
    })
});