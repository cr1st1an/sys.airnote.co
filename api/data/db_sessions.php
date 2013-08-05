<?php

class DB_Sessions {

    protected $_name = 'sessions';
    
    public function insert($ID_SUBSCRIBER) {
        $response = array();

        $id_subscriber = $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ID_SUBSCRIBER in DB_Sessions->insert()";
            $response['err'] = 9;
        }
        
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else
            $ip = $_SERVER['REMOTE_ADDR'];
        
        $token = md5($id_subscriber.date("Y-m-d H:i:s").$ip.'dance');
        
        if (empty($response)) {
            $insert_data = array(
                'id_subscriber' => $id_subscriber,
                'created' => date("Y-m-d H:i:s"),
                'token' => $token,
                'ip' => $ip
            );

            $id_session = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(id_subscriber, created, token, ip) VALUES(:id_subscriber, :created, :token, :ip)', $insert_data
            );
            
            $response['success'] = true;
            $response['message'] = "The session with id_subscriber '$id_subscriber' has been created.";
            $response['token'] = $token;
        }
        
        return $response;
    }
    
    public function search($TOKEN) {
        $response = array();
        $session_data = array();

        $token = (string) $TOKEN;
        if (empty($response) && empty($token)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of TOKEN in DB_Sessions->search()";
            $response['err'] = 8;
        }

        if (empty($response)) {
            $select_data = array(
                'token' => $token
            );
            $session_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE token=:token', $select_data
            );
            
            if (empty($session_data)) {
                $response['success'] = false;
                $response['message'] = "There's no session with token '$token'.";
                $response['err'] = 0;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here's the session with token '$token'.";
            $response['session_data'] = $session_data;
        }
        
        return $response;
    }
}