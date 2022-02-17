<?php
  $username = "";
  if(!empty($validateuser) && $validateuser != "false"){
    $username = $validateuser[0]['firstname'];
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Shiksha Answers</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="/public/css/botui.min.css" type="text/css" rel="stylesheet" />
    <style>
        b#chatnotification {
            color: #fff;
            font-size: 12px;
            background: #f37921;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            line-height: 26px;
            text-align: center;
            position: absolute;
            right: 0px;
            top: 0px;
            z-index: 5;
            transform: scale(0);
            transition: all .1s linear;
        }
        b#chatnotification.on {
            transform: scale(1);
        }
    </style>
    <script type="text/javascript">
        alert('hi');
        setTimeout(function(){
            $('#chatnotification').addClass('on');
        }, 3000);
        hideChatNotification(){
            $('#chatnotification').removeClass('on');
        }
    </script>

</head>

<body style="margin:0px;padding:0px;overflow:hidden">
<iframe id="frame1" src="<?php echo SHIKSHA_HOME;?>" frameborder="0" style="overflow:hidden;overflow-x:hidden;overflow-y:hidden;height:100%;width:100%;position:absolute;top:0px;left:0px;right:0px;bottom:0px" height="150%" width="150%" ></iframe>

<span class="primary-chat-icon openChat primary-chat-icon chatWeb hide" onclick="toggleChatWindow();">
    <b class="chatnotification" id="chatnotification">3</b>
      <svg width="39px" height="35px" viewBox="0 0 39 35" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <title>Group</title>
    <desc>Created with Sketch.</desc>
    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Android-Copy-3" transform="translate(-302.000000, -547.000000)">
            <g id="Group" transform="translate(298.486485, 541.223378)">
                <path d="M13.4964902,43.8923322 C15.640972,43.5378675 17.4636838,42.7987853 19.1993097,41.7175578 C19.6346992,41.4463271 20.0508404,41.1637208 20.5429681,40.8111197 C20.6354066,40.7448892 21.4672271,40.1401894 21.6916322,39.9829737 C22.660769,39.3040074 23.2801772,39 24,39 C32.836556,39 40,31.836556 40,23 C40,14.163444 32.836556,7 24,7 C15.163444,7 8,14.163444 8,23 C8,28.2815425 10.5765496,33.127986 14.8196105,36.105983 C16.6877341,37.4171276 16.0539728,40.5505776 13.4964902,43.8923322 Z" id="Oval" stroke="#FFFFFF" stroke-width="2" fill="#00A5B5" transform="translate(24.000000, 25.561091) rotate(24.000000) translate(-24.000000, -25.561091) "></path>
                <path d="M33.6345861,18.182783 L28.8737339,21.7899156 C28.4518267,22.10958 27.870302,22.115043 27.4424637,21.8033614 L22.8613044,18.4659708 C22.325635,18.0757338 21.5750396,18.1936301 21.1848027,18.7292994 C21.1653222,18.7560398 21.1469559,18.7835743 21.1297509,18.8118324 L15.7243903,27.6897808 C15.5520627,27.9728176 15.6418101,28.3419634 15.9248469,28.5142911 C16.1324546,28.6406935 16.3958123,28.6293561 16.5917889,28.4855795 L21.7583167,24.6952001 C22.1743458,24.3899839 22.738911,24.3848848 23.1603851,24.6825366 L27.6205461,27.8323737 C28.1618996,28.2146863 28.910679,28.0857581 29.2929915,27.5444045 C29.308789,27.5220353 29.3238162,27.4991319 29.3380466,27.4757345 L34.5095591,18.9728017 C34.6817527,18.6896833 34.5918304,18.3205801 34.308712,18.1483865 C34.0982478,18.0203815 33.8309286,18.0340212 33.6345861,18.182783 Z" id="Path-2" fill="#FFFFFF" transform="translate(25.148499, 23.278078) rotate(-9.000000) translate(-25.148499, -23.278078) "></path>
            </g>
        </g>
    </g>
</svg>
</span>

