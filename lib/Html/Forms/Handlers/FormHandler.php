<?php

namespace Forms\Handlers;

use Bitrix24\Bitrix24Api;
use Forms\Builder\IForm;
use Forms\Validators\IFormValidator;
use Helpers\ArrayHelper;
use Http\Response\IResponse;

class FormHandler implements IFormHandler
{
    public IForm $instance;
    public IResponse $response;
    public Bitrix24Api $bitrix24Api;
    public IFormValidator $validator;

    public function __construct(
        IForm $instance,
        IResponse $response,
        IFormValidator $validator,
        Bitrix24Api $bitrix24Api
    ) {
        $this->instance = $instance;
        $this->response = $response;
        $this->validator = $validator;
        $this->bitrix24Api = $bitrix24Api;
    }

    public function normalize(array $formData): array
    {
        $out = [];
        foreach ($this->instance->fields() as $field) {
            $value = ArrayHelper::getValue($field->getName(), $formData);
            $field->value($value);
            $field->normalize();

            $key = $field->getAlias();

            $out[$key] = $field->getValue();
        }

        return $out;
    }


    public function handle($formData)
    {
        $this->validator->load($formData);

        if (!$this->validator->validate()) {
            $this->response->error()->out([
                'message' => 'Ошибка валидации',
                'fields' => $this->validator->getErrors(),
            ]);
        }

        $b24response = $this->bitrix24Api->leadAdd($this->normalize($formData));

        $this->response->success()->out();
    }
}