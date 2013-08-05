<?php

class Validator {

    public function verifySession() {
        $response = array();

        $id_session = getSession()->get('id_session');

        if (empty($id_session)) {
            $response['success'] = false;
            $response['message'] = "There's not an active session.";
            $response['err'] = 4;
        }

        return $response;
    }

    public function getGetParams($KEYS) {
        $response = array();
        $get = array();

        foreach ($KEYS as $key) {
            if (empty($response)) {
                if (isset($_GET[$key])) {
                    $get[$key] = $_GET[$key];
                } else {
                    $response['success'] = false;
                    $response['message'] = "We couldn't find the required value for $key in the GET array";
                    $response['err'] = 5;
                }
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "The GET array contains all required parameters";
            $response['get'] = $get;
        }

        return $response;
    }

    public function getPostParams($KEYS) {
        $response = array();
        $post = array();

        foreach ($KEYS as $key) {
            if (empty($response)) {
                if (isset($_POST[$key])) {
                    $post[$key] = $_POST[$key];
                } else {
                    $response['success'] = false;
                    $response['message'] = "We couldn't find the required value for $key in the POST array";
                    $response['err'] = 6;
                }
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "The POST array contains all required parameters";
            $response['post'] = $post;
        }

        return $response;
    }

    public function getPutParams($KEYS) {
        $response = array();
        $put = array();

        parse_str(file_get_contents("php://input"), $_PUT);

        foreach ($KEYS as $key) {
            if (empty($response)) {
                if (isset($_PUT[$key])) {
                    $put[$key] = $_PUT[$key];
                } else {
                    $response['success'] = false;
                    $response['message'] = "We couldn't find the required value for $key in the PUT array";
                    $response['err'] = 7;
                }
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "The PUT array contains all required parameters";
            $response['put'] = $put;
        }

        return $response;
    }

}