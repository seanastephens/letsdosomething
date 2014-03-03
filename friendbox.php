<?php
require 'facebook.php';
ini_set('display_errors', 'On');
error_reporting(E_ALL);

if (!isset($_GET["genre"])) {
    echo("No 'genre' parameter passed!");
    die();
}

$genre    = $_GET['genre'];
$filename = 'genres/' . $genre . '.txt';

if (file_exists($filename)) {
    $handle = fopen('genres/' . $genre . '.txt', "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $movietitle[] = trim($line);
        }
    } else {
        echo "File opening failed.";
    }
} else {
    echo "File not found.";
    die();
}

$config = array(
    'appId' => '299220656893010',
    'secret' => '6d27ff1e8509d4fd017f16ed23cae89b',
    'allowSignedRequest' => false, // optional, but should be false
    'cookie' => true
);

$facebook = new Facebook($config);
$user     = $facebook->getUser();

if (!$user) {
    $loginUrl = $facebook->getLoginUrl(array(
        'scope' => 'user_friends, friends_likes, publish_stream'
    ));
    echo "Login using Facebook <a href=" . $loginUrl . ">here</a>";
    die();
}

$info = $facebook->api('/me?fields=location,friends.fields(name,movies)');

$friends = $info['friends']['data'];

echo '<form>';
$foundFriend = false;
for ($i = 0; $i < sizeof($friends); $i++) {
    for ($j = 0; $j < sizeof($movietitle); $j++) {
        if (isset($friends[$i]['movies'])) {
            for ($k = 0; $k < sizeof($friends[$i]['movies']['data']); $k++) {
                if ($friends[$i]['movies']['data'][$k]['name'] === $movietitle[$j]) {
                    $foundFriend = true;
                    echo '<input type="checkbox" name="friend" value="' . trim($friends[$i]['name']) . '">';
                    echo $friends[$i]['name'];
                    echo '<br>';
                    break 2;
                }
            }
        }
    }
}
echo '</form>';
if (!$foundFriend) {
    echo 'No friends found :(';
}
?> 
