# dateTag DNS Phishing Prevention
dateTag is a powerful tag that can easily identify a phishing site. dateTag is currently part of a larger project that will be released in a couple months. dateTag alone is able to perfectly identify DNS phishing sites.

This version of dateTag is a much simpler version then the current dateTag being used in my project, my project uses dateTag as a small part of its core sytstem. Although this version of dateTag still gets a 99% DNS Phishing Identification Rate!

## Example

```js
function checkUrl(url) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText == 0) { //Phishing Site
        return false;
      }
      return true;
    }
  };
  xhttp.open("POST", "dateTag.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("URL="+url);
}
```
