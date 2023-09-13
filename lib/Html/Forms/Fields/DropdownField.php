<?php

namespace Forms\Fields;

class DropdownField extends AField implements IDropdown
{
    public array $variants;
    public $default;

    public static function build(string $inputName, array $options = array())
    {
        return new static($inputName, $options);
    }

    public function variants(array $variants, $default = null)
    {
        $this->variants = $variants;
        if ($default) {
            $this->default = $default;
        }
        return $this;
    }

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function default($default)
    {
        $this->default = $default;
        return $this;
    }

    public function getDefault()
    {
        return $this->default;
    }
}