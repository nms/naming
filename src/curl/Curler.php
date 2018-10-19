<?php

namespace nms\naming\curl;


abstract class Curler implements CurlInterface
{
    /**
     * curl handle
     * @var resource
     */
    public $ch;

    /**
     * curl options
     * @var array
     */
    public $options = [];

    /**
     * curl headers
     * @var array
     */
    public $headers = [];

    /**
     * Curler constructor.
     * @param null $baseUrl
     * @throws \ErrorException
     */
    public function __construct($url = null)
    {
        if (!extension_loaded('curl')) {
            throw new \ErrorException('cURL 扩展未安装!');
        }

        $this->ch = curl_init($url);
    }


    /**
     * add option
     * @param $option
     * @param $value
     */
    public function addOpt($option, $value)
    {
        $this->options[$option] = $value;
    }

    /**
     * add options
     * @param $options
     */
    public function addOpts($options)
    {
        $this->options = array_merge($this->options, $options);
    }


    /**
     * add header
     * @param $key
     * @param $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * add headers
     * @param $headers
     */
    public function addHeaders($headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * set options
     * @return bool
     */
    public function setOpts()
    {
        if (!empty($this->options)) {
            return curl_setopt_array($this->ch, $this->options);
        }
    }

    /**
     * set headers
     * @return bool
     */
    public function setHeaders()
    {
        if (!empty($this->headers)) {
            return curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);
        }
    }


    /**
     * get curl handle
     * @return resource
     */
    public function getCh()
    {
        return $this->ch;
    }

    /**
     * get a new handle with a diferent url
     * @param $url
     * @return bool|resource
     */
    public function getChCopy($url)
    {
        $ch = curl_copy_handle($this->ch);
        if (curl_setopt($ch, CURLOPT_URL, $url)) {
            return $ch;
        }
        return false;
    }
}