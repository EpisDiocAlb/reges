<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Deacons Retreat RSVP</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script src="../../../js/modernizr-custom.js"></script>
    <script src="../../../js/functions.js"></script>
     <script src="../../../js/interactivefxs.js"></script>
    <script src="../../../js/drfx.js"></script>
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
<body class="style1" leftmargin="0" marginwidth="0" marginheight="0">
    <table width="779" border="0" align="center" cellpadding="0" cellspacing="0"> <!-- Header -->
        <tr>
            <td><img src="http://www.albanyepiscopaldiocese.org/NewImages/header.gif" width="779" height="175" alt=""></td>
        </tr>
    </table> <!-- End of Header -->
    <table width="779" border="0" align="center" cellpadding="6" cellspacing="0"> 
    <!-- Overall table for the whole page -->
        <tr> <!-- Table only has one row, but three columns - one for side nav, one for content, one for events -->
            <td valign="top" style="border-right: ridge 4px #EDEDED; background-color: #FFFFCC;" width="100">
                <!-- First column is for side nav (links list)-->
                <p>&nbsp;</p>
                <p><a class="style17" href="http://www.albanyepiscopaldiocese.org/">Home<br /></a><br /></p>
            </td>
            <td> <!-- Second column is for content --> 
                <h2 align="center" class="style8">2015 Deacons Retreat<br />October 23-25<br />
                    Christ the King Spiritual Life Center<br />RSVP Form</h2>
                    <h4 class="style8">Please fill out the form below to RSVP for the Deason's Retreat.<br/>
                    <i>Required fields are marked with a *</i>.</h4>
                <form name="reginfo" action="http://www.albanyepiscopaldiocese.org/forms/DeaconsRetreat/2015/sendDeaconRSVP.php" accept-charset="UTF-8" method="post" onsubmit = "return checkVals('RSVP','submit','added','parishName','churches','email','You have not chosen a parish, please choose a parish.', '','','combColl','','');">
                    <input type="hidden" name="event" value="Deacons Retreat 2015"/>
                    <span style="color: #CD0000">*</span><label style="color: #330066">First Name: <input type="text" id="firstname" name="firstname" size="50" maxlength="50" tabindex="1" onblur="check_reqd(\'firstname\',\'First Name\')" required/></label><br /><br />
                    <span style="color: #CD0000">*</span><label style="color: #330066">Last Name: <input type="text" id="lastname" name="lastname" size="50" maxlength="50" tabindex="2" onblur="check_reqd(\'lastname\',\'Last Name\')" required/></label><br /><br />
                    <span style="color: #CD0000">*</span><label style="color: #330066">Street Address: 
                    <input type="text" tabindex="3" id="address1" name="address1" id="address1" value="" size="30" maxlength="30" required onblur="check_reqd('AVSADDR','Street Address')"/></label><br /><br />
                    <label style="color: #330066">Street Address Line 2:
                    <input type="text" tabindex="4" id="address2" name="address2" id="address2" value="" size="30" maxlength="30" /></label><br /><br />
                    <span style="color: #CD0000">*</span><label style="color: #330066">City: 
                    <input type="text" tabindex="5" id="city" name="city" value="" id="city" size="30" maxlength="30" required onblur="check_reqd('city','City')" /></label>
                    <span style="color: #CD0000">*</span><label style="color: #330066">State: 
                    <input type="text" tabindex="6" id="state" name="state" value="" id="state" size="3" maxlength="30" required onblur="check_reqd('state','State')" /></label>
                    <span style="color: #CD0000">*</span><label style="color: #330066">Zip: 
                    <input type="text" tabindex="7" id="zip" name="zip" value="" id="zip" size="9" maxlength="9" required onblur="check_reqd('AVSZIP','Zip Code')" /></label><br /><br />
                    <span style="color: #CD0000">*</span><label style="color: #330066">E-mail Address: <input type="text" id="email" name="email" size="50" maxlength="75" tabindex="8" onblur="check_reqd(\'email\',\'Email\')" required/></label><br /><br />
                    <span style="color: #CD0000">*</span><label style="color: #330066">Parish (choose one): 
                    <?php
                    	   require '../../../pmtproc/filereadingfxcolon.php';
                           $churches = create_array('../../../files/churches.csv');
                           echo '<select tabindex="9" id="churches" onchange="getParishName(\'churches\', \'Other\', \'parishName\', \'added\',\'pNameLbl\', \'Enter the name of your parish: \', \'churches\')" onblur="check_reqd(\'churches\',\'Parish\')" required>';
                           foreach($churches as $key => $value) {
                               echo '<option value="'.$key.'">'.$value.'</option>';
                           }
                                echo '</select>';
                   ?></label><br /><br />
                   <span style="color: #CD0000">*</span><label style="color: #330066">Please choose whether you will attend the Deacons Retreat:</label><br /><br />
                    <input type="radio" name="RSVP" id="y" value="will attend" tabindex="10" onclick="addRoomieReqAndSpecNeeds('yesLbl','added','roommate','Roommate request (please check with potential roomate first!): ','n','physneeds','Please indicate any special physical accommodations you require: ','dietneeds','Please indicate any special dietary needs you have: ','#330066',['br','label','textarea']);"/>
                    <label style="color: #330066" id="yesLbl" for="y">Yes, I will be attending the 2015 Deacon's Retreat.</label><br />
                    <input type="radio" name="RSVP" id="n" value="will not attend" tabindex="14" onclick="addExcuse('noLbl','added','excuse','You must be excused from the retreat by Bishop Dan. Please enter your request for being excused here: ','#330066',['br','label','textarea']);"/>
                    <label style="color: #330066" id="noLbl" for="n">No, I will be unable to attend the 2015 Deacon's Retreat.</label>
                    <br />
                    <?php
                    $filename='combfields.txt';
                    if (!$fp=fopen($filename,'r')){
                    	echo "Cannot open $filename";
                    	exit;
                    }
                    while ($inString = read_file_line($fp)){
                    	list($htmlId,$htmlColl,$listOfFlds)=explode(';',$inString);
                    	$h .= '<input type="hidden" id="'.$htmlId.'" name="'.$htmlColl.'" value="'.$listOfFlds.'" />';
                    	$h.="\n";
                    }
                    echo $h;
                    ?><br />
                    <input tabindex="25" style="color: #330066; font-size: 110%" type="submit" name="Submit" value="Submit RSVP" id="Submit" />   
                </form>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
            </td>
            <td valign="top" style="border-left: ridge 4px #EDEDED; background-color: #FFFFCC;" width="100">
                <!--Third column to keep look and feel -->
            </td>
        </tr>
    </table> <!-- End of main table-->
    <table width="779" border="0" align="center" cellpadding="0" cellspacing="0"> <!-- Footer -->
  <tr>
    <td width="324" style="background-color: #333399; background-image: url(http://www.albanyepiscopaldiocese.org/NewImages/t5-1.gif); background-repeat: no-repeat;" class="bottom">&nbsp;</td>
    <td width="455" height="28" align="center" style="color: #FFFFFF; background-color: #333399">Copyright &copy; www.albanydiocese.org All rights are reserved</td>
  </tr>
</table> <!-- End of footer -->
</body>
</html>