<?php

namespace Forms\Builder;

use Bitrix24\Bitrix24Api;
use Forms\Fields\HiddenInput;
use Forms\Fields\InputField;
use Forms\Fields\InputPhoneField;
use Forms\Fields\TextareaField;
use Forms\Handlers\FormHandler;
use Forms\Handlers\IFormHandler;
use Forms\Validators\SimpleValidator;
use Http\Converter\Json;
use Http\HttpCurl;
use Http\Response\JsonResponse;

class HeaderForm extends AMainForm
{
    public function fields(): array
    {
        return [

            HiddenInput::build('utm_source')->alias('UF_CRM_1663239201'),
            HiddenInput::build('utm_medium')->alias('UF_CRM_1663239210'),
            HiddenInput::build('utm_campaign')->alias('UF_CRM_1663239221'),
            HiddenInput::build('utm_content')->alias('UF_CRM_1663239238'),
            HiddenInput::build('clientId')->alias('UF_CRM_1625137700150'),

            HiddenInput::build('TITLE', [
                'value' => $_REQUEST['title'] ?? 'Заполнение формы сайта Рассчитать стоимость',
                'type' => 'hidden',
            ]),

            InputField::build('NAME', [
                'label' => 'Имя',
            ]),

            InputPhoneField::build('PHONE', [
                'label' => 'Телефон',
            ]),

            TextareaField::build('comment', [
                'label' => 'Комментарий',
            ])->alias('UF_CRM_1549698769'),
        ];
    }

    public static function handler(): IFormHandler
    {
        return new FormHandler(
            new static(),
            new JsonResponse(),
            new SimpleValidator(array(
                'TITLE' => 'required',
                'NAME' => 'required',
                'PHONE' => 'required',
                'comment' => 'required',
            )),
            new Bitrix24Api(new HttpCurl(), new Json()),
        );
    }

    public function uniqKey(): string
    {
        return 'form.header';
    }
}