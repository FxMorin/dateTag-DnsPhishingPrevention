# dateTag DNS Phishing Prevention
It's very important that you read the full README for this project since it requires date/time sync and POST requests

dateTag is a powerful tag that can easily identify a phishing site. dateTag is currently part of a larger project that will be released in a couple months. dateTag alone is able to perfectly identify DNS phishing sites.

dateTag is minimal, to use it properly you should only call the dateTag.php on sites you know usually have dateTag. This means that you still need to write when dateTag.php gets called.

This version of dateTag is a much simpler version then the current dateTag being used in my project, my project uses dateTag as a small part of its core sytstem. Although this version of dateTag still gets a 99% DNS Phishing Identification Rate!

## Contents

- [Quick Start](#quick-start)
- [Setup](#setup)
- [Configuration](#configuration)
- [Communication](#communication)
- [Examples](#examples)
- [TODO](#todo)

## Quick Start

1. All you have to do it clone this repository and add it to your project:
   ```bash
   $ git clone https://github.com/fxmorin/dateTag-DnsPhishingPrevention.git
   ```
   You can also just download the [dateTag.php](../blob/master/src/dateTag.php) file & add it to your project
   
2. Make a POST request to the dateTag.php file whenever you want to check an url.
   Check [Communication](#communication) for more help! or Check [Examples](#examples) for examples on how to make the post requests!
   
## Setup
dateTag needs to be added to your website for it to work. The whole purpose of dateTag is so that you can recognise your own site depending on how it acts. This means dateTag must be run on the server-side of your website, so that phisherman can't see how to add it to there phishing sites.

Here is the format which you must use, depending on the configuration you have setup:

`<dateTag value=" md5( MM/DD/YYYY/HH + token ) ">`

MM = 1-12  **|**  DD = 1-31  **|**  YYYY = 1000-9999  **|**  HH = 0-23

There are many other ways to write the tag, such as removing the md5 if hashing is disabled!
You can get a list of premade dateTag's to add to your site [here](../blob/master/src/examples/dateTag-format.md)
There are multiple examples for many popular back-end web programming languages!
   
## Configuration

dateTag has a few multiple configurations which can be changed in dateTag.php (more coming soon!)

#### `date_default_timezone_set("America/Toronto");`

Make sure that the default timezone in dateTag is the same as the POST sender. Since if its not theres a chance you both have a diffrent timezone and that the time will never sync up, causing the dateTag.php to always respond 0


#### `$dateTagHashing = true;`

This option if set to `true` will tell the program that the dateTag value is hashed using md5: (more hash types coming soon)
**It's highly  recommended to have hashing enabled** , since phishing sites could try to mimic the dateTag's value if they find a pattern.


#### `$dateTagTokenType = 0;`

This option tell the program which type of method you will be using to specify the token to add to the dateTag value. The possible methods are:

**`0`:** String
**`1`:** Remote File
**`2`:** Api

Depending on the method you have decided to use, you will have to specify information:

0. #### String
   `$dateTagToken = "";`
   Specify a String which will be used as the token

1. #### Remote File
   `$dateTagRemote = "";`
   Specify a URL to a file containing the token you wish to use

2. #### Api
   `$dateTagApi = "";`
   Specify a URL which should be called to receive the token from


## Communication

This program currently requires you to make a POST request to the dateTag.php file. Here is how dateTag reads the POST request, and how it responds:

### Parameters
dateTag currently only checks for one parameter: (more coming soon)

`URL` should be the url which you want to check

### Response
dateTag currently only responds with either 0, 1, or 2:

0) means that the url is a phishing site, since the dateTag is incorrect. This could mean it was badly setup!

1) means that your url is not a phishing site, and that your dateTag seems to be working well.

2) means that your dateTag was either not found, or that it contains and empty value. Most of the time this is because its a    phishing site & they did not include the dateTag, therfore 2 should be seen as a phishing site unless you are testing. A    testing mode will be coming out soon so, 2 will most likley get removed soon and become 0.

## Examples
#### Making a post request

**Javascript:** (This does not need to be server-side. It could be part of a chrome extension)
```js
function checkUrl(url) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == 1) { //not a phishing site
        return true;
      }
      return false;
    }
  };
  xhttp.open("POST", "/path/to/file/dateTag.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("URL="+url);
}
```
**PHP:**
```php
function checkUrl($url) {
    $options = array('http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query(array())
    ));
		$context  = stream_context_create($options);
		$result = file_get_contents("/path/to/file/dateTag.php", false, $context);
    if ($result === 1) { //not a phishing site
      return true;
    }
    return false;
}
```
**Python:**
```python
import requests
def checkUrl(url):
    r = requests.post("/path/to/file/dateTag.php", data = {"URL" : url})
    if r.text == 1:
        return true
    return false
}
```
Feel free to give me post requests for other languages. I will be making a seperate file to display all of them while keeping a copy of these ones on the README.

## TODO

- [x] Creating the README
- [] Add iFrame prevention
- [] Add Proxy prevention
- [] Add error proofing
- [] Add configurations to dateTag.php
- [] Adding more hash methods
- [] Add more parameters to POST request
- [] Add POST request security
- [] Add other ways of using dateTag other then POST
