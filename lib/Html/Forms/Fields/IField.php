<?php

namespace Forms\Fields;

interface IField
{
    public function __construct(string $name, array $options = array());

    public static function build(string $name, array $options = array()): self;

    public function value($value);

    public function getName();

    public function getValue();

}