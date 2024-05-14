$(document).ready(function () {
    const imageInputsContainer = $('#images');
    let newInputIndex = 2;

    function handleDelete(label, input) {
        label.remove();
        input.remove();
    }

    imageInputsContainer.on('change', '.image-input', function (event) {
        const input = $(this)[0];
        const label = $(this).prev('label');

        if (input.files && input.files[0] && input.files[0].type.startsWith('image/')) {
            const newInput = $('<input>', {
                type: 'file',
                name: 'images[]',
                accept: 'image/*',
                class: 'image-input',
                id: 'input-' + newInputIndex
            });

            const newImg = $('<img>', {
                src: '',
                alt: ''
            });

            const reader = new FileReader();
            reader.onload = function (e) {
                newImg.attr('src', e.target.result);

                const deleteButton = $('<button>', {
                    class: 'delete-button'
                }).append($('<i>', {
                    class: 'fa-solid fa-xmark'
                }));

                label.html(newImg).append(deleteButton);

                deleteButton.on('click', function () {
                    handleDelete(label, input);
                });
            };
            reader.readAsDataURL(input.files[0]);

            const labelFor = 'input-' + newInputIndex;

            const newIcon = $('<i>', {
                class: 'bx bx-plus'
            });

            imageInputsContainer.append($('<label>', {
                for: labelFor,
                class: 'd-flex justify-content-center align-items-center'
            }).append(newIcon)).append(newInput);

            newInputIndex++;
        }
    });

    $('label[for^="old-image-"]').each(function () {

        var inputId = $(this).attr('for');

        const deleteButton = $("<button>", {
            class: "delete-button",
            click: function (e) {
                $("#" + inputId).remove();
                $(this).parent().remove();
            }
        }).append($("<i>", {
            class: "fa-solid fa-xmark"
        }));

        $(this).append(deleteButton);
    });
});