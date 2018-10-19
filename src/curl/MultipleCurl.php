<?php
namespace nms\naming\curl;

class MultipleCurl
{
    /**
     * @var resource Multiple Curl Handle
     */
    protected $master;

    /**
     * @var int Multiple Curl的'线程'数
     */
    public $thread;

    /**
     * @var int
     */
    public $timeout = 10;

    /**
     * @var Closure
     */
    public $callback;

    /**
     * @var AppendIterator
     * url的 path
     */
    public $handlesIterator;

    /**
     * MultipleCurl constructor.
     */
    public function __construct(\Closure $callback, $thread=20)
    {
        $this->master = curl_multi_init();
        $this->handlesIterator = new \AppendIterator();
        $this->callback = $callback;
        $this->thread = $thread;
    }

    /**
     * @param int $thread
     * @return bool
     */
    public function run($thread = 20) {
        $this->handlesIterator->rewind();

        if (!$this->handlesIterator->valid()) {
            return false;
        }

        for ($i = 0; $i < $this->thread; $i++) {
            if ($this->handlesIterator->valid()) {
                curl_multi_add_handle($this->master, $this->handlesIterator->current());
                $this->handlesIterator->next();
            }
        }
        do {

            while(($mrc = curl_multi_exec($this->master, $active)) == CURLM_CALL_MULTI_PERFORM);

            if($mrc != CURLM_OK)
                break;

            while($complete = curl_multi_info_read($this->master)) {

                $info = curl_getinfo($complete['handle']);
                $output = curl_multi_getcontent($complete['handle']);

                if (is_callable($this->callback)) {
                    call_user_func($this->callback, $output, $info);
                }

                if ($this->handlesIterator->valid()) {
                    curl_multi_add_handle($this->master, $this->handlesIterator->current());
                    $this->handlesIterator->next();
                }

                curl_multi_remove_handle($this->master, $complete['handle']);
            }

            if ($active) {
                curl_multi_select($this->master, $this->timeout);
            }
        } while ($active);

        curl_multi_close($this->master);

        return true;
    }


    /**
     * @param $iterator
     * curl handle的 数组或者生成器
     */
    public function addHandles($iterator)
    {
        if (!$iterator instanceof \Iterator) {
            $iterator = new \ArrayIterator($iterator);
        }

        $this->handlesIterator->append($iterator);
    }

}
