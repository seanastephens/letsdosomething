<?php

// Control flags
$timestamps = false;
// End Control flags


$start_time = microtime(true);
require 'facebook.php';
ini_set('display_errors', 'On');
error_reporting(E_ALL);

if(!isset($_GET["genre"])){
  echo "A problem has occurred.";
  die();
}

$genre = $_GET['genre'];
$filename = 'genres/' . $genre . '.txt';

if(file_exists($filename)) {
  $handle = fopen('genres/' . $genre . '.txt', "r");
  if ($handle) {
    while(($line = fgets($handle)) !== false) {
      $movietitle[] = trim($line);	
    }
  } else {
    echo "File opening failed."; 
  }
} else {
    echo "File not found."; 
    die();
}

$file_read_time = microtime(true);

$config = array(
  'appId' => '299220656893010',
  'secret' => '6d27ff1e8509d4fd017f16ed23cae89b',
  'allowSignedRequest' => false, // optional, but should be set to false for non-canvas apps
  'cookie' => true
);

$facebook = new Facebook($config);
$user = $facebook->getUser();

if ($user) {
  $info = $facebook->api('/me?fields=location,friends.fields(name,movies)');
  $friends = $info['friends']['data'];
  echo '<table border="1" style="width:300px">';
  for($i=0;$i<sizeof($friends);$i++){
    for($j=0;$j<sizeof($movietitle);$j++){
      if(isset($friends[$i]['movies'])){
        
        for($k=0;$k<sizeof($friends[$i]['movies']['data']);$k++){
          echo "<tr>";
          if($k === 0) {
            echo "<td>" . $friends[$i]['name'] . "</td>";
          } else {
            echo "<td></td>";
          }   
          echo "<td>" . $friends[$i]['movies']['data'][$k]['name'] . "</td>";
          echo "<td>" . $movietitle[$j] . "</td>";
          echo "<td>";
          echo strcmp($friends[$i]['movies']['data'][$k]['name'], $movietitle[$j]);
          echo "</td>";
        }
      }
    echo "</tr>";
    }
  }
  echo "</table>";
} else {
  $loginURL = $facebook->getLoginUrl();
  echo "You aren't logged in! Log in ";
  echo "<a href=" . $loginURL . ">here</a>";
} 
$after_search = microtime(true);

if($timestamps) {
  echo "Read File in " . ($file_read_time - $start_time) . " (sec)<br>";
  echo "Proc. Titles " . ($after_search - $file_read_time) . "(sec)<br>";
}
?>
