/**
 * @author Jill Stellman, except for loadmasks()
 * 
 * This file is for helper functions of various kinds, many 
 * (but not all) to do with interacting with the DOM.
 * Assumes modernizr-custom.js has been loaded first.
 * 
 */


function loadmasks(){
	if (document.getElementById('numRegs')){
		var regs = document.getElementById('numRegs').value;
	    if (regs >0){
	        alert("We're sorry, but your page has been reloaded and your prior registrations lost.\nPlease enter the registrations again.\nIf you continue to see this message, please enter your registrations individually, and call Mtr. Jill Stellman at the Diocesan Offices, (518) 692-3350 x520.");
	        clearForm();
	        document.getElementById('Amount').value=0;
	        removeElementsFromForm(['regCode','name','staddr','csz','badgename','hphone','cphone','email', 
	            'confEmail','inclTotCost','inclIndCost','sendrecpt','parishName','regCat','regType','roommate',
	            'cot','crib','phys_needs','parentPresent','friLunch','vol','indCost'],regs);
	    }
	}

	MaskedInput({
	  elm: document.getElementById('hphone'),
	  format: '(###) ###-####',
	  separator: '( -)',
	  typeon: '###'
	});
	
	MaskedInput({
	  elm: document.getElementById('cphone'),
	  format: '(###) ###-####',
	  separator: '( -)',
	  typeon: '###'
	});    
}

function check_reqd(elemId,alertText){
	if(!Modernizer.input.required){
		if (document.getElementById(elemId).value ===''){
				alert(alertText+'is a required field! Please enter a value for '+alertText);
		}
	}
}

function clean_num(num,remdec){
    //Remove $ from dollar values if the user enters them that way.
    var ind = num.indexOf("$");
    var clean = num;
    if (ind != -1){
        var clean1 = clean.substring(0,ind);
        clean = clean1 + clean.substring(ind+1);
    }
    if (remdec){
		 ind = clean.indexOf(".");
	     if (ind != -1){
	         clean = clean.substring(0,ind);
	     }	
    }
   
    return clean;
}


function get_code(){
    var regIdBase = Math.random();
    var numPart = Math.floor(regIdBase * 10000);
    var ltrBase1 = Math.round((regIdBase * 1000000) - (numPart * 100));
    var ltrBase2 = Math.round((regIdBase * 100000000) - (numPart * 10000));
    ltrBase2 = ltrBase2/100;
    var lb2flr = Math.floor(ltrBase2);
    ltrBase2 -= lb2flr;
    ltrBase2 *= 100;
    ltrBase2 = Math.floor(ltrBase2);
    if (ltrBase1 < 0){
        ltrBase1 *=(-1);
    }
    if (ltrBase2 < 0){
        ltrBase2 *=(-1);
    }
    if (ltrBase1 > 25){
        ltrBase1 = ltrBase1%26;
    }
    if (ltrBase2 > 25){
        ltrBase2 = ltrBase2%26;
    }
    var ltr1 = String.fromCharCode(ltrBase1+65);
    var ltr2 = String.fromCharCode(ltrBase2+97);
    var regId = numPart.toString() + ltr1 + ltr2;
    return regId;
}

function get_ids_from_elems_names(elemsName){
	var nameElems = document.getElementsByName(elemsName);
	var lgth = nameElems.length;
	var idArr = [];
	for (var i=0; i < lgth; ++i){
		idArr.push(nameElems[i].id);
	}
	return idArr;
}

function make_id_arr(idBase, num){
	var idArr = [];
	for (var i=0; i < num; ++i){
		idArr.push(idBase+i);
	}
	return idArr;
}

function make_id_arr_dbls(idBase, num){
	var idArr = [];
	for (var i=0; i < num; ++i){
		idArr.push(idBase+i+'-1');
		idArr.push(idBase+i+'-2');
	}
	return idArr;
}


function create_radio_btn(radBtnId,radBtnName,radBtnVal){
	var newRadio = document.createElement('input');
	newRadio.type = 'radio';
	newRadio.id = radBtnId;
	newRadio.name = radBtnName;
	newRadio.value = radBtnVal;
	return newRadio;
}

