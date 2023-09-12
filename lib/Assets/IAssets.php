<?php

namespace Assets;

interface IAssets
{
    public function registerJs();

    public function registerCss();

    public static function register();
}