<?php

require 'vendor/autoload.php';
use Auth0\SDK\Auth0;

$auth0 = new Auth0([
  'domain' => $settings['auth0_domain'],
  'client_id' => $settings['auth0_client_id'],
  'client_secret' => $settings['auth0_client_secret'],
  'redirect_uri' => $settings['auth0_redirect_uri'],
]);

$userInfo = $auth0->getUser();

if (isset($_POST['access_token']) && (($_SERVER['HTTP_HOST'] == 'localhost:8100') || $_SERVER['HTTP_HOST'] == 'https://staging.boxwise.co')) {
    $auth0->setAccessToken($_POST['access_token']);
    if (isset($_POST['refresh_token'])) {
        $auth0->setRefreshToken($_POST['refresh_token']);
    }
    if (isset($_POST['id_token'])) {
        $auth0->setIdToken($_POST['id_token']);
    }
    $curlcon = curl_init();
    curl_setopt($curlcon, CURLOPT_URL, 'https://'.$settings['auth0_domain'].'/userinfo');
    curl_setopt($curlcon, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlcon, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$_POST['access_token'], ));
    $userstring = curl_exec($curlcon);
    $userjson = json_decode($userstring, true);
    $auth0->setUser($userjson);
    $postit = $userjson;
}

if (!$userInfo && !$login) {
    redirect('/login.php?destination='.urlencode($_SERVER['REQUEST_URI']));
}
