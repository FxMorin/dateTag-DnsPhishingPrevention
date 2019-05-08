# dateTag "tag" Format Examples
These are the tag's which you place on your website. They must be running back-end!

## Node.js:
```js
  //var d = new Date().toLocaleString("en-US", {timeZone: "America/Toronto"}); //Use this to set timezone if needed
  var d = new Date();
  var md5 = require('md5'); //https://www.npmjs.com/package/md5
  var dateTag = document.createElement("dateTag");
  
  //dateTag
  dateTag.value = d.getMonth()+"/"+d.getDate()+"/"+d.getFullYear()+"/"+d.getHours();
  
  //dateTag with hashing (requires md5.js or node package md5)
  dateTag.value = md5(d.getMonth()+"/"+d.getDate()+"/"+d.getFullYear()+"/"+d.getHours());
  
  //dateTag with string token
  dateTag.value = d.getMonth()+"/"+d.getDate()+"/"+d.getFullYear()+"/"+d.getHours()+"theStringToken";
  
  //dateTag with string token + hashing
  dateTag.value = md5(d.getMonth()+"/"+d.getDate()+"/"+d.getFullYear()+"/"+d.getHours()+"theStringToken");
  
  //dateTag with remote/api token + hashing
  dateTag.value = md5(d.getMonth()+"/"+d.getDate()+"/"+d.getFullYear()+"/"+d.getHours()+tokenVar);
  
  document.body.appendChild(dateTag);
```

## PHP:
```php
  //dateTag
  echo '<dateTag value="'.date("n/j/Y/G").'">';

  //dateTag with hashing
  echo '<dateTag value="'.md5(date("n/j/Y/G")).'">';

  //dateTag with string token
  echo '<dateTag value="'.date("n/j/Y/G").'RANDOMTOKEN">';

  //dateTag with string token + hashing
  echo '<dateTag value="'.md5(date("n/j/Y/G")."RANDOMTOKEN").'">';

  //dateTag with remote/api token + hashing
  echo '<dateTag value="'.md5(date("n/j/Y/G").$remoteToken).'">';
```
