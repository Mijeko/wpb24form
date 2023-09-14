<?php

namespace Forms\Builder;

use Bitrix24\Bitrix24Api;
use Forms\Fields\DropdownField;
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

class CallbackForm extends AMainForm
{
    public function fields(): array
    {
        return [

            HiddenInput::build('utm_source')->alias('UF_CRM_1663239201'),
            HiddenInput::build('utm_medium')->alias('UF_CRM_1663239210'),
            HiddenInput::build('utm_campaign')->alias('UF_CRM_1663239221'),

            HiddenInput::build('TITLE', [
                'value' => 'Заполнение формы сайта Рассчитать стоимость',
            ]),

            InputField::build('NAME', [
                'label' => 'Имя',
            ]),

            InputPhoneField::build('PHONE', [
                'label' => 'Телефон',
            ]),

            DropdownField::build('utm_term')
                ->variants([
                    'Проектирование бассейнов' => 'Проектирование бассейнов',
                    'Строительство и реконструкция бассейнов' => 'Строительство и реконструкция бассейнов',
                    'Оборудование для бассейнов' => 'Оборудование для бассейнов',
                    'Системы управления бассейнов' => 'Системы управления бассейнов',
                    'Покрытия для бассейнов' => 'Покрытия для бассейнов',
                    'Сервисное и техническое обслуживание бассейнов' => 'Сервисное и техническое обслуживание бассейнов',
                    'Рассчитать стоимость' => 'Рассчитать стоимость',
                    'Бетонные бассейны' => 'Бетонные бассейны',
                    'Композитные бассейны' => 'Композитные бассейны',
                    'Сборные бассейны' => 'Сборные бассейны',
                ])
                ->default('Тема обращения')
                ->alias('UF_CRM_1694592756'),

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
                'utm_term' => 'required',
            )),
            new Bitrix24Api(new HttpCurl(), new Json()),
        );
    }

    public function uniqKey(): string
    {
        return 'form.callback';
    }
}