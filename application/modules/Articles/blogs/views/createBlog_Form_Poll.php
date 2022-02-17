<input type="hidden" name="articlePoll" id="articlePoll" value="<?=$pollJSON?>" />
<input type="hidden" name="articlePollchanged" id="articlePoll" value="0" />
<div id="addPoll" style="display:none;margin:20px;width:100%;text-align:center">
	<ul>
		<li>
			<div class="flLt" style="width:300px; padding:0 15px 12px 0">
				<div style="font-size: 14px;margin-bottom:10px">Upload Image (Optional)</div>
				<form action="/common/uploadImageDialog/blog/0" method="post" enctype="multipart/form-data" id="pollImageForm">
				<input class="universal-txt-field" id="shikshaImageDialog" type="file" onchange="this.form.submit();" name="shikshaImageDialog[]">
				</form>
				
				<div class="clearFix"></div>
				<div style="display: none;"><div style="padding-left:60px; clear:both; display:block" id="pollImage_error" class="errorMsg">Please upload an image</div></div>
			</div>
		</li>
	</ul>
	<form id="articlePoll" onsubmit="savePoll(); return false;" novalidate>
		<ul>
			<li>
			<div class="flLt" style="width:230px; padding:0 15px 12px 0">
				<img src="/public/images/blankImg.gif" id="shikshaImageDialogCont"/>
				<input class="universal-txt-field" type="hidden" name="pollImage" caption="Poll Image" id="pollImage">
				<div class="clearFix"></div>
			</div>
			</li>
			<li>
			<div class="flLt" style="width:230px; padding:0 15px 12px 0">
				<div style="font-size: 14px;margin-bottom:10px">Enter Poll Title </div>
				<input value="" class="universal-txt-field" type="text" name="pollTitle" profanity="true" minlength="3" maxlength="100" required="true" validate="validateStringColumnNames" caption="Poll Title" id="pollTitle">
				<div class="clearFix"></div>
				<div style="display: none;"><div style="clear:both; display:block" id="pollTitle_error" class="errorMsg">Please enter poll title</div></div>
			</div>
			</li>
			<li>
			<div class="flLt" style="width:230px; padding:0 15px 12px 0">
				<div  value=""  style="font-size: 14px;margin-bottom:10px">Enter Poll Question </div>
				<input class="universal-txt-field" type="text" name="pollQuestion" profanity="true" minlength="3" maxlength="100" required="true" validate="validateStringColumnNames" caption="Poll Question" id="pollQuestion">
				<div class="clearFix"></div>
				<div style="display: none;"><div style="clear:both; display:block" id="pollQuestion_error" class="errorMsg">Please enter poll question</div></div>
			</div>
			</li>
			
			
			
			<li id="addMore">
				<div class="flLt" style="width:300px; padding:0 15px 12px 0">
				<a href="#" onclick="addPollOption();return false;">+ Add Option</a>
				<div class="clearFix"></div>
				</div>
			</li>
			<li>
				<div class="flLt" style="width:230px; padding:0 15px 12px 0">
					<input id="pollSubmit" name="pollSubmit" style="font-size: 16px" type="submit" class="orange-button" value="Create" />
				</div>
				<div style="display: none;"><div style="clear:both; display:block" id="pollSubmit_error" class="errorMsg">Please enter poll question</div></div>
				<div class="clearFix"></div>
			</li>
			
			
		</ul>
		<div class="clearFix"></div>
	</form>
</div>

<script>
var pollJSON = <?=json_encode($pollJSON)?>;
var currentOption = 0;
function showPollOverlay(){
	 var content = $('addPoll').innerHTML;
     overlayParentAnA = $('addPoll');
     overlayParentAnA.innerHTML = '';
     showOverlayAnA(367,600,'Polls',content);
	 loadPollForm();
}

function savePoll(){
	try{
	if(validateFields($('articlePoll'))){
		var count  = 0;
		$j('.pollOptions').each(function(index) {
			if(trim($j(this).val()) != ""){
				count++;
			}
		});
		if(count < 2){
			alert("Please add atleast two options.");
			return false;
		}
		if(!confirm("This action will remove all the responses related to this poll. Are you sure you want to change this poll?")){
			return false;
		}
		
		pollJSON['title'] = $j('#pollTitle').val();
		$j('#pollTitleExternal').html($j('#pollTitle').val());
		pollJSON['question'] = $j('#pollQuestion').val();
		pollJSON['image'] = $j('#pollImage').val();
		$j('.pollOptions').each(function(index) {
			pollJSON['options'][index] = {};
			pollJSON['options'][index]["value"] = $j(this).val();
		});
		hideOverlayAnA();
		$j('#pollJSON').val(url_base64_encode(JSON.stringify(pollJSON)));
	}
	}catch(e){
		alert(e);
	}
}

