<?php

namespace Forms\Handlers;

use Bitrix24\Bitrix24Api;
use Forms\Validators\IFormValidator;
use Http\Response\IResponse;

interface IFormHandler
{
    public function __construct(
        IResponse $response,
        Bitrix24Api $b24Api,
        IFormValidator $validator,
    );

    public function handle($formData);

}