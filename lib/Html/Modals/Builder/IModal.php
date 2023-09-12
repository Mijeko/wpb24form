<?php

namespace Html\Modal\Builder;

use Forms\Builder\IForm;

interface IModal
{
    public function __construct(string $title, IForm $content = null, string $description = null);

    public function map(): array;

    public function build();

    public function response();
}