/**
 * @author Jill Stellman
 * 
 * This file is for functions that need to be used to enable forms to respond properly to various choices.
 * Assumes modernizr-custom.js and functions.js have been loaded first.
 * 
 */

var eventDays = {
	startDateFull:'',
	numDays:0,
	strlgth:0,
	prefix:'',
	incAll:false,
	allDaysTxt:''
};

var scholObj = {
	scholId:'',
	scholAnchElemId:'',
	scholAmtId:'',
	scholAmtLblId:'',
	scholAmtLblTxt:'',
	scholCodeTxt:'',
	alertTxt:'',
	scCode:''
};

var totalObj = {
	totAnchElemId:'',
	totElemId:'',
	totElemLblId:'',
	amtElemId:'',
	alertTxt:''
};


function cleanUpPage(addedCollName,cmtrLblId,cmtrLblTxt,tagNamesToDelArr){//coll of elements to delete, id for label for commuter radio button, text for label
	//get label for commuter radio button
    var commLbl = document.getElementById(cmtrLblId);
    if (commLbl){ //if there is one
    	if (commLbl.firstChild.nodeValue != null){ //replace with the passed in label text
            while (commLbl.firstChild){
                commLbl.removeChild(commLbl.firstChild);
            }
            commLbl.appendChild(document.createTextNode(cmtrLblTxt));
        }
    }
    //get elements whose name is addedCollName
    var addedColl = document.getElementsByName(addedCollName);
    var itemsToDel = [];
    for (var i=0; i<addedColl.length; ++i){ //loop through collection of elements and add to array for removal
        if (addedColl[i]){
        	itemsToDel.push(addedColl[i]); //removing items here removes them from the collection and messes up the loop
        }
    }
    var ckColl=[];
    for (var i=0; i<tagNamesToDelArr.length;++i){
    	//convert collection to Array            get collection with elements to delete
    	var tnColl = Array.prototype.slice.call(document.getElementsByTagName(tagNamesToDelArr[i]), 0);
    	ckColl = ckColl.concat(tnColl); //add to array to use to find deletable elements
    }
    //find what needs to be deleted
    for (var i=0; i < ckColl.length; ++i){
    	if(ckColl[i] && ckColl[i].id){
        	if (ckColl[i].id.substring(0,addedCollName.length)==addedCollName){
        		itemsToDel.push(ckColl[i]);
        	}
    	}
    }
    //delete what needs to be deleted
    for (var i=0; i<itemsToDel.length; ++i){
        itemsToDel[i].parentNode.removeChild(itemsToDel[i]);
    }
}

function addRoomieReq(anchElemId,addedCollName,cmtrLblId,cmtrLblTxt,rmtInpId,rmtLblTxt1,rmtLblTxt2,tagNamesToDelArr){
	cleanUpPage(addedCollName,cmtrLblId, cmtrLblTxt,tagNamesToDelArr); //remove previously added elements
	var lbl1 = create_label(addedCollName+'Lbl1',rmtLblTxt1,'');
	lbl1.style.color="#330066";
    if (rmtLblTxt2){ //if there should be two 'labels' for the roomate text input element
    	var lbl2 = create_label(addedCollName+'Lbl2',rmtLblTxt2,rmtInpId);
        lbl2.style.color="#330066";
        var br2 = document.createElement('br');
        br2.id=addedCollName+'br'+lbl2.id;
    }
    //create the text input element named so it appears in the collection of added elements
    var rmtxt = create_input_elem_no_replace('text', addedCollName, rmtInpId, '', '', 50, 75);
    var anchElem = document.getElementById(anchElemId); //the element to add the par elem after
    anchElem.parentNode.insertBefore(rmtxt, anchElem.nextSibling); // 'insertAfter'
    var txt = document.getElementById(rmtInpId);
    txt.parentNode.insertBefore(lbl1,txt);
    var br1 = document.createElement('br');
    br1.id=addedCollName+'br'+lbl1.id;
    lbl1.parentNode.insertBefore(br1,lbl1);
    if (lbl2){
    	txt.parentNode.insertBefore(br2,txt);
    	txt.parentNode.insertBefore(lbl2,txt);
    }
}

