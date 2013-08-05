<?php

class DB_Subscribers {

    protected $_name = 'subscribers';

    public function select($ID_SUBSCRIBER) {
        $response = array();
        $subscriber_data = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ID_SUBSCRIBER in DB_Subscribers->select()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $select_data = array(
                'id_subscriber' => $id_subscriber
            );
            $subscriber_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_subscriber=:id_subscriber', $select_data
            );

            if (empty($subscriber_data)) {
                $response['success'] = false;
                $response['message'] = "There's no subscriber with id '$id_subscriber'.";
                $response['err'] = 0;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here's the subscriber with id '$id_subscriber'.";
            $response['subscriber_data'] = $subscriber_data;
        }
        
        return $response;
    }

    public function insert($DATA) {
        $response = array();
        $id_subscriber = null;

        $email = (string) $DATA['email'];
        if (empty($response) && empty($email)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of EMAIL in DB_Subscribers->insert()";
            $response['err'] = 9;
        }

        $password = (string) $DATA['password'];
        if (empty($response) && empty($password)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of PASSWORD in DB_Subscribers->insert()";
            $response['err'] = 10;
        }

        if (empty($response)) {
            $select_data = array(
                'email' => $email
            );
            $subscriber_data = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE email=:email', $select_data);

            if (!empty($subscriber_data)) {
                $response['success'] = false;
                $response['message'] = "The email already exists, please try to update it.";
                $response['err'] = 11;
            }
        }

        if (empty($response)) {
            $insert_data = array(
                'created' => date("Y-m-d H:i:s"),
                'email' => $email,
                'password' => md5($password)
            );

            $id_subscriber = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(created, email, password) VALUES(:created, :email, :password)', $insert_data
            );

            $response['success'] = true;
            $response['message'] = "The subscriber with email '$email' has been created.";
            $response['id_subscriber'] = $id_subscriber;
        }

        return $response;
    }

    public function login($EMAIL, $PASSWORD) {
        $response = array();
        $subscriber_data = array();

        $email = (string) $EMAIL;
        if (empty($response) && empty($email)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of EMAIL in DB_Subscribers->login()";
            $response['err'] = 0;
        }

        $password = (string) $PASSWORD;
        if (empty($response) && empty($password)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of PASSWORD in DB_Subscribers->login()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $select_data = array(
                'email' => $email,
                'password' => md5($password)
            );
            $subscriber_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE email=:email AND password=:password', $select_data
            );

            if (empty($subscriber_data)) {
                $response['success'] = false;
                $response['message'] = "The login details are invalid.";
                $response['err'] = 0;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "The login details are valid";
            $response['subscriber_data'] = $subscriber_data;
        }

        return $response;
    }

    public function search($EMAIL) {
        $response = array();
        $subscriber_data = array();

        $email = (string) $EMAIL;
        if (empty($response) && empty($email)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of EMAIL in DB_Subscribers->search()";
            $response['err'] = 12;
        }

        if (empty($response)) {
            $select_data = array(
                'email' => $email
            );
            $subscriber_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE email=:email', $select_data
            );

            if (empty($subscriber_data)) {
                $response['success'] = false;
                $response['message'] = "There's no subscriber with email '$email'.";
                $response['err'] = 0;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here's the subscriber with email '$email'.";
            $response['subscriber_data'] = $subscriber_data;
        }

        return $response;
    }
    
    public function updatePack($ID_SUBSCRIBER, $PACK) {
        $response = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of PACK in DB_Subscribers->updatePack()";
            $response['err'] = 0;
        }

        $pack = (int) $PACK;
        if (empty($response) && !isset($pack)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of PACK in DB_Subscribers->updatePack()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $update_data = array(
                'id_subscriber' => $id_subscriber,
                'updated' => date("Y-m-d H:i:s"),
                'pack' => $pack
            );
            getDatabase()->execute('UPDATE ' . $this->_name . ' SET updated=:updated, pack=:pack WHERE id_subscriber=:id_subscriber', $update_data);

            $response['success'] = true;
            $response['message'] = "The subscriber with id '$id_subscriber' has been updated.";
        }

        return $response;
    }
}