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
	var customDataInCaseOfMultiSelectOptions = {};
	var customDataInCaseOfTextOutput = {};
	$(document).ready(function(){
		initializeChat();
		$("#chatSend").click(function(){
			/*var ajaxData = {'text' : $.trim($("#chatBox").val())}
			$.post('/autoAnswer/ShikshaBot/findResponse',ajaxData,function(response){
				console.log(response);
			});*/
			var action = $("#chatSendButtonAction").val();
			//Options click Send Button
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
					if(typeof(customDataInCaseOfMultiSelectOptions) == undefined || typeof(customDataInCaseOfMultiSelectOptions) == "undefined"){
						customDataInCaseOfMultiSelectOptions = {};
					}
					generateUserResponse(botResponseString);
					customDataInCaseOfMultiSelectOptions.optionsData = JSON.stringify(optionsSelected);
					resetOptionsLayer();
					fetchBotResponse(customDataInCaseOfMultiSelectOptions);
				}
				
			}
			else if(action == "0"){  // text Written send button
				sendResponseRequestToBot(customDataInCaseOfTextOutput);
			}
			
		});
	});

	function initializeChat(){
		var ajaxData = {'whichRes' : 'startChat'};
		sendResponseRequestToBot(ajaxData);
	}

	function  sendResponseRequestToBot (ajaxData) {

		ajaxData.text = $("#chatBox").val();
		ajaxData.requestType = $("#requestType").val();
		if($.trim(ajaxData.text) != ""){
			appendUserInputToChat(ajaxData.text);
		}
		$("#chatBox").val("");
		$("#chatBox").prop('disabled','true');
		sendAjaxRequest('/autoAnswer/ShikshaBot/findResponse',ajaxData, true,true,chatResponseHandler);	
	}

	function disableSendButton(){
		$("#chatSend").prop('disabled','true');
	}

	function enableSendButton(){
		$("#chatSend").removeProp('disabled');
	}

	function chatResponseHandler(response){
		customDataInCaseOfMultiSelectOptions = {};
		customDataInCaseOfTextOutput = {};
		response = JSON.parse(response);

		if(response.disableTextBox){
			$("#chatBox").prop('disabled','true');
		}else{
			$("#chatBox").removeProp('disabled');
		}
		if(response.isOptionsResponse){
			disableSendButton();
			optionsResponse = response.optionResponses;
			setOptionsHtml(optionsResponse);
		}
		if(response.disableSendButton && !response.isOptionsMultiSelect){
			disableSendButton();
		}else{
			enableSendButton();
		}

		if(response.isErrorInResponse){
			appendDataToChat(response.errorMessage);
		}
		else if(response.isTextResponse){
			appendDataToChat(response.finalTextResponse);
		}

		if(response.attachClickHandler){
			$(response.attrForClick).unbind('click');
			$(response.attrForClick).click(function(){
				fnName = response.clickHandlerName;
				window[fnName]($(this),response);

			});
		}else{
			$(response.attrForKeyUp).unbind('click');
		}

		if(response.attachKeyUpHandler){
			$(response.attrForKeyUp).unbind('keyup');
			$(response.attrForKeyUp).keyup(function(){
				fnName = response.keyUpHandleName;
				window[fnName]($(this),response);
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
			customDataInCaseOfTextOutput = response.customData;	
		}
		
	}

	function setOptionsHtml(optionsResponse){
		$(".optionSpaceAbove").html(optionsResponse).fadeIn(1000);
	}
	function keyUpHandleForChatBox(chatBoxObject,response){
		if(response.autoSuggestorFor == "instituteName"){
			$val = $("#chatBox").val();
			ajaxData = {'text' : $val};
			isPost = true;
			isAsync = true;
			if($val.length > 3){
				sendAjaxRequest('/autoAnswer/ShikshaBotAjaxCalls/autoSuggestor/instituteName',ajaxData,isPost,isAsync,keyUpSuccessCallBack,response);	
			}
			
		}
	}

	function keyUpSuccessCallBack(ajaxResponse,extraData){
		setOptionsHtml(ajaxResponse);
		setTimeout(function(){
			$(".optionsUl-Li").unbind('click');
			$(".optionsUl-Li").click(function(){
				clickForOptions($(this),extraData);
			});
		},300);
	}

	function fetchBotResponse(customData){
		$("#chatBox").prop('disabled',true);
		if(typeof(customData) != undefined){
			sendResponseRequestToBot(customData);
		}else{
			sendResponseRequestToBot({});
		}
	}

	function appendDataToChat(html){
		$("#chatList").append(html);
		$("#chatList").animate({ scrollTop: $('#chatList').prop("scrollHeight")}, 300);
	}

	function appendUserInputToChat(text){
		$html = "<li class='userResponse'>"+text+"</li>";
		appendDataToChat($html);
	}
	function clickForOptions(obj,response){
		if(!response.isOptionsMultiSelect){
			$("#chatBox").val("");
			var text = obj.text();
			var data = obj.attr('data');
			generateUserResponse(text);
			resetOptionsLayer();
			enableSendButton();
			var customData = response.customData;
			if(typeof(customData) == undefined || typeof(customData) == "undefined"){
				customData = {};
			}
			customData.optionsData = data;			
			fetchBotResponse(customData);
		}else{
			var isSelected = obj.attr('isSelected');
			if(typeof(isSelected) != "undefined" && isSelected == "true"){
				obj.attr('isSelected',"false");
				obj.removeClass('selectedOption');
			}else {
				obj.attr('isSelected','true');
				obj.addClass('selectedOption');
			}
			customDataInCaseOfMultiSelectOptions = response.customData;
		}
		
	}

	function resetOptionsLayer(){
		$(".optionSpaceBelow").fadeOut();
		$(".optionSpaceAbove").fadeOut();
		$("#chatBox").removeProp('disabled');
	}

	function generateBotResponse(text){
		var html = "";
		html = "<li class='botResponse'>"+text+"</li>";
		appendDataToChat(html);
	}
	function generateUserResponse(text){
		var html = "";
		html = "<li class='userResponse'>"+text+"</li>";
		appendDataToChat(html);
	}


	function sendAjaxRequest(ajaxURL,ajaxData,isPost,isAsync,successCallback,extraData){

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

</script>