function getDayOfWeek(dayNum){
	switch (dayNum){
		case 0:
			return 'Sunday';
		case 1:
			return 'Monday';
		case 2:
			return 'Tuesday';
		case 3:
			return 'Wednesday';
		case 4:
			return 'Thursday';
		case 5:
			return 'Friday';
		case 6:
			return 'Saturday';
		default:
			return '';
	}
}

function getTotal(radioCollName,commRadName){
	var radioColl=document.getElementsByName(radioCollName);
	var radVal = get_radio_val(radioColl);
	var ttlAmt=0;
	if (radVal==commRadName){
		var ckBoxIds = createCmtrCkBoxIds();
		var firstCkEl = document.getElementById(ckBoxIds[0]);
		if (eventDays.incAll && firstCkEl && firstCkEl.checked){
			ttlAmt=firstCkEl.value;
		}
		else {
			for (var i=0; i<ckBoxIds.length; ++i){
				var ckBox = document.getElementById(ckBoxIds[i]);
				if (ckBox && ckBox.checked){
					ttlAmt += parseFloat(ckBox.value);
				}
			}
		}
		
	} else {
		ttlAmt = document.getElementById(radVal).value;
	}
	return ttlAmt;
}

function createCmtrCkBoxLblTxt(){//create collection of names for the individual day checkboxes
	//with an optional 'all days' checkbox
	var startDate = new Date(eventDays.startDateFull);
	var cbNames=[];
	if (eventDays.incAll){// if there should be an 'all days' checkbox
		cbNames.push (eventDays.allDaysTxt);
	}
	for (var i=0; i < eventDays.numDays; ++i){
		var bxname = getDayOfWeek(startDate.getDay()+i);
		cbNames.push(bxname);
	}
	return cbNames;
}

function createCmtrCkBoxIds(){//create collection of ids for the individual day checkboxes
	//with an optional 'all days' checkbox
	var startDate = new Date(eventDays.startDateFull);
	var cbIds=[];
	if (eventDays.incAll){// if there should be an 'all days' checkbox
		cbIds.push (eventDays.prefix+eventDays.allDaysTxt);
	}
	for (var i=0; i < eventDays.numDays; ++i){
		//strlgth allows the day name to be truncated if desired, e.g. Monday becomes Mon if strlgth is 3
		var bxid = eventDays.prefix+getDayOfWeek(startDate.getDay()+i).substring(0,eventDays.strlgth);
		cbIds.push(bxid);
	}
	return cbIds;
}

function disableInd(){//disable is a boolean about whether to disable or enable the checkboxes - true disables
    var boxes = createCmtrCkBoxIds();
    if (eventDays.incAll){
    	var ck = document.getElementById(boxes[0]);
    	boxes[0]='';
    }
    if (ck && ck.checked){
        toggle_check_box_with_disable(boxes,true,true); //disable all the checkboxes
    }
    else{
        toggle_check_box_with_disable(boxes,false,false); //enable all the checkboxes
    }
}

