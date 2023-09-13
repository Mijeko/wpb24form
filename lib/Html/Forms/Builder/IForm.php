<?php

namespace Forms\Builder;

interface IForm
{
    public function uniqKey(): string;

    public function make(): void;

    public function response();

    public function formHeader(): void;

    public function formBody(): void;

    public function formFooter(): void;
}