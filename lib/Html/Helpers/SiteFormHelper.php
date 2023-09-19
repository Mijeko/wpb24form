<?php

namespace Html\Helpers;

class SiteFormHelper
{
    public static function dropdown($name, array $variants, $htmlOptions = array()):string
    {
        ['class' => $class] = $htmlOptions;
        unset($htmlOptions['label'], $htmlOptions['type'], $htmlOptions['class']);

        $defaultInputOptions = [
            'name' => $name,
            'class' => sprintf('%s %s', 'custom-site-form-field__select', $class),
        ];

        $options = [];

        if (array_key_exists('default', $htmlOptions)) {
            $options[] = HtmlHelper::defaultOption($htmlOptions['default']);
            unset($htmlOptions['default']);
        }

        foreach ($variants as $key => $value) {
            $options[] = HtmlHelper::option($value, $key);
        }

        $response = HtmlHelper::openTag('div', ['class' => 'custom-site-form-field custom-select', 'id' => 'custom-form-valid-' . $name]);

//            if($label) $response .= HtmlHelper::div($label, ['class'=>'custom-site-form-field__label']);

            $response .= HtmlHelper::tag('select', implode('', $options), array_merge($defaultInputOptions, $htmlOptions));

        $response .= HtmlHelper::endTag('div');


        return $response;
    }

    public static function input($name, $htmlOptions = array())
    {
        ['label' => $label, 'type' => $type, 'class' => $class] = $htmlOptions;
        unset($htmlOptions['label'], $htmlOptions['type'], $htmlOptions['class']);

        $defaultInputOptions = [
            'type' => $type,
            'name' => $name,
            'class' => sprintf('%s %s', 'custom-site-form-field__input', $class),
        ];

        $response = HtmlHelper::openTag('div', ['class' => 'custom-site-form-field', 'id' => 'custom-form-valid-' . $name]);

            if($label) $response .= HtmlHelper::div($label, ['class'=>'custom-site-form-field__label']);

            $response .= HtmlHelper::shortTag('input', array_merge($defaultInputOptions, $htmlOptions));

        $response .= HtmlHelper::endTag('div');

        return $response;
    }

    public static function phone($name, $htmlOptions = array())
    {
        ['label' => $label, 'type' => $type, 'class' => $class] = $htmlOptions;
        unset($htmlOptions['label'], $htmlOptions['type'], $htmlOptions['class']);

        $defaultInputOptions = [
            'type' => $type,
            'name' => $name,
            'class' => sprintf('%s %s', 'custom-site-form-field__input', $class),
        ];

        $response = HtmlHelper::openTag('div', ['class' => 'custom-site-form-field', 'id' => 'custom-form-valid-' . $name]);

            if($label) $response .= HtmlHelper::div($label, ['class'=>'custom-site-form-field__label']);

            $response .= HtmlHelper::shortTag('input', array_merge($defaultInputOptions, $htmlOptions));

        $response .= HtmlHelper::endTag('div');

        return $response;
    }

    public static function textarea($name,$options=array())
    {
        ['label' => $label] = $options;
        unset($options['label']);

        $response = HtmlHelper::openTag('div', ['class' => 'custom-site-form-field', 'id' => 'custom-form-valid-' . $name]);

            $response .= HtmlHelper::div($label, ['class'=>'custom-site-form-field__label']);

            $response .= HtmlHelper::openTag('textarea', [
                'name' => $name,
                'class' => 'custom-site-form-field__textarea'
            ]);
            $response .= HtmlHelper::endTag('textarea');
        $response .= HtmlHelper::endTag('div');

        return $response;
    }
}