function dispDays(costCollName,addedCollName,anchElemId,cmtrLblId,cmtrTxt,tagNamesToDelArr){
	//display the individual day checkboxes, along with the cost for each option (all days along with 
	//each indiviual day
   cleanUpPage(addedCollName, cmtrLblId, cmtrTxt,tagNamesToDelArr); //remove previously added elements when choice changes
   var dayCosts = document.getElementsByName(costCollName); //get the costs for each chkbx from the collection
   //of hidden elements created on the server and added to the page at page build time
   var ckBoxNames = createCmtrCkBoxLblTxt();
   var ckBoxIds = createCmtrCkBoxIds();
   var anchElId = anchElemId;
   var numEvDays = eventDays.numDays;
   if (eventDays.incAll){
	   numEvDays++;
   }
   for (var i=0; i < numEvDays; ++i){ //for each day of the event
	   var onClk='';
	   var lbl='';
	   var addBreak=false;
	   var val=0;
	   if (i === 0 && eventDays.incAll){ //if there should be an 'all days' chkbox
		   if (dayCosts[i] && ckBoxNames[i]){
			   val=dayCosts[i].value;
			   var lblTxt=ckBoxNames[i]+' - $'+dayCosts[i].value; //create the label for the chkbx
			   addBreak=true;
			   lbl = create_label(addedCollName+i,lblTxt,ckBoxIds[i]);
			   lbl.style.color='#330066';
			   onClk=function(){disableInd(true);};
			   
		   }
	   }
	   else{//for all other days
		   if (dayCosts[i] && ckBoxNames[i]){
			   val=dayCosts[i].value;
			   var lblTxt = ckBoxNames[i] + ' - $' +dayCosts[i].value; //create the label for the chkbx
			   addBreak=false;
			   var lblId = addedCollName+i;
			   lbl = create_label(addedCollName+i,lblTxt,ckBoxIds[i]);
			   lbl.style.color='#330066';
			   onClk=function(){chkAllBoxAllCkd();};
		   }
	   }
	   create_input_elem_and_add('checkbox',addedCollName,ckBoxIds[i],val,'','','','click',onClk,anchElId);
	   var elem = document.getElementById(ckBoxIds[i]);
	   elem.parentNode.insertBefore(lbl,elem.nextSibling); //add label to element
	   if (addBreak){
		   var elemLbl = document.getElementById(addedCollName+i);
		   add_break(elemLbl,addedCollName+'br'+ckBoxIds[i]);
	   }
	   anchElId=addedCollName+i; //make the checkbox label just added the next anchor element
   }
   add_break(cmtrLblId,addedCollName+'br'+cmtrLblId);   
}

function chkAllBoxAllCkd(){ //check to see if all the individual chkboxes are chkd and if so,
	//check the 'all days' check box (if it's there)
	if (!eventDays.incAll){//if there's not supposed to be an 'all days' checkbox, nothing to do so return
		return;
	}
	var boxes =createCmtrCkBoxIds(); //get the checkbox ids
    var lgth = boxes.length;
    var allchecked=true;
    for (var i=1; i<lgth; ++i){ //check to see if all individual day ckboxes are checked,
    	//skipping the 'all days' one (which is at index 0 in the boxes array)
        allchecked &= document.getElementById(boxes[i]).checked;
    }
    if (allchecked){ //if all the individual boxes are checked, check the 'all days' box and disable the 
    	//individual boxes (unchecking the 'all days' chkbox will re-enable the indiviual ones)
        document.getElementById(boxes[0]).checked=true;
        boxes[0]=''; //prevent all days box from being disabled
        toggle_check_box_with_disable(boxes,true,true);
    }
}

function figureTotal(radioCollName,commRadName,displayText){ //get the total cost for the event
	var radioColl = document.getElementsByName(radioCollName);
	if (!get_radio_val(radioColl)){ //make sure a radio button has been selected
        alert(totalObj.alertTxt); //alert the user that they need to choose a radio button
        radioColl[0].focus(); //put the focus on the first radio button
        return false;		
	}
	//figure out what the total amount owed is - this assumes a set of hidden elements, which have
	//ids matching the values of the radio buttons (one element per radio button). The values of
	//the hidden elements are the costs for the various event options. This could be done through
	//an AJAX call to the server, but we're trying to limit server calls because many using this
	//will have slow connections.
	var ttlAmt=getTotal(radioCollName,commRadName);
	var ttl = parseFloat(ttlAmt);
	var schEl = document.getElementById(scholObj.scholAmtId);
	if (schEl){ //if there's a scholarship available for this event
		var schol = parseFloat(schEl.value); 
        if (schol){ //and there's an amount in the scholarship box
            ttl -= schol; //subtract the scholarship amount from the total amount owed
        }
	}
    if (displayText) { //if the text needs to be displayed
    	var totEl = document.getElementById(totalObj.totElemId);
    	if (totEl){ //if there's already a total element displaying, remove it
    		remove_elements([totalObj.totElemId,totalObj.totElemLblId])
    	}
    	//create a new total element and add it after the 'anchor' element passed in, with an appropriate label
    	var newTotEl = create_element('p','',totalObj.totElemId,totalObj.totElemId,'');
    	var totAnch = document.getElementById(totalObj.totAnchElemId);
    	if (totAnch){
    		totAnch.parentNode.insertBefore(newTotEl, totAnch.nextSibling);
    	}
    	var totTxt=document.createTextNode("Total: $"+ttl.toFixed(2));
    	var bEl = document.createElement('b');
    	bEl.appendChild(totTxt);
    	document.getElementById(totalObj.totElemId).appendChild(bEl);
    }
    //put the total amount owed into the amount element required by the merchant provider
	var amtEl = document.getElementById(totalObj.amtElemId);
	if (amtEl){
		amtEl.value=ttl.toFixed(2);
	} else {
		create_hidden_element(totalObj.amtElemId,totalObj.amtElemId,ttl.toFixed(2));
	}
   return true;
}

