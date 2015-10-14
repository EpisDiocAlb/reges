<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Diocese of Albany Home</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="stylesheet/template03.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style3 {
	color: #000000;
	font-weight: bold;
}
.style7 {font-size: small; font-style: italic; }
.style8 {color: #CC6600}
.style9{color: #CC6600; font-style: italic;}
.style11 {color: #330066; font-weight: bold; }
.style16 {color: #333366}
.style17 {color: #CC6600; font-weight: bold; }
.style21 {color: #990000; font-weight: bold; font-size: medium; }
.style26 {color: #CC6600; font-weight: bold; font-size: medium; }
.style33 {font-size: x-small; color: #330099;}
.style34 {color: #330099; font-size: small; font-weight: bold; }
.style41 {color: #FF00FF}
.style42 {
	font-size: small;
	color: #330066;
	font-weight: bold;
}
.style49 {font-size: x-small; color: #333333;}
.style51 {color: #666666; font-weight: bold; }
.style53 {color: #CCCC00}
.style54 {color: #CC6600; font-weight: bold; font-size: small; }
.style55 {font-size: medium}
.style71 {color: #FF6600}
.style73 {font-size: small; color: #330099; }
.style74 {color: #330099;}
.style77 {color: #CC6600; font-weight: bold; font-size: larger; }
.style80 {color: #330066; font-weight: bold; font-size: medium; }
.style82 {color: #CC6600; font-style: italic; }
-->
</style>
</head>
<body class="style1">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0"> <!-- Header -->
  <tr>
      <td><img src="http://www.albanyepiscopaldiocese.org/NewImages/header.gif" width="100%" height="100%" alt=""></td>
  </tr>
</table> <!-- End of Header -->
    <table width="90%" border="0" align="center" cellpadding="6" cellspacing="0"> 
    <!-- Overall table for the whole page -->
        <tr> <!-- Table only has one row, but three columns - one for side nav, one for content, one for events -->
            <td valign="top" style="border-right: ridge 4px #EDEDED; background-color: #FFFFCC;" width="100">
                <!-- First column is for side nav (links list)-->
                <p><a class="style17" href="http://www.albanyepiscopaldiocese.org/">Home<br /></a><br /></p>
            </td>
            <td> <!-- Second column is for content --> 
               <!-- <p style="text-align: center"><img src="../ministries/convention/2013/PassionForJesusLogoColor.jpg" width="100" height="125"></p> --> 
          <?php
            	require 'pmtfx.php';
				extract($_POST);
				print("<p>&nbsp;</p>");
				print("<h3 style=\"text-align: center; color: #CC6600;\"><i>Your registration for the $event $sentMsg $sentTo. $othrMsg</i></h3>");
				print("<h3 style=\"text-align: center; color: #CC6600;\">To return to the Episcopal Diocese of Albany, click <a style=\"color: #CC6600;\" href=\"http://www.albanyepiscopaldiocese.org\">here</a>.</h3>");
			 	print("<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p><p>&nbsp;</p>");
			?>
            </td>
            <td valign="top" style="border-left: ridge 4px #EDEDED; background-color: #FFFFCC;" width="100">
                <!--Third column to keep look and feel -->
            </td>
        </tr>
    </table> <!-- End of main table-->
    <table width="779" border="0" align="center" cellpadding="0" cellspacing="0"> <!-- Footer -->
  <tr>
    <td width="48%" style="background-color: #333399; background-image: url(../NewImages/t5-1.gif); background-repeat: no-repeat;" class="bottom">&nbsp;</td>
    <td width="52%" height="28" align="center" style="color: #FFFFFF; background-color: #333399">Copyright &copy; www.albanydiocese.org All rights are reserved</td>
  </tr>
</table> <!-- End of footer -->    
</body>
</html>


