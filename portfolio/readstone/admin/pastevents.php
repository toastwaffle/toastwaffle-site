<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php?ref=pastevents.php");
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
    <?php
if (isset($_POST['submit'])) {
  if ($_POST['exists'] == "1") {
  $qry = sprintf("UPDATE $tblevents SET report='%s' WHERE calid = '%s'", mysql_real_escape_string($_POST['report']), mysql_real_escape_string($_POST['calid']));
  }
  else { 
  $qry = sprintf("INSERT INTO $tblevents (calid,report) VALUES ('%s','%s')", mysql_real_escape_string($_POST['calid']), mysql_real_escape_string($_POST['report']));
  }
  $result = mysql_query($qry);
  if (!$result) {
    echo("<p>Website Error. Please contact the administrator and try again later.".mysql_error()."</p>\n<p><a href=\"pastevents.php\">Back</a></p>");
  }
  else {
    echo("<h1>All changes saved.</h1>\n<p><a href=\"pastevents.php\">Back</a></p>");
  }
}
elseif (isset($_GET['id'])) {
$calqry = "SELECT * from $tblcal WHERE id = '".$_GET['id']."'";
$peqry = "SELECT * from $tblevents WHERE calid = '".$_GET['id']."'";
$calresult = mysql_query($calqry);
$peresult = mysql_query($peqry);
if (!$calresult) {
	echo("Website Error, Please contact administrator, and try again later");
}
elseif (!$peresult) {
	echo("Website Error, Please contact administrator, and try again later");
}
elseif (mysql_num_rows($calresult) != 1) {
	echo("Website Error, Please contact administrator, and try again later");
}
else
	if (mysql_num_rows($peresult) == 0) {
		$row = mysql_fetch_array($calresult);
?>
<form method="post" action="pastevents.php">
<h1><?php echo($row['name']); ?></h1>
<h3><?php echo($row['date']."&nbsp;-&nbsp;".$row['starttime']); ?></h3>
<textarea name="report" rows="20" cols="50">Enter Report Here</textarea>
<input name="submit" type="submit" value="Continue" />
<input name="calid" type="hidden" value="<?php echo($_GET['id']); ?>" />
<input name="exists" type="hidden" value="0" />
</form>
<?php
}
else {
$row = mysql_fetch_array($calresult);
$perow = mysql_fetch_array($peresult);
?>
<form method="post" action="pastevents.php">
<h1><?php echo($row['name']); ?></h1>
<h3><?php echo($row['date']."&nbsp;-&nbsp;".$row['starttime']); ?></h3>
<textarea name="report" rows="20" cols="50"><?php echo($perow['report']); ?></textarea>
<input name="submit" type="submit" value="Continue" />
<input name="calid" type="hidden" value="<?php echo($_GET['id']); ?>" />
<input name="exists" type="hidden" value="1" />
</form>
<?php }} else { ?>
<p>Select event to add report for</p>
<form method="get" action="pastevents.php">
<select name="id">
<?php
	  $today = date('Y-m-d');
          $query = "SELECT id,name,date,starttime,endtime,location FROM $tblcal ORDER BY date DESC, starttime DESC LIMIT 30";
          $result = mysql_query($query,$cxn);
          if (!$result) {
            echo("Website Error. Please Try Again Later.".mysql_error());
          }
	  else {
		while ($row = mysql_fetch_array($result)) {
			echo("<option value=\"".$row['id']."\">".$row['date']."&nbsp;".$row['name']."</option>");
		}
?>
</select>
<input type="submit" value="Continue" />
</form>
<?php } ?>
        <?php displayfooter(); ?>
      </div>
      </form>
    </div>
<?php } ?>
  </body>
</html>
