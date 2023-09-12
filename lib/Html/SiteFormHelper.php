<?php

namespace Html;

class SiteFormHelper
{
    public static function input($name,$options=array())
    {
        ['label'=>$label] = $options;
        unset($options['label']);

        HtmlHelper::openTag('div', ['class' => 'custom-site-form-field']);

            HtmlHelper::div($label, ['class'=>'custom-site-form-field__label']);

            HtmlHelper::shortTag('input', [
                'type' => 'text',
                'name' => $name,
                'class'=>'custom-site-form-field__input',
            ]);
        HtmlHelper::endTag('div');
    }

    public static function textarea($name,$options=array())
    {
        ['label'=>$label] = $options;
        unset($options['label']);

        HtmlHelper::openTag('div', ['class' => 'custom-site-form-field']);

            HtmlHelper::div($label, ['class'=>'custom-site-form-field__label']);

            HtmlHelper::openTag('textarea', [
                'name' => $name,
                'class'=>'custom-site-form-field__textarea'
            ]);
            HtmlHelper::endTag('textarea');
        HtmlHelper::endTag('div');


    }
}