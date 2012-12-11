<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php?ref=contact.php");
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
    echo("<h1>All changes saved.</h1>\n<p><a href=\"contact.php\">Back</a></p>");
  }
  else {
    echo("\n<p><a href=\"contact.php\">Back</a></p>");
  }
}
else {
    ?>
    <form name="index" action="contact.php" method="post">
    <div id="header">
      <div id="logo">
        <?php displaycontent("logo"); ?>
      </div>
      <div id="text">
        <?php displaycontent("title"); ?>
        <?php displaycontent("subtitle"); ?>
      </div>
    </div>
    <div id="canvas">
      <canvas id="myCanvas" height="50" width="400">
      </canvas>
    </div>
    <div id="bod">
      <?php displaynavigation(); ?>
      <div id="content">
        <textarea rows="20" cols="50" name="contact"><?php dispvalue("contact",2); ?></textarea>
        <input type="submit" name="submit" value="Save Changes" />
        <?php displayfooter(); ?>
      </div>
      </form>
    </div>
<?php } ?>
  </body>
</html>
