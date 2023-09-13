<?php

namespace Forms;

use Html\IContent;

class FormGenerator
{
    public static function show(IContent $form)
    {
        $form->response();
    }
}