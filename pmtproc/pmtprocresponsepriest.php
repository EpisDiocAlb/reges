<?php
extract($_POST);
$url= "https://www.albanyepiscopaldiocese.org/pmtproc/declinedresp.php";

if ($Auth && $Auth != "Declined"){ 
		$url="https://www.albanyepiscopaldiocese.org/pmtproc/completeRegistrationPriest.php";
}
$p ='<html><body onload="document.frmResponse.submit()">'; //  
$p .='<table width="779" border="0" align="center" cellpadding="0" cellspacing="0"> ';
$p .='<tr><td><img src="https://www.albanyepiscopaldiocese.org/NewImages/header.gif" width="779" height="175" alt=""></td></tr></table>';
$p .= '<table width="779" border="0" align="center" cellpadding="6" cellspacing="0">';
$p .= '<tr><td valign="top" style="border-right: ridge 4px #EDEDED; background-color: #FFFFCC;" width="100">';
$p .= '</td><td style="text-align: center"><table style="color: #CC6600;"  width="594"  border="0" cellpadding="0" cellspacing="0">';
$p .= '<tr><td><table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top" width="105">';
$p .= '<img src="https://www.albanyepiscopaldiocese.org/images/dioshield.jpg" width="140" height="140"></td>';
$p .= '</tr><tr><td colspan="3" style="text-align: center">&nbsp;</td></tr><tr><td><h3>Please wait while we process your request.</h3></td></tr>';
$p .= '</table></td></tr><td valign="top" style="border-left: ridge 4px #EDEDED; background-color: #FFFFCC;" width="100">';
$p .= '</td></tr></table>';
$p .= '<table width="779" border="0" align="center" cellpadding="0" cellspacing="0">';
$p .= '<tr><td width="324" style="background-color: #333399; background-image: url(https://www.albanyepiscopaldiocese.org/NewImages/t5-1.gif); background-repeat: no-repeat;" class="bottom">&nbsp;</td>';
$p .= '</tr></table>';
$p .='<form action='.$url.' method="post" name="frmResponse" id="frmResponse">';
$p .= '<input type="hidden" name="TransID"	value="' . $TransID . '">';
$p .= '<input type="hidden" name="RefID"	value="' . $RefNo . '">';
$p .= '<input type="hidden" name="AVSCode"	value="' . $AVSCode . '">';
$p .= '<input type="hidden" name="CVV2ResponseMsg"	value="' . $CVV2ResponseMsg . '">';
$p .= '<input type="hidden" name="Auth"	value="' . $Auth . '">';
if ($Notes){
	$p .= '<input type="hidden" name="Notes" value="' . $Notes . '">';
}
$p .=  "</form></body></html>";
echo $p;
?>
