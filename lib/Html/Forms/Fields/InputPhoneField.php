<?php

namespace Forms\Fields;

class InputPhoneField extends AField
{
    public function normalize()
    {
        $newValue = array();
        $newValue[] = ['VALUE' => $this->value, 'VALUE_TYPE' => 'WORK'];
        $this->value = $newValue;
    }
}