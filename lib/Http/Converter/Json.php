<?php

namespace Http\Converter;

class Json implements IConverter
{
    protected $source;

    public function input($source): self
    {
        $this->source = $source;

        return $this;
    }

    public function response(): array
    {
        return json_decode($this->source, true);
    }
}