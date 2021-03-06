<?php

Router::connect("/pisma/:page", array('plugin' => 'Pisma', 'controller' => 'Pisma', 'action' => 'patrzymynawas'), array('page' => '(?i:trybunal)'));
Router::connect("/pisma/:page", array('plugin' => 'Pisma', 'controller' => 'Pisma', 'action' => 'patrzymynawas'), array('page' => '(?i:trybunał)'));
Router::connect("/pisma/:page", array('plugin' => 'Pisma', 'controller' => 'Pisma', 'action' => 'patrzymynawas'), array('page' => '(?i:ratujmytrybunal)'));
Router::connect("/pisma/:page", array('plugin' => 'Pisma', 'controller' => 'Pisma', 'action' => 'patrzymynawas'), array('page' => '(?i:ratujmytrybunał)'));

$pisma_prefixes = array('/pisma');
foreach ($pisma_prefixes as $pisma_prefix) {
    Router::connect("$pisma_prefix", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'my',
        '[method]' => 'GET'
    ));
    Router::connect("$pisma_prefix/nowe", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'home',
        '[method]' => 'GET'
    ));
    Router::connect("$pisma_prefix/moje", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'my',
        '[method]' => 'GET'
    ));
    Router::connect("$pisma_prefix/moje", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'post',
        '[method]' => 'POST'
    ));
    Router::connect("$pisma_prefix", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'create',
        '[method]' => 'POST'
    ));


    // VIEW


    // GET
    Router::connect("$pisma_prefix/:id,:slug", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'view',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id', 'slug')));

    Router::connect("$pisma_prefix/:id", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'view',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));

    Router::connect("$pisma_prefix/:id,", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'view',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));


    // POST
    Router::connect("$pisma_prefix/:id,:slug", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'post',
        '[method]' => 'POST'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id', 'slug')));

    Router::connect("$pisma_prefix/:id", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'post',
        '[method]' => 'POST'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));

    Router::connect("$pisma_prefix/:id,", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'post',
        '[method]' => 'POST'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));


    // PUT
    Router::connect("$pisma_prefix/:id,:slug", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'put',
        '[method]' => 'PUT'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id', 'slug')));

    Router::connect("$pisma_prefix/:id", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'put',
        '[method]' => 'PUT'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));

    Router::connect("$pisma_prefix/:id,", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'put',
        '[method]' => 'PUT'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));


    // EDIT

    Router::connect("$pisma_prefix/:id,:slug/edit", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'edit',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id', 'slug')));

    Router::connect("$pisma_prefix/:id/edit", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'edit',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));

    Router::connect("$pisma_prefix/:id,/edit", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'edit',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));


    // HTML

    Router::connect("$pisma_prefix/:id,:slug/html", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'html',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id', 'slug')));

    Router::connect("$pisma_prefix/:id/html", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'html',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));

    Router::connect("$pisma_prefix/:id,/html", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'html',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));


    // SHARE

    Router::connect("$pisma_prefix/:id,:slug/share", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'share',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id', 'slug')));

    Router::connect("$pisma_prefix/:id/share", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'share',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));

    Router::connect("$pisma_prefix/:id,/share", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'share',
        '[method]' => 'GET'
    ), array('id' => '[A-Za-z0-9]{5}', 'pass' => array('id')));

	Router::connect("$pisma_prefix/:action", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        '[method]' => 'GET'
    ));

    /*
    Router::connect("$pisma_prefix/szablony/:id", array(
        'plugin' => 'Pisma',
        'controller' => 'Szablony',
        'action' => 'view'
    ), array('id' => '[0-9]+', 'pass' => array('id')));

    Router::connect("$pisma_prefix/szablon/:szablon_id/adresat/:adresat_id", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'editor',
        '[method]' => 'GET'
    ), array(
        'szablon_id' => '[0-9]+',
        'adresat_id' => '[0-9]+',
    ));

    Router::connect("$pisma_prefix/szablon/:szablon_id", array(
        'plugin' => 'Pisma',
        'controller' => 'Pisma',
        'action' => 'editor',
        '[method]' => 'GET'
    ), array(
        'szablon_id' => '[0-9]+',
    ));
    */

}
