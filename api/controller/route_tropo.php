<?php

class Route_Tropo {

    public function postDefault() {
        include_once Epi::getPath('data') . 'db_airnotes.php';
        include_once Epi::getPath('data') . 'db_requests.php';
        include_once Epi::getPath('lib') . 'tropo.php';

        $DB_Airnotes = new DB_Airnotes();
        $DB_Requests = new DB_Requests();
        
        $Tropo = new Tropo();
        $TropoSession = new Session();

        $id_subscriber = null;
        $id_airnote = null;
        $message = 'Hi! This is an Airnote number and the key you requested is available. If you wish to create an auto reply please go to http://airnote.co/';

        $r_search = $DB_Airnotes->search($TropoSession->getInitialText());

        if ($r_search['success']) {
            if (1 == $r_search['airnote_data']['active']) {
                $id_subscriber = $r_search['airnote_data']['id_subscriber'];
                $id_airnote = $r_search['airnote_data']['id_airnote'];
                
                $message = $r_search['airnote_data']['message'];
            } else {
                $message = 'Hi! This keyword is currently offline. If you wish to create an auto reply please go to http://airnote.co/';
            }
        }
        
        $from_data = $TropoSession->getFrom();
        $to_data = $TropoSession->getTo();
        
        $insert_data = array(
            'id_subscriber' => $id_subscriber,
            'id_airnote' => $id_airnote,
            'sms_sid' => $TropoSession->getCallId(),
            'account_sid' => $TropoSession->getAccountID(),
            'from_phone' => $from_data['id'],
            'to_phone' => $to_data['id'],
            'body' => $TropoSession->getInitialText(),
            'from_city' => '',
            'from_state' => '',
            'from_zip' => '',
            'from_country' => '',
            'to_city' => '',
            'to_state' => '',
            'to_zip' => '',
            'to_country' => ''
        );
        $DB_Requests->insert($insert_data);
        
        $Tropo->say($message);
        $Tropo->RenderJSON();
    }

    public function postFallback() {
        
    }

    public function postCallback() {
        
    }

}