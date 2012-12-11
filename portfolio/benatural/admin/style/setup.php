<?php
error_reporting(E_NONE);
include_once("markdown.php");
include_once("smartypants.php");

$host = 'localhost';
$usr = 'webuser';
$pwd = 'xs37re!g64';
$db="benatural";
$tbladmin="admin";
$tblcont="content";
$tblcal="calendar";
$tblevents="pastevents";

$cxn=mysql_connect($host,$usr,$pwd);
if(!$cxn) {
  die(mysql_error($cxn));
}
mysql_select_db($db, $cxn);

function displaycontent($name) {
global $tbladmin;
global $tblcont;
global $tblcal;
global $tblevents;
global $cxn;
  $query="SELECT content FROM $tblcont WHERE name=\"$name\"";
  $result=mysql_query($query, $cxn);
  if(mysql_num_rows($result) != 1) {
    echo("I don't know which $name the programmer means, but he's buggered it up...".mysql_error());
  }
  else {
    $row=mysql_fetch_assoc($result);
    extract($row);
    echo(smartypants(markdown($content)));
  }
}

function dispvalue($name,$mode) {
global $tbladmin;
global $tblcont;
global $tblcal;
global $tblevents;
global $cxn;
  $query="SELECT content FROM $tblcont WHERE name=\"$name\"";
  $result=mysql_query($query, $cxn);
  if(mysql_num_rows($result) != 1) {
    echo("I don't know which $name the programmer means, but he's buggered it up...".mysql_error());
  }
  else {
    $row=mysql_fetch_assoc($result);
    extract($row);
    if ($mode == 1) {
    echo("value=\"".$content."\"");
    }
    else {
    echo($content);
    }
  }
}

function changecontent($name,$content) {
global $tblcont;
global $cxn;
  $query=sprintf("UPDATE $tblcont SET content=\"%s\" WHERE name = \"$name\"", mysql_real_escape_string($content));
  $result=mysql_query($query, $cxn);
  if(!$result) {
    return 0;
  }
  else {
    return 1;
  }
}

function displayheaders($mode) {
switch ($mode) {
case 1:
$style = "\n<link rel=\"stylesheet\" type=\"text/css\" href=\"admin/style/style.css\" />\n";
break;
case 2:
$style = "\n<link rel=\"stylesheet\" type=\"text/css\" href=\"admin/style/style2.css\" />\n";
break;
case 3:
$style = "\n<link rel=\"stylesheet\" type=\"text/css\" href=\"style/style.css\" />\n";
break;
case 4:
$style = "\n<link rel=\"stylesheet\" type=\"text/css\" href=\"style/style2.css\" />\n";
break;
}
$text = "<title>Be Natural Singing Group</title>
  $style
  <script type=\"text/javascript\"><!--
  function setstyle() {
  var div = document.getElementById (\"canvas\");
  var attr = document.createAttribute (\"style\");
  attr.value = \"top: \" + (document.getElementById('header').clientHeight - 25) + \"px;\";
  div.setAttributeNode (attr);

  var elem = document.getElementById('myCanvas');
  if (!elem || !elem.getContext) {
    return;
  }
  elem.width = document.documentElement.clientWidth;
  var context = elem.getContext('2d');
  if (!context) {
    return;
  }
 
 
    // Create the linear gradient: sx, sy, dx, dy.
    // That's the start (x,y) coordinates, followed by the destination (x,y).
    gradient = context.createLinearGradient(0, 0, 0, elem.height);
 
    gradient.addColorStop(0, '#663300');
    gradient.addColorStop(1, '#ffff99');
 
  // Use the gradient for the fillStyle.
  context.fillStyle = gradient;
 
  context.fillRect(0, 0, elem.width, elem.height);
};

    // --></script>";
echo($text);
}

function displayfooter() {
$text = "        <div id=\"footer\">
          <hr class=\"footer\"/>
          <p>This website &copy;2010 Be Natural Acapella Singing Group. All Rights Reserved.</p>
          <p><a href=\"mailto:supersam.littley@gmail.com\">Contact Webmaster</a></p>
        </div>";
echo($text);
}

function displaynavigation() {
$text = "      <div id=\"leftcol\">
        <p class=\"body\">Navigation</p>
        <ul>
          <li class=\"body\"><a href=\"index.php\">Home</a></li>
          <li class=\"body\"><a href=\"calendar.php\">Calendar</a></li>
          <li class=\"body\"><a href=\"pastevents.php\">Past Events</a></li>
          <li class=\"body\"><a href=\"contact.php\">Contact</a></li>
      </div>";
echo($text);
}

?>
