<?php

namespace Forms\Validators;

class SimpleValidator implements IFormValidator
{
    public array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $rule) {
        }

        return true;
    }

    public function getErrors(): array
    {
        return [];
    }
}