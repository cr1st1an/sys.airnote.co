<?php

include_once Epi::getPath('controller') . 'route_airnotes.php';
getApi()->get('/v1/airnotes', array('Route_Airnotes', 'getRoot'), EpiApi::external);
getApi()->post('/v1/airnotes', array('Route_Airnotes', 'postRoot'), EpiApi::external);
getApi()->post('/v1/airnotes/active', array('Route_Airnotes', 'postActive'), EpiApi::external);
getApi()->post('/v1/airnotes/delete', array('Route_Airnotes', 'postDelete'), EpiApi::external);
getApi()->post('/v1/airnotes/search', array('Route_Airnotes', 'postSearch'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_requests.php';
getApi()->get('/v1/requests', array('Route_Requests', 'getRoot'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_subscribers.php';
getApi()->get('/v1/subscribers', array('Route_Subscribers', 'getRoot'), EpiApi::external);
getApi()->post('/v1/subscribers', array('Route_Subscribers', 'postRoot'), EpiApi::external);
getApi()->post('/v1/subscribers/login', array('Route_Subscribers', 'postLogin'), EpiApi::external);
getApi()->post('/v1/subscribers/pack', array('Route_Subscribers', 'postPack'), EpiApi::external);
getApi()->post('/v1/subscribers/search', array('Route_Subscribers', 'postSearch'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_nexmo.php';
getApi()->get('/v1/nexmo', array('Route_Nexmo', 'getDefault'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_tropo.php';
getApi()->post('/v1/tropo', array('Route_Tropo', 'postDefault'), EpiApi::external);

include_once Epi::getPath('controller') . 'route_twillio.php';
getApi()->post('/v1/twillio/request', array('Route_Twillio', 'postRequest'), EpiApi::external);

function block() {
    return array(
        'success' => false,
        'message' => t('These are not the endpoints you are looking for. inbox [at] airnote [dot] co')
    );
}
getApi()->get('(.*)', 'block', EpiApi::external);
getApi()->post('(.*)', 'block', EpiApi::external);
getApi()->delete('(.*)', 'block', EpiApi::external);
getApi()->put('(.*)', 'block', EpiApi::external);


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");