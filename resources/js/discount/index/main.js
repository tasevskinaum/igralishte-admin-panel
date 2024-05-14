$(document).ready(function () {
    $(".delete-discount-btn").click(function (e) {
        e.preventDefault();

        $("#delete-discount-input").val($(this).val());

        $('#discount-delete-modal').modal('show');
    });
});
