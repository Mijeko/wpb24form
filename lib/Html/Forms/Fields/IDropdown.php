<?php

namespace Forms\Fields;

interface IDropdown
{
    public function getVariants(): array;

    public function getDefault();

    public function default($default);
}