function getParishName(listId,selectVal,parNameElId,addedCollName,parNameLblId,parNameLblTxt,anchElId){
	var list=document.getElementById(listId);
	var selected=list.options[list.selectedIndex].text;
	var anchEl = document.getElementById(anchElId);
	if (selected == selectVal){
		var pEl = document.createElement('p');
		pEl.name=addedCollName;
		var parEl=create_input_elem_no_replace('text',parNameElId,parNameElId,'','','30','50');
		var parLbl=create_label(parNameLblId,parNameLblTxt,parNameElId);
		pEl.appendChild(parLbl);
		pEl.appendChild(parEl);
		anchEl.parentNode.insertBefore(pEl, anchEl.nextSibling);// 'insertAfter'
	} else {
		remove_elements([parNameElId,parNameLblId]);
		var parEl=create_hidden_element(parNameElId,parNameElId,selected);
		anchEl.parentNode.insertBefore(parEl,anchEl.nextSibling);
	}
}

function getScholarship(radioCollName,addedCollName,commRadName){
	var radioColl = document.getElementsByName(radioCollName);
	var scholEl = document.getElementById(scholObj.scholId);
    if (scholEl && !scholEl.checked){
        remove_elements([scholObj.scholAmtId,addedCollName+scholObj.scholAmtLblId]);
    }
    else{
        if(!get_radio_val(radioColl)){
            alert(scholObj.alertTxt);
            if (scholEl){
            	scholEl.checked = false;
            }
            remove_elements([scholObj.scholAmtId,addedCollName+scholObj.scholAmtLblId]);
        }
        else{
            var scholLbl = create_label(addedCollName+scholObj.scholAmtLblId,scholObj.scholAmtLblTxt,scholObj.scholAmtId);
            scholLbl.style.color="#330066"; //eeeewww gross!!! Make it go away!
            var schAnElem = document.getElementById(scholObj.scholAnchElemId);
            schAnElem.parentNode.insertBefore(scholLbl, schAnElem.nextSibling);
            add_break(schAnElem,addedCollName+'br'+scholObj.scholAmtLblId);
            var evtLstrFx = function(){checkSchol(addedCollName,radioCollName,commRadName);};
            create_input_elem_and_add('text',addedCollName,scholObj.scholAmtId,0,'',10,10,'change',evtLstrFx,addedCollName+scholObj.scholAmtLblId);
            add_break(schAnElem,addedCollName+'br'+scholObj.scholAmtId);
        }
    }
}

