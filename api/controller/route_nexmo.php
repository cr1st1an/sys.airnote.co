<?php

class Route_Nexmo {

    public function getDefault() {
        include_once Epi::getPath('data') . 'db_airnotes.php';
        include_once Epi::getPath('data') . 'db_requests.php';
        include_once Epi::getPath('lib') . 'NexmoMessage.php';

        $DB_Airnotes = new DB_Airnotes();
        $DB_Requests = new DB_Requests();

        $NexmoMessage = new NexmoMessage('1dc4eae3', '7a01d8bc');

        if ($NexmoMessage->inboundText()) {
            $id_subscriber = null;
            $id_airnote = null;
            $message = 'Hi! This is an Airnote number and the key you requested is available. If you wish to create an auto reply please go to http://airnote.co/';

            $r_search = $DB_Airnotes->search($_GET['text']);

            if ($r_search['success']) {
                if (1 == $r_search['airnote_data']['active']) {
                    $id_subscriber = $r_search['airnote_data']['id_subscriber'];
                    $id_airnote = $r_search['airnote_data']['id_airnote'];

                    $message = $r_search['airnote_data']['message'];
                } else {
                    $message = 'Hi! This keyword is currently offline. If you wish to create an auto reply please go to http://airnote.co/';
                }
            }

            $insert_data = array(
                'id_subscriber' => $id_subscriber,
                'id_airnote' => $id_airnote,
                'sms_sid' => $_GET['messageId'],
                'account_sid' => '',
                'from_phone' => $_GET['msisdn'],
                'to_phone' => $_GET['to'],
                'body' => $_GET['text'],
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

            $NexmoMessage->reply($message);
        }
    }

    public function postFallback() {
        
    }

    public function postCallback() {
        
    }

}