<div id="chat-window" class="chat-box">
 <div class="global-chat">
    <div class="chat-box">
      <div class="action-box">
        <div class="header-top-left-image"><img role="presentation" src="https://images.shiksha.ws/pwa/public/images/icons/shk_152x152.png"></div>
<div><span class="head2">Shiksha Assistant</span><span class="bot-status" >Online</span></div>
        <span class="head2">
          <!-- <span class="user-welcome">(Welcome <?php $username;?>)</span> -->
        </span>
        <span class="minimize-me" onclick="toggleChatWindow();$('.chatWeb').removeClass('disapear')"><i class="minimize-icon"></i></span>
        <span class="minimize-me maximize" onclick="toggleChatWindow();$('.chatWeb').removeClass('disapear')"><i class="icono-arrow2-left-up"></i></span>
        
        <span class=""><a class="close-btn" href="javascript:void(0);">X</a></span>
      </div>
      <div class="conversation-area">
          <bot-ui></bot-ui>
      </div>
      <div class="input-box">
        <input type="text" id="chatBox" class="chat-input" name="txtAsk" placeholder="Please provide your query here..." />
      </div>
    </div>
  </div> 
</div>
    <script src="/public/js/vue.min.js"></script>
    <script language="javascript" src="/public/js/botui.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript">

       const maxDataInStorage = <?php echo $configData['maxDataInBrowserStorage'];?>;  // need to add in some constant file
       const loadMoreHistoryDataCount = <?php echo $configData['loadMoreHistoryDataCount'];?>; // need to add in some constant file
       const localStorageKey = "<?php echo $configData['browserStorageKey'];?>"; // need to add in some constant file
       var lastConversationTime = '';

      function openContent(thisObj){
        $(thisObj).addClass("hide");
        $(thisObj).siblings(".answer-content").removeClass("hide-content");
      }
      function toggleChatWindow(){
        // $("#chat-window").animate( { "height": "toggle",opacity:"toggle"} , 200 );      
            $("#chat-window").toggleClass("closeChatWindow");
            $(".primary-chat-icon").toggleClass("hide");
      }
      
      var botui;
      
      $(document).ready(function() {

          window.redirectFrame = function(url){
            $("#frame1").attr("src", url);
          }

          $("#chatBox").select();
    // $(".close-btn").on("click", function(){
    //   $("#chat-window").animate( { "height": "hide", opacity:"toggle"} , 500 );
    // });

    
    
    /*boat initialization*/
    botui = new BotUI('chat-window');

    // show data from storage
    var previousConversationHistory =  fetchDataFromStorage();
    //console.log("Previous history : "+previousConversationHistory);
    if(previousConversationHistory != null){
        var currentHistory = '';

        var addClass = "";
        var showPrompt = 1;
        var historyConversationCount = 0;

        for(var key in previousConversationHistory) {
            historyConversationCount ++;
            currentHistory = previousConversationHistory[key];

            if(historyConversationCount == 1){
                
                //console.log(currentHistory.response.queryTime);
                if(typeof currentHistory !== "undefined" && typeof currentHistory.response !== "undefined" && typeof currentHistory.response.queryTime !== "undefined" && currentHistory.response.queryTime != null && currentHistory.response.queryTime != ""){
                    lastConversationTime = currentHistory.response.queryTime;
                }
            }

            botui.message.human({
                content: currentHistory.userQuery
            });

            if(typeof currentHistory.response.startConversation !== "undefined" && currentHistory.response.startConversation != null){
                botui.message.bot({
                  content: currentHistory.response.startConversation
                });
            }

            var addClass = "";
            if(typeof currentHistory.response.addClass !== "undefined"){
                addClass = currentHistory.response.addClass;
            }

            if(typeof currentHistory.response.answer !== 'undefined' && currentHistory.response.answer != ""){
                botui.message.bot({
                    type: 'html', // this is 'text' by default
                    content: currentHistory.response.answer,
                    cssClass: addClass
                  }).then(function(res) {
                        attachAccordianEvents();
                  });
            }

            if(typeof currentHistory.response.prompts !== "undefined"){
                botui.message.bot({
                    type: 'html', // this is 'text' by default
                    content: currentHistory.response.promptTitle
                });

                botui.action.button({
                    action: currentHistory.response.prompts
                }).then(function (res) { 
                    var quickResponseChat = {
                        content: res.text,
                        value: res.value,
                        type: "quickreply"
                    }
                    sendMessage(quickResponseChat);
                });
                showPrompt = 1;
              }else{
                showPrompt = 0;
              }
        }

        if(showPrompt != 1){
            setTimeout(function(){ clearQuickReply(); }, 200);
        }

        if(historyConversationCount >= maxDataInStorage){
            addLoadMoreHistoryButton();
        }

    }else{
        botui.message.add({
          content: 'Hi <?php echo $username;?>, Good Morning !',
          delay: 500
        }).then(function () {
          botui.message.add({
            content: 'I am your Assistant at Shiksha.com '
          });  
        }).then(function(){
          showInitialSuggestions();
        });
    }

    function addLoadMoreHistoryButton(){
        if($(".load-more-history").length >=1) {
            var offset = $(".load-more-history").offset().top - $(".conversation-area").offset().top - 8;
            console.log(offset);
            if(offset > 0){
                $(".conversation-area").animate({scrollTop: offset}, 0);
            }            
            $(".load-more-history").remove();
        }
        $(".botui-messages-container").prepend('<button type="button" class="botui-actions-buttons-button load-more-history"> Load More</button>');
        setTimeout(function(){ attachLoadMoreHistoryEvents('load-more-history'); }, 200);
    }

    function attachLoadMoreHistoryEvents(loadMoreHistoryClass){
        $(".load-more-history").bind("click", function() { 
            //$(".load-more-history").remove();
            var response = getHistoryData(lastConversationTime, loadMoreHistoryDataCount, "desc");
            showPreviousHistory(response);
        });
    }

    function showPreviousHistory(response){
        //console.log(response);
        //console.log(" in Previous History Function ");
        var conversation = null;
        var addClass = "";

        //console.log("FFFF  "+response);

        if(response == null){
            // add a msg
            $(".botui-messages-container").prepend('<div class="botui-message '+addClass+'"><div class="botui-message-content html">No Previous History</div></div>');
            $(".load-more-history").remove();
        }else{
            for(key in response){
                conversation = response[key];
                lastConversationTime = conversation.response.queryTime;
                addClass = "";

                //console.log("ccc  "+conversation.response.prompts);
                if(typeof conversation.response.prompts !== "undefined"){
                    $(".botui-messages-container").prepend('<div class="botui-message"><div><div class="botui-message-content html"><span>'+conversation.response.promptTitle+'</span></div></div></div>');
                }

                if(typeof conversation.response.addClass !== "undefined" && conversation.response.addClass != null){
                    addClass = conversation.response.addClass;
                }

                if(typeof conversation.response.answer !== 'undefined' && conversation.response.answer != "" && conversation.response.answer != null){
                    $(".botui-messages-container").prepend('<div class="botui-message '+addClass+'"><div class="botui-message-content html">'+conversation.response.answer+'</div></div>');
                }

                if(typeof conversation.response.startConversation !== "undefined" && conversation.response.startConversation != null){
                    $(".botui-messages-container").prepend('<div class="botui-message"><div><div class="botui-message-content text"><span>'+conversation.response.startConversation+'</span></div></div></div>');
                }

                $(".botui-messages-container").prepend('<div class="botui-message"><div><div class="human botui-message-content text"><span>'+conversation['userQuery']+'</span></div></div></div>');
            }
            setTimeout(function(){ attachAccordianEvents(); }, 200);

            addLoadMoreHistoryButton();
        }
            

    }
   
    function showInitialSuggestions(){

      var msg = "";
      $.ajax({
        url: "/common/ChatPlugin/getInitialSuggestions", 
        type:"POST",
        async: true,
        success: function(result){
          result = JSON.parse(result);
          msg = result.answer;
          // botui.message.remove(res);

          botui.message.bot({
            content: result.startConversation,
            delay: 1500,
            loading:true
          }).then(function(){

                botui.action.button({
                  action: result.prompts
                }).then(function (res) { 
                        var quickResponseChat = {
                            content: res.text,
                            value: res.value,
                            type: "quickreply"
                        }
                        sendMessage(quickResponseChat);
                });  
          });

          

        }
      });
   }

    function start_func(msg) {
        var promise = new Promise(function(resolve, reject) {
            console.log(msg);
            var type = msg.type;
            clearQuickReply();
            switch (type.toLowerCase()) {
                case "text":
                    console.log("Text");
                    botui.message.bot({
                        content: msg.content
                    });
                    break;
                case "quickreply":
                    console.log("QR");
                    botui.message.bot({
                        content: msg.content
                    }).then(function() {
                    console.log("QRRRRRRRRRRRRR");

                        return botui.action.button({
                            action: msg.options
                        });
                    }).then(function(res) {
                        console.log("resssssssssss");
                        console.log(res);
                        var quickResponseChat = {
                            content: res.text,
                            value: res.value,
                            type: "quickreply"
                        }
                        alert("aaaaaaaaaaaa");
                        sendMessage(quickResponseChat);
                    });
                    //    removeQRScrollElements();
                    //  appendScrollElements(document.getElementsByClassName("botui-actions-container")[0], "leftQR");
                    //appendScrollElements(document.getElementsByClassName("botui-actions-container")[0], "rightQR");
                    
                    ps2.update();
                    break;
                case "buttons":
                    console.log("button");
                    botui.message.bot({
                        content: msg.content
                    }).then(function() {
                        console.log("button promise");
                        var buttonListContent = document.createElement('div');
                        buttonListContent.className = 'btn-group';
                        for (item in msg.buttons) {
                            var button = document.createElement("BUTTON");
                            button.textContent = msg.buttons[item].text;
                            button.setAttribute("data-payload", item);
                            button.onclick = function() {
                                var buttonPayload = msg.buttons[this.getAttribute("data-payload")];
                                if (buttonPayload.url == undefined) {
                                    botui.message.human({
                                        content: buttonPayload.text
                                    });
                                    var quickResponseChat = {
                                        content: buttonPayload.text,
                                        value: buttonPayload.value,
                                        type: msg.type
                                    }
                                    sendMessage(quickResponseChat);
                                } else {
                                    window.open(buttonPayload.url, "_blank");
                                }
                            };
                            buttonListContent.appendChild(button);
                        }
                        console.log("buttonEsja");
                        console.log(document.getElementsByClassName("botui-messages-container")[0]);

                        document.getElementsByClassName("botui-messages-container")[0]
                            .appendChild(buttonListContent);
                    }).then(function(res) {
                        console.log(res);
                    });

                    break;
                case "cards":
                    console.log("card");
                    var botCardScrollContainer = document.createElement('div');
                    botCardScrollContainer.className = "bot-card-scroll-container";
                    var botCardContainer = document.createElement('div');
                    botCardContainer.className = 'bot-card-container';
                    botCardContainer.id = "card-container" + cardNo;
                    removeCardScrollElements();
                    //   appendScrollElements(botCardScrollContainer, "left");
                    botCardScrollContainer.appendChild(botCardContainer);
                    // appendScrollElements(botCardScrollContainer, "right");

                    for (cardItem in msg.cards) {
                        var jsonCard = msg.cards[cardItem];
                        var card = document.createElement('div');
                        card.className = "bot-card";

                        if (jsonCard.imgURL != undefined && jsonCard.imgURL) {
                            var cardImage = document.createElement('img');
                            cardImage.src = jsonCard.imgURL;
                            card.appendChild(cardImage);
                        }

                        var cardContent = document.createElement('div');
                        cardContent.className = "bot-card-content";
                        cardContent.innerHTML += '<p class="bot-card-content-text title">' + jsonCard.title + '</p>\
                                                        <p class="bot-card-content-text subtitle">' + jsonCard.subtitle + '</p>';
                        var cardButtons = document.createElement('div');
                        cardButtons.className = "bot-card-buttons";
                        for (buttonItem in jsonCard.buttons) {
                            var jsonButton = jsonCard.buttons[buttonItem];
                            var button = document.createElement("BUTTON");
                            button.textContent = jsonButton.text;
                            button.setAttribute("data-button-payload", buttonItem);
                            button.setAttribute("data-card-payload", cardItem);
                            button.onclick = function() {
                                var buttonPayload = msg.cards[this.getAttribute("data-card-payload")]
                                    .buttons[this.getAttribute("data-button-payload")];
                                if (buttonPayload.url == undefined) {
                                    botui.message.human({
                                        content: buttonPayload.text
                                    });
                                    var quickResponseChat = {
                                        cardNumber: this.getAttribute("data-card-payload"),
                                        content: buttonPayload.text,
                                        value: buttonPayload.value,
                                        type: msg.type
                                    }
                                    sendMessage(quickResponseChat);
                                } else {
                                    window.open(buttonPayload.url, "_blank");
                                }
                            };
                            cardButtons.appendChild(button);
                        }
                        cardContent.appendChild(cardButtons);
                        card.appendChild(cardContent);
                        botCardContainer.appendChild(card);
                    }
                    document.getElementsByClassName("botui-messages-container")[0]
                        .appendChild(botCardScrollContainer);
                    var containerId = "#card-container" + cardNo;
                    new PerfectScrollbar(containerId, {
                        wheelSpeed: 2,
                        wheelPropagation: true,
                        minScrollbarLength: 20,
                        swipeEasing: true,
                        scrollingThreshold: 0
                    });

                    cardNo++;
                    break;
                default:
                    botui.message.bot({
                        content: "Unsupported message received"
                    });
            }
            resolve("done!");
        });
    }

    function triggerGetResponse(){

            var enteredChat = $('#chatBox').val();
            if (enteredChat) {
                botui.message.human({
                    content: enteredChat
                });
                var userChat = {
                    content: enteredChat,
                    type: "text"
                }
                sendMessage(userChat);
                console.log(userChat);
                $('#chatBox').val('');
            }
            $('#chatBox').val('')
            return false;
    }

    function clearQuickReply() {
        botui.action.hide();
    }

    function appendScrollElements(container, id1) {
        var scrollelement = document.createElement('div');
        scrollelement.id = id1;
        scrollelement.innerHTML += '<i class="arrow "' + id1 + '"></i>';


        container.appendChild(scrollelement);

    }

    function removeQRScrollElements() {
        $('.botui-actions-container #leftQR').remove();
        $('.botui-actions-container #rightQR').remove();

    }

    function removeCardScrollElements() {
        $('.bot-card-scroll-container #left').remove();
        $('.bot-card-scroll-container #right').remove();

    }

    function sendMessage(messagePayload) {
        clearQuickReply();
        var i = botui.message.bot({
            loading: true
        }).then(function(res) {
            var objDiv = $(".conversation-area");
            $(objDiv).prop("scrollTop",$(objDiv).prop("scrollHeight"));

            msg = getResponse(res, messagePayload);
        });
    }

    function getResponse(res, messagePayload){

      var msg = "";
      $.ajax({
        url: "/common/ChatPlugin/getResponse", 
        type:"POST",
        async: true,
        headers: {
          "Authrequest": "INFOEDGE_SHIKSHA"
        },
       
        data: {'question':messagePayload.content, 'value':messagePayload.value, 'type':messagePayload.type},
        success: function(result){
          result = JSON.parse(result);
          storeDataInSessionStorage(result, messagePayload);

          msg = result.answer;

          botui.message.remove(res);

          if(typeof result.startConversation !== "undefined"){
            botui.message.bot({
              content: result.startConversation
            });
          }
          var addClass = "";
          if(typeof result.addClass !== "undefined"){
            addClass = result.addClass;
          }


         if(typeof msg !== 'undefined' && msg != ""){          
              botui.message.bot({
                type: 'html', // this is 'text' by default
                content: msg,
                cssClass: addClass
              }).then(function(res) {
                    attachAccordianEvents();
                    scrollAnswerToView();
              });
          }

          if(typeof result.prompts !== "undefined"){

                botui.message.bot({
                  type: 'html', // this is 'text' by default
                  content: result.promptTitle
                });
                botui.action.button({
                  action: result.prompts,
                  cssClass: result.promptClass
                }).then(function (res) { 
                        var quickResponseChat = {
                            content: res.text,
                            value: res.value,
                            type: "quickreply"
                        }
                        sendMessage(quickResponseChat);
                });
                setTimeout(function(){
                    showFewRecommendations(6);
                },200);
          }

          if(typeof(result.redirecturl) != "undefined" && result.redirecturl != ""){
            window.redirecturl = result.redirecturl;
            console.log(result.redirecturl);
            window.redirectFrame(result.redirecturl);
          }

          // var objDiv = $(".conversation-area");
          // $(objDiv).prop("scrollTop",$(objDiv).prop("scrollHeight"));
          //setTimeout(function(){ $(".conversation-area").prop("scrollTop", $(".conversation-area").offset().top+$(".botui-message-content.human").last().offset().top-$(".conversation-area").height()) }, 500);

          
          // $(".conversation-area").prop("scrollTop", $(".botui-message-content.human:last").position().top-50)
        }
      });

      return msg;

    }

    var ps = null,ps2 = null;

    function initializeBoatSider(){
      ps = new PerfectScrollbar('.botui-container', {
          wheelSpeed: 2,
          wheelPropagation: true,
          minScrollbarLength: 20,
          swipeEasing: true,
          scrollingThreshold: 0
      });
      ps2 = new PerfectScrollbar('.botui-actions-container', {
          wheelSpeed: 2,
          wheelPropagation: true,
          minScrollbarLength: 20,
          swipeEasing: true,
          scrollingThreshold: 0
      });

    }

    /*chat input*/
    $('.chat-input').on('keypress', function(e) {
        if (e.which == 13) {
            return triggerGetResponse();
        }
    });

    $("#leftQR").click(function() {
        var leftPos = $(".botui-actions-container").scrollLeft();
        $(".botui-actions-container").animate({
            scrollLeft: leftPos - 150
        }, 500, "linear");
    });
    $("#rightQR").click(function() {
        var rightPos = $(".botui-actions-container").scrollLeft();
        $(".botui-actions-container").animate({
            scrollLeft: rightPos + 150
        }, 500, "linear");

    });

    $("#left").click(function() {
        var leftPos = $(containerId).scrollLeft();
        $(containerId).animate({
            scrollLeft: leftPos - 250
        }, 500, "linear");
    });

    $("#right").click(function() {
        var leftPos2 = $(containerId).scrollLeft();
        $(containerId).animate({
            scrollLeft: leftPos2 + 250
        }, 500, "linear");
    });

    /*initializing boat slider*/
    // initializeBoatSider();

});

