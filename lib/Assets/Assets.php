<?php

namespace Assets;

use Html\Helpers\HtmlHelper;

class Assets implements IAssets
{
    public static function register()
    {
        $obj = new static();
        $obj->registerJs();
        $obj->registerCss();
    }

    public function mapCss()
    {
        return [
            'wp_footer' => [
                [
                    'priority' => 100,
                    'src' => '/wp-content/plugins/formsintegrator/assets/css/formsintegrator.css'
                ],
            ],
        ];
    }

    public function mapJs()
    {
        return [
            'wp_footer' => [
                [
                    'priority' => 100,
                    'src' => '/wp-content/plugins/formsintegrator/assets/js/formsintegrator.js'
                ],
            ],
        ];
    }

    public function registerJs()
    {
        $map = $this->mapJs();

        foreach ($map as $wordpressHook => $bundles) {
            foreach ($bundles as $bundle) {
                add_action(
                    $wordpressHook,
                    function () use ($bundle) {
                        echo HtmlHelper::tag('script', null, ['src' => $bundle['src']]);
                    },
                    $bundle['priority']
                );
            }
        }
    }

    public function registerCss()
    {
        $map = $this->mapCss();

        foreach ($map as $wordpressHook => $bundles) {
            foreach ($bundles as $bundle) {
                add_action(
                    $wordpressHook,
                    function () use ($bundle) {
                        echo HtmlHelper::shortTag('link', ['rel' => 'stylesheet', 'href' => $bundle['src']]);
                    },
                    $bundle['priority']
                );
            }
        }
    }
}