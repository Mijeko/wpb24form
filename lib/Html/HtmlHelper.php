<?php

namespace Html;

class HtmlHelper
{
    public static function shortTag(string $tag, $htmlOptions = array())
    {
        echo sprintf(
            '<%s %s />',
            $tag,
            $htmlOptions ? self::formatHtmlOptions($htmlOptions) : null
        );
    }

    public static function hidden($name, $value = null)
    {
        $params = [
            'name' => $name,
            'type' => 'hidden',
        ];

        if ($value) {
            $params['value'] = $value;
        }

        self::shortTag('input', $params);
    }

    public static function textarea($htmlOptions = array())
    {
        self::openTag('textarea', $htmlOptions);
        self::endTag('textarea');
    }

    public static function beginForm($htmlOptions = array())
    {
        self::openTag('form', $htmlOptions);
    }

    public static function endForm()
    {
        self::endTag('form');
    }

    public static function submit(string $content, array $options = array())
    {
        $options['type'] = 'submit';

        self::tag('button', $content, $options);
    }

    public static function button(string $content, array $options = array())
    {
        $options['type'] = 'button';

        self::tag('button', $content, $options);
    }

    public static function tag(string $tag, $content, array $options)
    {
        echo sprintf(
            "<%s %s >%s</%s>",
            $tag,
            self::formatHtmlOptions($options),
            $content,
            $tag
        );
    }

    public static function div(string $content, array $options)
    {
        self::tag('div', $content, $options);
    }

    public static function openTag(string $tag, array $htmlOptions = array())
    {
        echo sprintf(
            '<%s %s>',
            $tag,
            $htmlOptions ? self::formatHtmlOptions($htmlOptions) : null
        );
    }

    public static function endTag(string $tag)
    {
        echo sprintf(
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