function loadPollForm(){
	$j('#pollTitle').val(pollJSON['title']);
	$j('#pollQuestion').val(pollJSON['question']);
	$j('#pollImage').val(pollJSON['image']);
	if(pollJSON['image'] && pollJSON['image'] != ""){
		$('shikshaImageDialogCont').src = pollJSON['image'];
		document.getElementById('shikshaImageDialogCont').style.width = '200px';
	}else{
		$('shikshaImageDialogCont').src = "/public/images/blankImg.gif";
		document.getElementById('shikshaImageDialogCont').style.width = '0px';
	}
	if(!pollJSON['options'] || pollJSON['options'] == ""){
		pollJSON['options'] = new Array();
		pollJSON['options'][0] = new Array();
		pollJSON['options'][0]['value'] = "";
	}
	$j.each(pollJSON['options'],function(index,option){
		addPollOption(option['value'],index);
	});
}

function addPollOption(option,index){
	if(typeof(option) == 'undefined'){
		option = "";
	}
	if(typeof(index) == 'undefined'){
		currentOption++;
	}else{
		currentOption = index+1;
	}
	if($('pollOption'+currentOption)){
		$j('#pollOption'+currentOption).val(option);
	}else{
		$j('#addMore').before(''+
		'<li>'+
			'<div class="flLt" style="width:230px; padding:0 15px 12px 0">'+
			'<div style="font-size: 14px;margin-bottom:10px">Enter Option '+currentOption+'</div>'+
			'<input class="universal-txt-field pollOptions" value="'+option+'" type="text" name="pollOption'+currentOption+'" profanity="true" minlength="2" maxlength="100" validate="validateStringColumnNames" caption="Poll Option" id="pollOption'+currentOption+'">'+
			'<div class="clearFix"></div>'+
			'<div style="display: none;"><div style="clear:both; display:block" id="pollOption'+currentOption+'_error" class="errorMsg">Please enter Option</div></div>'+
			'</div>'+
		'</li>');
		pollJSON['options'][currentOption-1] = {};
		pollJSON['options'][currentOption-1]['value'] = option;
	}
	
}


function showUploadImageResponseForBlogPoll(response){
	
	try{
		var mediaDetails = eval('eval('+response+')');
	}catch(e){
		$j('#pollImage').val("");
		$('shikshaImageDialogCont').src="/public/images/blankImg.gif";
		document.getElementById('shikshaImageDialogCont').style.width = '0px';
		alert(response);
		return false;
	}
	if(mediaDetails['imageurl'] != undefined){
		$j('#pollImage').val(mediaDetails['imageurl']);
		document.getElementById('shikshaImageDialogCont').src=mediaDetails['imageurl'];
		document.getElementById('shikshaImageDialogCont').style.width = '200px';
	}else{
		$j('#pollImage').val("");
		$('shikshaImageDialogCont').src="/public/images/blankImg.gif";
		alert(mediaDetails);
	}
}

AIM2={
	frame:function(c){
		var n="a";
		var d=document.createElement("DIV");
		d.innerHTML="<iframe style=\"display:none\" src=\"about:blank\" id=\""+n+"\" name=\""+n+"\" onload=\"AIM2.loaded('"+n+"')\"></iframe>";
		document.body.appendChild(d);
		var i=document.getElementById(n);
		if(c&&typeof (c.onComplete)=="function"){
			i.onComplete=c.onComplete;
		}
	return n;
	},
	form:function(f,_6){
		f.setAttribute("target",_6);
	},
	submit:function(f,c){
		AIM2.form(f,AIM2.frame(c));
		if(c&&typeof (c.onStart)=="function"){
			return c.onStart();
		}else{
			
			return true;
		}
	},
	loaded:function(id){
		var i=document.getElementById(id);
		if(i.contentDocument){
			
			
			var d=i.contentDocument;
			i.onComplete = showUploadImageResponseForBlogPoll;
			
		}else{
			
			if(i.contentWindow){
				
				var d=i.contentWindow.document;
			}else{
				
				var d=window.frames[id].document;
			}		
		}
		if(d.location.href=="about:blank"){
			return;
		}
		if(typeof (i.onComplete)=="function"){
			
			i.onComplete(d.body.innerHTML);
		}
	}
};



</script>