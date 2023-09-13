<?php

namespace Forms\Handlers;

use Bitrix24\Bitrix24Api;
use Forms\Validators\IFormValidator;
use Http\Response\IResponse;

interface IFormHandler
{
    public function __construct(
        IResponse $response,
        IFormValidator $validator,
        Bitrix24Api $b24Api
    );

    public function handle($formData);
}