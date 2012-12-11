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
//define a maxim size for the uploaded images in Kb
 define ("MAX_SIZE","250"); 

//This function reads the extension of the file. It is used to determine if the file  is an image by checking the extension.
 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }

//This variable is used as a flag. The value is initialized with 0 (meaning no error  found)  
//and it will be changed to 1 if an errro occures.  
//If the error occures the file will not be uploaded.
 $errors=0;
//checks if the form has been submitted
 if(isset($_POST['Submit'])) 
 {
 	//reads the name of the file the user submitted for uploading
 	$image=$_FILES['image']['name'];
 	//if it is not empty
 	if ($image) 
 	{
 	//get the original name of the file from the clients machine
 		$filename = stripslashes($_FILES['image']['name']);
 	//get the extension of the file in a lower case format
  		$extension = getExtension($filename);
 		$extension = strtolower($extension);
 	//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
	//otherwise we will do more tests
 if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
 		{
		//print error message
 			echo '<h1>Unknown extension!</h1>';
 			$errors=1;
 		}
 		else
 		{
//get the size of the image in bytes
 //$_FILES['image']['tmp_name'] is the temporary filename of the file
 //in which the uploaded file was stored on the server
 $size=filesize($_FILES['image']['tmp_name']);

//compare the size with the maxim size we defined and print error if bigger
if ($size > MAX_SIZE*1024)
{
	echo '<h1>You have exceeded the size limit!</h1>';
	$errors=1;
}
else {
$newname="images/".$filename;
if (file_exists($newname)) {
        echo("<h1>A file with that name already exists, please rename the image you're trying to upload and try again</h1>");
	$errors=1;
}
else {
$copied = copy($_FILES['image']['tmp_name'], $newname);
if (!$copied) 
{
	echo '<h1>Copy unsuccessful!</h1>';
	$errors=1;
}}}}}}

//If no errors registred, print the success message
 if(isset($_POST['Submit']) && !$errors) 
 {#
	global $newname;
        chmod($newname, 0777);
 	echo "<h1>File Uploaded Successfully to 'admin/$newname'! You can use this location (without the quotes) to insert images into the website, using the code below.</h1>\n<code>![Image](admin/$newname)</code>";
	echo(markdown("![Image]($newname)"));
 }

 ?>

 <!--next comes the form, you must set the enctype to "multipart/frm-data" and use an input type "file" -->
 <form name="newad" method="post" enctype="multipart/form-data"  action="">
 <table>
 	<tr><td><input type="file" name="image"></td></tr>
 	<tr><td><input name="Submit" type="submit" value="Upload image"></td></tr>
 </table>	
 </form>
        <?php displayfooter(); ?>
    </div>
</html>
