$(document).ready(function () {
    $(".toggle-btn").on("click", function () {
        $("#sidebar").toggleClass("expand");
        $(".sidebar-footer i.logout").toggleClass("border-white");
    });

    $("#close-sidebar-btn").on("click", function () {
        $("#sidebar").toggleClass("expand");
        $(".sidebar-footer i.logout").toggleClass("border-white");
    });
});
