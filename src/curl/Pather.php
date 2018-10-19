<?php
/**
 * 26个英文字母的全排列,包含重复值
 */
namespace nms\naming\curl;


class Pather
{

    protected $size;
    protected $values = [];

    public function __construct($values=[], $size=3)
    {
        $this->size = $size;
        if (!empty($values) && is_array($values)) {
            $this->values = $values;
        }
    }

    public function permutations()
    {
        $values = $this->values;
        $size = $this->size;

        if (!empty($values)) {
            // $res = [];
            $count = count($values);
            $pow = pow($count, $size);
            for ($i = 0; $i<$pow; $i++) {
                $tmp = [];
                for ($j = 0; $j < $size; $j++) {
                    $selector = ($i / pow($count, $j)) % $count;
                    $tmp[$j] = $values[$selector];
                }
                yield implode('', $tmp);
                // $res[$i] = $tmp;
            }
            // return $res;
        }

    }

    public function setValues($values)
    {
        $this->values = $values;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }
}