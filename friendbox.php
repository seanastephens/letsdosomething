<?php
require 'facebook.php';
ini_set('display_errors', 'On');
error_reporting(E_ALL);

/*
 * Check that required parameters are present, and store them.
 */
if (!isset($_GET["genre"])) {
    echo("No 'genre' parameter passed!");
    die();
}

if (!isset($_GET["token"])) {
    echo("No 'token' parameter passed!");
    die();
}

$genre    = $_GET['genre'];
$filename = 'genres/' . $genre . '.txt';

$token = $_GET['token'];

/*
 * If $genre is a valid filename, read all the titles from that file into
 * an array.
 */
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
);

/*
 * Try to pull info from the facebook API. This is the code that is 
 * having problems with curllib. FIXME
 */
try{

    $facebook = new Facebook($config);
    $user     = $facebook->getUser();
    $facebook->setAccessToken($token);
    $info = $facebook->api('/me?fields=location,friends.fields(name,movies)');

} catch (Exception $e) {
    echo 'caught an exception';
    echo '<br>';
    echo $e;
}

if (!$user) {
    echo "User not logged in!";
    die();
}

/*
 * Code below here assumes $info is good. Just checks the friend list for 
 * matches between liked movies and the titles in $movietitles that we 
 * loaded earlier, and produces checkbox html.
 */
$friends = $info['friends']['data'];

echo '<form>';
$foundFriend = false;
                            
// For all friends and movie titles
for ($i = 0; $i < sizeof($friends); $i++) {
    for ($j = 0; $j < sizeof($movietitle); $j++) {

        // If the friend has movie likes
        if (isset($friends[$i]['movies'])) {

            // Check each of their movie likes against the movie title.
            for ($k = 0; $k < sizeof($friends[$i]['movies']['data']); $k++) {
                if ($friends[$i]['movies']['data'][$k]['name'] === $movietitle[$j]) {
                    
                    // Print out an html checkbox; 'break 2' stops the same friend
                    // from being printed multiple times.
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
