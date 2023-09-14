<?php

namespace Html\Helpers;

class FancyboxHtmlHelper
{
    private static function toolTemplateUrl()
    {
        ob_start();
        bloginfo('template_url');
        $_tmp = ob_get_contents();
        ob_end_clean();

        return $_tmp;
    }

    public static function headerCallbackLink($modalIdentity): string
    {
        $useElement = HtmlHelper::tag('use', '', array('xlink:href' => sprintf('%s/images/sprite/sprite.svg#phone', self::toolTemplateUrl())));

        $svgElement = HtmlHelper::tag('svg', $useElement) . HtmlHelper::tag('span', 'Заказать звонок');

        return HtmlHelper::a($svgElement, [
            'class' => 'header__callback',
            'data-fancybox' => null,
            'data-type' => 'ajax',
            'href' => sprintf('/wp-content/plugins/formsintegrator/ajax.php?action=getModal&modal=%s', $modalIdentity)
        ]);
    }

    public static function sliderButton($label = 'Расcчитать стоимость', $modalIdentity = 'modal.calculate')
    {
        return HtmlHelper::a($label, array(
            'data-fancybox' => null,
            'data-type' => 'ajax',
            'class' => 'hero-slider__button btn',
            'href' => sprintf('/wp-content/plugins/formsintegrator/ajax.php?action=getModal&modal=%s', $modalIdentity)
        ));
    }

    public static function circleButton($modalIdentity = 'modal.calculate')
    {
        $useElement = HtmlHelper::tag('use', '', array('xlink:href' => sprintf('%s/images/sprite/sprite.svg#chevron', self::toolTemplateUrl())));

        $svgElement = HtmlHelper::tag('svg', $useElement);

        return HtmlHelper::tag('span', $svgElement, array(
            'data-fancybox' => null,
            'data-type' => 'ajax',
            'href' => sprintf('/wp-content/plugins/formsintegrator/ajax.php?action=getModal&modal=%s', $modalIdentity),
            'class' => 'what-item__arrow',
        ));
    }
}