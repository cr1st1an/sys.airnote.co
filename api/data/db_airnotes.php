<?php

class DB_Airnotes {

    protected $_name = 'airnotes';
    
    public function select($ID_AIRNOTE){
        $response = array();
        $airnote_data = array();

        $id_airnote = (int) $ID_AIRNOTE;
        if (empty($response) && empty($id_airnote)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ID_AIRNOTE in DB_Airnotes->select()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $select_data = array(
                'id_airnote' => $id_airnote
            );
            
            $airnote_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_airnote=:id_airnote AND status=1', $select_data
            );
            
            $response['success'] = true;
            $response['message'] = "Here is the airnote with id '$id_airnote'.";
            $response['airnote_data'] = $airnote_data;
        }
        
        return $response;
    }
    
    public function selectAll($ID_SUBSCRIBER) {
        $response = array();
        $airnotes_data = array();

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ID_SUBSCRIBER in DB_Airnotes->selectAll()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $select_data = array(
                'id_subscriber' => $id_subscriber
            );
            
            $airnotes_data = getDatabase()->all(
                    'SELECT * FROM ' . $this->_name . ' WHERE id_subscriber=:id_subscriber AND status=1 ORDER BY keyword ASC', $select_data
            );
            
            $response['success'] = true;
            $response['message'] = "Here are the airnotes for subscriber with id '$id_subscriber'.";
            $response['airnotes_data'] = $airnotes_data;
        }
        
        return $response;
    }
    
    public function insert($DATA) {
        $response = array();
        $id_airnote = null;

        $keyword = (string) $DATA['keyword'];
        if (empty($response) && empty($keyword)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of KEYWORD in DB_Streams->insert()";
            $response['err'] = 1;
        }

        $message = (string) $DATA['message'];
        if (empty($response) && empty($message)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of MESSAGE in DB_Streams->insert()";
            $response['err'] = 2;
        }

        if (isset($DATA['id_subscriber']))
            $id_subscriber = $DATA['id_subscriber'];
        else
            $id_subscriber = null;

        if (empty($response)) {
            $select_data = array(
                'keyword' => $keyword
            );
            $airnote_data = getDatabase()->one('SELECT * FROM ' . $this->_name . ' WHERE id_subscriber IS NOT NULL AND keyword=:keyword AND status=1 ORDER BY id_airnote DESC', $select_data);

            if (!empty($airnote_data)) {
                $response['success'] = false;
                $response['message'] = "The keyword already exists, please try to update it.";
                $response['err'] = 3;
            }
        }

        if (empty($response)) {
            $insert_data = array(
                'id_subscriber' => $id_subscriber,
                'created' => date("Y-m-d H:i:s"),
                'keyword' => $keyword,
                'message' => $message
            );

            $id_airnote = getDatabase()->execute(
                    'INSERT INTO ' . $this->_name . '(id_subscriber, created, keyword, message) VALUES(:id_subscriber, :created, :keyword, :message)', $insert_data
            );

            $response['success'] = true;
            $response['message'] = "The airnote with keyword '$keyword' has been created.";
            $response['id_airnote'] = $id_airnote;
        }

        return $response;
    }

    public function search($KEYWORD) {
        $response = array();
        $airnote_data = array();

        $keyword = (string) $KEYWORD;
        if (empty($response) && empty($keyword)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of KEYWORD in DB_Airnotes->search()";
            $response['err'] = 8;
        }

        if (empty($response)) {
            $select_data = array(
                'keyword' => $keyword
            );
            $airnote_data = getDatabase()->one(
                    'SELECT * FROM ' . $this->_name . ' WHERE keyword=:keyword AND status=1 ORDER BY id_airnote DESC', $select_data
            );

            if (empty($airnote_data)) {
                $response['success'] = false;
                $response['message'] = "There's no airnote with keyword '$keyword'.";
                $response['err'] = 0;
            }
        }

        if (empty($response)) {
            $response['success'] = true;
            $response['message'] = "Here's the airnote with keyword '$keyword'.";
            $response['airnote_data'] = $airnote_data;
        }

        return $response;
    }

    public function updateSubscriber($ID_AIRNOTE, $ID_SUBSCRIBER) {
        $response = array();

        $id_airnote = (int) $ID_AIRNOTE;
        if (empty($response) && empty($id_airnote)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ID_AIRNOTE in DB_Airnotes->updateSubscriber()";
            $response['err'] = 0;
        }

        $id_subscriber = (int) $ID_SUBSCRIBER;
        if (empty($response) && empty($id_subscriber)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ID_SUBSCRIBER in DB_Airnotes->updateSubscriber()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $update_data = array(
                'id_airnote' => $id_airnote,
                'updated' => date("Y-m-d H:i:s"),
                'id_subscriber' => $id_subscriber
            );
            getDatabase()->execute('UPDATE ' . $this->_name . ' SET updated=:updated, id_subscriber=:id_subscriber WHERE id_airnote=:id_airnote', $update_data);

            $response['success'] = true;
            $response['message'] = "The airnote with id '$id_airnote' has been updated.";
        }

        return $response;
    }
    
    public function updateActive($ID_AIRNOTE, $ACTIVE){
        $response = array();

        $id_airnote = (int) $ID_AIRNOTE;
        if (empty($response) && empty($id_airnote)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ID_AIRNOTE in DB_Airnotes->updateActive()";
            $response['err'] = 0;
        }

        $active = (int) $ACTIVE;
        if (empty($response) && !isset($active)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ACTIVE in DB_Airnotes->updateActive()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $update_data = array(
                'id_airnote' => $id_airnote,
                'updated' => date("Y-m-d H:i:s"),
                'active' => $active
            );
            getDatabase()->execute('UPDATE ' . $this->_name . ' SET updated=:updated, active=:active WHERE id_airnote=:id_airnote', $update_data);

            $response['success'] = true;
            $response['message'] = "The airnote with id '$id_airnote' has been updated.";
        }
        
        return $response;
    }
    
    public function updateStatus($ID_AIRNOTE, $STATUS){
        $response = array();

        $id_airnote = (int) $ID_AIRNOTE;
        if (empty($response) && empty($id_airnote)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of ID_AIRNOTE in DB_Airnotes->updateStatus()";
            $response['err'] = 0;
        }

        $status = (int) $STATUS;
        if (empty($response) && !isset($status)) {
            $response['success'] = false;
            $response['message'] = "We couldn't find the value of STATUS in DB_Airnotes->updateStatus()";
            $response['err'] = 0;
        }

        if (empty($response)) {
            $update_data = array(
                'id_airnote' => $id_airnote,
                'updated' => date("Y-m-d H:i:s"),
                'status' => $status
            );
            getDatabase()->execute('UPDATE ' . $this->_name . ' SET updated=:updated, status=:status WHERE id_airnote=:id_airnote', $update_data);

            $response['success'] = true;
            $response['message'] = "The airnote with id '$id_airnote' has been updated.";
        }
        
        return $response;
    }
    
}