<?php

require_once('src/Request.php');
require_once('src/TextProcessingClient.php');

$key = file_get_contents('key');
$language = 'dutch';
$text = 'Hallo Theuy, hoe gaat het?';

$client = new TextProcessingClient($key);
$response = $client->tag($language, $text);

var_dump($response);