function get_radio_id(radios){
        var ri="";
        if (radios){
            var radioLgth = radios.length;
            var i=0;
            while (i < radioLgth){
                if (radios[i].checked){
                   ri=radios[i].id;
                   i=radioLgth;
                }
                i++;
            }
        }
        return ri;	
}

function get_radio_val(radios){
    var rv="";
    if (radios){
        var radioLgth = radios.length;
        var i=0;
        while (i < radioLgth){
            if (radios[i].checked){
               rv=radios[i].value;
               i=radioLgth;
            }
            i++;
        }
    }
    return rv;
}

function set_radio_val(radioName, radioVal){
	var radios = document.getElementsByName(radioName);
    var i=0;
    var numRads = radios.length;
    while (i<numRads && (radios[i].value != radioVal)){
        ++i;
    }
    if (i<numRads){
        radios[i].checked = true;
        return radios[i].id;
    }
    return " ";
}

function clear_radio_vals(radios){
	if (radios){
		var radioLgth = radios.length;
        var i=0;
        while (i < radioLgth){
        	if (radios[i].checked){
        		radios[i].checked=false;
                i=radioLgth;
            }
            i++;
        }
    }
}

function get_list_index(listName, optionVal){
	
	var list = document.getElementById(listName);
	var numOpts = list.options.length;
	for (var i=0; i < numOpts; ++i){
		if (list.options[i].text == optionVal){
			return i;
		}
	}
	return 0;
}

function clear_checkboxes(boxes){
	var numBoxes = boxes.length;
	for (var i=0; i<numBoxes; ++i){
		var box = document.getElementById(boxes[i]);
		if (box){
			box.checked=false;
		}
	}
}

function toggle_check_box_with_disable(boxes,is_checked,is_disabled){
	var numBoxes = boxes.length;
	for (var i=0; i<numBoxes; ++i){
		if (boxes[i]){
			var elem = document.getElementById(boxes[i]);
			if (elem){
				elem.checked=is_checked;
				elem.disabled=is_disabled;
			}
		}
	}
}

function hide_element(existingElem,existingLbl){
	if(existingLbl){
		erase_label(existingLbl);
	}
	hide_element_only(existingElem);
}

function hide_element_only(existingElem){
	var currElem = document.getElementById(existingElem);
	if (currElem){
	    var newElem = document.createElement('input');
	    newElem.type = 'hidden';
	    newElem.id = currElem.id;
	    newElem.name = currElem.name;
	    newElem.value = currElem.value;
	    newElem.onclick = currElem.onclick;
	    newElem.onchange = currElem.onchange;
	    if (currElem.size){
	    	newElem.size = currElem.size;
	    }
	    newElem.maxlength = currElem.maxlength;
	    currElem.parentNode.replaceChild(newElem, currElem);
    }
}

function remove_elements(elemIdArr){
	if (elemIdArr){
		var lgth = elemIdArr.length;
		for (var i=0; i < lgth; ++i){
			remove_element(elemIdArr[i]);
		}
	}
}

function remove_element(elemId){
	var elem = document.getElementById(elemId);
	if (elem){
		elem.parentNode.removeChild(elem);
	}
}

function erase_label(existingLbl){
    var currLbl = document.getElementById(existingLbl);
    if (currLbl){
		while (currLbl && currLbl.firstChild) {
	    	currLbl.removeChild(currLbl.firstChild);
	    }
	    currLbl.appendChild(document.createTextNode(' '));
    }
}

function add_break(appendElem,brkId){
	var elem = document.getElementById(appendElem);
	var br = document.createElement('br');
	if (brkId){
    	br.id=brkId;
    }
    if (elem){
    	elem.parentNode.insertBefore(br, elem.nextSibling);  
    }
}

function create_label(lblId,lblText,lblFor){
	var newLbl = create_element('label','',lblId,'','');
	newLbl.appendChild(document.createTextNode(lblText));
	newLbl.htmlFor = lblFor;
	return newLbl;
}

function create_label_with_color(lblId,lblText,lblFor,colorHash){
	var lbl = create_label(lblId,lblText,lblFor);
	if(colorHash){
		lbl.style.color=colorHash;
	}
	return lbl;
}

