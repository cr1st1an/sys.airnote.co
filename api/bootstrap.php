<?php

include_once '../vendors/epi/Epi.php';

Epi::setPath('root', '/srv/vhosts/sys.airnote.co');
Epi::setPath('base', Epi::getPath('root') . '/vendors/epi/');
Epi::setPath('config', Epi::getPath('root') . '/api/config/');
Epi::setPath('controller', Epi::getPath('root') . '/api/controller/');
Epi::setPath('data', Epi::getPath('root') . '/api/data/');
Epi::setPath('locale', Epi::getPath('root') . '/api/locale/');
Epi::setPath('lib', Epi::getPath('root') . '/api/lib/');

Epi::init('api', 'config', 'database', 'route', 'session', 'template');

getConfig()->load('default.ini', 'secure.ini');

define('MANDRILL_API_KEY', getConfig()->get('mandrill')->api_key);

EpiDatabase::employ(
        getConfig()->get('db')->type, getConfig()->get('db')->name, getConfig()->get('db')->host, getConfig()->get('db')->username, getConfig()->get('db')->password
);
getDatabase()->execute('SET NAMES utf8mb4 COLLATE utf8mb4_bin;');

EpiSession::employ(
        array(
            EpiSession::MEMCACHED,
            getConfig()->get('memcached')->host,
            getConfig()->get('memcached')->port,
            getConfig()->get('memcached')->compress,
            getConfig()->get('memcached')->expiry
        )
);

// GLOBAL FUNCTIONS
include_once Epi::getPath('locale') . getConfig()->get('locale') . ".php";

function t($key) {
    global $lang;
    if (isset($lang[$key]))
        return $lang[$key];
    else
        return $key;
}

// MAIN CONTROLLER
include_once 'controller/v1.php';

getRoute()->run();