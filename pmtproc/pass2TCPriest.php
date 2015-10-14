<?php

require 'filefx.php';
//print_r($_POST);
extract ($_POST);
$RefID = $RefID2 . "," . $RefID3 . "," . $RefID;
$filename = $_POST['RefID'];
$filename = preg_replace('/\s+/', '_', $filename).'.txt';

$regData="regCode=$regCode";
foreach ( $_POST as $key => $value) {
	if ($regData != ""){
		$regData .= ",";
	}
	if ($key=="Amount"){
		$value='\$'.$value;
	}
	if ($key != "regCode"){
		$value = preg_replace('/,/',';', $value);
		$regData .= $key."=".$value;	
	}
}
$regData .= "\n";
append_to_file($filename, $regData);

$MerchantID = "107176";
$RegKey = "5B080255-FB4D-4AEB-8AB6-109AB736C339";
$RURL = "https://www.albanyepiscopaldiocese.org/pmtproc/pmtprocresponsepriest.php";  //Replace this with your response page

$x ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$x .='<html><body onload="document.frmReturn.submit()";>';// 
if ($Amount > '0'){
	//$x .= '<form action="https://www.albanyepiscopaldiocese.org/pmtproc/completeRegistrationPriest.php" method="post" name="frmReturn" id="frmReturn">';
    $x .= '<form action="https://webservices.primerchants.com/billing/TransactionCentral/EnterTransaction.asp?" method="post" name="frmReturn" id="frmReturn">';
    $x .= '<input type="hidden" name="MerchantID"   value="' . $MerchantID . '">';
    $x .= '<input type="hidden" name="RegKey"   value="' . $RegKey . '">';
    $x .= '<input type="hidden" name="RURL" value="' . $RURL . '">';
    $x .= '<input type="hidden" name="ConfirmPage"  value="' . $ConfirmPage . '">';
    $x .= '<input type="hidden" name="TransType"    value="' . $TransType . '">';
    $x .= '<input type="hidden" name="RefID"    value="' . $RefID . '">';
    $x .= '<input type="hidden" name="Amount"   value="' . $Amount . '">';
    $x .= '<input type="hidden" name="AVSADDR"  value="' . $AVSADDR . '">';
    $x .= '<input type="hidden" name="AVSZIP"   value="' . $AVSZIP . '">';
    $x .= '<input type="hidden" name="NameonAccount"    value="' . $NameonAccount . '">';
} 
else{
    $x .='<form action="https://www.albanyepiscopaldiocese.org/pmtproc/pmtprocresponsepriest.php" method="post" name="frmReturn" id="frmReturn">';
    $x .= '<input type="hidden" name="TransID"    value="' . '12345' . '">';
    $x .= '<input type="hidden" name="RefNo"    value="' . $RefID . '">';
    $x .= '<input type="hidden" name="AVSCode"  value="' . '12345' . '">';
    $x .= '<input type="hidden" name="CVV2ResponseMsg"  value="' . 'OK' . '">';
    $x .= '<input type="hidden" name="Auth" value="' . 'Approved' . '">';
}
$x .=  "</form></body></html>";
if ($_POST) {
  if (isset($_COOKIE['fam'])){
   setcookie('fam', '', time()-3600);
     echo $x;
   }
   else {
   echo ("JavaScript is needed for this page. Please go ");
   $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
   echo "<a href='$url'>back</a>";
   echo ", turn on JavaScript and try again - thanks!"; 	
}}