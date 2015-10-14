/**
 * @author Jill Stellman
 * 
 * This file is for functions specific to the priest retreat online registration form.
 * Assumes modernizr-custom.js, functions.js, and interactivefxs.js have been loaded first.
 * 
 */

function addRoomieReqAndSpecNeeds(anchElemId,addedCollName,rmtInpId,rmtLblTxt,specAnchElemId,physElemId,
		physNdTxt,dietElemId,dietNdTxt,lblColorHash,tagNamesToDelArr){
	addRoomieReq(anchElemId,addedCollName,'','',rmtInpId,rmtLblTxt,'',tagNamesToDelArr);
	var rmEl = document.getElementById(rmtInpId);
	if (rmEl){
		rmEl.tabIndex='11';
	}
	create_element_with_label_and_add('text',addedCollName,physElemId,'','12','75','100',specAnchElemId,
			addedCollName+physElemId+'Lbl',physNdTxt,lblColorHash,true,addedCollName+'br'+physElemId);
	create_element_with_label_and_add('text',addedCollName,dietElemId,'','13','75','100',specAnchElemId,
			addedCollName+dietElemId+'Lbl',dietNdTxt,lblColorHash,true,addedCollName+'br'+dietElemId);
	var hidEl = create_hidden_element('recipient',addedCollName,'hhuth');
	var anchEl = document.getElementById(specAnchElemId);
	if (anchEl){
		anchEl.parentNode.insertBefore(hidEl,anchEl.nextSibling);
	}
}   

function addExcuse(anchElemId,addedCollName,excInpId,exLblTxt,lblColorHash,tagNamesToDelArr){
	cleanUpPage(addedCollName,'', '',tagNamesToDelArr);
	var newElem = create_element('input','textarea',excInpId,addedCollName,'');
	newElem.style.width='400px';
	newElem.style.height='100px';
	newElem.tabIndex='15'
	newElem.required=true;
	var lbl = create_label_with_color(addedCollName+excInpId+'Lbl',exLblTxt,excInpId,lblColorHash);
	var anchEl = document.getElementById(anchElemId);
	if (anchEl){
		anchEl.parentNode.insertBefore(lbl,anchEl.nextSibling);
		anchEl.parentNode.insertBefore(newElem,lbl.nextSibling);
		var hidEl = create_hidden_element('recipient',addedCollName,'dherzog|hhuth');
		anchEl.parentNode.insertBefore(hidEl, newElem.nextSibling);
	}
	add_break(anchElemId,addedCollName+excInpId+'br1');
	add_break(anchElemId,addedCollName+excInpId+'br2');
}
    
function errorAlert(inEl, fldName){
    if (!inEl.value || inEl.value==' '){
        alert("You did not type your "+fldName+".\n\nPlease type your "+fldName+".");
        inEl.focus();
        return false;
    } else {
        return true;
    }
        
}
    
function validateForm(){
	 var exc = document.getElementById('excuse');
	 if (exc && exc.value == ''){
		 errorAlert(exc, "reason for not attending the Deacons' Retreat");
		 return false;
	 }
     return errorAlert(document.getElementById("firstname"), "first name")
      && errorAlert(document.getElementById("lastname"), "last name")
      && errorAlert(document.getElementById("address1"), "address")
      && errorAlert(document.getElementById("city"), "city")
      && errorAlert(document.getElementById("state"), "state")
      && errorAlert(document.getElementById("zip"), "zip code")
      && errorAlert(document.getElementById("email"), "email address")
      && errorAlert(document.getElementById("parishName"), "parish name and city");     
}
    