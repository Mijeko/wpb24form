<?php

namespace Forms\Builder;

use Forms\Handlers\IFormHandler;

interface IForm
{
    public static function handler(): IFormHandler;

    public function fields(): array;

    public function uniqKey(): string;

    public function buildForm(): void;

    public function formHeader(): void;

    public function formBody(): void;

    public function formFooter(): void;
}