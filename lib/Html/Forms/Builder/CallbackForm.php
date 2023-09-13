<?php

namespace Forms\Builder;

use Bitrix24\Bitrix24Api;
use Forms\Fields\DropdownField;
use Forms\Fields\InputField;
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
            InputField::build('TITLE', [
                'value' => 'Заполнение формы сайта Рассчитать стоимость',
                'type' => 'hidden',
            ]),
            InputField::build('NAME', [
                'label' => 'Имя',
            ]),
            InputField::build('PHONE', [
                'label' => 'Телефон',
            ]),
            DropdownField::build('UF_CRM_1694592756')
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
                ->default('Тема обращения'),
            TextareaField::build('COMMENT', [
                'label' => 'Комментарий',
            ]),
        ];
    }

    public static function handler(): IFormHandler
    {
        return new FormHandler(
            new JsonResponse(),
            new SimpleValidator(array(
                'TITLE' => 'required',
                'NAME' => 'required',
                'PHONE' => 'required',
                'UF_CRM_1694592756' => 'required',
                'COMMENT' => 'required',
            )),
            new Bitrix24Api(new HttpCurl(), new Json()),
        );
    }

    public function uniqKey(): string
    {
        return 'form.callback';
    }
}