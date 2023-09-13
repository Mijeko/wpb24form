console.log('i work 123');
document.addEventListener('DOMContentLoaded', function () {
    new FormHandler('.js-handle-custom-form', {
        events: {
            beforeSubmit: function () {
                console.log('before submit');
            },
            successSubmit: function () {
                console.log('success submit');
            },
        }
    });
});

class FormHandler {

    selector;
    config;
    EVENT_BEFORE_SUBMIT = 'beforeSubmit';
    EVENT_SUCCESS_SUBMIT = 'successSubmit';

    constructor(selector, config) {

        this.selector = selector;
        this.config = config;

        if (this.selector.length <= 0) {
            return false;
        }

        this.bindEvent();
    }


    bindEvent() {

        const instance = this;

        $(document).on('submit', this.selector, function (event) {
            event.preventDefault();

            const form = $(event.target);
            instance.runEvent(instance.getEvent(instance.EVENT_BEFORE_SUBMIT));

            fetch(form.attr('action'), {
                method: form.attr('method'),
                body: new FormData(form[0]),
            }).then(response => response.json())
                .then(function (data) {
                    if (data.status === 200) {
                        form[0].reset();
                        instance.runEvent(instance.getEvent(instance.EVENT_SUCCESS_SUBMIT));
                    }
                })

        });
    }

    runEvent(callable) {

        if (typeof callable !== 'function') {
            return false;
        }

        try {
            callable();
        } catch (e) {
            console.log('Ошибка при запуске события: ', e);
        }

    }

    getEvent(eventName) {
        if (!('events' in this.config)) return false;

        if (!(eventName in this.config.events)) return false;


        return this.config.events[eventName];
    }

}
