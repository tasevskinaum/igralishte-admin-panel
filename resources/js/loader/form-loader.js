$(document).ready(function () {
    $("form").submit(() => {
        $("body").css("overflow-y", "hidden");
        $("body").append($("<div>")
            .addClass("loader")
            .append(
                $("<div>").addClass("dot-falling")
            ));
        return true;
    });
});