<?php

namespace Forms\Builder;

use Ajax\AjaxRequestRouter;
use Forms\Fields\InputField;
use Forms\Fields\TextareaField;
use Html\HtmlHelper;
use Html\SiteFormHelper;

class FormHeader implements IForm
{
    public function uniqKey()
    {
        return 'form.header';
    }

    public static function getInstance()
    {
        return new static();
    }

    public function map(): array
    {
        return [
            new InputField('name', [
                'label' => 'Имя',
            ]),
            new InputField('phone', [
                'label' => 'Телефон',
            ]),
            new TextareaField('comment', [
                'label' => 'Комментарий',
            ]),
        ];
    }

    public function build()
    {
        HtmlHelper::beginForm(array(
            'method' => 'post',
            'action' => '/wp-content/plugins/formsintegrator/ajax.php',
            'class' => 'js-handle-custom-form'
        ));

        HtmlHelper::hidden('form', $this->uniqKey());
        HtmlHelper::hidden('action', AjaxRequestRouter::ACTION_HANDLE_FORM);

        foreach ($this->map() as $fieldModel) {
            if ($fieldModel instanceof InputField) {
                SiteFormHelper::input($fieldModel->getInputName(), $fieldModel->getOptions());
            }

            if ($fieldModel instanceof TextareaField) {
                SiteFormHelper::textarea($fieldModel->getInputName(), $fieldModel->getOptions());
            }
        }

        HtmlHelper::submit('Отправить', ['class' => 'custom-site-form-button --submit']);

        HtmlHelper::endForm();
    }

    public function response()
    {
        $this->build();
    }
}