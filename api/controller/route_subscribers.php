<?php

class Route_Subscribers {

    public function getRoot() {
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Sessions = new DB_Sessions();
        $DB_Subscribers = new DB_Subscribers();

        $Validator = new Validator();

        $response = array();
        $get = array();
        $session_data = array();
        $subscriber_data = array();

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
            $r_select = $DB_Subscribers->select($session_data['id_subscriber']);

            if (!$r_search['success']) {
                $response = $r_search;
            } else {
                $subscriber_data = $r_select['subscriber_data'];
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here is the subscriber for the token '" . $get['token'] . "'.";
            $response['pack_data'] = getConfig()->get('pack_' . $subscriber_data['pack']);
        }

        return $response;
    }

    public function postRoot() {
        include_once Epi::getPath('data') . 'db_airnotes.php';
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Airnotes = new DB_Airnotes();
        $DB_Sessions = new DB_Sessions();
        $DB_Subscribers = new DB_Subscribers();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $id_subscriber = null;
        $token = null;

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('keyword', 'email', 'password'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $insert_data = array(
                'email' => $post['email'],
                'password' => $post['password']
            );
            $r_insert = $DB_Subscribers->insert($insert_data);

            if (!$r_insert['success']) {
                $response = $r_insert;
            } else {
                $id_subscriber = $r_insert['id_subscriber'];
            }
        }

        if (empty($response)) {
            $r_insert_2 = $DB_Sessions->insert($id_subscriber);

            if (!$r_insert_2['success']) {
                $response = $r_insert_2;
            } else {
                $token = $r_insert_2['token'];
            }
        }

        if (empty($response)) {
            $r_search = $DB_Airnotes->search($post['keyword']);
            if ($r_search['success']) {
                $DB_Airnotes->updateSubscriber($r_search['airnote_data']['id_airnote'], $id_subscriber);
            }

            $response['success'] = true;
            $response['message'] = "The user with email '" . $post['email'] . "' has been created.";
            $response['token'] = $token;
        }

        return $response;
    }

    public function postLogin() {
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Sessions = new DB_Sessions();
        $DB_Subscribers = new DB_Subscribers();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $id_subscriber = null;
        $token = null;

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('email', 'password'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $r_login = $DB_Subscribers->login($post['email'], $post['password']);

            if (!$r_login['success']) {
                $response = $r_login;
            } else {
                $id_subscriber = $r_login['subscriber_data']['id_subscriber'];
            }
        }

        if (empty($response)) {
            $r_insert = $DB_Sessions->insert($id_subscriber);

            if (!$r_insert['success']) {
                $response = $r_insert;
            } else {
                $token = $r_insert['token'];
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "The login details are valid, here's the token.";
            $response['token'] = $token;
        }

        return $response;
    }

    public function postSearch() {
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Subscribers = new DB_Subscribers();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $subscriber_data = array();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('email'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response) && !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $response['success'] = false;
            $response['message'] = "The email '" . $post['email'] . "' is invalid";
            $response['err'] = 13;
        }

        if (empty($response)) {
            $r_search = $DB_Subscribers->search($post['email']);

            if (!$r_search['success']) {
                $response = $r_search;
            } else {
                $subscriber_data = $r_search['subscriber_data'];
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here is the subscriber with email '" . $subscriber_data['email'] . "'."; // make sure to return safe info
        }

        return $response;
    }
    
    public function postPack() {
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_subscribers.php';
        include_once Epi::getPath('lib') . 'validator.php';
        
        $DB_Sessions = new DB_Sessions();
        $DB_Subscribers = new DB_Subscribers();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $session_data = array();
        $pack_id = null;

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('token', 'pack_id'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $r_search = $DB_Sessions->search($post['token']);

            if (!$r_search['success']) {
                $response = $r_search;
            } else {
                $session_data = $r_search['session_data'];
            }
        }
        
        if (empty($response)) {
            if(3 < (int)$post['pack_id'] || 0 > (int)$post['pack_id']){
                $response['success'] = false;
                $response['message'] = "The selected pack does not exist.";
                $response['err'] = 0;
            } else {
                $pack_id = (int) $post['pack_id'];
            }
        }
        
        if (empty($response)) {
            $r_updatePack = $DB_Subscribers->updatePack($session_data['id_subscriber'], $pack_id);

            if (!$r_updatePack['success']) {
                $response = $r_updatePack;
            }
        }
        
        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "The subscriber with id '" . $session_data['id_subscriber'] . "' was updated successfully.";
            $response['pack'] = $pack_id;
        }

        return $response;
    }
    
}