function scrollAnswerToView(){
    var offset = $(".human.botui-message-content:last").offset().top-$(".botui-message-content:first").offset().top;
    if(offset > 0){
        $(".conversation-area").animate({scrollTop: offset}, 400);
    }
}


function fetchDataFromStorage(){
    var conversionHistory = null;
    var lastDocTime = null;
    var getDataFromBackend = false;
    if(isStorageSupported()){
        var previousConversationHistory = localStorage.getItem(localStorageKey);
        var sessionId = getSessionCookie("visitorSessionId");
        if(previousConversationHistory != null){
            previousConversationHistory = JSON.parse(previousConversationHistory);
            if(previousConversationHistory['sessionId'] != sessionId){
                // clear data 
                localStorage.removeItem(localStorageKey);
                getDataFromBackend =true;
            }else{
                conversionHistory = JSON.parse(previousConversationHistory['previousConversationHistory']);
            }
        }else{ // get data from backend API  and save to locale storage
            getDataFromBackend =true;
        }
    }else{ // get data from backend API
        getDataFromBackend =true;
    }

    if(getDataFromBackend == true){
        conversionHistory = getHistoryData(lastDocTime, maxDataInStorage, "asc");
        saveDataForStroage(conversionHistory);
    }
    return conversionHistory;
}

