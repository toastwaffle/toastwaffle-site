<?php
	if (defined('AJAX') and AJAX or isset($_POST['ajax']))
	     exit($body."HEY_JAVASCRIPT_THIS_IS_AN_ERROR_JUST_SO_YOU_KNOW");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Chyrp: <?php echo $title; ?></title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<style type="text/css">
			html, body, ul, ol, li,
			h1, h2, h3, h4, h5, h6,
			form, fieldset, a, p {
				margin: 0;
				padding: 0;
				border: 0;
			}
			html {
				font-size: 62.5%;
			}
			body {
				font: 1.25em/1.5em normal "Verdana", Helvetica, Arial, sans-serif;
				color: #626262;
				background: #e8e8e8;
				padding: 0 0 5em;
			}
			.window {
				width: 30em;
				background: #fff;
				padding: 2em;
				margin: 5em auto 0;
				-webkit-border-radius: 2em;
			}
			h1 {
				color: #ccc;
				font-size: 3em;
				margin: .25em 0 .5em;
				text-align: center;
			}
			code {
				color: #06B;
				font-family: Monaco, monospace;
			}
			.footer {
				color: #777;
				margin-top: 1em;
				font-size: .9em;
				text-align: center;
			}
			.error {
				color: #F22;
				font-size: 12px;
			}
			a:link, a:visited {
				xtext-decoration: none;
				color: #6B0;
			}
			a:hover {
				text-decoration: underline;
			}
			a.big {
				background: #eee;
				margin-top: 2em;
				display: block;
				padding: .75em 1em;
				color: #777;
				text-shadow: #fff 1px 1px 0;
				font: 1em normal "Lucida Grande", "Verdana", Helvetica, Arial, sans-serif;
				text-decoration: none;
				-webkit-border-radius: .5em;
			}
			a.big:hover {
				background: #f5f5f5;
			}
			a.big:active {
				background: #e0e0e0;
			}
		</style>
		<script type="text/javascript" charset="utf-8">
			$(function(){
				$(".message").append('<a class="big" href="javascript:history.back()">&larr; Back</a>')
			})
		</script>
	</head>
	<body>
		<div class="window">
			<h1><?php echo $title; ?></h1>
			<div class="message">
				<?php echo nl2br($body); ?>
			</div>
		</div>
		<p class="footer">Chyrp <?php echo CHYRP_VERSION; ?> &copy; 2008 Alex Suraci</p>
	</body>
</html>
