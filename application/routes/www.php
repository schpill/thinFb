<?php
    namespace Thin;
    $router = context('router');

    $route   = new Container;
    $route->setName(404)
    ->setModule('www')
    ->setController('static')
    ->setAction('is-404')
    ->setLanguage('fr')
    ->setPath('/404');
    $router->route($route);

    $route   = new Container;
    $route->setName('home')
    ->setModule('www')
    ->setController('static')
    ->setAction('home')
    ->setLanguage('fr')
    ->setPath('/');
    $router->route($route);

    $route   = new Container;
    $route->setName('test')
    ->setModule('www')
    ->setController('static')
    ->setAction('test')
    ->setLanguage('fr')
    ->setParam1('language')
    ->setSettings1(function ($culture) use ($route) {
        return checkCulture($culture, $route);
    })
    ->setPath('/(.*)/test.html');
    $router->route($route);
