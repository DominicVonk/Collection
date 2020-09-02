<?php

namespace DominicVonk;

class Collection implements \Iterator
{
    private $array = [];
    private $position = 0;
    public function rewind()
    {
        $this->position = 0;
    }
    public function current()
    {
        return $this->array[$this->position];
    }
    public function key()
    {
        return $this->position;
    }
    public function next()
    {
        ++$this->position;
    }
    public function valid()
    {
        return isset($this->array[$this->position]);
    }
    public function reverse()
    {
        return new Collection(array_reverse($this->array));
    }
    public function map($fn)
    {
        return new Collection(array_map($fn, $this->array));
    }
    public function each($callback)
    {
        foreach ($this->array as $index => &$item) {
            if ($callback($item, $index) === false) {
                break;
            }
        }
        return $this;
    }
    public function __construct($array)
    {
        $this->array = array_values($array);
    }
    public function filter($callback)
    {
        $arr = array_filter($this->array, $callback, ARRAY_FILTER_USE_BOTH);
        return new Collection($arr);
    }
    public function sort($cmp_function)
    {
        $arr = array_values($this->array);
        usort($arr, $cmp_function);
        return new Collection($arr);
    }
    public function first()
    {
        $arr = array_values($this->array);
        return reset($arr);
    }
    public function last()
    {
        $arr = array_values($this->array);
        return end($arr);
    }
    public function nth($i)
    {
        return $this->array[$i];
    }
    public function count()
    {
        return count($this->array);
    }
    public function toArray()
    {
        return $this->array;
    }
    public function slice($offset, $length = null)
    {
        return new Collection(array_slice($this->array, $offset, $length));
    }
    public function pluck($v, $k = null)
    {
        $plucked = array();
        foreach ($this->array as $item) {
            if (is_array($item)) {
                $val = $item[$v];
                if ($k === null) {
                    $plucked[] = $val;
                    continue;
                }
                $plucked[$item[$k]] = $val;
            } else {
                $val = $item->{$v};
                if ($k === null) {
                    $plucked[] = $val;
                    continue;
                }
                $plucked[$item->{$k}] = $val;
            }
        }
        return $plucked;
    }
}
