    <?php
      include("admin/style/setup.php");
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <?php displayheaders(1); ?>
  </head>
  <body onload="setstyle()" onmousemove="setstyle()"> 
  </head>
  <body>
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
        <h1>Past Events</h1>
        <hr />
        <?php
        if (isset($_GET['id'])) {
          $query="SELECT * FROM ".$tblevents.",".$tblcal." WHERE ".$tblevents.".calid = ".$tblcal.".id AND ".$tblevents.".id=".mysql_real_escape_string($_GET['id']);
          $result = mysql_query($query,$cxn);
          if (!$result) {
            echo("Website Error. Please Try Again Later.");
          }
          elseif (mysql_num_rows($result) != 1) {
            echo("Website Error. Please Try Again Later.");
          }
          else {
            $row = mysql_fetch_array($result);
            $date = new DateTime($row['date']);
            $starttime = new DateTime($row['starttime']);
            $endtime = new DateTime($row['endtime']);
            echo("<h3>".$row['name']."</h3>\n"); 
            echo("<h5>".$date->format('D, jS F Y').", ".$starttime->format('g:ia')." - ".$endtime->format('g:ia')."</h5>\n"); 
            echo("<h5>".$row['location']."</h5>\n"); 
            echo(markdown($row['report'])."\n");
            echo("<br />\n<br />\n<p><a href=\"pastevents.php\">Back</a></p>\n");
          }
        }
        else {
          displaycontent("pastevents"); ?>
        <br />
          <?php
          $query = "SELECT ".$tblevents.".id,name,date,starttime,endtime,location FROM ".$tblevents.",".$tblcal." WHERE ".$tblevents.".calid = ".$tblcal.".id ORDER BY date, starttime";
          $result = mysql_query($query,$cxn);
          if (!$result) {
            echo("Website Error. Please Try Again Later.");
          }
          else {
            echo("<table><tr><th>Event</th><th>Date</th><th>Time</th><th>Location</th></tr>\n");
            while($row = mysql_fetch_array($result)) {
              $date = new DateTime($row['date']);
              $starttime = new DateTime($row['starttime']);
              $endtime = new DateTime($row['endtime']);
              echo("<tr>\n");
              echo("<td><a href=\"pastevents.php?id=".$row['id']."\">".$row['name']."</a></td>\n");
              echo("<td>".$date->format('D, jS F Y')."</td>\n");
              echo("<td>".$starttime->format('g:ia')." - ".$endtime->format('g:ia')."</td>\n");
              echo("<td>".$row['location']."</td>\n");
              echo("</tr>\n");
            }
            echo("</table>\n");
          }
          }
          ?>
        <?php displayfooter(); ?>
      </div>
    </div>
  </body>
</html>
