<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php?ref=index.php");
}
include("style/setup.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <?php displayheaders(3); ?>
  </head>
  <body onload="setstyle()" onmousemove="setstyle()">
    <?php
if (isset($_POST['submit'])) {
  $errors = 0;
  foreach ($_POST as $key => $value) {
    $result = changecontent($key,$value);
    if ($result != 1) { 
      echo("$key not changed ");
      $errors = 1;
    }
  }
  if ($errors == 0) {
    echo("<h1>All changes saved.</h1>\n<p><a href=\"index.php\">Back</a></p>");
  }
  else {
    echo("\n<p><a href=\"index.php\">Back</a></p>");
  }
}
else {
    ?>
    <form name="index" action="index.php" method="post">
    <div id="header">
      <div id="logo">
        <input type="text" id="logo" name="logo" length="1000" <?php dispvalue("logo",1); ?> />
      </div>
      <div id="text">
        <input type="text" name="title" size="100" <?php dispvalue("title",1); ?> />
        <input type="text" name="subtitle" size="100" <?php dispvalue("subtitle",1); ?> />
      </div>
    </div>
    <div id="canvas">
      <canvas id="myCanvas" height="50" width="400">
      <p>Your Browser sucks, so this page isn't going to be in full glory as intended. Might I just suggest getting a standards compliant browser such as Firefox, Chrome, Opera or Safari?</p>
      </canvas>
    </div>
    <div id="bod">
      <?php displaynavigation(); ?>
      <div id="content">
        <textarea rows="20" cols="50" name="section1"><?php dispvalue("section1",2); ?></textarea>
        <br />
        <textarea rows="20" cols="50" name="frontimage1"><?php dispvalue("frontimage1",2); ?></textarea>
        <p><a href="images.php" target="_blank">Click here to upload images</a></p>
	<p><a href="http://daringfireball.net/projects/markdown/syntax" target="_blank">Text Formatting Syntax</a></p>
        <input type="submit" name="submit" value="Save Changes" />
        <?php displayfooter(); ?>
      </div>
      </form>
    </div>
<?php } ?>
  </body>
</html>
