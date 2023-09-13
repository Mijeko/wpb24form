<?php

namespace Forms\Handlers;

use Bitrix24\Bitrix24Api;
use Forms\Validators\IFormValidator;
use Http\Response\IResponse;

class FormHandler implements IFormHandler
{
    public IResponse $response;
    public Bitrix24Api $bitrix24Api;
    public IFormValidator $validator;

    public function __construct(
        IResponse $response,
        Bitrix24Api $bitrix24Api,
        IFormValidator $validator,
    ) {
        $this->response = $response;
        $this->bitrix24Api = $bitrix24Api;
        $this->validator = $validator;
    }

    public function handle($formData)
    {
        if (!$this->validator->validate()) {
            $this->response->error()->out([
                'message' => 'Ошибка валидации',
                'fields' => $this->validator->getErrors(),
            ]);
        }

        $this->bitrix24Api->leadAdd($formData);

        $this->response->success()->out();
    }
}