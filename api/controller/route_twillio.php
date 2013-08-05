<?php

class Route_Twillio {

    public function postRequest() {
        include_once Epi::getPath('data') . 'db_airnotes.php';
        include_once Epi::getPath('data') . 'db_requests.php';

        $DB_Airnotes = new DB_Airnotes();
        $DB_Requests = new DB_Requests();

        $id_subscriber = null;
        $id_airnote = null;
        $message = 'Hi! This is an Airnote number and the key you requested is available. If you wish to create an auto reply please go to http://airnote.co/';

        $r_search = $DB_Airnotes->search($_POST['Body']);

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
            'sms_sid' => $_POST['SmsSid'],
            'account_sid' => $_POST['AccountSid'],
            'from_phone' => $_POST['From'],
            'to_phone' => $_POST['To'],
            'body' => $_POST['Body'],
            'from_city' => $_POST['FromCity'],
            'from_state' => $_POST['FromState'],
            'from_zip' => $_POST['FromZip'],
            'from_country' => $_POST['FromCountry'],
            'to_city' => $_POST['ToCity'],
            'to_state' => $_POST['ToState'],
            'to_zip' => $_POST['ToZip'],
            'to_country' => $_POST['ToCountry']
        );
        $DB_Requests->insert($insert_data);

        header("content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>\n";
        echo "<Sms>" . $message . "</Sms>\n";
        echo "</Response>\n";

        exit();
    }

    public function postFallback() {
        
    }

    public function postCallback() {
        
    }

}