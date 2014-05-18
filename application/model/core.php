<?php
    namespace Thin;
    $db = new Container;

    $functions = array();
    $functions['owner'] = function($obj) {
        return $obj->model('user')->find($obj->getOwner());
    };
    $functions['user'] = function($obj) {
        return $obj->model('user')->find($obj->getUser());
    };
    $functions['object'] = function($obj) {
        return $obj->model($obj->getClass())->find($obj->getParent());
    };

    $db->model('notification')->config('functions', $functions);
