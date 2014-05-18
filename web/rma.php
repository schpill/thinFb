<?php
    namespace Thin;

    require_once __DIR__ . DIRECTORY_SEPARATOR . 'init.php';
    require_once APPLICATION_PATH . DS . 'Bootstrap.php';

    Bootstrap::cli();

    $error = null;
    $action = request()->getAction();

    $action = is_null($action) ? 'home' : $action;

    $sbAuth     = container()->model('rma_auth');
    $session    = session('rma');
    $auth       = $session->getAuth();
    $isAuth     = !is_null($auth);

    if (!$isAuth && count($_POST)) {
        $login      = request()->getLogin();
        $password   = request()->getPassword();
        if (!is_null($login) && !is_null($password)) {
            $count = $sbAuth->where("login = $login")->where('password = ' . sha1($password))->count();
            if (0 < $count) {
                $isAuth = true;
                $session->setAuth($isAuth);
            }
        } else {
            $error = 'Wrong credentials.';
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>redisMyAdmin</title>
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" media="screen" rel="stylesheet" type="text/css" />
        <link href="http://fonts.googleapis.com/css?family=Oswald:400,300,700|Questrial:400,300,700,900,600&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css" />
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" />
        <style>
        .affix {
            position: fixed;
        }

        body {
            font-family: Oswald;
            background: #222;
            color: #f1f1f1;
            font-size: 18px;
        }

        .half, .third, .fourth, .fifth, .sixth {
            float: left;
            min-height: 1px;
            margin-right: 2%;
        }

        .half {
            width: 49%;
        }

        .third {
            width: 32%;
        }

        .fourth {
            width: 23.5%;
        }

        .fifth {
            width: 18.4%;
        }

        .sixth {
            width: 15%;
        }

        .half:last-child, .third:last-child, .fourth:last-child, .fifth:last-child, .sixth:last-child {
            margin-right: 0;
        }
        section {
            margin-top: 50px;
            max-width: 100% !important;
            *zoom: 1;
        }

        section:before,
        section:after {
            display: table;
            line-height: 0;
            content: "";
        }

        section:after {
            clear: both;
        }

        .title {
            padding: 10px;
            border: solid 1px;
        }

        .bordered {
            border: solid 1px;
            padding: 10px;
        }

        button, input, textarea, select {
            color: #f1f1f1;
            background: #222;
            border: solid 1px;
            padding: 10px;
            margin-bottom: 25px;
            margin-right: 25px;
        }

        .title, button {
            cursor: pointer;
        }

        .title:hover, button:hover {
            color: #ffdd00;
        }

        .inRow {
            margin-bottom: 25px;
        }
        </style>
    </head>
    <body>
        <div class="container">
            <?php if(false === $isAuth): ?>
            <h1 class="text-center">
                <span onclick="document.location.href = '/rma.php';" class="title">
                    <i class="fa fa-cogs fa-3x"></i>&nbsp;&nbsp;redisMyAdmin
                </span>
            </h1>
            <section id="auth" class="row text-center">
                <form action="" method="post" id="authForm">
                    <i class="fa fa-user fa-3x inRow"></i><p />
                    <input required id="login" name="login" placeholder="login" />
                    <input required type="password" id="password" name="password" placeholder="password" />
                    <button onclick="document.getElementById('authForm').submit();">OK</button>
                </form>
            </section>
            <?php else: ?>
                <i class="fa fa-money fa-3x"></i>
                Tables
            <?php endif; ?>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <script src="//cdn.cityzend.com/u/45880241/cdn/js/hover.js"></script>
        <script>
        $(document).ready(function() {
            $('.openHover').dropdownHover().dropdown();
            $('[rel=tooltip]').tooltip({
                placement: 'bottom'
            });
            $('[rel=tooltip-b]').tooltip({
                placement: 'bottom'
            });
            $('[rel=tooltip-t]').tooltip({
                placement: 'bottom'
            });
            $('[rel=tooltip-l]').tooltip({
                placement: 'left'
            });
            $('[rel=tooltip-r]').tooltip({
                placement: 'right'
            });
        });
        </script>
    </body>
</html>
