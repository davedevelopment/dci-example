<?php

namespace BookShop\Util;

trait Delegator 
{
    protected $obj;

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->obj, $method), $args);
    }

    public function __get($property)
    {
        return $this->obj->{$property};
    }

    public function __set($property, $value)
    {
        return $this->obj->{$property} = $value;
    }

    public function delegateTo($obj)
    {
        $this->obj = $obj;
    }
}
