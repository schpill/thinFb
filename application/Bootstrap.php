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
                    require_once($file);
                }
            }
        }
    }
