<?php

namespace Forms\Fields;

abstract class AField implements IField
{
    public string $inputName;
    public array $options;

    public function __construct(string $inputName, array $options = array())
    {
        $this->inputName = $inputName;
        $this->options = $options;
    }

    public static function build(string $inputName, array $options = array())
    {
        return new static($inputName, $options);
    }

    public function fieldName(): string
    {
        return $this->inputName;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}