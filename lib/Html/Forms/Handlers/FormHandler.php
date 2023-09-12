<?php

namespace Forms\Handlers;

use Bitrix24\Bitrix24Api;
use Http\Response\IResponse;

class FormHandler implements IFormHandler
{
    public IResponse $response;

    public function __construct(IResponse $response, Bitrix24Api $bitrix24Api)
    {
        $this->response = $response;
        $this->bitrix24Api = $bitrix24Api;
    }

    public function handle($formData)
    {
        $this->bitrix24Api->leadAdd($formData);

        $this->response->input(rand());

        $this->response->out();
    }
}