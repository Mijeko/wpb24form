<?php

namespace Http;

interface ICurlWrapper
{
    public function post(string $url, array $params);

    public function get(string $url, array $params);
}