function change_label(existingLbl, newLblTxt, addBreak, brkName){
	var currLbl = document.getElementById(existingLbl);
	if (currLbl){
		while (currLbl.firstChild) {
	    	currLbl.removeChild(currLbl.firstChild);
	    }
    	currLbl.appendChild(document.createTextNode(newLblTxt));
   }
    if (addBreak){
    	var br = document.createElement('br');
    	if (brkName){
    		br.id=brkName;
    		br.name=brkName;
    	}
    	currLbl.appendChild(br);
    }
}

function change_label_bold(existingLbl, newLblTxt, addBreak, brkName){
	var currLbl = document.getElementById(existingLbl);
	if (currLbl){
		while (currLbl.firstChild) {
	    	currLbl.removeChild(currLbl.firstChild);
	    }
	    var b=document.createElement('b');
	    b.appendChild(document.createTextNode(newLblTxt));
    	currLbl.appendChild(b);
   }
    if (addBreak){
    	var br = document.createElement('br');
    	if (brkName){
    		br.id=brkName;
    		br.name=brkName;
    	}
    	currLbl.appendChild(br);
    }
}

function removeLabelsAndHideElems(lbls,ids,brks){
        if(lbls.length==ids.length){
            for (var i=0; i<ids.length; ++i){
                hide_element(ids[i],lbls[i]);
            }
        }
        if (brks){
            remove_brks(brks);
        }
}

function create_select_list(elemName,elemId,existingElem,optionList,valueList){
	var currList = document.getElementById(existingElem);
	var newList = document.createElement('select');
	newList.name = elemName;
	newList.id = elemId;
	if (currList){
		newList.onchange = currList.onchange;
	}
	newList.style.visibility='visible';
	var numOpts = optionList.length;
	
	var option;
	for (var i=0; i < numOpts; ++i){
		option = document.createElement('option');
        option.text = optionList[i];
        if (valueList && valueList.length == numOpts){
        	option.value = valueList[i];
        } else {
        	option.value = optionList[i];
        }
        newList.add(option, null);
	}
	if (currList){
		currList.parentNode.replaceChild(newList, currList);
	}
	return newList;
}

function create_element(elemKind,elemType,elemId,elemName,elemVal){
	var newElem = document.createElement(elemKind);
	if (elemType){
		newElem.type = elemType;
	}
	newElem.id = elemId;
	if (elemName){
		newElem.name = elemName;
	}
	if (elemVal){
		newElem.value = elemVal;
	}
	return newElem;
}

function create_element_with_label_and_add(elemType,elemName,elemId,elemVal,elemTabInd,elemSize,elemMax,prpndElem,
		lblId,lblText,lblColorHash,addBreak,brkName){
			
	var newElem = create_input_elem_no_replace(elemType,elemName,elemId,elemVal,elemTabInd,elemSize,elemMax);
	if (lblId){
		var newLbl = create_label_with_color(lblId,lblText,elemId,lblColorHash);
	}
	
	if (elemType == 'text' || elemType == 'password'){
		var elems = [newLbl,newElem];
		add_elems_to_doc(elems,prpndElem,addBreak,brkName);
	} else if (elemType == 'button'){
		var elems = [newElem];
		add_elems_to_doc(elems,prpndElem,addBreak,brkName);
	} else {
		var elems = [newElem,newLbl];
		add_elems_to_doc(elems,prpndElem,addBreak,brkName);
	}
}

function add_elems_to_doc(elemArr,prpndElem,addBreak,brkName){
	var prpdEl = document.getElementById(prpndElem);
	if (prpdEl){
		var arrlgth = elemArr.length;
		for (var i=0; i < arrlgth; ++i){
			prpdEl.parentNode.insertBefore(elemArr[i],prpdEl);
		}
		if (addBreak){
			add_break(elemArr[arrlgth-1].id,brkName+"-1");
			add_break(elemArr[arrlgth-1].id,brkName+"-2");
		}
	}
}

function create_text_elem_with_color(txt,hashcolor){
	var e = document.createElement("span");
    e.style.color = hashcolor;
    e.appendChild(document.createTextNode(txt));
	return e;
}

