<?php

namespace Forms\Validators;

class SimpleValidator implements IFormValidator
{
    public array $rules;
    public array $errors;
    private array $formData;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
        $this->errors = [];
    }

    public function load(array $formData)
    {
        $this->formData = $formData;
    }

    public function validate(): bool
    {
        foreach ($this->rules as $field => $rule) {
            $this->valid($field, $rule);
        }

        return !$this->hasErrors();
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function valid($field, $ruleString): bool
    {
        $rules = $this->parseRuleString($ruleString);

        foreach ($rules as $rule) {
            $this->applyRule($field, $rule);
        }

        return true;
    }

    public function applyRule($field, $rule): bool
    {
        $value = $this->value($field);

        switch ($rule) {
            case 'required':
                $result = strlen($value) <= 0;
                if ($result) {
                    $this->setError($field, "Поле должно быть заполнено.");
                }
                return $result;
            default:
                return true;
        }
    }

    public function parseRuleString(string $ruleString): array
    {
        return explode('|', $ruleString);
    }

    public function value($fieldName)
    {
        return $this->formData[$fieldName];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setError($field, $text)
    {
        $this->errors[$field] = $text;
    }
}