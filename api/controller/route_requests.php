<?php

class Route_Requests {
    
    public function getRoot() {
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_requests.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $DB_Sessions = new DB_Sessions();
        $DB_Requests = new DB_Requests();

        $Validator = new Validator();
        
        $response = array();
        $get = array();
        $session_data = array();
        $request_count = array();
        
        if (empty($response)) {
            $r_getGetParams = $Validator->getGetParams(array('token'));

            if (!$r_getGetParams['success']) {
                $response = $r_getGetParams;
            } else {
                $get = $r_getGetParams['get'];
            }
        }

        if (empty($response)) {
            $r_search = $DB_Sessions->search($get['token']);

            if (!$r_search['success']) {
                $response = $r_search;
            } else {
                $session_data = $r_search['session_data'];
            }
        }
        
        if (empty($response)) {
            $r_select = $DB_Requests->count($session_data['id_subscriber']);

            if (!$r_search['success']) {
                $response = $r_search;
            } else {
                $request_count = $r_select['request_count'];
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here are the requests for the token '" . $get['token'] . "'.";
            $response['request_count'] = $request_count;
        }
        
        return $response;
    }
    
}