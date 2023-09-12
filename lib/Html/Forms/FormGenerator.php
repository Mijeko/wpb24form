<?php

namespace Forms;

use Forms\Builder\IForm;

class FormGenerator
{
    public static function show(IForm $form)
    {
        $form->response();
    }
}