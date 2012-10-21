<?php
function obfuscateEmails($text) {
   
   
$rand = "<span style=\"display:none;\">";
$rand .= uniqid();
$rand .="</span>";
$text= str_replace("@", "$rand@", $text);
   
   return $text;
}
?>