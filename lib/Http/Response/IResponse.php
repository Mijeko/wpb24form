<?php

namespace Http\Response;

interface IResponse
{
    public function success(): self;

    public function error(): self;

    public function out(array $content = null);
}