function add_table_row(tableRef, elemsToAdd){
	var newRow   = tableRef.insertRow(tableRef.rows.length);
    var newCell  = newRow.insertCell(0);
	var elLgth = elemsToAdd.length;
	for (var i=0; i < elLgth; ++i){
		newCell.appendChild(elemsToAdd[i]);
	}
}

function create_hidden_element(elemId,elemName,elemVal){
	var newElem = document.createElement('input');
	newElem.type='hidden';
	newElem.id = elemId;
	newElem.name = elemName;
	newElem.value = elemVal;
	return newElem;
}

function create_input_element(elemType,elemName,elemId,elemVal,existingElem,existingLbl,newLblTxt,addBreak,brkName){
	var currElem = document.getElementById(existingElem);
	if (currElem){
		var newElem = document.createElement('input');
	    newElem.type = elemType;
	    newElem.id = elemId;
	    newElem.name = elemName;
	    newElem.tabIndex = currElem.tabIndex;
	    newElem.value = elemVal;
	    if (currElem.size){
	    	newElem.size = currElem.size;
	    }
	    newElem.maxlength = currElem.maxlength;
	    newElem.onclick = currElem.onclick;
	    newElem.onchange = currElem.onchange;
	    currElem.parentNode.replaceChild(newElem, currElem);
    }
    change_label(existingLbl, newLblTxt, addBreak, brkName);
}

function create_input_elem_no_replace(elemType,elemName,elemId,elemVal,elemTabInd,elemSize,elemMax,evtLstnrName,evtLstnrFx){
	var newElem = document.createElement('input');
    newElem.type = elemType;
    newElem.id = elemId;
    newElem.name = elemName;
    if (elemTabInd){
    	newElem.tabIndex = elemTabInd;
    }
    if (elemVal){
    	newElem.value = elemVal;
    }
    
    if (elemSize){
    	newElem.size = elemSize;
    }
    if (elemMax){
    	newElem.maxlength = elemMax;
    }
    if (evtLstnrName){
    	if (newElem.addEventListener){
    		newElem.addEventListener(evtLstnrName,evtLstnrFx,false);
    	} else {
    		if (newElem.attachEvent){
    			newElem.attachEvent(evtLstnrName,evtLstnrFx);
    		}
    	}
    }
    return newElem;
}

function create_input_elem_and_add(elemType,elemName,elemId,elemVal,elemTabInd,elemSize,elemMax,evtLstnrName,evtLstnrFx,anchElemId){
	var newElem = create_input_elem_no_replace(elemType,elemName,elemId,elemVal,elemTabInd,elemSize,elemMax,evtLstnrName, evtLstnrFx);
	var anchElem = document.getElementById(anchElemId);
	anchElem.parentNode.insertBefore(newElem,anchElem.nextSibling);
}

function create_input_element_disabled(elemType,elemName,elemId,elemVal,existingElem,existingLbl,newLblTxt,addBreak){
	var newElem = document.createElement('input');
    newElem.type = elemType;
    var currElem = document.getElementById(existingElem);
    if (currElem){
	    newElem.id = elemId;
	    newElem.name = elemName;
	    newElem.tabIndex = currElem.tabIndex;
	    newElem.value = elemVal;
	    newElem.size = currElem.size;
	    newElem.maxlength = currElem.maxlength;
	    newElem.onclick = currElem.onclick;
	    newElem.onchange = currElem.onchange;
	    newElem.disabled=true;
	    currElem.parentNode.replaceChild(newElem, currElem);
    }
    change_label(existingLbl, newLblTxt, addBreak, brkName);
}

function create_link(linkloc, linktext, elemName){
	var a = document.createElement('a');
	a.id=elemName;
	a.name=elemName;
    a.href = linkloc; 
    a.innerHTML = linktext;
    a.target='_blank';
    return a;
}

function reset_lists(listNames){
	var num = listNames.length;
	for (var i=0; i< num; ++i){
		document.getElementById(listNames[i]).selectedIndex=0;
	}
}

