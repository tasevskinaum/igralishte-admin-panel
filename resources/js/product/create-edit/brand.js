$(document).ready(function () {
    $('#category').change(function () {
        fillBrandsByCategory();
    });

    function fillBrandsByCategory() {
        const category = $('#category').val();

        if (category) {
            axios.get(`/api/categories/${category}/brands`)
                .then(function (response) {
                    const brands = response.data;

                    $("#brand").empty();

                    $("#brand").append($('<option>', {
                        value: "",
                        text: "Одбери"
                    }));

                    $.each(brands.data, function (key, value) {
                        $("#brand").append($('<option>', {
                            value: value.id,
                            text: value.name
                        }));
                    });
                })
                .catch(function (error) {
                    Swal.fire({
                        text: "Се случи неочекувана грешка. Обиди се повторно!",
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'swal2-btn-sm'
                        },
                        confirmButtonText: 'Обиди се повторно',
                        icon: "error"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                });
        } else {
            $("#type").empty();
            $("#brand").append($('<option>', {
                value: "",
                text: "Одбери"
            }));
        }
    }
});