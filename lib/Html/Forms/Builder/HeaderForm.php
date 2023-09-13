<?php

namespace Forms\Builder;

use Bitrix24\Bitrix24Api;
use Forms\Fields\InputField;
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
            InputField::build('name', [
                'label' => 'Имя',
            ]),
            InputField::build('phone', [
                'label' => 'Телефон',
            ]),
            TextareaField::build('comment', [
                'label' => 'Комментарий',
            ]),
        ];
    }

    public static function handler(): IFormHandler
    {
        return new FormHandler(
            new JsonResponse(),
            new Bitrix24Api(new HttpCurl(), new Json()),
            new SimpleValidator([]),
        );
    }

    public function uniqKey(): string
    {
        return 'form.header';
    }
}