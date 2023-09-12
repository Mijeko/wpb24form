<?php

namespace Http\Response;

interface IResponse
{
    public function input($content);
    public function out();
}