<?php
$basePath = dirname(__DIR__);
$qa = [];
foreach (glob($basePath . '/raw/json/*.json') as $jsonFile) {
    $json = json_decode(file_get_contents($jsonFile), true);
    $qa = array_merge($qa, $json);
}
file_put_contents($basePath . '/qa.json', json_encode($qa, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