function copy_select_list(origListId, newListId, newListName){
	var origList = document.getElementById(origListId);
	var origElem = document.getElementById(newListId);
	var newList = document.createElement('select');
	newList.id=newListId;
	newList.name=newListName;
	newList.onchange=origElem.onchange;
	var num = origList.length;
	for (var i=0; i < num; ++i){
		var op = document.createElement('option');
		op.id=origList.options[i].id;
		op.name=origList.options[i].name;
		op.value=origList.options[i].value;
		op.text=origList.options[i].text;
		newList.options.add(op);
	}
	return newList;
}

function show_hide_text_from_list(listName,selectedItem,existingElem,existingLbl,lblText)
{
        var list = document.getElementById(listName);
        var selected = list.options[list.selectedIndex].text;
        if (selected == selectedItem){
            var newElem = document.createElement('input');
            newElem.type = 'text';
            var currElem = document.getElementById(existingElem);
            newElem.id = currElem.id;
            newElem.name = currElem.name;
            newElem.value = currElem.value;
            currElem.parentNode.replaceChild(newElem, currElem);
            var currLbl = document.getElementById(existingLbl);
            while (currLbl.firstChild){
                currLbl.removeChild(currLbl.firstChild);
            }
            currLbl.appendChild(document.createTextNode(lblText));
        } else {
            var currElem = document.getElementById(existingElem);
            currElem.value=list.options[list.selectedIndex].text;
            hide_element(existingElem,existingLbl);
        }
}

function dispPrice(cost,totalElem,totalLbl,newLblTxt,inc){
	var newCost=parseInt(cost);
	if (inc){
    	var tot = document.getElementById(totalElem);
    	newCost += parseInt(tot.value);
   	}
   	document.getElementById(totalElem).value = newCost;
   	newLblTxt+='$'+newCost;
    change_label(totalLbl,newLblTxt,false);
}

function make_bold(cost,totalElem,totalLbl,newLblTxt,inc){
    var newCost=parseInt(cost);
    if (inc){
        var tot = document.getElementById(totalElem);
        newCost += parseInt(tot.value);
    }
    document.getElementById(totalElem).value = newCost;
    newLblTxt+='$'+newCost;
    var lbl = document.getElementById(totalLbl);
    lbl.style.fontSize="larger";
    lbl.innerHTML="<b>"+newLblTxt+"</b>";
}
    
function clearFormVals(elemsToClear){
    var numElems=elemsToClear.length;
    for (var i=0; i<numElems; ++i){
        document.getElementById(elemsToClear[i]).value="";
    }
}

function checkForBlankFld(inEl){
	if (!inEl.value || inEl.value==' '){
		return true;
	}
	return false;
}

function fillInTextFld(formInfo){
    var numInfos = formInfo.length;
    for (var i=0; i < numInfos; ++i){
        var fldInfo = formInfo[i].split('=');
        if (fldInfo.length == 2){
            document.getElementById(fldInfo[0]).value=fldInfo[1];
        }
    }
}

function errorAlertTxtFld(inEl, fldName){
    if (checkForBlankFld(inEl)){
        alert("You did not type your "+fldName+", which is required.\n\nPlease type your "+fldName+".");
        inEl.focus();
        return false;
    }
    return true;
}

function errorAlertRadioBtn(radios, fldName){
	var val = get_radio_val(radios);
	if (val == ""){
		alert("You have not chosen a "+fldName+", which is required.\n\nPlease choose a "+fldName+".");
		radios[0].focus();
		return false;
	}
	return true;
}

function disp_alert()
{
alert("The Security of Your Personal Information\n\nWhat Personal Information We Collect:\nWe collect personal information that you provide when you make a donation or register for an event at our web site. This information includes your email address and mailing address and phone number. We may also ask for personal information when you contact us about your donation. This will allow us to protect your confidentiality by verifying your identity.\n\nWe do not intentionally collect personal information about children. If you do not agree to (or cannot comply with) any of these terms and conditions, do not use this Site.\n\nOur return policy is to refrain from issuing refunds under normal circumstances. Any questions or problems can be directed to the Diocesan Office at (518) 692-3350."
);
}

function get_date(){
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
