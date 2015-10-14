<?php
require 'config.php';
$scriptName = "ContactForm";
$version = "1.2.0";

// print("<p style='color: #330066'>post array: ");
// print_r($_POST);
// print("</p>");

$errors = array();

function show_error_list($errors) {
    $nerrors = count($errors);

    print("<ul>\n");
    for($i = 0; $i < $nerrors; $i++)
	print("<li>" . $errors[$i] . "</li>\n");
    print("</ul>\n");
}

function show_errors($errors) {
    print("<html>\n<head><title>Error</title></head>\n
	   <body>\n<p>There were problems with your submission. Please go
	   back to the previous page and correct the following errors:
	   </p>\n");
    show_error_list($errors);
    print("</body>\n</html>");
}


function show_fatal($errors) {
    print("<html>\n<head><title>Error</title></head>\n
	   <body>\n<p>There were problems with your submission.</p>\n");
    show_error_list($errors);
    print("This may be through no fault of your own and is probably not
	   immediately correctable.  Please come back and try again later.
	   </p>\n</body>\n</html>\n");
}

function read_file_line($fp) {
    while(($inString = fgets($fp, 2048)) != false) {
	$inString = rtrim(preg_replace('/\s*#.*/', '', $inString));
	if(!empty($inString))
	    break;
    }

    return $inString;
}

function check_referer($referers, $logOnReferer) {

    global $errors, $scriptName;

    $found = true;

    if(count($referers)) {
	$found = false;

	if(!empty($_SERVER['HTTP_REFERER'])) {
	    list($referer) =
		array_slice(explode("/", $_SERVER['HTTP_REFERER']), 2, 1);
	    for($x = 0; $x < count($referers); ++$x) {
		if(eregi($referers[$x], $referer)) {
		    $found = true;
		    break;
		}
	    }
	}

	if(!$found) {
	    $errors[] = "This form was used from an unauthorized server! (" .
			$_SERVER['HTTP_REFERER'] . ")";
	    if($logOnReferer) {
		error_log("[$scriptName] Illegal Referer. (" .
			   $_SERVER['HTTP_REFERER'] . ") ", 0);
	    }
	}
    }

    return $found;
}

function check_ip($chkAgainst, $chkAddr) {

    $addrMatch = false;

    if(ereg('/[0-9]', $chkAgainst)) {

	list($addrBase, $hostBits) = explode('/', $chkAgainst);
	list($w, $x, $y, $z) = explode('.', $addrBase);

	$chkAgainst = ($w << 24) + ($x << 16) + ($y << 8) + $z;
	$mask = $hostBits == 0? 0 : (~0 << (32 - $hostBits));
	$lowLimit = $chkAgainst & $mask;
	$highLimit = $chkAgainst | (~$mask & 0xffffffff);

	list($w, $x, $y, $z) = explode('.', $chkAddr);
	$chkAddr = ($w << 24) + ($x << 16) + ($y << 8) + $z;

	$addrMatch = (($chkAddr >= $lowLimit) && ($chkAddr <= $highLimit));

    } else {
	$addrMatch = ereg("^$chkAgainst", $chkAddr);
    }

    return $addrMatch;
}

function check_banlist($logOnBan, $email) {

    global $errors, $scriptName, $banListFile;

    $notAllowed = false;

    $banList = array();

    if($fp = @fopen($banListFile, "r")) {
	while($inString = read_file_line($fp))
	    $banList[] = $inString;
	fclose($fp);
    }

    if(count($banList)) {
	$emailFix = trim(strtolower($email));
	$remoteHostFix = trim(strtolower($_SERVER['REMOTE_HOST']));

	foreach($banList as $banned) {
	    $banFix = trim(strtolower(ereg_replace('\.', '\\.', $banned)));
	    if(strstr($banFix, "@")) {
		if(ereg('^@', $banFix)) {
		    $banFix = ereg_replace('^@', '[@\\.]', $banFix);
		    if(($notAllowed = ereg("$banFix$", $emailFix))) {
			$bannedOn = $emailFix;
			break;
		    }
		} elseif(ereg('@$', $banFix)) {
		    if(($notAllowed = ereg("^$banFix", $emailFix))) {
			$bannedOn = $emailFix;
			break;
		    }
		} else {	
		     if(($notAllowed = (strtolower($banned) == $emailFix))) {
			$bannedOn = $emailFix;
			break;
		    }
		}
	    } elseif(preg_match('/^\d{1,3}(\\\.\d{1,3}){0,3}(\/\d{1,2})?$/',
	                        $banFix)) {
		if($notAllowed = check_ip($banFix, $_SERVER['REMOTE_ADDR'])) {
		    $bannedOn = $_SERVER['REMOTE_ADDR'];
		    break;
		}

		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    if($notAllowed =
			    check_ip($banFix, $_SERVER['HTTP_X_FORWARDED_FOR']))
		    {
			$bannedOn = $_SERVER['HTTP_X_FORWARDED_FOR'];
			break;
		    }
		}

	    } else {
		if(($notAllowed = ereg("$banFix$", $remoteHostFix))) {
		    $bannedOn = $remoteHostFix;
		    break;
		}
	    }
	}
    }

    if($notAllowed) {
	$errors[] = "Attempt from a banned email address, host or domain! ($bannedOn)";
	if($logOnBan) {
	    error_log("[$scriptName] Banned on \"$bannedOn\"", 0);
	}
    }

    return $notAllowed;
}

function generate_additional_headers() {

    global $scriptName, $version, $reportRemoteHost, $reportRemoteAddr,
	   $reportRemoteUser, $reportRemoteIdent, $reportOrigReferer;

    $addlHeaders = "X-Mailer: $scriptName v${version}\r\n";

    if($reportRemoteHost && !empty($_SERVER['REMOTE_HOST']))
	$addlHeaders .= "X-Remote-Host: " . $_SERVER['REMOTE_HOST'] . "\r\n";
    if($reportRemoteAddr) {
	if(!empty($_SERVER['REMOTE_ADDR']))
	    $addlHeaders .= "X-Remote-Addr: " . $_SERVER['REMOTE_ADDR'] . "\r\n";

	if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	    $addlHeaders .= "X-Http-X-Forwarded-For: " .
			     $_SERVER['HTTP_X_FORWARDED_FOR'] . "\r\n";
    }
    if($reportRemoteUser && !empty($_SERVER['REMOTE_USER']))
	$addlHeaders .= "X-Remote-User: " . $_SERVER['REMOTE_USER'] . "\r\n";
    if($reportRemoteIdent && !empty($_SERVER['REMOTE_IDENT']))
	$addlHeaders .= "X-Remote-Ident: " . $_SERVER['REMOTE_IDENT'] . "\r\n";
    if($reportOrigReferer && !empty($_POST['orig_referer']))
	$addlHeaders .= "X-Form-Referer: " . $_POST['orig_referer'] . "\r\n";

    return $addlHeaders;
}

function mail_advisory($errors) {

    global $errorsTo, $addSubjSig;

    if(!empty($errorsTo)) {
	if($addSubjSig)
	    $finalSubject = "[$dfltSubj] ";
	$finalSubject .= "Problem with form processing";

	$content = "The following problem(s) occurred with contact form processing:\n\n";
	$nerrors = count($errors);

	for($i = 0; $i < $nerrors; $i++)
	    $content .= "    . " . $errors[$i] . "\n";

	mail($errorsTo, $finalSubject, $content, generate_additional_headers());
    }
}

if(!check_referer($allowedReferers, $logOnReferer)) {
    show_fatal($errors);
    if($adviseOnReferer == true)
	mail_advisory($errors);
    exit;
}

$whotos = array();

if(($fp = fopen($recipientFile, "r")) == false) {
     die("Can't open data file '$recipientFile'.\n");
}
while($inString = read_file_line($fp)) {
    list($key, $description, $value) = explode(':', $inString);
    $whotos[trim($key)] = trim($value);
}
fclose($fp);

$recipient = $_POST['whoto'];

if(empty($_POST['email']))
     $errors[] = "You didn't enter your email address.";
elseif(!preg_match('/^[^\s@]+@[a-z0-9\.-]+?\.[a-z]{2,4}$/i', $_POST['email']))
    $errors[] = "\"" . $_POST['email'] .
		"\" doesn't look like a valid email address";
		
//if($_POST['test']=="yes") {
//	$errors[] = "You can't send spam through this form.";
//	error_log("spam whoo", 0);
//}

if($banned = check_banlist($logOnBan, $_POST['email'])) {
    if($adviseOnBan)
	mail_advisory($errors);
    if($warnBanned) {
	show_fatal($errors);
	exit;
    } else {
	unset($errors);	
    }
}

if(empty($_POST['name']))
     $errors[] = "You didn't enter your name.";

if(empty($_POST['subject']))
     $errors[] = "You didn't enter a subject.";

if(empty($_POST['message']))
     $errors[] = "You didn't enter a message.";

if(count($errors)) {
    show_errors($errors);
    exit;
}

if(!empty($_POST['name']))
$content .= preg_replace('/\r/', '', stripslashes($_POST['message']));

$addlHeaders = "From: " . $_POST['email'] . "\r\n" .
               "Reply-To: " . $_POST['email'] . "\r\n";

//$addlHeaders .= generate_additional_headers();

if($_POST['bcc']){
	$addlHeaders .= "Bcc: ". $_POST['bcc']. "\r\n";
}


if(empty($_POST['subject']))
    $finalSubject = $dfltSubj;
else {
    if($addSubjSig)
	$finalSubject = "[$dfltSubj] ";
    $finalSubject .= stripslashes($_POST['subject']);
}

if(!$banned) {
    if(!mail($recipient, $finalSubject, $content, $addlHeaders)) {
	$errors[] = "Unable to process form at this time";
	show_fatal($errors);
	exit;
    }
    $urlFile=$_POST['url'];
    //$urlFile="http://www.albanyepiscopaldiocese.org/pmtproc/approvedresp.php";
	//send user to approved response page
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
	curl_setopt($chdl3, CURLOPT_POSTFIELDS, "event=".$_POST['event']);
	 
	//perform the request
	$result = curl_exec($chdl3);
	
	//show information regarding the request - for debugging only
	//echo "<p style='color: #330066'>result of approvedresp: ". $result . "</p>";
	//print_r(curl_getinfo($chdl3));
	//echo "<p style='color: #330066'>".curl_errno($chdl3) . '-' .
	//           curl_error($chdl3)."</p>";
	 
		//close the connection
	curl_close($chdl3);
}
?>
