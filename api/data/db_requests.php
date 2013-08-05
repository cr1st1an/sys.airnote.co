<?php

class DB_Requests {

    protected $_name = 'requests';
    
    public function insert($DATA) {
        $response = array();
        
        $id_subscriber = null;
        $id_airnote = null;
        $sms_sid = null;
        $account_sid = null;
        $from_phone = null;
        $to_phone = null;
        $body = null;
        $from_city = null;
        $from_state = null;
        $from_zip = null;
        $from_country = null;
        $to_city = null;
        $to_state = null;
        $to_zip = null;
        $to_country = null;
        
        if(!empty($DATA['id_subscriber']))
            $id_subscriber = $DATA['id_subscriber'];
        if(!empty($DATA['id_airnote']))
            $id_airnote = $DATA['id_airnote'];
        if(!empty($DATA['sms_sid']))
            $sms_sid = $DATA['sms_sid'];
        if(!empty($DATA['account_sid']))
            $account_sid = $DATA['account_sid'];
        if(!empty($DATA['from_phone']))
            $from_phone = $DATA['from_phone'];
        if(!empty($DATA['to_phone']))
            $to_phone = $DATA['to_phone'];
        if(!empty($DATA['body']))
            $body = $DATA['body'];
        if(!empty($DATA['from_city']))
            $from_city = $DATA['from_city'];
        if(!empty($DATA['from_state']))
            $from_state = $DATA['from_state'];
        if(!empty($DATA['from_zip']))
            $from_zip = $DATA['from_zip'];
        if(!empty($DATA['from_country']))
            $from_country = $DATA['from_country'];
        if(!empty($DATA['to_city']))
            $to_city = $DATA['to_city'];
        if(!empty($DATA['to_state']))
            $to_state = $DATA['to_state'];
        if(!empty($DATA['to_zip']))
            $to_zip = $DATA['to_zip'];
        if(!empty($DATA['to_country']))
            $to_country = $DATA['to_country'];
        
        if (empty($response)) {
            $insert_data = array(
                'id_subscriber' => $id_subscriber,
                'id_airnote' => $id_airnote,
                'created' => date("Y-m-d H:i:s"),
                'sms_sid' => $sms_sid,
                'account_sid' => $account_sid,
                'from_phone' => $from_phone,
                'to_phone' => $to_phone,
                'body' => $body,
                'from_city' => $from_city,
                'from_state' => $from_state,
                'from_zip' => $from_zip,
                'from_country' => $from_country,
                'to_city' => $to_city,
                'to_state' => $to_state,
                'to_zip' => $to_zip,
                'to_country' => $to_country
            );

            $id_request = getDatabase()->execute(
                    "INSERT INTO " . $this->_name . "(id_subscriber, id_airnote, created, sms_sid, account_sid, from_phone, to_phone, body, from_city, from_state, from_zip, from_country, to_city, to_state, to_zip, to_country) VALUES(:id_subscriber, :id_airnote, :created, :sms_sid, :account_sid, :from_phone, :to_phone, :body, :from_city, :from_state, :from_zip, :from_country, :to_city, :to_state, :to_zip, :to_country)", $insert_data
            );

            $response['success'] = true;
            $response['message'] = "The request with id '$id_request' has been created.";
            $response['id_request'] = $id_request;
        }
        
        return $response;
    }
    
    public function count($ID_SUBSCRIBER) {
        $response = array();
        $requests_data = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ID_SUBSCRIBER in DB_Requests->count()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $select_data = array(
                'id_subscriber' => $id_subscriber,
                'since' => date("Y-m-01 00:00:00")
            );
            
            $requests_data = getDatabase()->all(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_subscriber=:id_subscriber and created>:since', $select_data
            );
            
            $response['success'] = true;
            $response['message'] = "Here are the airnotes for subscriber with id '$id_subscriber'.";
            $response['request_count'] = count($requests_data);
        }
        
        return $response;
    }
}