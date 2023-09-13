<?php

namespace Forms\Validators;

interface IFormValidator
{
    public function __construct(array $rules);

    public function validate(): bool;

    public function getErrors(): array;

    public function setError($field, $text);

    public function load(array $formData);

    public function hasErrors(): bool;
}