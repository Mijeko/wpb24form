<?php

namespace Forms\Fields;

class InputPhoneField extends AField
{
    public function normalize()
    {
        return $this->value = ['VALUE' => $this->value, 'VALUE_TYPE' => 'WORK'];
    }
}