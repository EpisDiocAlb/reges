<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta content="utf-8" http-equiv="encoding">
<meta http-equiv="Description" content="Payment Processing Response">
<title>Epsicopal Diocese of Albany - Payment Processing Response</title>
<!-- <link rel="stylesheet" href="/main.css" type="text/css"> -->
<link href="../stylesheet/template03.css" rel="stylesheet" type="text/css">
<script type="text/javascript">


</script>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style3 {
    color: #000000;
    font-weight: bold;
}
.style7 {font-size: small; font-style: italic; }
.style8 {color: #CC6600}
.style11 {color: #330066; font-weight: bold; }
.style16 {color: #333366}
.style17 {color: #CC6600; font-weight: bold; }
.style42 {
    font-size: small;
    color: #330066;
    font-weight: bold;
}
.style53 {color: #CCCC00}
.style71 {color: #FF6600}
.style69 {font-size: large; color: #330066;}
-->
</style>
</head>
<body class="style1" leftmargin="0" marginwidth="0" marginheight="0">
        <table width="779" border="0" align="center" cellpadding="0" cellspacing="0"> <!-- Header -->
        <tr>
            <td><img src="../NewImages/header.gif" width="779" height="175" alt=""></td>
        </tr>
    </table> <!-- End of Header -->
    <table width="779" border="0" align="center" cellpadding="6" cellspacing="0"> 
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
				print("<h3 style=\"text-align: center; color: #CC6600;\"><i>Your registration for the " .$event. " has been forwarded to the Diocesan Business Office.</i><br />");
				if($emailSent){
					print("$emailSent<br /><br /></h3>");
				}				
				print("<h2 style=\"text-align: center; color: #CC6600;\"><i>Do not press the back button!</i></h2>");
				print("<h3 style=\"text-align: center; color: #CC6600;\">To return to the Episcopal Diocese of Albany, click <a style=\"color: #CC6600;\" href=\"http://www.albanyepiscopaldiocese.org\">here</a>.</h3>");
				//print("<h2 style=\"text-align: center; color: #CC6600;\"><b>If you have additional registrations to enter, <i>DO NOT CLICK THE BACK BUTTON</i>. To enter additional registrations, click <a style=\"color: #CC6600;\" href=\"http://www.albanyepiscopaldiocese.org/forms/2013ConventionRegistrationForm.php\" target=\"_blank\">here</a>.</b></h2>");
			 	print("<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p><p>&nbsp;</p>");
			 	//
			?>
            </td>
            <td valign="top" style="border-left: ridge 4px #EDEDED; background-color: #FFFFCC;" width="100">
                <!--Third column to keep look and feel -->
            </td>
        </tr>
    </table> <!-- End of main table-->
    <table width="779" border="0" align="center" cellpadding="0" cellspacing="0"> <!-- Footer -->
  <tr>
    <td width="324" style="background-color: #333399; background-image: url(../NewImages/t5-1.gif); background-repeat: no-repeat;" class="bottom">&nbsp;</td>
    <td width="455" height="28" align="center" style="color: #FFFFFF; background-color: #333399">Copyright &copy; www.albanydiocese.org All rights are reserved</td>
  </tr>
</table> <!-- End of footer -->    
</body>
</html>


