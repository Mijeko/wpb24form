<?php

namespace Forms\Fields;

use Bitrix24\CrmField\ICrmField;

abstract class AField implements IField, ICrmField
{
    public string $name;
    public array $options;
    public $value;
    public $alias;

    public function __construct(string $name, array $options = array())
    {
        $this->name = $name;
        $this->options = $options;
        $this->alias = null;
    }

    public static function build(string $name, array $options = array()): self
    {
        return new static($name, $options);
    }

    public function normalize()
    {
        return $this->value;
    }

    public function getAlias()
    {
        return $this->alias ?? $this->name;
    }

    public function alias($value): \Bitrix24\CrmField\ICrmField
    {
        $this->alias = $value;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function fieldName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}