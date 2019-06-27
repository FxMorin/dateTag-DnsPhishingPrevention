<?php
/**
  * @author Fx Morin
  * @version 1.3.0
  */
date_default_timezone_set("America/Toronto"); //Yes I live in Toronto
//Make sure that the timezone is the same on both the website & dateTag

//dateTag is a program created by Fx Morin. It easily detects dns phishing sites
//dateTag is a tag '<dateTag value="f3dce3f6672951976f9809abdd2c1a3b">' that holds
//a md5 hash of the current date and time in hours, a token, and a UniqueID. The
//token is what makes it so nobody can bypass this. The token is given by you, it
//can also be access remotly so that the token changes after a certain amount of
//times. If the token is incorrect, the program checks the hour before, if that
//one also fails then its a phishing site. To not use token (not recommended) just
//use TokenType 0, & set the dateTagToken to an empty string! UniqueID stops the
//ability to use a server-side proxy to display the site if it has a modified
//login to phish you. UniqueID has 4 different ways of creating an ID.
//For more info, visit https://github.com/fxmorin/dateTag-DnsPhishingPrevention

$hashing = true;
$tokenType = 0; //0=string, 1=remote file, 2=api
$token = "";
//The dateTag date format is "n/j/Y/G"
//dateTag can be placed anywhere in the site
//go to https://github.com/fxmorin/dateTag-DnsPhishingPrevention to get more
//information on the API

$uniqueID = false;
$uniqueType = 0; //0=userAgent,1=ip-address,2=both,3=get_Browser()
//UniqueID as described above is an extra security mesure which prevents server-
//side proxy's from being able act as a middle-man to capture passwords. This
//would prevent XSS, Click-Jacking, & proxy's from phishing you. Please read
//the online documentation on this before using it.

// ========== DEPRECATED ==========
//$customTag = false;
//$customTagString = "dateTag"; //Must be a-z, A-Z, 0-9
//DO NOT USE SPECIAL CHARACTERS. DO NOT USE EXISTING TAGS such as <html>
//CustomTag is a feature that you should use. It makes it nearly impossible for
//a phisherman to find out if your using dateTag. dateTag is not common through
//so this feature isin't really going to affect the security of the site. It
//does help dateTag blend in to your site better & provides a personal touch.
//Be warned, special characters will not work for the time being. This will be
//updated in a later release. E.x. if $customTagString = "fx"; Then <fx value="...">
//This feature has now been converted so that it must now be client-side.
//You must look for the tag yourself. In PHP you can use:
//preg_match('/</'.$dateTag.'/(.*?)value=\"(.*?)\"/', $html, $info);
//$dateTag being the name of the tag your looking for. $html is the website html.
//And $info is the data, $info[1] is the dateTag value.

$testingMode = false;
//This allows you to see how the program will respond. Allowing you to test if
//dateTag is properly installed on the website, the dateTag.php, & the POST sender
//testingMode is still a work in progress, more is being added in every release
//For more information go to https://github.com/fxmorin/dateTag-DnsPhishingPrevention

//==============================================================================
//                      DO NOT MODIFY PAST THIS POINT
//==============================================================================

$console = array();

function clean($data) {
    $d = $data;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($testingMode && $d != $data) {
        $console[sizeof($console)+1] = "Invalid Entry was detected: ".$d."  -  Characters,Whitespaces, and Slashes should not be used";
    }
    return $data;
}
function doMD5($data) {
    return ($hashing ? md5($data) : $data);
}
function isSafe($h,$t,$US) {
    if ($h == doMD5(date("n/j/Y/G").$t.$US)) {
        return true;
    } elseif ($h == doMD5(date("n/j/Y/").(date("G")-1).$t.$US)) {
        //^^^ Add 5 min token change period (currently token lasts 2 hours without it)
        if ($testingMode) {
            $console[sizeof($console)+1] = "This token was registered in the 5 min token change period. Make sure it does not do this everytime. Are you lagging?";
        }
        return true;
    } else {
        if ($testingMode) {
            $console[sizeof($console)+1] = "Value's do not match. Hash from website: ".$h." - Hash from dateTag: ".doMD5(date("n/j/Y/G").$t.$US)."\n It is recommended to remove hashing while testing!";
        }
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['dateTag']) && isset($_POST['dateTag'])) {
        $uniqueString = ""; $safe = false; $hash = $_POST['dateTag'];
        //security to prevent looking for existing tags will be added in a later release
        if ($uniqueID) {
            if ($uniqueType == 0) { //userAgent
                $uniqueString = $_SERVER['HTTP_USER_AGENT']??null;
            } else if ($uniqueType == 1) {//ip-address
                $uniqueString = $_SERVER["REMOTE_ADDR"]??null;
            } else if ($uniqueType == 2) { //both
                $uniqueString = $_SERVER["REMOTE_ADDR"]??null." ".$_SERVER['HTTP_USER_AGENT']??null;
            } else if ($uniqueType == 3) { //get_browser()
                $uniqueString = implode("",get_Browser());
            }
            if ($testingMode) {
                $console[sizeof($console)+1] = "Your uniqueID on dateTag has been set to: ".$uniqueString;
            }
        }
        if ($hash != "") {
		        if ($tokenType == 0){ //string
                $safe = isSafe($hash,$token,$uniqueString);
            } elseif ($tokenType == 1) { //remote file
			          $token = file_get_contents($token); //Add remove file error handling
			          $safe = isSafe($hash,$token,$uniqueString);
		        } elseif ($tokenType == 2) { //Api
			          //use key 'http' even if you send the request to https://...
			          $options = array(
    		            'http' => array(
        	              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        	              'method'  => 'POST',
        	              'content' => http_build_query(array())
    		            )
			          );
			          $context  = stream_context_create($options);
			          $result = file_get_contents($token, false, $context);
		            if ($result === FALSE) {
                    if ($testingMode) {
                        $console[sizeof($console)+1] = "API invalid! The API failed, most likely due to invalid API endpoint!";
                    }
                } else {
                    $safe = isSafe($hash,$result,$uniqueString);
		            }
            }
            //1 means its not a phishing site. 0 means it is.
            if ($testingMode) {
                if ($safe) {
                    $console[sizeof($console)+1] = "This site is not a phishing site!";
                } else {
                    $console[sizeof($console)+1] = "This site has been classified as a phishing site!";
                }
            } else {
                echo ($safe ? 1 : 0);
            }
	      } else { //If dateTag was not found or if it was empty
            echo ($testingMode ? "dateTag was not found or is empty" : 0);//if testing!
        }
        if ($testingMode && !empty($console)) {
            for ($x = 0;$x < sizeof($console); $x++) {
                echo $console[$x];
            }
        } else if ($testingMode) {
            echo "console is empty";
        }
    } else if ($testingMode) {
        echo "dateTag has not been set in your post request!!!";
    }
} else if ($testingMode) {
    echo "This needs to be a POST request for security reasons!";
}
?>
