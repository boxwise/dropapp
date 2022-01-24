<?php

// this allows us to pass a username/password credential
// and sign in to auth0 using these credentials
// in the background

use Auth0\SDK\API\Authentication;
use Auth0\SDK\Auth0;
use Auth0\SDK\Utility\HttpResponse;

include 'library/lib/session.php';

header('Content-type:application/json;charset=utf-8');

$hostName = @parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST);
if (('localhost' !== $hostName && 'staging.boxtribute.org' !== $hostName)) {
    echo json_encode(['success' => false]);

    return;
}

// extracted from Auth0 client source
// https://docs.cypress.io/guides/testing-strategies/auth0-authentication#Adapting-the-front-end and
// https://auth0.com/blog/end-to-end-testing-with-cypress-and-auth0/
// otherwise doesn't seem to work for a PHP server-side scenario vs react SPA
// as the client won't process access_token automatically
// u need to enable grant Type "Password in your Auth0 application for this to work.

$auth0 = getAuth0($settings);

// Authentication instance
$auth = getAuth0Authentication($settings);

if (isset($_POST['logout'])) {
    $auth0->logout();
    echo json_encode(['success' => true]);

    exit;
}

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo json_encode(['success' => false]);

    return;
}

$response = $auth->loginWithDefaultDirectory(
    $_POST['email'],
    $_POST['password'],
    ['scope' => 'openid profile email']
);

if (!HttpResponse::wasSuccessful($response)) {
    echo json_encode(['success' => false]);

    return;
}

$oauth_token = HttpResponse::decodeContent($response);

if (!isset($oauth_token['access_token']) || !$oauth_token['access_token']) {
    $auth0->clear();

    throw \Auth0\SDK\Exception\StateException::badAccessToken();
}

$auth0->setAccessToken($oauth_token['access_token']);

if (isset($oauth_token['scope'])) {
    $auth0->setAccessTokenScope(explode(' ', $oauth_token['scope']));
}

if (isset($oauth_token['refresh_token'])) {
    $auth0->setRefreshToken($oauth_token['refresh_token']);
}

if (isset($oauth_token['id_token'])) {
    $auth0->setIdToken($oauth_token['id_token']);
    $user = $auth0->decode($oauth_token['id_token'])->toArray();
}

if (isset($oauth_token['expires_in']) && is_numeric($oauth_token['expires_in'])) {
    $expiresIn = time() + (int) $oauth_token['expires_in'];
    $auth0->setAccessTokenExpiration($expiresIn);
}

if (null === $user || true === $auth0->configuration()->getQueryUserInfo()) {
    $response = $auth0->authentication()->userInfo($oauth_token['access_token']);

    if (HttpResponse::wasSuccessful($response)) {
        $user = HttpResponse::decodeContent($response);
    }
}

$auth0->setUser($user ?? []);

echo json_encode(['success' => true, 'user' => $user]);
