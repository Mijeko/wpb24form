document.addEventListener('DOMContentLoaded', function () {
    new FormHandler('.js-handle-custom-form', {
        events: {
            beforeSubmit: function (form) {
                console.log('before submit');

                if (ym) {
                    ym(88152310, 'reachGoal', 'custom-form');
                    ym(88152310, 'reachGoal', 'custom-form', {title: form[0].get('TITLE')});
                    console.log('yam goal');
                }
            },
            successSubmit: function (form) {
                console.log('successSubmit');

                if (ym) {
                    ym(88152310, 'reachGoal', 'custom-form');
                    ym(88152310, 'reachGoal', 'custom-form', {title: form[0].get('TITLE')});
                    console.log('yam goal');
                }
            },
        }
    });
});

class FormHandler {

    selector;
    config;
    form;
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

            const urlParams = new URLSearchParams(window.location.search);
            instance.form = $(event.target);
            let formData = new FormData(instance.form[0]);


            for (const [key, value] of urlParams.entries()) {

                if (formData.has(key)) {
                    if (formData.get(key).length <= 0) {
                        formData.delete(key);
                    }
                }

                formData.append(key, value);
            }

            instance.runEvent(instance.EVENT_BEFORE_SUBMIT, formData);
            instance.resetErrors();

            fetch(instance.form.attr('action'), {
                method: instance.form.attr('method'),
                body: formData,
            }).then(response => response.json())
                .then(function (data) {

                    if (data.status === 500) {
                        instance.applyValid(data.data.fields);
                    }

                    if (data.status === 200) {
                        instance.form[0].reset();
                        instance.runEvent(instance.EVENT_SUCCESS_SUBMIT, formData);
                        instance.showSuccessStash('success');
                    }
                })
        });
    }

    showSuccessStash(type) {

        this.activeStash();

        switch (type) {
            case 'success':
                let el = document.querySelector('.custom-modal-success');
                if (el) {
                    el.classList.add('active');
                }
                break;
        }
    }

    activeStash() {
        let el = document.querySelector('.custom-modal-stash');
        if (el) {
            el.classList.add('active');
        }
    }

    deactiveStash() {
        let el = document.querySelector('.custom-modal-stash');
        if (el) {
            el.classList.remove('active');
        }
    }

    resetErrors() {
        let fields = this.form[0].querySelectorAll('.custom-site-form-field');

        if (fields) {
            fields.forEach(field => {
                field.classList.remove('isNoValid');
            })
        }
    }

    applyValid(fieldData) {
        let form = this.form[0];
        Object.keys(fieldData).map(function (key) {
            let field = form.querySelector(`#custom-form-valid-${key}`);
            if (field) {
                field.classList.add('isNoValid');
            }
        });
    }

    runEvent(eventName, ...args) {

        const callable = this.getEvent(eventName);

        if (typeof callable !== 'function') {
            return false;
        }

        try {
            callable(args);
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
