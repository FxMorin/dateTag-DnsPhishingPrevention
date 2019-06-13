<?php
/**
  * @version 1.2
  */
date_default_timezone_set("America/Toronto");

//dateTag is a method created by Fx Morin, that makes phishing sites easily found
//dateTag is a tag '<dateTag value="f3dce3f6672951976f9809abdd2c1a3b">' that holds
//a md5 hash of the current date and time in hours, and a token. The token is what
//makes it so nobody can bypass this. The token is given by you, it can also be
//access remotly so that the token changes after a certain amount of times.
//If the token is incorrect, the program checks the hour before, if that one also
//fails then its a phishing site. To not use token (not recommended) just use
//dateTagTokenType 0, & set the dateTagToken to an empty string! UniqueID was
//added in Version 1.3, it stops the ability to use a server-side proxy to display
//the site but with a modified login to phish you. UniqueID has 4 different
//ways of creating an ID. For more info, visit https://github.io/fxmorin/dateTag
$dateTagHashing = true;
$dateTagTokenType = 0; //0=string, 1=remote file, 2=api
$dateTagToken = "";

$dateTagUniqueID = true;
$dateTagUniqueType = 0; //0=userAgent,1=ip-address,2=both,3=get_Browser()
//The dateTag date format is "n/j/Y/G"
//dateTag can be placed anywhere in the site
//go to https://github.io/fxmorin/dateTag to get more information on the API

//Testing Mode
//This allows you to see how the program will respond. Allowing you to test if
//dateTag is properly installed on the website, the dateTag.php, & the POST sender
//For more information go to https://github.io/fxmorin/dateTag
$testingMode = false;


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
    $dateTagUniqueString = "";
    $safe = false;
		$url = clean($_POST['URL']);
		$html = strtolower(file_get_contents($url));

		preg_match('/<dateTag(.*?)value=\"(.*?)\"/', $html, $dateTagInfo);
		$dateTagHash = $dateTagInfo[1];
    if ($dateTagUniqueID) {
      if ($dateTagUniqueType == 0) { //userAgent
        $dateTagUniqueString = $_SERVER['HTTP_USER_AGENT']??null;
      } else if ($dateTagUniqueType == 1) {//ip-address
        $dateTagUniqueString = $_SERVER["REMOTE_ADDR"]??null;
      } else if ($dateTagUniqueType == 2) { //both
        $dateTagUniqueString = $_SERVER["REMOTE_ADDR"]??null." ".$_SERVER['HTTP_USER_AGENT']??null;
      } else if ($dateTagUniqueType == 3) {
        $dateTagUniqueString = implode("",get_Browser());
      }
    }
    if ($dateTagHash != "") {
		if ($dateTagTokenType == 0){ //string
			if ($dateTagHash == md5(date("n/j/Y/G").$dateTagToken.$dateTagUniqueString)) {
				$safe = true;
			} elseif ($dateTagHash == md5(date("n/j/Y/").(date("G")-1).$dateTagToken.$dateTagUniqueString)) { //Add 5 min token change period (currently token lasts 2 hours without it)
				$safe = true;
			} else {
				$safe = false;
			}
		} elseif ($dateTagTokenType == 1) { //remote file
			$dateTagToken = file_get_contents($dateTagToken);
			if ($dateTagHash == md5(date("n/j/Y/G").$dateTagToken.$dateTagUniqueString)) {
				$safe = true;
			} elseif ($dateTagHash == md5(date("n/j/Y/").(date("G")-1).$dateTagToken.$dateTagUniqueString)) { //Add 5 min token change period (currently token lasts 2 hours without it)
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
			$result = file_get_contents($dateTagToken, false, $context);
		  if ($result === FALSE) { /* Handle error */ } else {
			     if ($dateTagHash == md5(date("n/j/Y/G").$result.$dateTagUniqueString)) {
				         $safe = true;
			     } elseif ($dateTagHash == md5(date("n/j/Y/").(date("G")-1).$result.$dateTagUniqueString)) { //Add 5 min token change period (currently token lasts 2 hours without it)
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
    echo ($testingMode ? 2 : 0);//if testing!
  }
  }
}
?>
