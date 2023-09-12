<?php

namespace Forms\Builder;

interface IForm
{
    public function uniqKey();

    public function map(): array;

    public function build();

    public function response();
}