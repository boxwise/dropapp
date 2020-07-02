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
/*
$email = 'some@email.com';
$connection = 'demo-auth0';
$management_token = getManagmentToken($settings['auth0_client_id'], $settings['auth0_client_secret'], $settings['auth0_domain']);
$token = $management_token['access_token'];
$created_user = createUserAuth0($token, $email, 'As1234asdf!', $connection, $settings['auth0_domain']);
//$user_id = $created_user['user_id'];
$user = checkUserAuth0($token, $settings['auth0_domain'], $email);
$verify = verifyEmailAuth0($token, $settings['auth0_domain'], $user_id, $settings['auth0_client_id']);
$password_reset = resetPasswordAuth0($settings['auth0_domain'], $settings['auth0_client_id'], $email, $connection);
*/
if (!$userInfo && !$login) {
    redirect('/login.php?destination='.urlencode($_SERVER['REQUEST_URI']));
}
