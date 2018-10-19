<?php
require '../../../autoload.php';

use nms\naming\curl\method\Head;
use nms\naming\curl\Pather;
use nms\naming\curl\MultipleCurl;
use nms\naming\curl\CurlIterator;

$head = new Head();

$pather = new Pather(range('a', 'z'), 5);
$paths = $pather->permutations();

$curlIterator = new CurlIterator($paths, $head, 'https://github.com/');

$callback = function ($output, $info) {
    // 404 代表未占用或者 付费用户占用
    // 301 代表github 内置路由
    // 200 代表已被占用
    $name = ltrim(parse_url($info['url'])['path'], '/');
    $httpCode = $info['http_code'];
    echo 'name: ' . $name . ' http_code: ' . $httpCode . PHP_EOL;
};

$mult = new MultipleCurl($callback, 30);
$mult->addHandles($curlIterator);

$mult->run();