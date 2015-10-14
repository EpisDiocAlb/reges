<?php
function read_file_line($fp) {
	while(($inString = fgets($fp)) != false) {
		if(!empty($inString)){
			break;
		}
	}
	return $inString;
}

function append_to_file($filename, $data){
	if (is_writable($filename)) {

	    if (!$handle = fopen($filename, 'a')) {
	         echo "Cannot open file ($filename)";
	         exit;
	    }
	    // Write $data to our opened file.
	    if (fwrite($handle, $data) === FALSE) {
	        echo "Cannot write to file ($filename)";
	        exit;
	    }
	    fclose($handle);

	} else {
    	echo "The file $filename is not writable";
	}
}
//writes each element of the array as one line in the file
function overwrite_file($filename, $dataArr){
	if (is_writable($filename)) {
	    if (!$handle = fopen($filename, 'w')) {
	         echo "Cannot open file ($filename)";
	         exit;
	    }
	    $data="";
	    $arrSize=count($dataArr);
	    for($i=0; $i < $arrSize; $i++){
	    	$data.=trim($dataArr[$i])."\n";
	    }
	    if (fwrite($handle, $data) === FALSE) {
	        echo "Cannot write to file ($filename)";
	        exit;
	    }
	    fclose($handle);
	} else {
    	echo "The file $filename is not writable";
	}
}

function overwrite_file_as_string($filename, $string){
	if (is_writable($filename)) {
	    if (!$handle = fopen($filename, 'w')) {
	         echo "Cannot open file ($filename)";
	         exit;
	    }
	    // Write $string to the file, which will overwrite whatever was there before
	    if (fwrite($handle, $string) === FALSE) {
	        echo "Cannot write to file ($filename)";
	        exit;
	    }
	    fclose($handle);

	} else {
    	echo "The file $filename is not writable";
	}	
}

function create_and_write_file($filename, $string){
	if (!$handle = fopen($filename, 'w')) {
	         echo "Cannot create file ($filename)";
	         exit;
	}
	// Write $string to the file, which will overwrite whatever was there before
    if (fwrite($handle, $string) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }
    fclose($handle);
}

function create_data_string($srcArr, $excludeArr, $incKeys){
	$newData="";
	$srcKeys=array_keys($srcArr);
	foreach ($srcKeys as $key){
		if (!array_key_exists($key, $excludeArr)){
			if($newData != ""){
				$newData.="|";
			}
			if ($incKeys){
				$newData.=$key."|".$srcArr[$key];
			}else{
				$newData.=$srcArr[$key];
			}
			
		}
	}
	return $newData;
}

function sendemail($name,$subject,$fromemail,$whoto,$message){
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
	curl_setopt($chdl, CURLOPT_POSTFIELDS, "name=".$name."&subject=".$subject
		."&email=".$fromemail."&whoto=".$whoto."&message=".$message);
	 
	//perform the request
	$result = curl_exec($chdl);
	//show information regarding the request - for debugging only
	//echo "<p style='color: #CD0000'>result of $subject e-mail: <span style='color: #CD0000'>". $result . "</span></p>";
	//print_r(curl_getinfo($chdl));
	//echo "<p style='color: #CD0000'>".curl_errno($chdl) . '-' .
	//	curl_error($chdl)."</p>";
	
	//close the connection
	curl_close($chdl); 
}

function send_email_attach($filearr,$fromstr,$fromname,$emailsubj,$emailmsg,$whotoarr){
	require '../php/PHPMailer-master/PHPMailerAutoload.php';
	$email = new PHPMailer(true);
	try{
		$email->From      = $fromstr;
	    $email->FromName  = $fromname;
	    $email->Subject   = $emailsubj;
	    $email->Body      = $emailmsg;
	    foreach ($whotoarr as $towho){
	    	$email->AddAddress($towho);
	    }
	    foreach($filearr as $filename){
	    	$email->AddAttachment($filename);
	    }
	    $email->Send();
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}
}

?>
