<?php

namespace Bitrix24\CrmField;

interface ICrmField
{
    public function alias($value): self;

    public function getAlias();

    public function normalize();
}