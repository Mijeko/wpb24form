<?php

namespace Html\Helpers;

class SiteFormHelper
{
    public static function dropdown($name, array $variants, $htmlOptions = array())
    {
        $htmlOptions['name'] = $name;

        $options = [];

        if (array_key_exists('default', $htmlOptions)) {
            $options[] = HtmlHelper::defaultOption($htmlOptions['default']);
            unset($htmlOptions['default']);
        }

        foreach ($variants as $key => $value) {
            $options[] = HtmlHelper::option($value, $key);
        }

        echo HtmlHelper::tag('select', implode('', $options), $htmlOptions);
    }

    public static function input($name,$options=array())
    {
        ['label'=>$label] = $options;
        unset($options['label']);

        echo HtmlHelper::openTag('div', ['class' => 'custom-site-form-field']);

            echo HtmlHelper::div($label, ['class'=>'custom-site-form-field__label']);

            echo HtmlHelper::shortTag('input', [
                'type' => 'text',
                'name' => $name,
                'class'=>'custom-site-form-field__input',
            ]);
        echo HtmlHelper::endTag('div');
    }

    public static function textarea($name,$options=array())
    {
        ['label'=>$label] = $options;
        unset($options['label']);

        echo HtmlHelper::openTag('div', ['class' => 'custom-site-form-field']);

            echo HtmlHelper::div($label, ['class'=>'custom-site-form-field__label']);

            echo HtmlHelper::openTag('textarea', [
                'name' => $name,
                'class'=>'custom-site-form-field__textarea'
            ]);
            echo HtmlHelper::endTag('textarea');
        echo HtmlHelper::endTag('div');
    }
}