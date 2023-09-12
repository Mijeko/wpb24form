<?php

namespace Forms\Fields;

interface IField
{
    public function __construct(string $inputName, array $options = array());
}