<?php

namespace nms\naming\curl;

interface CurlInterface
{
    public function getCh();
    public function getChCopy($url);

    public function addHeader($key, $value);
    public function addHeaders($headers);

    public function addOpt($key, $value);
    public function addOpts($options);

    public function setOpts();
    public function setHeaders();
}