function checkSchol(addedCollName,radioCollName,commRadName){
    var scholAmt = document.getElementById(scholObj.scholAmtId);
    if (scholAmt){//if there's a scholarship amount text box, pull off a dollar sign the user may have entered
    	var scholValStr = clean_num(scholAmt.value,false);
    	//and assign that value back to the element
        document.getElementById(scholObj.scholAmtId).value = scholValStr;
    }else{
    	var scholValStr=0; //nothing in the scholarship box or no box
    }
    var scholVal = parseFloat(clean_num(scholValStr,false)); //make the scholarship amount a number (not str)
   //get the total amount owed (will be put in the amount element by figureTotal), but don't display it yet
	var feeAmt=parseFloat(getTotal(radioCollName,commRadName));
    if (scholVal > feeAmt){
        alert("The scholarship cannot be more than the fees! Please enter a valid scholarship amount.");
        if (scholAmt){
        	scholAmt.value = 0;
        }
    }
    else {
        if (scholVal > feeAmt*.5){ //scholarships only automatically approved for up to 1/2 the cost
            var scholCode=prompt(scholObj.scholAprvlTxt);  //tell user how to get code for add'l amt
            if (scholCode != scholObj.sctCode){
                alert(scholObj.scholCodeTxt); //tell user they got the code wrong and remove schol elements
                remove_elements([scholObj.scholAmtId,addedCollName+scholObj.scholAmtLblId]); 
                document.getElementById(scholObj.scholId).checked=false; //uncheck scholarship box
            }
        }
        figureTotal(radioCollName,commRadName,true); //get the total after any scholarships and display it
    }
    
}

function checkVals(radioCollName,anchElId,addedCollName,parElId,chrchElId,emailId,noparTxt,
		discAmt1Id,discAmt2Id,combCollId,amtElId,commRadName){
	var pName = document.getElementById(parElId)
    if (!pName){
    	return false;
    }
	if (!pName.value){
		alert(noParTxt);
    	document.getElementById(chrchElId).focus();
    	return false;
	}
    var emailaddr=document.getElementById(emailId).value;
    if (emailaddr.search(/.+@.+/) == -1){
    	alert("You have not entered a valid e-mail address. Please correct your e-mail address.");
    	return false;
    }
    if(!validateForm()){
        return false;
    }
    var anchEl=document.getElementById(anchElId);
    var amt1=0;
    var amt2=0;
    var scholEl = document.getElementById(scholObj.scholAmtId)
    if (scholEl){
    	var scholAmt = scholEl.value;
        var ttlAmt=getTotal(radioCollName,commRadName);
        var fee=parseFloat(ttlAmt);
        if (scholAmt){
            var schol = parseFloat(scholAmt);
            var halfFee = fee*.5;
            if (schol > halfFee){
                amt1 = halfFee;
                amt2 = schol - halfFee;
            }else{
                amt1 = schol;
            }
        }
        var disc1El = create_hidden_element(discAmt1Id,discAmt1Id,amt1);
        anchEl.parentNode.insertBefore(disc1El,anchEl);
        var disc2El = create_hidden_element(discAmt2Id,discAmt2Id,amt2);
        anchEl.parentNode.insertBefore(disc2El,anchEl);	
    }
    var amtEl = document.getElementById(amtElId);
    if (amtEl){
    	var amt = amtEl.value;
        if(amt && amt == 0){//"Click the calculate totals button" and allow for scholarships
            if (figureTotal(radioCollName,commRadName,false)){
            	setCookie();
            } 
            else{
            	return false;
            }
        }
    }
    
    var replArr = []; //create array of elements to replace, since replacing in loop messes up the index
    var hids=document.getElementsByName(addedCollName); //get hidden items that were added in the course of the registration
    for (var i=0; i < hids.length; ++i){
    	var newEl = create_hidden_element(hids[i].id,hids[i].id,hids[i].value);
    	replArr.push(newEl);
    }

    var combColl=document.getElementsByName(combCollId); //get items that need to be combined before being sent
    for (var i=0; i < combColl.length; ++i){
    	var ids = combColl[i].value.split("|");
    	var valStr='';
    	for (var j=0; j < ids.length; ++j){
    		var el = document.getElementById(ids[j]);
    		if (el){
    			if (j!=0){
    				valStr+=" ";
    			}
    			valStr+=el.value;
    		}
    	}
    	newEl = create_hidden_element(combColl[i].id,combColl[i].id,valStr);
    	replArr.push(newEl);
    }
    for (var i=0; i<replArr.length; ++i){
    	var el2Repl = document.getElementById(replArr[i].id);
    	el2Repl.parentNode.replaceChild(replArr[i],el2Repl);
    }    
    setCookie();
    return true;
}

function setCookie(){
    //cookie for cc processing site
    document.cookie = "fam=barn; path=/pmtproc";
}