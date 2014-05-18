<?php
    namespace Thin;

    $containerConfig    = new Container;
    $conf               = array();

    $db = new Container;
    $db->setUsername('root')
    ->setPassword('root')
    ->setDsn('mysql:host=localhost;dbname=ajf');
    $conf['db'] = $db;

    /* ... */

    $models = array();

    $models['db'] = array();
    $models['db']['tables'] = array();
    $models['db']['tables']['book'] = array();
    $models['db']['tables']['book']['relationship'] = array();
    $models['db']['tables']['book']['relationship']['user_id'] = array(
        'type'          => 'manyToOne',
        'fieldName'     => 'user_id',
        'foreignTable'  => 'sf_guard_user',
        'foreignKey'    => 'id',
        'relationKey'   => 'user',
    );

    $containerConfig->setDb($conf);
    $containerConfig->setModels($models);
    container()->setConfig($containerConfig);

    event('redis', function() {
        static $i;
        if (null === $i) {
            $i = new \Predis\Client;
        }
        return $i;
    });

    event('dump', function() {
        echo '<pre>';
        print_r(func_get_args());
        echo '</pre>';
    });

    event('kvs', function() {
        static $i;
        if (null === $i) {
            $i = new Kvdb();
        }
        return $i;
    });

    event('textdb', function() {
        static $i;
        if (null === $i) {
            $i = new Textdb();
        }
        return $i;
    });

    event('bucket', function() {
        static $i;
        if (null === $i) {
            $i = new Bucket('abc');
        }
        return $i;
    });

    event('log', function($str) {
        error_log($str);
    });

    event('db', function($name) {
        static $i = array();
        $db = isAke($i, $name, null);
        if (is_null($db)) {
            $i[$name] = $db = new Memorydb($name);
        }
        return $db;
    });

    event('model', function($name) {
        static $i = array();
        $db = isAke($i, $name, null);
        if (is_null($db)) {
            $i[$name] = $db = new Memorydb($name);
        }
        return $db;
    });

    $conf = function() {
        $args = func_get_args();
        if (count($args) == 1) {
            return registry(Arrays::first($args));
        } elseif (count($args) == 2) {
            registry(Arrays::first($args), Arrays::last($args));
            return container();
        }

        return null;
    };

    $mailer = function () {
        $config = array(
            'host'      => 'smtp.mandrillapp.com',
            'login'     => 'mail@mail.com',
            'password'  => 'password',
            'port'      => 587,
            'secure'    => null,
            'auth'      => true,
            'debug'     => false
        );
        $smtp = new Smtp($config);
        return $smtp;
    };

    event('mailer', $mailer);
    event('reg', $conf);
    event('context', function($context){return context($context);});
