<?php
  $username = "";
  if(!empty($validateuser) && $validateuser != "false"){
    $username = $validateuser[0]['firstname'];
  }
  $cssUrl = getCssUrl();

    $time = date("H");
    $timezone = date("e");
    $dayGreeting = "";
    if ($time < "12") {
        $dayGreeting = "Good morning";
    } else
    if ($time >= "12" && $time < "17") {
        $dayGreeting = "Good afternoon";
    } else
    if ($time >= "17") {
        $dayGreeting = "Good evening";
    }
?>
<!DOCTYPE html>
<html class="fullheight">
<head>
  <meta charset="utf-8">
  <title>Shiksha Answers</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link href="/public/css/botui.min.css" type="text/css" rel="stylesheet" /> -->
  <link href="//<?php echo $cssUrl; ?>/public/css/<?php echo getCSSWithVersion("chatui"); ?>" type="text/css" rel="stylesheet" />
</head>

<body style="margin:0px;padding:0px;overflow:hidden;height: 100%;width: 100%;">

    <div id="chat-window" class="fullheight">
          <div class="conversation-area">
              <div id="init-effect" class="image-div" style="max-width: 100%;display: none;"><div><div class="image"><img src="<?php echo MEDIAHOSTURL;?>/public/images/assistant-first-conv.gif" style="width: 100%;"></div></div></div>
              <bot-ui></bot-ui>
          </div>
          <div class="input-box">
            <span class="send-button"><svg fill="#008489" viewBox="0 0 24 24" height="20" width="20" class="transition-ease-point-3-s" style="vertical-align: middle; margin-left: 10px;"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg></span>
            <input type="text" id="chatBox" class="chat-input" name="txtAsk" placeholder="Write your query on colleges, exam here..." autocomplete="off" />
          </div>
    </div>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('vue'); ?>"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('chatui'); ?>"></script>
    <script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('jquery-2.1.4.min'); ?>"></script>
    
    <script type="text/javascript">

       const maxDataInStorage = <?php echo $configData['maxDataInBrowserStorage'];?>;  // need to add in some constant file
       const loadMoreHistoryDataCount = <?php echo $configData['loadMoreHistoryDataCount'];?>; // need to add in some constant file
       const localStorageKey = "<?php echo $configData['browserStorageKey'];?>"; // need to add in some constant file
       var lastConversationTime = '';

      function openContent(thisObj){
        $(thisObj).addClass("hide");
        $(thisObj).siblings(".answer-content").removeClass("hide-content");
      }
      // function toggleChatWindow(){
      //   // $("#chat-window").animate( { "height": "toggle",opacity:"toggle"} , 200 );      
      //       $("#chat-window").toggleClass("closeChatWindow");
      // }
      
      var botui;

      function scrollToEndOfChatbox(){
            var objDiv = $(".conversation-area");
            $(objDiv).prop("scrollTop",$(objDiv).prop("scrollHeight"));
        }
      
      $(document).ready(function() {

          window.redirectFrame = function(url){
            // $("#frame1").attr("src", url);
            url = url.replace("www.shiksha.com", "localshiksha.com");
            window.top.location.href = url;
          }

          $("#chatBox").select();
    // $(".close-btn").on("click", function(){
    //   $("#chat-window").animate( { "height": "hide", opacity:"toggle"} , 500 );
    // });

    
    
    /*boat initialization*/
    botui = new BotUI('chat-window');
    var chatBoxPlaceholderText = $("#chatBox").attr("placeholder");

    // show data from storage
    var previousConversationHistory =  fetchDataFromStorage();
    //console.log("Previous history : "+previousConversationHistory);
    if(previousConversationHistory != null){
        initEffect("hide");
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
                
                // botui.message.bot({
                //     type: 'html', // this is 'text' by default
                //     content: currentHistory.response.promptTitle
                // });

                botui.action.button({
                    heading: currentHistory.response.promptTitle,
                    action: currentHistory.response.prompts,
                    cssClass: currentHistory.response.promptClass
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
        else{
            setTimeout(function(){ showFewRecommendations(10); }, 200);
        }

        if(historyConversationCount >= maxDataInStorage){
            addLoadMoreHistoryButton();
        }
        setTimeout(function(){ scrollToEndOfChatbox(); }, 200);


    }else{
        initEffect("show");
        botui.message.add({
          content: 'Hi <?php echo $username;?>, <?php echo $dayGreeting;?> !',
          delay: 500
        }).then(function () {
          botui.message.add({
            content: 'I am your assistant at Shiksha and can help you with answers to your queries on colleges, courses, careers and exams you are interested in.'
          });  
        }).then(function(){
          showInitialSuggestions();
        });
    }

    function addLoadMoreHistoryButton(){
        if($(".load-more-history").length >=1) {
            var offset = $(".load-more-history").offset().top - $(".conversation-area").offset().top - 8;
            if(offset > 0){
                $(".conversation-area").animate({scrollTop: offset}, 0);
            }            
            $(".load-more-history").remove();
        }
        $(".botui-messages-container").prepend('<button type="button" class="botui-actions-buttons-button load-more-history"> Load past conversation</button>');
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
        data: {t:"<?php echo $referrerTitle;?>"},
        cache: false, 
        type:"POST",
        async: true,
        success: function(result){
          result = JSON.parse(result);
          msg = result.answer;
          // botui.message.remove(res);

          botui.message.bot({
            delay: 1500,
            loading:true
          }).then(function(res){
                // remove loader
                botui.message.remove(res);  

                botui.action.button({
                heading: result.startConversation,
                  action: result.prompts
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
          });

          

        }
      });
   }

    function start_func(msg) {
        var promise = new Promise(function(resolve, reject) {
            var type = msg.type;
            clearQuickReply();
            switch (type.toLowerCase()) {
                case "text":
                    botui.message.bot({
                        content: msg.content
                    });
                    break;
                case "quickreply":
                    botui.message.bot({
                        content: msg.content
                    }).then(function() {
                    
                        return botui.action.button({
                            action: msg.options
                        });
                    }).then(function(res) {
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
                    botui.message.bot({
                        content: msg.content
                    }).then(function() {
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
                        
                        document.getElementsByClassName("botui-messages-container")[0]
                            .appendChild(buttonListContent);
                    }).then(function(res) {

                    });

                    break;
                case "cards":
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

    function initEffect(showhideFlag){
        if(showhideFlag == 'show'){
            if($("#init-effect").length > 0){
                $("#init-effect").show();
            }
        }
        else if(showhideFlag == 'hide'){
            if($("#init-effect").length > 0){
                $("#init-effect").hide();
            }
        }
    }

    var refinePrompts = [];
    var refinePromptTitle = "";
    var refinePromptClass = "";
    function sendMessage(messagePayload) {
        clearQuickReply();
        var i = botui.message.bot({
            loading: true
        }).then(function(res) {
            scrollToEndOfChatbox();
            initEffect("hide");

            if(typeof(messagePayload.value) !== undefined && messagePayload.value != 'refine'){
                msg = getResponse(res, messagePayload);
            }
            else if(typeof(messagePayload.value) !== undefined && messagePayload.value == 'refine'){

                setTimeout(function(){
                    // remove loader
                    botui.message.remove(res);
                    // show filters
                    showFilters();    
                }, 1000);
                
            }
        });
    }

    function getResponse(res, messagePayload){

      var msg = "";
      $.ajax({
        url: "/common/ChatPlugin/getResponse", 
        type:"POST",
        async: true,

        data: {'question':messagePayload.content, 'value':messagePayload.value, 'type':messagePayload.type},
        success: function(result){
          result = JSON.parse(result);
          storeDataInSessionStorage(result, messagePayload);

          msg = result.answer;

          botui.message.remove(res);

          if(typeof result.startConversation !== "undefined" && result.startConversation !== null){
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
                    setTimeout(function(){
                        scrollAnswerToView();
                    },200);
                    
              });
          }

          if(typeof result.prompts !== "undefined"){

                if(typeof(result.closureIntent) != "undefined"){

                    // botui.message.bot({
                    //     type: 'html', // this is 'text' by default
                    //     content: "Did you got the answer you were looking for ?"
                    // });
                    botui.action.button({
                        heading: "Did you got the answer you were looking for ?",
                        action: [
                        result.closureIntent,
                        {text: "No help me refine my query further", value: "refine", cssClass: "refine-class"}
                        ],
                        cssClass: "disambiguity"
                    }).then(function (res) { 
                        var quickResponseChat = {
                            content: res.text,
                            value: res.value,
                            type: "quickreply"
                        }
                        sendMessage(quickResponseChat);
                    });

                    refinePrompts = result.prompts;
                    refinePromptTitle = result.promptTitle;
                    refinePromptClass = result.promptClass;
                }
                else{
                    // botui.message.bot({
                    //   type: 'html', // this is 'text' by default
                    //   content: result.promptTitle
                    // });
                    botui.action.button({
                      heading: result.promptTitle,
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
                }
                setTimeout(function(){
                    showFewRecommendations(10);
                    $("#chatBox").attr("placeholder", result.promptTextMessage);

                    // add target blank to all internal links
                    $(".answer-content:last a").each(function(){
                        if(typeof($(this).attr("target")) === 'undefined' || $(this).attr("target") !== '_blank'){
                            $(this).attr("target","_blank");
                        }
                    });
                },100);
                
          }
          else{
            if(typeof chatBoxPlaceholderText != "undefined")
                $("#chatBox").attr("placeholder", chatBoxPlaceholderText);
          }

          if(typeof(result.redirecturl) != "undefined" && result.redirecturl != ""){
            window.redirecturl = result.redirecturl;
            // window.redirectFrame(result.redirecturl);
          }

          attachAccordianEvents();
          // var objDiv = $(".conversation-area");
          // $(objDiv).prop("scrollTop",$(objDiv).prop("scrollHeight"));
          // setTimeout(function(){ $(".conversation-area").prop("scrollTop", $(".conversation-area").offset().top+$(".botui-message-content.human").last().offset().top-$(".conversation-area").height()) }, 500);

          
          // $(".conversation-area").prop("scrollTop", $(".botui-message-content.human:last").position().top-50)
        }
      });

      return msg;

    }

    function showFilters(){

        // title
        // botui.message.bot({
        //   type: 'html',
        //   content: refinePromptTitle
        // });

        // prompts
        botui.action.button({
          heading: refinePromptTitle,
          action: refinePrompts,
          cssClass: refinePromptClass
        }).then(function (res) { 
            var quickResponseChat = {
                content: res.text,
                value: res.value,
                type: "quickreply"
            }
            sendMessage(quickResponseChat);
        });

    }
    var ps = null,ps2 = null;

    /*chat input*/
    $('.chat-input').on('keypress', function(e) {
        if (e.which == 13) {
            return triggerGetResponse();
        }
    });
    $('.send-button').on('click', function(e) {
            return triggerGetResponse();
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

});

function scrollAnswerToView(){
    var offset = $(".conversation-area").scrollTop()+$(".human.botui-message-content:last").offset().top;
    if(offset > 0){
        $(".conversation-area").animate({scrollTop: offset}, 1000);
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
    if(typeof localStorage === 'undefined')
        return false;

    var testKey = 'test', storage = localStorage;//window.sessionStorage;
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
