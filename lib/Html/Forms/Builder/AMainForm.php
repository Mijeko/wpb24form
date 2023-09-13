<?php

namespace Forms\Builder;

use Ajax\AjaxRequestRouter;
use Forms\Fields\DropdownField;
use Forms\Fields\InputField;
use Forms\Fields\TextareaField;
use Html\Helpers\HtmlHelper;
use Html\Helpers\SiteFormHelper;

abstract class AMainForm implements IForm
{
    public static function build()
    {
        return new static();
    }

    public static function getInstance()
    {
        return new static();
    }

    public function formHeader(): void
    {
        echo HtmlHelper::beginForm(array(
            'method' => 'post',
            'action' => '/wp-content/plugins/formsintegrator/ajax.php',
            'class' => 'js-handle-custom-form'
        ));

        echo HtmlHelper::hidden('form', $this->uniqKey());
        echo HtmlHelper::hidden('action', AjaxRequestRouter::ACTION_HANDLE_FORM);
    }


    public function formBody(): void
    {
        foreach ($this->fields() as $fieldModel) {
            if ($fieldModel instanceof InputField) {
                echo SiteFormHelper::input($fieldModel->fieldName(), $fieldModel->getOptions());
            }

            if ($fieldModel instanceof TextareaField) {
                echo SiteFormHelper::textarea($fieldModel->fieldName(), $fieldModel->getOptions());
            }

            if ($fieldModel instanceof DropdownField) {
                $options = $fieldModel->getOptions();
                $options['default'] = $fieldModel->getDefault();

                echo SiteFormHelper::dropdown($fieldModel->fieldName(), $fieldModel->getVariants(), $options);
            }
        }
    }

    public function formFooter(): void
    {
        echo HtmlHelper::submit('Отправить', ['class' => 'custom-site-form-button --submit']);

        echo HtmlHelper::endForm();
    }

    public function make(): void
    {
        $this->formHeader();

        $this->formBody();

        $this->formFooter();
    }

    public function response()
    {
        $this->make();
    }
}