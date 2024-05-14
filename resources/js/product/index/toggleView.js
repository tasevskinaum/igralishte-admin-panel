$(document).ready(function () {
    const search = $(".search"),
        gridIcon = search.find(".bxs-grid-alt"),
        menuIcon = search.find(".bx-menu"),
        productListView = $("#product-list-view"),
        productGalleryView = $("#product-gallery-view");

    function toggleGridView() {
        if (!gridIcon.hasClass("active")) {
            menuIcon.removeClass("active");
            productListView.stop().fadeOut(200, function () {
                productGalleryView.addClass("active").stop().fadeIn(200);
                gridIcon.addClass("active");
            });
        }
    }

    function toggleMenuView() {
        if (!menuIcon.hasClass("active")) {
            gridIcon.removeClass("active");
            productGalleryView.stop().fadeOut(200, function () {
                productListView.addClass("active").stop().fadeIn(200);
                menuIcon.addClass("active");
            });
        }
    }

    let selectedView = localStorage.getItem("selectedView");
    if (!selectedView || (selectedView !== "grid" && selectedView !== "menu")) {
        selectedView = "grid"; // Default to "grid" if not set or invalid
        localStorage.setItem("selectedView", selectedView);
    }

    if (selectedView === "grid") {
        toggleGridView();
    } else if (selectedView === "menu") {
        toggleMenuView();
    }

    gridIcon.click(function () {
        toggleGridView();
        localStorage.setItem("selectedView", "grid");
    });

    // Click event for menu view
    menuIcon.click(function () {
        toggleMenuView();
        localStorage.setItem("selectedView", "menu");
    });
});