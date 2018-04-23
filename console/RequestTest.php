<?php

include_once '../bootstrap-cli.php';

use Http\Request\HttpClient;
use Http\Request\Request;
use Http\Request\Uri;

$request = (new Request())
    ->withUri(new Uri('http://petrovich.konovalov.dev.local/'));

echo (new HttpClient())
    ->sendRequest($request)
    ->getResponse()
    ->returnOutput();