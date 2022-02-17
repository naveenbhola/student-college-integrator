<div style="line-height:5px">&nbsp;</div>
<div id='preferredLocalityBlocks'>
	<div id='preferredLocalityBlock_1'>
		<?php $this->load->view('enterprise/preferredLocalities',array('blockNum' => 1)); ?>
	</div>
</div>

<a href='javascript:void(0);' onclick="addMoreLocalityBlock();" style='margin-left:<?php if($isMMM) { echo '210'; } else { echo '200'; } ?>px;'>Add More</a>
<input type='hidden' name='prefLocalityNumBlocks' id='prefLocalityNumBlocks' value='1' />

<div style="line-height:15px">&nbsp;</div>
<div class="cmsSearch_SepratorLine">&nbsp;</div>
<script>
var prefLocalityBlockNum = 1;

var addMoreLocalityBlock = function() {
	prefLocalityBlockNum = parseInt(document.getElementById('prefLocalityNumBlocks').value);
	prefLocalityBlockNum++;
	var mysack = new sack();
    mysack.requestFile = '/enterprise/shikshaDB/ajax_add_preference_locality_block/'+prefLocalityBlockNum+'/<?php echo $isMMM; ?>';
    mysack.method = 'POST';
    mysack.onLoading = function() { };
    mysack.onCompletion = function() {
		var newBlock = document.createElement('div');
		newBlock.id  = 'preferredLocalityBlock_'+prefLocalityBlockNum;
		newBlock.innerHTML = mysack.response;
		newBlock.style.marginTop = '10px';
		document.getElementById('preferredLocalityBlocks').appendChild(newBlock);
		document.getElementById('prefLocalityNumBlocks').value = prefLocalityBlockNum;
	}
	mysack.runAJAX();
}

var load_preference_zone = function (obj,num){
    var courseOptions = obj.options;
    var selectindex = courseOptions.selectedIndex;
    var DD_text = courseOptions[selectindex].text;
    var str = '';
    var checks = document.getElementsByName('LocalityArr'+num+'[]');
    var boxLength = checks.length;
    if (boxLength > 0) {
		for ( i=0; i < boxLength; i++ ) {
		    checks[i].checked == false;
		}
		document.getElementById('hiddenpreferedCity_'+num).value= "";
    }
	
	document.getElementById('hiddenpreferedCity_'+num).value= "";
	
    if (DD_text != 'Select City') {
		document.getElementById('hiddenpreferedMainCity_'+num).value = DD_text;
    } else {
		document.getElementById('hiddenpreferedMainCity_'+num).value = "";
    }
    var DD_value = courseOptions[selectindex].getAttribute("ddid");
    var mysack = new sack();
    mysack.requestFile = '/enterprise/shikshaDB/ajax_preference_locality/'+DD_value+ '/1';
    mysack.method = 'POST';
    mysack.onLoading = function() { };
    mysack.onCompletion = function() {
	document.getElementById("zone_container_"+num).style.display = 'none';
	document.getElementById("zone_name_placeholder_"+num).innerText = '';
	document.getElementById("result_set_data_"+num).innerText = '';
	document.getElementById("parent_div_localities_"+num).style.display = 'none';
	//alert(mysack.response);
	var response = eval("eval("+mysack.response+")");
	if (response.length > 0 ) {
	    document.getElementById("zone_container_"+num).style.display = '';
	    var f = document.getElementById('zone_id_'+num);
	    while (f.hasChildNodes()) {
		f.removeChild(f.firstChild);
	    }
	    var ddId = 'zone_id_'+num;
	    var obj = response;
	    var optionElement = document.createElement('option');
	    optionElement.value = '';
	    optionElement.innerText = 'Select Zone' ;
	    document.getElementById(ddId).appendChild(optionElement);
	    for(i = 0;i < obj.length;i++)
	    {
		var optionElement = document.createElement('option');
		optionElement.value = obj[i].zoneId;
		optionElement.title = obj[i].zoneName;
		optionElement.innerHTML = obj[i].zoneName ;
		document.getElementById(ddId).appendChild(optionElement);
	    }
	} else {
		document.getElementById("zone_container_"+num).style.display = 'none';
		document.getElementById("zone_name_placeholder_"+num).innerText = '';
		document.getElementById("result_set_data_"+num).innerText = '';
		document.getElementById("parent_div_localities_"+num).style.display = 'none';
	}
    };
    mysack.runAJAX();
}

