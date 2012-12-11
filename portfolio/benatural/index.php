    <?php
      include("admin/style/setup.php");
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <?php displayheaders(1); ?>
    <link rev="made" href="mailto:olwyn@naturalvoice.net">
    <meta name="keywords" content="Acapella, A capella, Singing Group, Lancashire, Be Natural">
    <meta name="description" content="An Acapella singing group from Lancashire, United Kingdom">
    <meta name="ROBOTS" content="ALL">
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
        <?php displaycontent("section1"); ?>
        <?php displaycontent("frontimage1"); ?>
        <?php displayfooter(); ?>
      </div>
    </div>
  </body>
</html>
