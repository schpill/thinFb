<?php
    namespace Thin;

    class Bootstrap
    {
        public static function start()
        {
            static::config();
            static::context();
            static::model();
            static::routes();
            static::tests();
            static::run();
        }

        public static function cli()
        {
            static::config();
            static::context();
            static::model();
        }

        private static function config()
        {
            session_start();
            define('DIR_APPS', __DIR__);
            static::load(__DIR__ . DS . 'config' . DS . '*.php');
        }

        private static function context()
        {
            static::load(__DIR__ . DS . 'context' . DS . '*.php');
        }

        private static function model()
        {
            static::load(__DIR__ . DS . 'model' . DS . '*.php');
        }

        private static function routes()
        {
            static::load(__DIR__ . DS . 'routes' . DS . '*.php');
            Router::context();
        }

        private static function run()
        {
            Router::deliver();
        }

        private static function tests()
        {
            static::load(__DIR__ . DS . 'tests' . DS . '*.php');
        }

        private static function load($pattern)
        {
            $files = glob($pattern);
            if (count($files)) {
                foreach ($files as $file) {
                    static::inc($file);
                }
            }
        }

        private static function inc($file)
        {
            $check = function () {
                if (php_sapi_name() !== 'cli') {
                    if (version_compare(phpversion(), '5.4.0', '>=')) {
                        return session_status() === PHP_SESSION_ACTIVE ? true : false;
                    } else {
                        return session_id() === '' ? false : true;
                    }
                }
                return false;
            };
            if (false === $check()) {
                require_once $file;
            } else {
                $session = session('bootstrap');
                $key = sha1($file);
                $getter = getter($key);
                $setter = setter($key);
                $sessObj = $session->$getter();
                if (null === $sessObj) {
                    $code = fgc($file);
                    eval($code);
                    $session->$setter($code);
                } else {
                    eval($sessObj);
                }
            }
        }
    }
