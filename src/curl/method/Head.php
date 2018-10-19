<?php
namespace nms\naming\curl\method;


use nms\naming\curl\Curler;

class Head extends Curler
{
    public $options = [
        CURLOPT_NOBODY => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 10,
    ];

    public function __construct($url = null)
    {
        parent::__construct($url);
        $this->setOpts();
    }

}