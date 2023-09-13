<?php

namespace Html\Helpers;

class ToolsHelper
{
    public static function fancyboxLinkModal($modalIdentity): string
    {
        ob_start();
        bloginfo('template_url');
        $_tmp = ob_get_contents();
        ob_end_clean();

        $useElement = HtmlHelper::tag('use', '', array('xlink:href' => sprintf('%s/images/sprite/sprite.svg#phone', $_tmp)));

        $svgElement = HtmlHelper::tag('svg', $useElement) . HtmlHelper::tag('span', 'Заказать звонок');

        return HtmlHelper::a($svgElement, [
            'class' => 'header__callback',
            'data-fancybox' => null,
            'data-type' => 'ajax',
            'href' => sprintf('/wp-content/plugins/formsintegrator/ajax.php?action=getModal&modal=%s', $modalIdentity)
        ]);
    }
}