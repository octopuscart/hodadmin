<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class MobileApi extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->API_ACCESS_KEY = 'AIzaSyDexvTRWYvnqy5DM1OhCpZ0u3VFlticyk4';
        // (iOS) Private key's passphrase.
        $this->passphrase = 'joashp';
        // (Windows Phone 8) The name of our push channel.
        $this->channelName = "joashp";
    }

    private function useCurl($url, $headers, $fields = null) {
        // Open connection
        $ch = curl_init();
        if ($url) {
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if ($fields) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            }

            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }

            // Close connection
            curl_close($ch);

            return $result;
        }
    }

    public function android($data, $reg_id_array) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $message = array(
            'title' => $data['title'],
            'message' => $data['message'],
            'subtitle' => '',
            'tickerText' => '',
            'msgcnt' => 1,
            'vibrate' => 1
        );

        $headers = array(
            'Authorization: key=' . $this->API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $fields = array(
            'registration_ids' => $reg_id_array,
            'data' => $message,
        );

        return $this->useCurl($url, $headers, json_encode($fields));
    }

    public function iOS($data, $devicetoken) {
        $deviceToken = $devicetoken;
        $ctx = stream_context_create();
        // ck.pem is your certificate file
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        // Create the payload body
        $body['aps'] = array(
            'alert' => array(
                'title' => $data['mtitle'],
                'body' => $data['mdesc'],
            ),
            'sound' => 'default'
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        // Close the connection to the server
        fclose($fp);
        if (!$result)
            return 'Message not delivered' . PHP_EOL;
        else
            return 'Message successfully delivered' . PHP_EOL;
    }

    function testGet_get() {
        print_r($this->checklogin['user_type']);
    }

    function songCategoryDetails_get($category_id) {
        $this->config->load('rest', TRUE);
        $this->db->order_by('id');
        $this->db->where('id', $category_id);
        $query = $this->db->get("song_category");
        $songCategory = $query->row();
        $this->response($songCategory);
    }

    function songIndexList_get($category_id) {
        $this->config->load('rest', TRUE);
        $this->db->order_by('id');
        $this->db->where('category_id', $category_id);
        $query = $this->db->get("song_index");
        $songIndexData = $query->result();
        $this->response($songIndexData);
    }
    
    function songTemplate_get() {
        $this->config->load('rest', TRUE);
        $this->db->order_by('id');
        $query = $this->db->get("song_request_template");
        $songTemplateData = $query->row();
        $this->response($songTemplateData);
    }

    function songList_get($index_id) {
        $this->config->load('rest', TRUE);
        $this->db->order_by('display_index');
        $this->db->where('song_index_id', $index_id);
        $query = $this->db->get("song_lyrics");
        $songIndexData = $query->result();
        $this->response($songIndexData);
    }

    function bibleBook_get() {
        $this->config->load('rest', TRUE);
        $this->db->order_by('book_no');
        $query = $this->db->get("bible_book");
        $biblebookData = $query->result();
        $this->response($biblebookData);
    }

    function bookDetails_get($book_id) {
        $this->config->load('rest', TRUE);
        $this->db->order_by('id');
        $this->db->where('id', $book_id);
        $query = $this->db->get("bible_book");
        $bible_book = $query->row();
        $this->response($bible_book);
    }

    function bibleChepters_get($book_id) {
        $this->config->load('rest', TRUE);
        $this->db->where('book_id', $book_id);
        $query = $this->db->get("bible_chapter");
        $bibleChepterData = $query->result();
        $this->response($bibleChepterData);
    }
    
    function bibleVerses_get($chepter_id) {
        $this->config->load('rest', TRUE);
        $this->db->order_by('verse_no');
        $this->db->where('chapter_id', $chepter_id);
        $query = $this->db->get("bible_verses");
        $bibleChepterData = $query->result();
        $this->response($bibleChepterData);
    }
    
    function appPages_get($pageid) {
        $this->config->load('rest', TRUE);
        $this->db->where('id', $pageid);
        $query = $this->db->get('app_pages');
        $pageobj = $query->row();
        $this->response($pageobj);
    }

}

?>