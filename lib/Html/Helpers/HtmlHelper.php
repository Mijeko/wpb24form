<?php

namespace Html\Helpers;

class HtmlHelper
{
    public static function shortTag(string $tag, $htmlOptions = array()): string
    {
        return sprintf(
            '<%s %s />',
            $tag,
            $htmlOptions ? self::formatHtmlOptions($htmlOptions) : null
        );
    }

    public static function defaultOption($label)
    {
        return self::tag('option', $label, array('value' => null));
    }

    public static function option($label, $value = null): string
    {
        $options = array();

        if (!$value) {
            $value = $label;
        }

        $options['value'] = $value;

        return self::tag('option', $label, $options);
    }

    public static function img(string $src, array $options = array())
    {
        $options['src'] = $src;
        return self::shortTag('img', $options);
    }

    public static function a($content, array $options = array()): string
    {
        return self::tag('a', $content, $options);
    }

    public static function hidden($name, $value = null): string
    {
        $params = [
            'name' => $name,
            'type' => 'hidden',
        ];

        if ($value) {
            $params['value'] = $value;
        }

        return self::shortTag('input', $params);
    }

    public static function textarea($htmlOptions = array()): string
    {
        return self::tag('textarea', null, $htmlOptions);
    }

    public static function beginForm($htmlOptions = array())
    {
        return self::openTag('form', $htmlOptions);
    }

    public static function endForm(): string
    {
        return self::endTag('form');
    }

    public static function submit(string $content, array $options = array()): string
    {
        $options['type'] = 'submit';

        return self::tag('button', $content, $options);
    }

    public static function button(string $content, array $options = array()): string
    {
        $options['type'] = 'button';

        return self::tag('button', $content, $options);
    }

    public static function tag(string $tag, $content, array $options = array()): string
    {
        return sprintf(
            "<%s %s>%s</%s>",
            $tag,
            self::formatHtmlOptions($options),
            $content,
            $tag
        );
    }

    public static function div(string $content, array $options): string
    {
        return self::tag('div', $content, $options);
    }

    public static function openTag(string $tag, array $htmlOptions = array()): string
    {
        return sprintf(
            '<%s %s>',
            $tag,
            $htmlOptions ? self::formatHtmlOptions($htmlOptions) : null
        );
    }

    public static function endTag(string $tag): string
    {
        return sprintf(
            '</%s>',
            $tag
        );
    }

    protected static function formatHtmlOptions($options = array()): string
    {
        $rawHtml = [];

        foreach ($options as $key => $val) {
            $rawHtml[] = sprintf('%s="%s"', $key, $val); // to class="class-name"
        }

        return implode(' ', $rawHtml);
    }
}