function saveDataForStroage(conversionHistory){
    if(conversionHistory != null){
        // save data in storage
        var previousConversationHistory = {};
        var i=0;
        for(var key in conversionHistory) {
            previousConversationHistory[i] = conversionHistory[key];
            i++;
            if(i >= maxDataInStorage){
                break;
            }
        }

        previousConversationHistory = JSON.stringify(previousConversationHistory);
        var sessionId = getSessionCookie("visitorSessionId");
        var cacheData = JSON.stringify({"sessionId":sessionId,"previousConversationHistory":previousConversationHistory});
        localStorage.setItem(localStorageKey , cacheData);
    }
}

function getSessionCookie(c_name){
    if (document.cookie.length>0){
        c_start=document.cookie.indexOf(c_name + "=");
        if (c_start!=-1){
            c_start=c_start + c_name.length+1;
            c_end=document.cookie.indexOf(";",c_start);
            if (c_end==-1) { c_end=document.cookie.length ; }
            return unescape(document.cookie.substring(c_start,c_end));
        }
    }
    return "";
}


function getHistoryData(lastDocTime, count, order){
    var conversionHistory = null;
      $.ajax({
        url: "/common/ChatPlugin/getUserConversationHistory", 
        type:"POST",
        async: false,
        data: {'lastDocTime':lastDocTime, 'count':count, 'order':order},
        success: function(result){
          conversionHistory = JSON.parse(result);
        }
    });
      return conversionHistory;
}

