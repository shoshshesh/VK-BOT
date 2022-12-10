<?php

const VK_API_VERSION = '5.131'; //Используемая версия API
const VK_API_ENDPOINT = 'https://api.vk.com/method/';

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
function vkApi_usersGet($user_id) {
    return _vkApi_call('users.get', array(
        'user_id' => $user_id,
    ));
}

/**
 * @throws Exception
 */
function vkApi_photosGetMessagesUploadServer($peer_id) {
    return _vkApi_call('photos.getMessagesUploadServer', array(
        'peer_id' => $peer_id,
    ));
}

/**
 * @throws Exception
 */
function vkApi_photosSaveMessagesPhoto($photo, $server, $hash) {
    return _vkApi_call('photos.saveMessagesPhoto', array(
        'photo'  => $photo,
        'server' => $server,
        'hash'   => $hash,
    ));
}

/**
 * @throws Exception
 */
function vkApi_docsGetMessagesUploadServer($peer_id, $type) {
    return _vkApi_call('docs.getMessagesUploadServer', array(
        'peer_id' => $peer_id,
        'type'    => $type,
    ));
}

/**
 * @throws Exception
 */
function vkApi_docsSave($file, $title) {
    return _vkApi_call('docs.save', array(
        'file'  => $file,
        'title' => $title,
    ));
}

/**
 * @throws Exception
 */
function _vkApi_call($method, $params = array()) {
    $params['access_token'] = VK_API_ACCESS_TOKEN;
    $params['v'] = VK_API_VERSION;

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