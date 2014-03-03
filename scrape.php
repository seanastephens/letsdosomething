<?php
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    $genre = $_GET['genre'];
    $uid   = $_GET['uid'];
    $locale   = "Tucson, Arizona";
    $api_key = "kvscnr9agpmsxungb7ka4bza";

    $let0 = " ";
    $rep0 = "+";
    $locale = str_replace($let0,$rep0,$locale);
    $zip_url = "https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=1+main+st,+".$locale;
    $zipj = file_get_contents($zip_url);
    $z = json_decode($zipj,true);
    $i = $z['results'][0]['address_components'];
    $i = sizeof($i);
    $zip = $z['results'][0]['address_components'][$i-1]['long_name'];
 //   echo $zip;

    $base_url = "http://data.tmsapi.com/v1/movies/showings?startDate=2014-03-01&zip=" . $zip . "&api_key=" . $api_key;
//    $base_url = "http://data.tmsapi.com/v1/movies/showings?startDate=2014-03-01&zip=85364&api_key=" . $api_key;
//    $base_url = "file.json";
    
//    echo $base_url; 

    $page = file_get_contents($base_url);
    $j = json_decode($page,true);
    //var_dump($j,true);
    for($i=0; $i < sizeof($j); $i++) {
    //--Get info
        $tit   = $j[$i]['title'];
        $year  = $j[$i]['releaseYear'];
        $desc  = $j[$i]['shortDescription'];
        $genres = $j[$i]['genres'];
        $times = $j[$i]['showtimes'];
        foreach($genres as $g) {
//            echo $g . '&nbsp;&nbsp;' . $genre . '</br>';
            if($g == $genre) {
            //--Printing
                echo '<br/>' . $tit . "  (" .  $year . ")";
                echo '<br/>' . "[" . $genres[0];
                for($k=1;$k<sizeof($genres);$k++) {
                    echo"," .  $genres[$k];
                }
                echo "]";
                echo '<br/>' . $desc;

            //--Print Showtimes
                for($k=0; $k<sizeof($times); $k++) {
                    echo '<br/> <b>';
                    echo $times[$k]['theatre']['name'] . '</b>';
                    $let = "T";
                    $rep = " ";
                    $time = $times[$k]['dateTime'];
                    $time = str_replace($let,$rep,$time);
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $time ;
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" onClick="invite()">Invite</button>'; 
                }
                echo '<hr/>';

            }
        }
    }
?>
