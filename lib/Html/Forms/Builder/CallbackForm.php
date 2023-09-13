<?php

namespace Forms\Builder;

use Bitrix24\Bitrix24Api;
use Forms\Handlers\FormHandler;
use Forms\Handlers\IFormHandler;
use Http\Converter\Json;
use Http\HttpCurl;
use Http\Response\JsonResponse;

class CallbackForm extends AMainForm
{
    public static function handler(): IFormHandler
    {
        return new FormHandler(
            new JsonResponse(),
            new Bitrix24Api(new HttpCurl(), new Json()),
        );
    }

    public function uniqKey(): string
    {
        return 'form.callback';
    }
}