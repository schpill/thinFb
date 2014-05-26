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

    $functions = array();
    $functions['table'] = function($obj) {
        return $obj->model('rma_table')->find($obj->getTable());
    };
    $functions['field'] = function($obj) {
        return $obj->model('rma_field')->find($obj->getField());
    };

    $db->model('rma_structure')->config('functions', $functions);

    $functions = array();
    $functions['table'] = function($obj) {
        return $obj->dbm('dma_table')->find($obj->getTable());
    };
    $functions['field'] = function($obj) {
        return $obj->dbm('dma_field')->find($obj->getField());
    };

    $db->dbm('dma_structure')->config('functions', $functions);

    $functions = array();
    $functions['string'] = function($obj) {
        return $obj->getFirstname() . ' ' . $obj->getName();
    };

    $db->model('user')->config('functions', $functions);

    $functions = array();
    $functions['string'] = function($obj) {
        return $obj->getName();
    };

    // $db->dbm('product')->config('functions', $functions);

    $functions = array();
    $functions['table'] = function($obj) {
        return $obj->sbm('sma_table')->find($obj->getTable());
    };
    $functions['field'] = function($obj) {
        return $obj->sbm('sma_field')->find($obj->getField());
    };

    $db->sbm('sma_structure')->config('functions', $functions);

    $functions = array();
    $functions['table'] = function($obj) {
        return $obj->qbm('qma_table')->find($obj->getTable());
    };
    $functions['field'] = function($obj) {
        return $obj->qbm('qma_field')->find($obj->getField());
    };

    $db->qbm('qma_structure')->config('functions', $functions);
