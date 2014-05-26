<?php
    namespace Thin;

    $core = context();
    $core->debug(function($value = null, $message = null) {
        $trace      = debug_backtrace();
        $fichier    = basename($trace[0]["file"]);
        $ligne      = $trace[0]["line"];
        $printTrace = function($trace) {
            unset($trace[0]);
            $disp = null;
            if (count($trace) > 0) {
                $disp = '<ul class="caller">';
                foreach ($trace as $entry) {
                    $disp .= '<li class="caller">Call : <b>';
                    if (isset($entry["class"])) {
                        $disp .= $entry["class"] . "::" . $entry["function"];
                    } else {
                        $disp .= $entry["function"];
                    }
                    $disp .= "()</b>";
                    if (isset($entry["file"])) {
                        $disp .= "<br />Into : <i>";
                        $disp .= $entry["file"];
                        $disp .= " on line " . $entry["line"];
                        $disp .= "</i>";
                    }
                    $disp .= "</li>";
                }
                $disp .= "</ul>";
            }
            return $disp;
        };

        $intro = '<div class="file">Into : ' . $fichier . " on line " . $ligne . "</div>";

        $disp = ''
            . PHP_EOL . '<style>'
            . PHP_EOL . 'div.Debug {text-align:left; }'
            . PHP_EOL . 'div.Debug pre {padding:10px; color:#333333; background-color:#DDDDDD; font-family: mono; font-size: 9pt; line-height:10pt;}'
            . PHP_EOL . 'div.Debug .file {color:#060606; font-style:italic; padding-bottom:5px;}'
            . PHP_EOL . 'div.Debug .message {color:#006600;}'
            . PHP_EOL . 'div.Debug .stabilo {background-color:yellow; padding-left:3px; padding-right:3px;}'
            . PHP_EOL . 'div.Debug .caller {color:#C0222A; list-style:square; margin:5px; line-height:9pt;}'
            . PHP_EOL . 'div.Debug pre strong em {color:#993300;}'
            . PHP_EOL . '</style>'
            . PHP_EOL;

        $disp .= PHP_EOL . PHP_EOL . '<!-- START DEBUG -->' . PHP_EOL . '<div class="Debug">' . PHP_EOL . '<pre>' . PHP_EOL;

        if (is_object($value)) {
            $disp .= $intro . '<span class="message">' . $message . '</span> => ';
            $disp .= print_r($value, true);
            $disp .= $printTrace($trace);
        } elseif (is_array($value)) {
            $disp .= $intro . '<span class="message">' . $message . '</span> => ';
            $disp .= print_r($value, true);
            $disp .= $printTrace($trace);
        } elseif (is_bool($value)){
            $disp .= $intro . '<span class="message">' . $message . '</span> => ' . ucfirst(gettype($value)) . PHP_EOL;
            if ($value) {
                $value = 'True'.PHP_EOL;
            } else{
                $value = 'False'.PHP_EOL;
            }
            $disp .= '{' . PHP_EOL . '    [] => ' . $value . '}' . PHP_EOL;
            $disp .= $printTrace($trace);
        } elseif (is_null($value)){
            $disp .= $intro . '<span class="stabilo">' . $message . '</span>';
            $disp .= $printTrace($trace);
        } elseif (is_string($value) && is_file($value)) {
            $disp .= $intro . '<span class="message">' . $message . '</span> => File' . PHP_EOL;
            $disp .= '{' . PHP_EOL . '    [] => ' . $value . PHP_EOL . '}' . PHP_EOL;
        } else {
            $disp .= $intro . '<span class="message">' . $message . '</span> => ' . ucfirst(gettype($value)) . PHP_EOL;
            $disp .= '{' . PHP_EOL . '    [] => ' . $value . PHP_EOL . '}' . PHP_EOL;
            $disp .= $printTrace($trace);
        }
        $disp .= '</pre>' . PHP_EOL . '</div>' . PHP_EOL . '<!-- END DEBUG -->' . PHP_EOL . PHP_EOL;
        echo $disp;
    });

    $core->config(function ($name, $value = null) {
        static $settings = array();
        if (func_num_args() === 1) {
            if (is_array($name)) {
                $settings = array_merge($settings, $name);
            } else {
                return array_key_exists($name, $settings) ? $settings[$name] : null;
            }
        } else {
            $settings[$name] = $value;
        }
    });

    $core->cache(function ($key, $value = null) {
        $db = container()->redis();
        $key = SITE_NAME . '_cache_' . Inflector::lower($key);
        if (!strlen($value)) {
            $val = $db->get($key);
            if (strlen($val)) {
                return json_decode($val, true);
            }
            return null;
        }
        $db->set($key, json_encode($value));
        $db->expire($key, 3600);
    });

    $router = context('router');

    $router->error(function($data) {
        header('content-type: application/json; charset=utf-8');
        die(json_encode($data));
    });

    $router->route(function ($route) {
        $routes = context('router')->getRoutes();
        $routes = is_null($routes) ? array() : $routes;
        array_push($routes, $route);
        context('router')->setRoutes($routes);
    });


    context('socket')->emit(
        function ($page) {
            $socket = new Socketio('http://localhost:7777');
            $socket->init()->emit('live', array($page));
        }
    );

    context('db')->table(function($table) {
        return em('db', $table);
    });

    context()->isPost(function($except = array()) {
        if (count($_POST) && count($except)) {
            foreach ($except as $key) {
                if (Arrays::exists($key, $_POST)) {
                    unset($_POST[$key]);
                }
            }
        }
        return count($_POST) ? true : false;
    });

    function checkCulture($culture, $route)
    {
        $test1 = ctype_alpha($culture);
        $test2 = ctype_lower($culture);
        $test3 = strlen($culture) == 2;
        $check = $test1 && $test2 && $test3;
        if (true === $check) {
            session('web')->setLanguage($culture);
            $route->setLanguage($culture);
            return true;
        }
        return false;
    }
