# dateTag "tag" Format Examples
These are the tag's which you place on your website. They must be running back-end!
It doesn't matter if the tag is closed or not. E.x. </dateTag> wont do anything

Example of what tag should look like: <dateTag value="f3dce3f6672951976f9809abdd2c1a3b"> (value will be diffrent)

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

## ASP
```asp
  //dateTag
  ="<dateTag value='"+Month(Now)+"/"+Day(Now)+"/"+Year(Now)+"/"+Hour(Now)+"'>"
  
  //dateTag with hashing (requires md5.vbs at https://github.com/Wikinaut/md5.vbs)
  ="<dateTag value='"+MD5(Month(Now)+"/"+Day(Now)+"/"+Year(Now)+"/"+Hour(Now))+"'>"
  
  //dateTag with string token
  ="<dateTag value='"+Month(Now)+"/"+Day(Now)+"/"+Year(Now)+"/"+Hour(Now)+"theStringToken'>"
  
  //dateTag with string token + hashing
  ="<dateTag value='"+MD5(Month(Now)+"/"+Day(Now)+"/"+Year(Now)+"/"+Hour(Now)+"theStringToken")+"'>"
  
  //dateTag with remote/api token + hashing
  ="<dateTag value='"+MD5(Month(Now)+"/"+Day(Now)+"/"+Year(Now)+"/"+Hour(Now)+theToken)+"'>"
```

Feel Free to give me examples in diffrent languages for me to add!












