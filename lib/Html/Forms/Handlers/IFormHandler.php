<?php

namespace Forms\Handlers;

use Bitrix24\Bitrix24Api;
use Forms\Builder\IForm;
use Forms\Validators\IFormValidator;
use Http\Response\IResponse;

interface IFormHandler
{
    public function __construct(
        IForm $instance,
        IResponse $response,
        IFormValidator $validator,
        Bitrix24Api $b24Api
    );

    public function handle($formData);
}