<?php

class Route_Airnotes {

    public function getRoot() {
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_airnotes.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Airnotes = new DB_Airnotes();
        $DB_Sessions = new DB_Sessions();

        $Validator = new Validator();

        $response = array();
        $get = array();
        $session_data = array();
        $airnotes_data = array();

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
            $r_selectAll = $DB_Airnotes->selectAll($session_data['id_subscriber']);

            if (!$r_selectAll['success']) {
                $response = $r_selectAll;
            } else {
                $airnotes_data = $r_selectAll['airnotes_data'];
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here are the airnotes for the token '" . $get['token'] . "'.";
            $response['airnotes_data'] = $airnotes_data;
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
        $session_data = array();
        $subscriber_data = array();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('keyword', 'message'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $r_search = $DB_Sessions->search($_POST['token']);

            if ($r_search['success']) {
                $session_data = $r_search['session_data'];
                
                $r_select = $DB_Subscribers->select($session_data['id_subscriber']);
                if ($r_search['success']) {
                    $subscriber_data = $r_select['subscriber_data'];
                }
            }
        }

        if (empty($response)) {
            $message = $post['message'];
            if(isset($subscriber_data['pack'])){
                $message .= getConfig()->get('pack_'.$subscriber_data['pack'])->append;
            } else {
                $message .= getConfig()->get('pack_0')->append;
            }
            
            $insert_data = array(
                'keyword' => str_replace(' ', '', strtolower($post['keyword'])),
                'message' => $message
            );
            $r_insert = $DB_Airnotes->insert($insert_data);

            if (!$r_insert['success']) {
                $response = $r_insert;
            } else if (!empty($session_data)) {
                $DB_Airnotes->updateSubscriber($r_insert['id_airnote'], $session_data['id_subscriber']);
            }
        }

        if (empty($response)) {
            getSession()->set('keyword', $post['keyword']);

            $response['success'] = true;
            $response['message'] = "The airnote with keyword '" . $post['keyword'] . "' has been created.";
        }

        return $response;
    }

    public function postSearch() {
        include_once Epi::getPath('data') . 'db_airnotes.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Airnotes = new DB_Airnotes();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $airnote_data = array();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('keyword'));

            if (!$r_getPostParams['success']) {
                $response = $r_getPostParams;
            } else {
                $post = $r_getPostParams['post'];
            }
        }

        if (empty($response)) {
            $r_search = $DB_Airnotes->search($post['keyword']);

            if (!$r_search['success']) {
                $response = $r_search;
            } else {
                $airnote_data = $r_search['airnote_data'];
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here is the airnote with keyword '" . $airnote_data['keyword'] . "'.";
            $response['airnote_data'] = $airnote_data;
        }

        return $response;
    }

    public function postActive() {
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_airnotes.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Airnotes = new DB_Airnotes();
        $DB_Sessions = new DB_Sessions();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $session_data = array();
        $airnote_data = array();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('token', 'id_airnote'));

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
            $r_select = $DB_Airnotes->select($post['id_airnote']);

            if (!$r_select['success']) {
                $response = $r_select;
            } else {
                $airnote_data = $r_select['airnote_data'];
            }
        }

        if (empty($response)) {
            if ($session_data['id_subscriber'] !== $airnote_data['id_subscriber']) {
                $response['success'] = false;
                $response['message'] = "Looks like you can't do this";
                $response['err'] = 0;
            }
        }

        if (empty($response)) {
            $active = 0;
            if (0 == $airnote_data['active'])
                $active = 1;
            $r_updateActive = $DB_Airnotes->updateActive($airnote_data['id_airnote'], $active);

            if (!$r_updateActive['success']) {
                $response = $r_updateActive;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "The airnote with id '" . $airnote_data['id_airnote'] . "' was updated successfully.";
            $response['active'] = $active;
        }

        return $response;
    }

    public function postDelete() {
        include_once Epi::getPath('data') . 'db_sessions.php';
        include_once Epi::getPath('data') . 'db_airnotes.php';
        include_once Epi::getPath('lib') . 'validator.php';

        $DB_Airnotes = new DB_Airnotes();
        $DB_Sessions = new DB_Sessions();

        $Validator = new Validator();

        $response = array();
        $post = array();
        $session_data = array();
        $airnote_data = array();

        if (empty($response)) {
            $r_getPostParams = $Validator->getPostParams(array('token', 'id_airnote'));

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
            $r_select = $DB_Airnotes->select($post['id_airnote']);

            if (!$r_select['success']) {
                $response = $r_select;
            } else {
                $airnote_data = $r_select['airnote_data'];
            }
        }

        if (empty($response)) {
            if ($session_data['id_subscriber'] !== $airnote_data['id_subscriber']) {
                $response['success'] = false;
                $response['message'] = "Looks like you can't do this.";
                $response['err'] = 0;
            }
        }

        if (empty($response)) {
            if (1 == $airnote_data['active']) {
                $response['success'] = false;
                $response['message'] = "Please deactivate the airnote first.";
                $response['err'] = 0;
            }
        }

        if (empty($response)) {
            $r_updateStatus = $DB_Airnotes->updateStatus($airnote_data['id_airnote'], 0);

            if (!$r_updateStatus['success']) {
                $response = $r_updateStatus;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "The airnote with id '" . $airnote_data['id_airnote'] . "' was deleted successfully.";
        }

        return $response;
    }

}