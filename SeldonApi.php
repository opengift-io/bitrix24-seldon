<?php
/**
 * Created in OpenGift.
 * User: gvammer gvammer@rambler.ru
 * Date: 18.08.2018
 * Time: 20:46
 */

namespace Opengift\Api;

define('SERVER_API', 'https://api.seldon2010.ru/');

class SeldonApi
{
    const AUTH_URL = '/user/login';
    const PURCHASES_URL = '/purchases/get';

    static $authData = [
        'login' => 'Ayveber',
        'hash' => 'Jhge384fdhjv'
    ];

    private function _request($url, $fields, $method = 'GET')
    {
        $dataPath = realpath(dirname(__FILE__).'/data');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, $dataPath . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, $dataPath . '/cookie.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
        } else {
            $url .= '?' . http_build_query($fields);
        }

//        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, SERVER_API . $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $webPage = curl_exec($ch);

        curl_close($ch);
        return $webPage;
    }

    private function _auth() {
        return $this->_request(
            self::AUTH_URL,
            [
                "name" => static::$authData['login'],
                "password" => static::$authData['hash'],
            ],
            'POST'
        );
    }

    public function auth() {
        return $this->_auth();
    }

    public function getPurchase($reportId, $seldonId, $token) {
        $this->_auth();
        return new self($this->_request(
            self::PURCHASES_URL . '?token=' . $token,
            [
                'reportId' => $reportId,
                'seldonId' => $seldonId
            ],
            'POST'
        ));
    }
}