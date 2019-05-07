<html>
<footer>
  <?php
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
  ?>
  <!--Examples of what dateTag looks like in html (they all look the same)-->
  <dateTag value="f3dce3f6672951976f9809abdd2c1a3b">

</footer>
</html>
