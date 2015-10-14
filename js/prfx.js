/**
 * @author Jill Stellman
 * 
 * This file is for functions specific to the priest retreat online registration form.
 * Assumes modernizr-custom.js, functions.js, and interactivefxs.js have been loaded first.
 * 
 */

function setRegCode(regCdId,anchElId){
	var newEl = create_hidden_element(regCdId,regCdId,get_code());
	var anchEl = document.getElementById(anchElId);
	if (anchEl){
		anchEl.parentNode.insertBefore(newEl, anchEl);
	}
}

function setEventDays(startDateFull,numDays,strlgth,prefix,incAll,allDaysTxt){
	//default values: startDateFull:'', numDays:0, strlgth;0, prefix:'', incAll:false, allDaysTxt:''
	eventDays.startDateFull=startDateFull;
	eventDays.numDays=numDays;
	eventDays.strlgth=strlgth;
	eventDays.prefix=prefix;
	eventDays.incAll=incAll;
	eventDays.allDaysTxt=allDaysTxt;
}


function setScholObj(schId,schAnchEl,schAmtId,schAmtLblId,schAmtLblTxt,aprvlTxt,schCdTxt,alrtTxt){
	//default values: scholId:''; scholAnchElem:''; scholAmtId:''; scholAmtLblId:''; scholAmtLblTxt:''; alertTxt:'';
	scholObj.scholId=schId;
	scholObj.scholAnchElemId=schAnchEl;
	scholObj.scholAmtId=schAmtId;
	scholObj.scholAmtLblId=schAmtLblId;
	scholObj.scholAmtLblTxt=schAmtLblTxt;
	scholObj.scholCodeTxt=schCdTxt;
	scholObj.scholAprvlTxt=aprvlTxt;
	scholObj.alertTxt=alrtTxt;
	scholObj.sctCode='PR2015HSKOK'
}

function setTotObj(totAnEl,totElId,totElLblId,amtElId,alrtTxt){
	totalObj.totAnchElemId=totAnEl;
	totalObj.totElemId=totElId;
	totalObj.totElemLblId=totElLblId
	totalObj.amtElemId=amtElId;
	totalObj.alertTxt=alrtTxt;
}
    
function dispGenderChoice(addedCollName,cmtrLblId,cmtrLblTxt,dispGdrsArr,fullGdrsArr,gdrReqdMsg,gdrLblId,gdrBtnGrpName,anchElId,tagNamesToDelArr){
    cleanUpPage(addedCollName,cmtrLblId,cmtrLblTxt,tagNamesToDelArr);
    var gdrMsgLbl = create_label(addedCollName+'gdrMsgLbl',gdrReqdMsg,'');
    gdrMsgLbl.style.fontStyle='italic'; //ick make this go away!
    var anchEl = document.getElementById(anchElId);
    anchEl.parentNode.insertBefore(gdrMsgLbl,anchEl);
    add_break(gdrMsgLbl.id,addedCollName+'brgdrReqdMsg');
    var lastBrkElId='';
    for (var i=0; i<dispGdrsArr.length; ++i){
    	var full=false;
    	var radBtn = create_radio_btn(addedCollName+gdrBtnGrpName+dispGdrsArr[i],gdrBtnGrpName,dispGdrsArr[i]);
    	for (var j=0; j<fullGdrsArr.length; ++j){
    		if (dispGdrsArr[i] == fullGdrsArr[j]){
    			full=true;
    		}
    	}
    	radBtn.disabled=full;
    	anchEl.parentNode.insertBefore(radBtn,anchEl);
    	var btnLbl = create_label(addedCollName+gdrBtnGrpName+'Lbl'+dispGdrsArr[i],dispGdrsArr[i]+' ',addedCollName+gdrBtnGrpName+dispGdrsArr[i]);
    	btnLbl.style.color='#330066' //double ick make this go away
    	anchEl.parentNode.insertBefore(btnLbl,anchEl);
    	lastBrkElId=btnLbl.id;
    	if (full){
    		var fullLbl = create_label(addedCollName+gdrBtnGrpName+dispGdrsArr[i]+'Full','Full ','');
    		fullLbl.style.color="#CD0000" //full should always be red, right? or not...
    	    anchEl.parentNode.insertBefore(fullLbl,anchEl);
    		lastBrkElId=fullLbl.id;
    	}
    }
    add_break(lastBrkElId,addedCollName+'br'+gdrBtnGrpName);
}
    
function getExpiry(){
    //set to expire in 10 minutes
    var today= new Date();
    var expire = new Date();
    expire.setTime(today.getTime() + 600000);
    return expire.toGMTString();
}
    

    

function getDate(){
    //get today's date, formatted as mm/dd/yy
    var today = new Date();
    var day = today.getDate();
    var month = today.getMonth()+1;
    var fullyear = today.getYear().toString();
    var yl = fullyear.length;
    var year = fullyear.substr(yl-2, 2);
    if (day < 10){
        day = "0"+day;
    }
    if (month < 10){
        month="0"+month;
    }
    return month+"/"+day+"/"+year;
}


    
function errorAlert(inEl, fldName){
    if (!inEl.value || inEl.value==' '){
        alert("You did not type your "+fldName+"\n\nPlease type your "+fldName+".");
        inEl.focus();
        return false;
    } else {
        return true;
    }
        
}
    
function validateForm(){
     return errorAlert(document.getElementById("fname"), "first name")
      && errorAlert(document.getElementById("lname"), "last name")
      && errorAlert(document.getElementById("AVSADDR"), "address")
      && errorAlert(document.getElementById("city"), "city")
      && errorAlert(document.getElementById("state"), "state")
      && errorAlert(document.getElementById("AVSZIP"), "zip code")
      && errorAlert(document.getElementById("badgefirst"), "First name for your name tag")
      && errorAlert(document.getElementById("badgelast"), "Last name for your name tag")
      && errorAlert(document.getElementById("email"), "email address")
      && errorAlert(document.getElementById("parishName"), "parish name and city");     
}
    