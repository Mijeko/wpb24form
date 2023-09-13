console.log('i work');
document.addEventListener('DOMContentLoaded', function () {
    $(document).on('submit', '.js-handle-custom-form', function (event) {
        event.preventDefault();

        const form = $(this);

        fetch(form.attr('action'), {
            method: form.attr('method'),
            body: new FormData(form[0]),
        }).then(response => response.json())
            .then(data => {
                if (data.status === 200) {
                    form[0].reset();
                }
            })

    });
});
