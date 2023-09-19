<?php

namespace Forms\Builder;

use Ajax\AjaxRequestRouter;
use Forms\Fields\DropdownField;
use Forms\Fields\HiddenInput;
use Forms\Fields\InputField;
use Forms\Fields\InputPhoneField;
use Forms\Fields\TextareaField;
use Html\Helpers\HtmlHelper;
use Html\Helpers\SiteFormHelper;
use Html\IContent;

abstract class AMainForm implements IForm, IContent
{
    public $id;
    public $action;
    public $method;

    public static function build(
        string $id = null,
        string $method = 'post',
        string $action = '/wp-content/plugins/formsintegrator/ajax.php'
    ) {
        return new static($id, $method, $action);
    }

    public function __construct(
        string $id = null,
        string $method = 'post',
        string $action = '/wp-content/plugins/formsintegrator/ajax.php'
    ) {
        $this->id = $id;
        $this->method = $method;
        $this->action = $action;
    }

    public static function getInstance(): self
    {
        return new static();
    }

    public function formHeader(): void
    {
        echo HtmlHelper::beginForm(array(
            'method' => $this->method,
            'action' => $this->action,
            'class' => 'js-handle-custom-form',
            'id' => $this->id
        ));

        echo HtmlHelper::hidden('form', $this->uniqKey());
        echo HtmlHelper::hidden('action', AjaxRequestRouter::ACTION_HANDLE_FORM);
    }


    public function formBody(): void
    {
        foreach ($this->fields() as $fieldModel) {
            if (
                $fieldModel instanceof InputField
                || $fieldModel instanceof HiddenInput
            ) {
                echo SiteFormHelper::input($fieldModel->fieldName(), $fieldModel->getOptions());
            }

            if ($fieldModel instanceof InputPhoneField) {
                echo SiteFormHelper::phone($fieldModel->fieldName(), $fieldModel->getOptions());
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

    public function buildForm(): void
    {
        $this->formHeader();

        $this->formBody();

        $this->formFooter();
    }

    public function response()
    {
        $this->buildForm();
    }
}