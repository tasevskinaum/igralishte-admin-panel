$(document).ready(function () {
    $(".delete-brand-btn").click(function (e) {
        e.preventDefault();

        $("#delete-brand-input").val($(this).val());

        $('#brand-delete-modal').modal('show');
    })
});