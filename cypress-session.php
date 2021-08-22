<?php

// this allows us to pass a username/password credential
// and sign in to auth0 using these credentials
// in the background

use Auth0\SDK\API\Authentication;
use Auth0\SDK\Auth0;

include 'library/lib/session.php';

header('Content-type:application/json;charset=utf-8');

$hostName = @parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST);
if (('localhost' !== $hostName && 'staging.boxwise.co' !== $hostName)) {
    echo json_encode(['success' => false]);

    return;
}

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo json_encode(['success' => false]);

    return;
}

$auth = getAuth0Authentication($settings);

$oauth_token = $auth->login_with_default_directory([
    'username' => $_POST['email'],
    'password' => $_POST['password'],
    'scope' => 'openid profile email',
]);

// extracted from Auth0 client source
// https://docs.cypress.io/guides/testing-strategies/auth0-authentication#Adapting-the-front-end and
// https://auth0.com/blog/end-to-end-testing-with-cypress-and-auth0/
// otherwise doesn't seem to work for a PHP server-side scenario vs react SPA
// as the client won't process access_token automatically
// u need to enable grant Type "Password in your Auth0 application for this to work.

if (empty($oauth_token['access_token'])) {
    throw new Exception('Invalid access_token - Retry login.');
}

$auth0 = getAuth0($settings);

$auth0->setAccessToken($oauth_token['access_token']);
if (isset($oauth_token['refresh_token'])) {
    $auth0->setRefreshToken($oauth_token['refresh_token']);
}
if (isset($oauth_token['id_token'])) {
    $auth0->setIdToken($oauth_token['id_token']);
}

$user = $auth->userinfo($oauth_token['access_token']);
if ($user) {
    $auth0->setUser($user);
}
$userInfo = $auth0->getUser();
echo json_encode(['success' => true, 'user' => $userInfo]);
