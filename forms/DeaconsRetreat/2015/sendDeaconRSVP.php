<?php

require '../../../pmtproc/filefx.php';

// print("<p style='color: #000033'>This is the post array:");
// print_r($_POST);
// print("</p>");
extract($_POST);
//$info = explode (",", $RefNo);
$fieldsFile = preg_replace('/\s+/', '_', $event).'_Fields.txt';
if (!($fp=fopen($fieldsFile,'r'))){
	echo "Cannot open $fieldsFile";
	exit;
}
//read labels from label file into an array
$fieldArr=array();
$inString="";
while ($inString=read_file_line($fp)){
	list($key, $val)=explode('=', $inString);
	$fieldArr[trim($key)]=$val;
}
fclose($fp);

$recpFile='../../../contact/contacts.cfg';
$recpArr=array();
$recpNameArr=array();
if (!($fp3=fopen($recpFile,'r'))){
	echo "Cannot open $recpFile";
	exit;
}
$inString="";
while ($inString=read_file_line($fp3)){
	list($key,$recpName,$recpEmail)=explode(':', $inString);
	$recpArr[trim($key)]=$recpEmail;
	$recpNameArr[trim($key)]=$recpName;
}
fclose($fp3);
// print("<p style='color: #000033'>This is the fields array:");
// print_r($fieldArr);
// print("</p>");
$ofcPref="The following ".$event." RSVP was received:\n\n";
$regPref="Your ".$event." RSVP was received as follows.\n\n";
$regPref.="If there is an error, please call Harvey Huth at (518) 765-4625.\n\n";
$data="";
$name="";
$whoto="";
$ofMsg="";
$regOfMsg="";
$room="";
$arInd="";
$upAddr=false;
$fullname="";
$sentTo="";
foreach($_POST as $key => $value) {
	$fldVal = stripslashes($value);
	if($key === 'recipient'){
		$recpts = explode("|",$value);
		foreach($recpts as $recpt){
			if($recpArr[$recpt]){
				$emailRecpt.=",$recpArr[$recpt]";
				if($sentTo){
					$sentTo.=" and ";
				}
				$sentTo.=$recpNameArr[$recpt];
			}
			
		}
	}
	if ($fldVal){
		$fldVal=preg_replace('/;/', ',', $fldVal);
		$lbl="";
		$ofcMail=0;
		$regMail=0;
		if ($fieldArr[$key]){
			list($lbl,$regMail,$ofcMail,$dispOrder,$brkAfter)=explode('|',$fieldArr[$key]);
		}
		if($ofcMail){
			$brk="";
			if($brkAfter!=0){
				$brk="\n";
			}
			$ofcMailArr[$dispOrder]=$lbl.($lbl?": ":"").$fldVal.$brk;
		}
		if($regMail){
			$regMailArr[$dispOrder]=$lbl.($lbl?": ":"").$fldVal;
		}
	}
	switch($lbl){
		case "Name":
			$name=$fldVal;
			break;
		case "Email":
			if ($fldVal != ""){
				$whoto=$fldVal;	
			}
			break;
		default:
			break;
	}
}
ksort($ofcMailArr);
ksort($regMailArr);
$ofcMailData="";
foreach ($ofcMailArr as $key => $ofcData){
	if($ofcMailData != ""){
		$ofcMailData.="\n";
	}
	$ofcMailData.=urlencode($ofcData);

}
$regMailData="";
foreach ($regMailArr as $key => $regData){
	if($regMailData != ""){
		$regMailData.="\n";
	}
	$regMailData.=urlencode($regData);

}
//send e-mail to business office
$chdl = curl_init();
curl_setopt($chdl, CURLOPT_URL, "http://www.albanyepiscopaldiocese.org/contact/formprocregstrnt.php");
//set options
curl_setopt($chdl, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($chdl, CURLOPT_USERAGENT,
	"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($chdl, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($chdl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($chdl, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($chdl, CURLOPT_POST, true);
//set data to be posted
$ofcRecpt="jstellman@albanydiocese.org";
	curl_setopt($chdl, CURLOPT_POSTFIELDS, "name=".$name
	."&subject=$event RSVP&email=registration@albanydiocese.org&whoto=".$emailRecpt."&bcc=".$ofcRecpt."&message="
 	.$ofcPref."\n".$ofcMailData."\n");
$result = curl_exec($chdl);
//show information regarding the request - for debugging only
// echo "<p>result of office e-mail: ". $result . "</p>";
// print_r(curl_getinfo($chdl));
// echo "<p>".curl_errno($chdl) . '-' .
//          curl_error($chdl)."</p>";

	//close the connection
curl_close($chdl); 

//send e-mail to registrant
$chdl2 = curl_init();
curl_setopt($chdl2, CURLOPT_URL, "http://www.albanyepiscopaldiocese.org/contact/formprocregstrnt.php");
//set options
curl_setopt($chdl2, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($chdl2, CURLOPT_USERAGENT,
	"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($chdl2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chdl2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($chdl2, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($chdl2, CURLOPT_POST, true);
//set data to be posted
curl_setopt($chdl2, CURLOPT_POSTFIELDS, "name=".$name
	."&subject=$event RSVP Confirmation&email=webmaster@albanydiocese.org&whoto=".$whoto
	."&message=".$regPref.$regMailData."\n\n");
 
	//perform the request
$result = curl_exec($chdl2);

//show information regarding the request - for debugging only
// echo "<p>result of registrant's e-mail: ". $result . "</p>";
// print_r(curl_getinfo($chdl2));
// echo "<p>".curl_errno($chdl2) . '-' .
//  curl_error($chdl2)."</p>";
//close the connection
curl_close($chdl2);

$urlFile="https://www.albanyepiscopaldiocese.org/pmtproc/approvedrespnoconf.php";
//send to approved response page
$chdl3 = curl_init();
curl_setopt($chdl3, CURLOPT_URL, $urlFile);
//set options
curl_setopt($chdl3, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($chdl3, CURLOPT_USERAGENT,
	"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($chdl3, CURLOPT_RETURNTRANSFER, false);
curl_setopt($chdl3, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($chdl3, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($chdl3, CURLOPT_POST, true);
//set data to be posted
curl_setopt($chdl3, CURLOPT_POSTFIELDS, "event=$event&sentMsg=has been sent to&sentTo=$sentTo&othrMsg=You should be receiving a confirmation email shortly.");
 
	//perform the request
$result = curl_exec($chdl3);

//show information regarding the request - for debugging only
//echo "<p>result of approvedresp: ". $result . "</p>";
//print_r(curl_getinfo($chdl3));
//echo "<p>".curl_errno($chdl3) . '-' .
//           curl_error($chdl3)."</p>";
 
	//close the connection
curl_close($chdl3);
?>