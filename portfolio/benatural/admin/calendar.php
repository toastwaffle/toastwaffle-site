<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php?ref=calendar.php");
}
include("style/setup.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <?php displayheaders(4); ?>
 <script type="text/javascript" src="style/calendarDateInput.js"></script> 
  </head>
  <body onload="setstyle()" onmousemove="setstyle()">
    <?php
if (isset($_POST['submit'])) {
  if ($_POST['act'] == 'content') {
    $errors = 0;
    foreach ($_POST as $key => $value) {
      $result = changecontent($key,$value);
      if ($result != 1) { 
        echo("$key not changed ");
        $errors = 1;
      }
    }
    if ($errors == 0) {
      echo("<h1>All changes saved.</h1>\n<p><a href=\"calendar.php\">Back</a></p>");
    }
    else {
      echo("\n<p><a href=\"calendar.php\">Back</a></p>");
    }
  }
  else {
    $query = sprintf("INSERT INTO `%s`.`%s` (`name`, `date`, `starttime`, `description`, `location`, `endtime`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", mysql_real_escape_string($db), mysql_real_escape_string($tblcal), mysql_real_escape_string($_POST['name']), mysql_real_escape_string($_POST['date']), mysql_real_escape_string($_POST['starttime']), mysql_real_escape_string($_POST['description']), mysql_real_escape_string($_POST['location']), mysql_real_escape_string($_POST['endtime'])); 
    $result=mysql_query($query, $cxn);
    if (!$result) {
      echo(mysql_error());
      echo("<p>Website error. Please contact the webmaster and try again later</p>");
    }
    else {
      echo("<h1>Event Added.</h1>\n<p><a href=\"calendar.php\">Back</a></p>");
    }
  }
}
else {
    ?>
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
        <form name="index" action="calendar.php" method="post">
        <textarea rows="20" cols="50" name="calendar"><?php dispvalue("calendar",2); ?></textarea>
        <input type="submit" name="submit" value="Save Changes" />
        <input type="hidden" name="act" value="content" />
        </form>
        <form name="index" action="calendar.php" method="post">
        <input type="hidden" name="act" value="date" />
        <p>Event Name: <input type="text" name="name" /></p>
        <table><tr><td><p>Event Date: </p></td><td><script>DateInput('date', true, 'YYYY-MM-DD')</script></td></tr></table>
        <p>Start Time (hh:mm): <input type="text" name="starttime" /></p>
        <p>End Time (hh:mm): <input type="text" name="endtime" /></p>
        <p>Event Location: <input type="text" name="location" /></p>
        <textarea rows="20" cols="50" name="description">Event Description</textarea>
        <input type="submit" name="submit" value="Add Event" />
        </form>
        <?php displayfooter(); ?>
      </div>
    </div>
<?php } ?>
  </body>
</html>
