<?php

namespace nms\naming\curl;

use nms\naming\curl\CurlInterface as Curl;
use \Traversable;
class CurlIterator extends \IteratorIterator
{
    public $curl;
    public $baseUrl;

    public function __construct(Traversable $iterator, Curl $curl, $baseUrl)
    {
        parent::__construct($iterator);
        $this->curl = $curl;
        $this->baseUrl = $baseUrl;
    }

    public function current() {
        $baseUrl = $this->baseUrl;
        $path = parent::current();
        return $this->curl->getChCopy($baseUrl . $path);
    }

}