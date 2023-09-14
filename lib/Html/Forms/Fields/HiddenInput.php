<?php

namespace Forms\Fields;

class HiddenInput extends AField
{
    public static function build(string $name, array $options = array()): AField
    {
        $options['type'] = 'hidden';
        return parent::build($name, $options);
    }
}