<html>
<head>
<style type="text/css">
.errorMessage{
	 bottom: 34px;
    color: #f00;
    font-weight: bold;
    position: absolute;
    right: 224px;
}
.selectedOption{
	background: none repeat scroll 0 0 #ac9244 !important;
}
.optionSpaceAbove {
    bottom: 55px;
    cursor: pointer;
    margin-left: 60px;
    position: absolute;
    display: none;
    right: 200px;
}
.optionsUl{
	
	list-style: outside none none;
	padding: 0px;
}
.optionsUl > li {
	background: #F8C476;
    border: 1px solid black;
    padding: 10px 20px;
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-bottom: 1px solid black;
    border-image: none;
    border-left: 1px solid black;
    border-radius: 13px;
    border-top: 1px solid black;
    width: 300px;
    text-align: center;
}
.mainPage{
	border: 1px solid #ccc;
	width: 30%;
	height: 100%;
	margin: auto;
}
.bottom_sticy{
	position: absolute;
	bottom: 10px;
	width: 30%;
	height: 60px;
}
.bottom_sticy textarea{
	width: 80%;
	height: 105%;
	padding: 5px;
}
body{
	padding: 0;
	margin: 0;
}
.left{
	border: 1px solid #ccc;
	/*float: left;*/
	width: 99%;
	height: 100%;
	overflow: auto;
}
.right{
	border: 1px solid #ccc;
	float: right;
	width: 38%;
	height: 100%;
	overflow: auto;
}
ul#chatList{
    height: auto;
    list-style: outside none none;
    margin-bottom: 60px;
    margin-top: 0;
    padding-left: 2px;
    padding-top: 2px;
}
ul#chatList li{
	width: 99%;
	padding: 10px 2px;
	border : 1px solid #b1b1b1
}
.userResponse{
	background: #f1f1f1;
	text-align:right;  
}
.botResponse{
	background: #ccc;
}
</style>
</head>
<body>
	<div class='mainPage'>
		<div class='chatContainer'>
			<div class='left'>
				<ul id='chatList'>
					<li class='botResponse'><?=$initialMessage;?></li>
	
				</ul>
			</div>
			<!-- <div class='right'>

			</div>
			 -->
		</div>
		
		<div class='bottom_sticy'>
			<input type='button' style='float:right;height:100%;width:20%;' value='Send' id='chatSend'>
			<textarea id='chatBox'></textarea>
			<input type='hidden' id='requestType' value=''>
			<input type='hidden' id='chatSendButtonAction' value='0'>
		</div>
	<!-- 	<div class='optionSpaceBelow'>

		</div> -->
	</div>
	<div class='errorMessage' id='errorMessage' style='displayn:none;color:#f00'>

	</div>
	<div class='optionSpaceAbove'>
			<?php echo $htmlForOptions;?>
	</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
	
	var ShikshaBotHandler = function(){

	    var currentObject = this;
	    this.customDataInCaseOfMultiSelectOptions = {};
		this.customDataInCaseOfTextOutput = {};
		this.isTextResponse = false;

	    ShikshaBotHandler.prototype.bindElementsAndInitialize = function(){
	    	$("#chatSend").click(function(){
    			currentObject.chatSendClickAction();
	    	});
	    }	

	    ShikshaBotHandler.prototype.chatSendClickAction = function() {
	    	var action = $("#chatSendButtonAction").val();

	    	$isDisable = $("#chatBox").prop('disabled');
	    	$chatText = $.trim($("#chatBox").val());
	    	if($isDisable == false && $chatText == ""){
	    		//alert("Please Type response");
	    		$("#errorMessage").text("Please Type Something and then press Send").show();
	    		return;
	    	}else{
	    		$("#errorMessage").text("").hide();
	    	}

			if(action == "1"){
				var botResponseString = "";
				var optionsSelected = [];
				var selectedOptionsLength = $('.optionsUl-Li.selectedOption').length;
				if(selectedOptionsLength == 0){
					$("#errorMessage").text("Please Select At Least one option;").show();
				}else{
					$("#errorMessage").text("").hide();
					$('.optionsUl-Li.selectedOption').each(function(index){
					    optionsSelected.push($(this).attr('data'));
					    botResponseString = botResponseString + $(this).text() + ", ";
					});
					if(typeof(currentObject.customDataInCaseOfMultiSelectOptions) == undefined || typeof(currentObject.customDataInCaseOfMultiSelectOptions) == "undefined"){
						currentObject.customDataInCaseOfMultiSelectOptions = {};
					}
					currentObject.generateUserResponse(botResponseString);
					currentObject.customDataInCaseOfMultiSelectOptions.optionsData = JSON.stringify(optionsSelected);
					currentObject.resetOptionsLayer();
					currentObject.fetchBotResponse(currentObject.customDataInCaseOfMultiSelectOptions);
				}
				
			}
			else if(action == "0"){  // text Written send button
				currentObject.sendResponseRequestToBot(currentObject.customDataInCaseOfTextOutput);
			}
	    }

	    ShikshaBotHandler.prototype.initializeChat = function() {
	    	currentObject.bindElementsAndInitialize();
			var ajaxData = {'whichRes' : 'startChat'};
			currentObject.sendResponseRequestToBot(ajaxData);
		}

		ShikshaBotHandler.prototype.sendResponseRequestToBot = function(ajaxData) {
			ajaxData.text = $("#chatBox").val();
			ajaxData.requestType = $("#requestType").val();
			if($.trim(ajaxData.text) != ""){
				currentObject.appendUserInputToChat(ajaxData.text);
			}
			$("#chatBox").val("");
			$("#chatBox").prop('disabled','true');
			currentObject.sendAjaxRequest('/autoAnswer/ShikshaBot/findResponse',ajaxData, true,true,currentObject.chatResponseHandler);	
		}

		ShikshaBotHandler.prototype.disableSendButton = function() {
			$("#chatSend").prop('disabled','true');
		}

		ShikshaBotHandler.prototype.enableSendButton = function() {
			$("#chatSend").removeProp('disabled');
		}

		ShikshaBotHandler.prototype.chatResponseHandler = function(response) {
			currentObject.customDataInCaseOfMultiSelectOptions = {};
			currentObject.customDataInCaseOfTextOutput = {};

			response = JSON.parse(response);
			currentObject.isTextResponse = response.isTextResponse;
			if(response.disableTextBox){
				$("#chatBox").prop('disabled','true');
			}else{
				$("#chatBox").removeProp('disabled');
			}

			if(response.isOptionsResponse){
				currentObject.disableSendButton();
				optionsResponse = response.optionResponses;
				currentObject.setOptionsHtml(optionsResponse);
			}
			if(response.disableSendButton && !response.isOptionsMultiSelect){
				currentObject.disableSendButton();
			}else{
				currentObject.enableSendButton();
			}

			if(response.isErrorInResponse){
				currentObject.appendDataToChat(response.errorMessage);
			}
			else if(response.isTextResponse){
				currentObject.appendDataToChat(response.finalTextResponse);
			}

			if(response.attachClickHandler){
				$(response.attrForClick).unbind('click');
				$(response.attrForClick).click(function(){
					fnName = response.clickHandlerName;
					window['shikshaBotHandler'][fnName]($(this),response);
				});
			}else{
				$(response.attrForKeyUp).unbind('click');
			}

			if(response.attachKeyUpHandler){
				$(response.attrForKeyUp).unbind('keyup');
				$(response.attrForKeyUp).keyup(function(){
					fnName = response.keyUpHandleName;
					window['shikshaBotHandler'][fnName]($(this),response);
				});
			}else{
				$(response.attrForKeyUp).unbind('keyup');
			}
			$("#requestType").val(response.nextRequestType);

			if(response.isOptionsMultiSelect){
				$("#chatSendButtonAction").val("1");
			}else{
				$("#chatSendButtonAction").val("0");
			}

			if(response.isCustomDataForTextRequest){
				currentObject.customDataInCaseOfTextOutput = response.customData;	
			}
		}


		ShikshaBotHandler.prototype.setOptionsHtml = function(optionsResponse) {
			$(".optionSpaceAbove").html(optionsResponse).fadeIn(1000);
		}

		ShikshaBotHandler.prototype.keyUpHandleForChatBox = function(chatBoxObject,response) {
			if(response.autoSuggestorFor == "instituteName"){
				$val = $("#chatBox").val();
				ajaxData = {'text' : $val};
				isPost = true;
				isAsync = true;
				if($val.length > 3){
					currentObject.sendAjaxRequest('/autoAnswer/ShikshaBotAjaxCalls/autoSuggestor/instituteName',ajaxData,isPost,isAsync,currentObject.keyUpSuccessCallBack,response);	
				}
				
			}
		}

		ShikshaBotHandler.prototype.keyUpSuccessCallBack = function(ajaxResponse,extraData) {
			currentObject.setOptionsHtml(ajaxResponse);
			setTimeout(function(){
				$(".optionsUl-Li").unbind('click');
				$(".optionsUl-Li").click(function(){
					currentObject.clickForOptions($(this),extraData);
				});
			},300);
		}

		ShikshaBotHandler.prototype.fetchBotResponse = function(customData) {
			$("#chatBox").prop('disabled',true);
			if(typeof(customData) != undefined){
				currentObject.sendResponseRequestToBot(customData);
			}else{
				currentObject.sendResponseRequestToBot({});
			}
		}

		ShikshaBotHandler.prototype.appendDataToChat = function(html) {
			$("#chatList").append(html);
			$("#chatList").animate({ scrollTop: $('#chatList').prop("scrollHeight")}, 300);
		}

		ShikshaBotHandler.prototype.appendUserInputToChat = function(text) {
			$html = "<li class='userResponse'>"+text+"</li>";
			currentObject.appendDataToChat($html);
		}

		ShikshaBotHandler.prototype.clickForOptions = function(obj, response) {

			if(!response.isOptionsMultiSelect){
				$("#chatBox").val("");
				var text = obj.text();
				var data = obj.attr('data');
				currentObject.generateUserResponse(text);
				currentObject.resetOptionsLayer();
				currentObject.enableSendButton();
				var customData = response.customData;
				if(typeof(customData) == undefined || typeof(customData) == "undefined"){
					customData = {};
				}
				customData.optionsData = data;			
				currentObject.fetchBotResponse(customData);
			}else{
				var isSelected = obj.attr('isSelected');
				if(typeof(isSelected) != "undefined" && isSelected == "true"){
					obj.attr('isSelected',"false");
					obj.removeClass('selectedOption');
				}else {
					obj.attr('isSelected','true');
					obj.addClass('selectedOption');
				}
				currentObject.customDataInCaseOfMultiSelectOptions = response.customData;
			}
		}

		ShikshaBotHandler.prototype.resetOptionsLayer = function() {
			$(".optionSpaceBelow").fadeOut();
			$(".optionSpaceAbove").fadeOut();
			$("#chatBox").removeProp('disabled');
		}

		ShikshaBotHandler.prototype.generateBotResponse = function(text) {
			var html = "";
			html = "<li class='botResponse'>"+text+"</li>";
			currentObject.appendDataToChat(html);
		}

		ShikshaBotHandler.prototype.generateUserResponse = function(text) {
			var html = "";
			html = "<li class='userResponse'>"+text+"</li>";
			currentObject.appendDataToChat(html);
		}

		ShikshaBotHandler.prototype.sendAjaxRequest = function(ajaxURL,ajaxData,isPost,isAsync,successCallback,extraData) {

	       var typeOfRequest = "GET";
	       if(typeof(isPost) != "undefined" && isPost == true){
	          typeOfRequest = "POST";
	       }
	       if(typeof(isAsync) == "undefined"){
	          isAsync = true;
	       }

	        $.ajax({
	            type: typeOfRequest,
	            async: isAsync,
	            data: ajaxData,
	            url : ajaxURL,
	            success : function(response){
	                if(typeof successCallback == "function"){

	                	if(typeof(extraData) != "undefined"){
	                		successCallback(response,extraData);
	                	}else{
	                		successCallback(response);	
	                	}
	                    
	                }

	            }
	        });
		}

	}

	var shikshaBotHandler = new ShikshaBotHandler();
	shikshaBotHandler.initializeChat();
</script>

