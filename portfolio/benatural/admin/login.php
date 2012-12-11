<?php
session_start();
if (isset($_SESSION['user'])) {
header("Location:index.php");
}
include("style/setup.php");
	if (isset($_POST['myusername'])) {
	$myusername=$_POST['myusername'];
	$mypassword=$_POST['mypassword'];

	$myusername = stripslashes($myusername);
	$mypassword = stripslashes($mypassword);
	$myusername = mysql_real_escape_string($myusername);
	$mypassword = mysql_real_escape_string($mypassword);
	$mypassword = md5($mypassword);

	$sql="SELECT * FROM users WHERE user='$myusername'";
	$result=mysql_query($sql);
	$count=mysql_num_rows($result);

	if($count != 1){
	  echo "<p>Wrong Username - Username not found</p>";
	}
	else {
	  $sql2="SELECT * FROM users WHERE user='$myusername' and pass='$mypassword'";
	  $result2=mysql_query($sql2);
	  if (!$result2) {
		die(mysql_error());
	  }
	  $count2=mysql_num_rows($result2);
	  if($count2 != 1){
	    echo "<p>Wrong Password</p>";
	  }
	  else {
		$_SESSION['user'] = $myusername;	  
		if (isset($_POST['ref'])) {
		  $ref = $_POST['ref'];
		  header("location:$ref");
		}
		else {  
	          header("location:index.php");
		}
	}
	}
	}
	else {
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
	<form name="form1" method="post" action="login.php">
	<table width="300" border="0" align="center" cellpadding="3" cellspacing="1">
	<tr>
	<td colspan="3"><strong>Member Login </strong></td>
	</tr>
	<tr>
	<td width="78">Username</td>
	<td width="6">:</td>
	<td width="294"><input name="myusername" type="text" id="myusername"></td>
	</tr>
	<tr>
	<td>Password</td>
	<td>:</td>
	<td><input name="mypassword" type="password" id="mypassword"></td>
	</tr>
	<tr>
	<td>
	<?php if (isset($_GET['ref'])) {
	$uri = $_GET['ref'];
	echo "<input type=\"hidden\" name=\"ref\" value=\"$uri\"";
	} ?>
	</td>
	<td>&nbsp;</td>
	<td><input type="submit" name="Submit" value="Login"></td>
	</tr>
	</table>
	</form>
        <?php displayfooter(); ?>
    </div>
</html>
<?php } ?>
