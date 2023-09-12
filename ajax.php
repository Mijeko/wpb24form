<?php

require_once 'lib/autoload.php';

$router = new \Ajax\AjaxRequestRouter();
$request = $_REQUEST;

$action = $request['action'];
unset($request['action']);


$router->route($action, $request);