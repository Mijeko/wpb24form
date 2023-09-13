<?php

namespace Forms\Fields;

interface IField
{
    public function __construct(string $inputName, array $options = array());

    public static function build(string $inputName, array $options = array());

}