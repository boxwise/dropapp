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

if (!$userInfo && !$login) {
    redirect('/login.php?destination='.urlencode($_SERVER['REQUEST_URI']));
}
