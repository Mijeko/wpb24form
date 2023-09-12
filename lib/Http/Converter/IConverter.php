<?php

namespace Http\Converter;

interface IConverter
{
    public function input($source): self;

    public function response(): array;
}