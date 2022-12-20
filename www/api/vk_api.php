<?php

const VK_API_VERSION = '5.131'; //Используемая версия API
const VK_API_ENDPOINT = 'https://api.vk.com/method/';
const INT32_MIN = -2147483648;
const INT32_MAX = 2147483647;

/**
 * @throws Exception
 */
function vkApi_messagesSend($peer_id, $message, $attachments = array()) {
    return _vkApi_call('messages.send', array(
        'peer_id'    => $peer_id,
        'message'    => $message,
        'attachment' => implode(',', $attachments)
    ));
}

/**
 * @throws Exception
 */
function vkApi_userGet($user_id) {
    $users =  _vkApi_call('users.get', array(
        'user_id' => $user_id,
    ));
    return array_pop($users);
}

/**
 * @throws Exception
 */
function _vkApi_call($method, $params = array()) {
    $params['access_token'] = VK_API_ACCESS_TOKEN;
    $params['v'] = VK_API_VERSION;
    $params['random_id'] = random_int(INT32_MIN, INT32_MAX);

    $query = http_build_query($params);
    $url = VK_API_ENDPOINT.$method.'?'.$query;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $error = curl_error($curl);
    if ($error) {
        log_error($error);
        throw new Exception("Failed {$method} request");
    }

    curl_close($curl);

    $response = json_decode($json, true);
    if (!$response || !isset($response['response'])) {
        log_error($json);
        throw new Exception("Invalid response for {$method} request");
    }

    return $response['response'];
}

/**
 * @throws Exception
 */
function vkApi_upload($url, $file_name) {
    if (!file_exists($file_name)) {
        throw new Exception('File not found: '.$file_name);
    }

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array('file' => new CURLfile($file_name)));
    $json = curl_exec($curl);
    $error = curl_error($curl);
    if ($error) {
        log_error($error);
        throw new Exception("Failed {$url} request");
    }

    curl_close($curl);

    $response = json_decode($json, true);
    if (!$response) {
        throw new Exception("Invalid response for {$url} request");
    }

    return $response;
}