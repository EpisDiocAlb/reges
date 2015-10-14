<?php

require 'filefx.php';
//print_r($_POST);
extract($_POST);
extract($_COOKIE);
$info = explode (",", $RefID);
$event = $info[2];
$filename = preg_replace('/\s+/', '_', $event).'.txt';
$fieldsFile = preg_replace('/\s+/', '_', $event).'_Fields.txt';
$filename2='roomcount.txt';
$regCode = $info[1];
$ofcPref="The following ".$event." registration was received:\n\n";
$regPref="Your registration was received as follows.\n\n";
$regPref.="If there is an error, please call the Diocesan Business Office at (518) 692-3350, between the hours of 9 am and 4:30 pm Eastern Time.\n\n";
$name=$info[0];
$whoto="";
$ofMsg="";
$regOfMsg="";
$amtStrOfc="\nAmount Charged to Credit Card: \$0";
if (!$fp3 = fopen($fieldsFile, 'r')) {
     echo "Cannot open file ($fieldsFile)";
     exit;
}
//read labels from label file into an array
$fieldArr=array();
$inString2="";
while ($inString2=read_file_line($fp3)){
	list($key, $val)=explode('=', $inString2);
	$fieldArr[trim($key)]=$val;
}
fclose($fp3);
if (!$fp = fopen($filename, 'r')) {
     echo "Cannot open file ($filename)";
     exit;
}
$ofcMailArr = array();
$regMail = array();
$arInd="";
while($inString = read_file_line($fp)) {
		$nvp = explode(',',$inString);
		list($reg,$regVal) = explode('=',$nvp[0]);
		if ($regVal == $regCode){
			$isStaffCabin=false;
			foreach($nvp as $pair) {
				list($key, $value) = explode('=', $pair);
				$fldVal = stripslashes($value);
				if ($fldVal){
					$fldVal=preg_replace('/;/', ',', $fldVal);
					$lbl="";
					$ofcMail=0;
					$regMail=0;
					if ($fieldArr[$key]){
						list($lbl,$regMail,$ofcMail,$dispOrder,$brkAfter)=explode('|',$fieldArr[$key]);
					}
					if ($key=="Amount"){
						$amtStrOfc="\nAmount Charged to Credit Card: ".$fldVal."\n";
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
					
					switch($lbl){
						case "Name":
							$name=$value;
							break;
						case "Email":
							if ($value != ""){
								$whoto=$value;
							}
							break;
						case "Room Type":
							$arInd=$fldVal;
							break;
						case "Gender":
							if ($fldVal!=""){
								$arInd .="-".$fldVal;
							}
							break;
					}
				}
		}
	}
}
if(($fp2 = fopen($filename2, "r")) == false) {
		die("Can't open data file '$filename2'.\n");
}
$regByType=array();
$currArInd="";
$addedToRC=false;
while($inString = read_file_line($fp2)) { //Add new registrant to appropriate category
	list($lname, $fname) = explode(',', $inString);
	if ($fname == ""){//this is a heading in the file
		$currArInd=trim($lname);
		if ($arInd == $currArInd){//This is the category the person has registered for
			list($regFName, $regLName) = explode(" ", $name);//Split name into components
			//Add new registrant's name to array
			$regByType[$currArInd] = $regLName.",".$regFName;
			$addedToRC=true;
		}
	}
	else {//This is a name in the file - just add them to the array for writing back to the file
		if ($currArInd){
			if (!empty($regByType[$currArInd])){
				$regByType[$currArInd].=";";
			}
			$regByType[$currArInd] .= trim($lname).",".trim($fname);
		}
	}
}
if (!$addedToRC){ //The file is blank or the registered category isn't in the file
	list($regFName, $regLName) = explode(" ", $name);
	$regByType[$arInd]=trim($regLName).",".trim($regFName);//add new category as key and name to array
}
fclose($fp2);

if(($fp2 = fopen($filename2, "w")) == false) {
		die("Can't open data file '$filename2'.\n");
}
$regCat = array_keys($regByType);
foreach ($regCat as $cat){
	fwrite($fp2, $cat);
	fwrite($fp2, "\n");
	if ($regByType[$cat] != ""){
		$names = explode (";",$regByType[$cat]);
		foreach($names as $regName){
			fwrite($fp2, $regName);
			fwrite($fp2, "\n");
		}
	}
}
fclose($fp2);

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

$data = urlencode($data);

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
$ofcRecpt="sconner@albanydiocese.org,jstellman@albanydiocese.org";
	curl_setopt($chdl, CURLOPT_POSTFIELDS, "name=".$name
	."&subject=2015 Priest Retreat Registration&email=registration@albanydiocese.org&whoto=".$ofcRecpt."&message="
	.$ofcPref.$ofcMailData.$amtStrOfc."\n\n");
 
//perform the request
$result = curl_exec($chdl);
//show information regarding the request - for debugging only
//echo "<p>result of office e-mail: ". $result . "</p>";
//print_r(curl_getinfo($chdl));
//echo "<p>".curl_errno($chdl) . '-' .
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
	."&subject=2015 Priest Retreat Registration Confirmation/Receipt&email=webmaster@albanydiocese.org&whoto=".$whoto
	."&message=".$regPref.$regMailData."\n\n".$ofMsg);
 
	//perform the request
$result = curl_exec($chdl2);

//show information regarding the request - for debugging only
//echo "<p>result of registrant's e-mail: ". $result . "</p>";
//print_r(curl_getinfo($chdl2));
//echo "<p>".curl_errno($chdl2) . '-' .
//         curl_error($chdl2)."</p>";
 
	//close the connection
curl_close($chdl2); 

$urlFile="https://www.albanyepiscopaldiocese.org/pmtproc/approvedresp.php";
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
curl_setopt($chdl3, CURLOPT_POSTFIELDS, "event=".$event);
 
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