var load_preference_localities = function (obj,num){
    var DD_value = obj.value;
    var mysack = new sack();
    mysack.requestFile = '/enterprise/shikshaDB/ajax_preference_locality/'+DD_value+ '/2';
    mysack.method = 'POST';
    mysack.onLoading = function() { };
    if (DD_value == '') {
	var str = '';
	var checks = document.getElementsByName('LocalityArr_'+num+'[]');
	var boxLength = checks.length;
	if (boxLength > 0) {
	    for ( i=0; i < boxLength; i++ ) {
			checks[i].checked == false;
	    }
	    document.getElementById('hiddenpreferedCity_'+num).value= "";
	}
	document.getElementById("zone_name_placeholder_"+num).innerText = '';
	document.getElementById("result_set_data_"+num).innerText = '';
	document.getElementById("parent_div_localities_"+num).style.display = 'none';
	return false;
    }
    mysack.onCompletion = function() {
	//alert(mysack.response);
	var response = eval("eval("+mysack.response+")");
	if (response.length > 0 ) {
	    // open parent div id : parent_div_localities
	    document.getElementById("parent_div_localities_"+num).style.display = '';
	    // set zone name id : zone_name_placeholder <b>
	    document.getElementById("zone_name_placeholder_"+num).innerHTML = '<b>'+document.getElementById("zone_id_"+num).options[document.getElementById("zone_id_"+num).selectedIndex].text+'</b>' ;
	    var ddId = 'zone_id';
	    var obj = response;
	    var result_str = "";
	    for(i = 0;i < obj.length;i++)
	    {
		result_str +='<div class="float_L" style="width:<?php echo $isMMM ? 220 : 240; ?>px"><input checked ddname="'+obj[i].localityName+'" onclick="selectAllCheckBoxes(2,'+num+');" type="checkbox" name="LocalityArr_'+num+'[]" value="'+obj[i].localityId+'" >'+obj[i].localityName+'</div>';
	    }
	    document.getElementById("result_set_data_"+num).innerHTML = result_str;
	    putvalues(num);
	    document.getElementById("flag_select_all_"+num).checked = true;
	} else {
	    document.getElementById("zone_name_placeholder_"+num).innerText = '';
	    document.getElementById("result_set_data_"+num).innerText = '';
	    document.getElementById("parent_div_localities_"+num).style.display = 'none';
	}
    };
    mysack.runAJAX();
}

var selectAllCheckBoxes = function (ref,num) {
    var str = '';
    var checks = document.getElementsByName('LocalityArr_'+num+'[]');
    var boxLength = checks.length;
    var chkAll = document.getElementById("flag_select_all_"+num);
    var allChecked = false;
    if ( ref == 1 ) {
		if ( chkAll.checked == true ){
			if (boxLength !== 0 ) {
				for ( i=0; i < boxLength; i++ ) {
					checks[i].checked = true;
				}
			}
		}
		else {
			for ( i=0; i < boxLength; i++ ) {
				checks[i].checked = false;	
			}
		}
		putvalues(num);
    }
	else {
		for ( i=0; i < boxLength; i++ ) {
			if(checks[i].checked == true){
				str += checks[i].getAttribute('ddname') + ',';
			}
		}

		for ( i=0; i < boxLength; i++ ) {
            if ( checks[i].checked == true ) {
                allChecked = true;
                continue;
            }
            else {
                allChecked = false;
                break;
            }
        }
		if ( allChecked == true ) {
			chkAll.checked = true;
		}
        else {
			chkAll.checked = false;
		}
		str = str.substring(0, str.length-1);
		document.getElementById('hiddenpreferedCity_'+num).value= str;
    }
}

function putvalues(num) {
    try{
	    var str = '';
	    var checks = document.getElementsByName('LocalityArr_'+num+'[]');
	    var boxLength = checks.length;
	    if (boxLength > 0) {
			for ( i=0; i < boxLength; i++ ) {
				if(checks[i].checked == true){
					str += checks[i].getAttribute('ddname') + ',';
				}
			}
			str = str.substring(0, str.length-1);
			document.getElementById('hiddenpreferedCity_'+num).value= str;
	    }
    }catch(e) {
		//alert(e);
    }
}

function addlocality(csvids,divid) {
    var xmlHttp = getXMLHTTPObject();
        xmlHttp.onreadystatechange=function()
        {
            if (xmlHttp.readyState !== 4) {
                 document.getElementById('ajax-loader-display').style.display = "";
            }
            if(xmlHttp.readyState==4)
            {
                if(trim(xmlHttp.responseText) != "")
                {
		    var response = eval("eval("+xmlHttp.responseText+")");
		    var Edu = response;
                    document.getElementById(divid).innerText = '';
		    var response_str = '';
                    if(response != 0)
		    {
			for(i = 0;i < response.length;i++)
			{
			    var check_locality_value = Edu[i].countryId+":"+Edu[i].state_id+":"+Edu[i].cityId+":"+Edu[i].localityId;
			    var check_locality_title = Edu[i].localityName;
			    response_str += '<div style="display:block;padding-left:5px"><input type="checkbox" id="base64_encode('+check_locality_value+'"); name="prefLocArr[]" value="'+Edu[i].localityId+'" prefLOCCititesName="'+check_locality_title+'" onClick="prefLOCSingleCheckBox(this)">'+check_locality_title+'</div>';
			}
			
		    }
			document.getElementById(divid).innerHTML = response_str;
                }
                document.getElementById('ajax-loader-display').style.display = "none";
            }
        };
        url = '/enterprise/shikshaDB/callAjax' + '/2/' + csvids;
	xmlHttp.open("POST",url,true);
        xmlHttp.send(null);
}
</script>