function storeDataInSessionStorage(apiResult, messagePayload){
    var result = {"userQuery":messagePayload.content, "response":apiResult};
    // check if session storage support
    if(isStorageSupported()){
        // get previous data from is any.
        var previousConversationHistory = localStorage.getItem(localStorageKey);

        if(previousConversationHistory != null){  // data present in storage
            //alert(12);
            //console.log(previousConversationHistory);
            previousConversationHistory = JSON.parse(previousConversationHistory);
            previousConversationHistory = JSON.parse(previousConversationHistory['previousConversationHistory']);
            var currentDataSize = 0;

            for(var key in previousConversationHistory) {
                currentDataSize++;
            }
            //console.log("Current Size : "+currentDataSize);

            if(currentDataSize < maxDataInStorage){  // addand new data
                //console.log(" Size is Less");
                var newIndex = currentDataSize;
                previousConversationHistory[newIndex] = result;
            }else{  // remove old and add new data (like queue structue)
                var i=0;
                for(i=0; i< maxDataInStorage; i++){
                    previousConversationHistory[i] = previousConversationHistory[i+1];
                }

                console.log("New index : "+(i-1));
                previousConversationHistory[i-1] = result;
            }
            previousConversationHistory = JSON.stringify(previousConversationHistory);
            var cacheData = JSON.stringify({"sessionId":getSessionCookie("visitorSessionId"),"previousConversationHistory":previousConversationHistory});
            localStorage.setItem(localStorageKey , cacheData);
        }else{  // no data in storage, adding first data
            //console.log("First data to storage");
            var cacheData = JSON.stringify({"sessionId":getSessionCookie("visitorSessionId"),"previousConversationHistory":JSON.stringify({"0" : result})});
            localStorage.setItem(localStorageKey , cacheData);
        }
    }
}

function isStorageSupported() {
    var testKey = 'test', storage = window.sessionStorage;
    try 
    {
        storage.setItem(testKey, '1');
        storage.removeItem(testKey);
        return true;
    } 
    catch (error) 
    {
        return false;
    }
}


    </script>


</body>

</html>
