<?php

namespace Forms\Builder;

use Forms\Handlers\IFormHandler;

interface IForm
{
    public static function handler(): IFormHandler;

    public function uniqKey(): string;

    public function make(): void;

    public function response();

    public function formHeader(): void;

    public function formBody(): void;

    public function formFooter(): void;
}