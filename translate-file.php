<?php

$words = [
    'дом',
    'собака',
    'зима',
    'чашка',
    'яблоко',
    'обращаться',
];

foreach ($words as $word) {

    $ch = \curl_init('https://www.translate.ru/services/soap.asmx/CallForvo');

    \curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    \curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    \curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    \curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36');

    \curl_setopt($ch, CURLOPT_POST, true);
    \curl_setopt($ch, CURLOPT_POSTFIELDS, "{ dirCode:'ru-en', dKey:'" . $word . "'}");
    \curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-type: application/json'
    ]);

    $response = \curl_exec($ch);
    \curl_close($ch);

    $mp3URL = \json_decode($response, true)['d']['mp3URL'] ?? '';

    if (empty($mp3URL)) {
        die('MP3 file for ' . $word . ' not found.' . PHP_EOL);
    }

    if (!\copy($mp3URL, $word . '.mp3')) {
        die('Unable copy file ' . $word . '.' . PHP_EOL);
    }

    echo 'File for ' . $word . ' copied!' . PHP_EOL;

}