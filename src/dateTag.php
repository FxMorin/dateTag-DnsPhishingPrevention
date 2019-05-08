<?php
date_default_timezone_set("America/Toronto");

//dateTag is a method created by Fx Morin, that makes phishing sites easily found
//dateTag is a tag '<dateTag value="f3dce3f6672951976f9809abdd2c1a3b">' that holds
//a md5 hash of the current date and time in hours, and a token. The token is what
//makes it so nobody can bypass this. The token is given by you, it can also be
//access remotly so that the token changes after a certain amount of times.
//If the token is incorrect, the program checks the hour before, if that one also
//fails then its a phishing site.
$dateTagHashing = true;
$dateTagTokenType = 0; //0=string, 1=remote file, 2=api
$dateTagToken = "";
$dateTagRemote = "";
$dateTagApi = "";
//The dateTag date format is "n/j/Y/G"
//dateTag can be placed anywhere in the site
//go to https://github.io/fxmorin/dateTag to get more information on the API


//==============================================================================
//                         DO NOT MODIFY PAST HERE
//==============================================================================

function clean($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!empty($_POST['URL']) && isset($_POST['URL'])) {
    $safe = false;
		$url = clean($_POST['URL']);
		$html = strtolower(file_get_contents($url));
		preg_match('/<dateTag(.*?)value=\"(.*?)\"/', $html, $dateTagInfo);
		$dateTagHash = $dateTagInfo[1];
    if ($dateTagHash != "") {
		if ($dateTagTokenType == 0){ //string
			if ($dateTagHash == md5(date("n/j/Y/G").$dateTagToken)) {
				$safe = true;
			} elseif ($dateTagHash == md5(date("n/j/Y/").(date("G")-1).$dateTagToken)) { //Add 5 min token change period (currently token lasts 2 hours without it)
				$safe = true;
			} else {
				$safe = false;
			}
		} elseif ($dateTagTokenType == 1) { //remote file
			$dateTagToken = file_get_contents($dateTagRemote);
			if ($dateTagHash == md5(date("n/j/Y/G").$dateTagToken)) {
				$safe = true;
			} elseif ($dateTagHash == md5(date("n/j/Y/").(date("G")-1).$dateTagToken)) { //Add 5 min token change period (currently token lasts 2 hours without it)
				$safe = true;
			} else {
				$safe = false;
			}
		} elseif ($dateTagTokenType == 2) { //Api
			// use key 'http' even if you send the request to https://...
			$options = array(
    				'http' => array(
        			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        			'method'  => 'POST',
        			'content' => http_build_query(array())
    			)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($dateTagApi, false, $context);
		if ($result === FALSE) { /* Handle error */ } else {
			if ($dateTagHash == md5(date("n/j/Y/G").$result)) {
				$safe = true;
			} elseif ($dateTagHash == md5(date("n/j/Y/").(date("G")-1).$result)) { //Add 5 min token change period (currently token lasts 2 hours without it)
				$safe = true;
			} else {
				$safe = false;
			}
		}

		}
	if ($safe) {
  		//=========================
  		//   NOT A PHISHING SITE
  		//=========================
  		echo 1;
  	} else {
  		//=========================
  		//     A PHISHING SITE
  		//=========================
  		echo 0;
  	}
	} else { //If dateTag was not found or if it was empty
    		echo 2;
  	}
  }
}
?>
