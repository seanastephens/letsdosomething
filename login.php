<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require 'facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '299220656893010',
  'secret' => '6d27ff1e8509d4fd017f16ed23cae89b',
  'allowSignedRequest' => false,
  'cookie' => true,
  'code' => $_GET['code']
));

if (!session_id()) { session_start(); }

// Get User ID
$user = $facebook->getUser();

// Login or logout url will be needed depending on current user state.
if (!$user) {
  $loginUrl = $facebook->getLoginUrl(array('scope' => 'user_friends, friends_likes, publish_stream', 'redirect_uri' => 'http://www2.engr.arizona.edu/~taylordixon/letsdosomething/'));
  echo "Login using Facebook <a href=";
  echo $loginUrl; 
  echo ">here</a>";
}

?>
