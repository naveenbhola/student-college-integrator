<style>
.raised_skyWhite {background: transparent; } 
.raised_skyWhite  .b1, .raised_skyWhite  .b2, .raised_skyWhite  .b3, .raised_skyWhite  .b4, .raised_skyWhite  .b1b, .raised_skyWhite  .b2b, .raised_skyWhite  .b3b, .raised_skyWhite  .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_skyWhite  .b1, .raised_skyWhite  .b2, .raised_skyWhite  .b3, .raised_skyWhite  .b1b, .raised_skyWhite  .b2b, .raised_skyWhite  .b3b {height:1px;} 
.raised_skyWhite  .b2 {background:#6198BE; border-left:1px solid #6198BE; border-right:1px solid #6198BE;} 
.raised_skyWhite  .b3 {background:#FFFFFF; border-left:1px solid #6198BE; border-right:1px solid #6198BE;} 
.raised_skyWhite  .b4 {background:#FFFFFF; border-left:1px solid #6198BE; border-right:1px solid #6198BE;} 
.raised_skyWhite  .b4b {background:#FFFFFF; border-left:1px solid #6198BE; border-right:1px solid #6198BE;} 
.raised_skyWhite  .b3b {background:#FFFFFF; border-left:1px solid #6198BE; border-right:1px solid #6198BE;} 
.raised_skyWhite  .b2b {background:#FFFFFF; border-left:1px solid #6198BE; border-right:1px solid #6198BE;} 
.raised_skyWhite  .b1b {margin:0 5px; background:#6198BE;} 
.raised_skyWhite  .b1 {margin:0 5px; background:#ffffff;} 
.raised_skyWhite  .b2, .raised_skyWhite  .b2b {margin:0 3px; border-width:0 2px;} 
.raised_skyWhite  .b3, .raised_skyWhite  .b3b {margin:0 2px;} 
.raised_skyWhite  .b4, .raised_skyWhite  .b4b {height:2px; margin:0 1px;} 
.raised_skyWhite  .boxcontent_skyWhite {display:block; background-color:#FFFFFF; border-left:1px solid #6198BE; border-right:1px solid #6198BE;} 
</style>
<?php 
	$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
	echo "<script language=\"javascript\"> ";
	echo "var currentCategoeySelected = '1';";
	echo "var COMPLETE_INFO = ".$quickSignUser.";";	
      	echo "var URLFORREDIRECT = '".base64_encode(site_url('events/Events/index'))."';";	
	echo "</script> ";	
	$this->load->view('events/eventAlertForm'); ?>		
		<div  class="raised_skyWhite float_L row">

				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b> 

				<div class="boxcontent_skyWhite">

					<div style="padding:2px 5px 2px 5px">

						<div class="raised_skyWithBGW">

							<b class="b2"></b><b class="b3"></b><b class="b4"></b> 

							<div id="widgetHolder" class="boxcontent_skyWithBGW" style="height:140px;">
								

							</div>
							<div id="status_msg"></div>

							<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>		

						</div>												

					</div>			    	

				</div>

				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>		

			</div>

			<div class="lineSpace_10">&nbsp;</div>
<script>
function setEventAlertOverlay(currentCategory)
{
	if(COMPLETE_INFO == 1)
	{
		location.replace('/user/Userregistration/index/'+URLFORREDIRECT+'/1');
		return false;
	}
	var overlayWidth = 550;
	var overlayHeight = window.screen.height/2;
	var overlayTitle = 'Create an Alert';
	var leftCategorySelected = 0;
	alertId = '';	
	document.getElementById('alertId').value = alertId;	
    if(getCookie('user') != '')
    {
        var cookie = (getCookie('user')).split('|');
        document.getElementById('alertemail').innerHTML = 'Email ' + cookie[0];
    }
	var overLayForm = document.getElementById('eventAlertForm').innerHTML;
	document.getElementById('eventAlertForm').innerHTML = '';
	overlayContent = overLayForm;
	overlayParent = document.getElementById('eventAlertForm'); // Global variable For all the parent overlay contents;
	
	showOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent); 
	
}

function updateWidget(alertType,categoryId)
{
		var redirectUrl =  SITE_URL +'events/Events/index';
		var msg = '';
		if(categoryId != 1)
			msg = 'for category '+completeCategoryTree[categoryId][0];
			
		var xmlHttp = getXMLHTTPObject();
		xmlHttp.onreadystatechange=function()
		{	

		if(xmlHttp.readyState==4)
		{ 		
			var alertResult =  eval("eval("+xmlHttp.responseText+")");
			if((alertResult.result == 0)||(alertResult.loggedIn == 0))
			{
		document.getElementById('widgetHolder').innerHTML = '<div class="lineSpace_20">&nbsp;</div>\
							<div style="background:url(/public/images/eventbell.gif) no-repeat; padding:0px 20px 30px 115px;background-position:0 0px;">\
									<span class="normaltxt_11p_blk fontSize_14p bld lineSpace_20">\
										Set An Email Alert</span><br />\
										<span class="normaltxt_11p_blk lineSpace_16" id="alertLabel">\
											Press Subscribe button below to receive an email alert if a new event is posted '+msg+'.</span>\
								</div>\
								<div style="margin-left:10px; margin-top:-20px" id="subscribeButton">';
							if(alertResult.loggedIn == 1)
							{
	document.getElementById('widgetHolder').innerHTML += '<button class="btn-submit13 w3" onClick="javascript:setEventAlertOverlay('+categoryId+')" style="margin-left:115px" id="subscribeAlertBtn">\
						<div class="btn-submit13"><p id="submitbutton3" class="btn-submit14 btnTxtBlog">Subscribe</p></div>\
							</button> <a href="#" name="collegeRating"></a>';
							}
							else
							{
	document.getElementById('widgetHolder').innerHTML += '<button class="btn-submit13 w3" onClick="showuserLoginOverLay(this,\'EVENTS_EVENTSHOME_RIGHTPANEL_SUBSCRIBEALERT\',\'jsfunction\',\'setEventAlertOverlay\',\''+categoryId+'\');"  style="margin-left:115px" id="subscribeAlertBtn">\
						<div class="btn-submit13"><p id="submitbutton3" class="btn-submit14 btnTxtBlog">Subscribe</p></div>\
							</button>';
							}	
	document.getElementById('widgetHolder').innerHTML += '</div>';
			}
			else
			{	
				var str = '<div class="lineSpace_20">&nbsp;</div>\
						<img src="/public/images/eventbell.gif" align="left" style="margin-right:20px" />\						<span class="normaltxt_11p_blk fontSize_14p bld lineSpace_20">\
						Set An Email Alert</span><br />\
						<span class="normaltxt_11p_blk lineSpace_16" id="alertLabel">\
						</span>\
						<div class="clear_L"></div>\
						<div style="margin-left:115px" id="subscribeButton">';
						if(alertResult.state == 'off')
						{	
							str= str + '<button class="btn-submit13 w3" onClick="javascript:subscribeAlert('+alertResult.alert_id+',\'on\');" style="margin-left:115px" id="subscribeAlertBtn">\
								<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Subscribe</p></div>\
							</button>';
			var alertLabel =  'Press Subscribe button below to receive an email alert if a new event is posted for category '+completeCategoryTree[currentCategoeySelected][0]+'.';
						}
						else
						{
							str= str + '<button class="btn-submit13 w3" onClick="javascript:subscribeAlert('+alertResult.alert_id+',\'off\');" style="margin-left:115px" id="subscribeAlertBtn">\
								<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Unsubscribe</p></div>\
							</button>';
							var alertLabel = 'You are registered to receive an email alert if a new event is posted for category '+completeCategoryTree[currentCategoeySelected][0]+'. Press Unsubscribe button below to remove this  alert';
						}				
						str = str + '</div>\
					  <div id="status_msg" class="normaltxt_11p_blk mar_full_10p"  style="margin-left:10px;"></div>';
			 document.getElementById('widgetHolder').innerHTML = str;
			 document.getElementById('alertLabel').innerHTML = alertLabel;
			}
		}
		};
		var url = SITE_URL + '/alerts/Alerts/getWidgetAlert/6/'+alertType+'/'+categoryId;						
		xmlHttp.open("POST",url,true);
		xmlHttp.send(null);		
	
}

function updateEventHomePage(responseText)
{
	response = eval("eval("+responseText+")");

	if(response.result != 0)
	{
		document.getElementById('eventAlertForm_error').innerHTML = response.error_msg;
	}
	else
	{
        anotheraction = 1;
        hideOverlay();
		var str = "You have successfully subscribed the alert";
		commonShowConfirmMessage(str);
		if(currentCategoeySelected != 1)
		{
		var str = '<div class="lineSpace_20">&nbsp;</div>\
								<img src="/public/images/eventbell.gif" align="left" style="margin-right:20px" />\								<span class="normaltxt_11p_blk fontSize_14p bld lineSpace_20">\
								Set An Email Alert</span><br />\								<span class="normaltxt_11p_blk lineSpace_16" id="alertLabel">\
								You are registered to receive an email alert if a new event is posted for category '+completeCategoryTree[currentCategoeySelected][0]+'. Press Unsubscribe button below to remove this alert.</span>\								<div class="clear_L"></div>\									<div style="margin-left:115px" id="subscribeButton">';
								
							
	str += '<button class="btn-submit13 w3" onClick="javascript:subscribeAlert('+response.alert_id+',\'off\');" style="margin-left:115px" id="subscribeAlertBtn">\
						<div class="btn-submit13"><p id="submitbutton3" class="btn-submit14 btnTxtBlog">Unsubscribe</p></div>\
							</button> <a href="#" name="collegeRating"></a></div>';	
		document.getElementById('widgetHolder').innerHTML = str;
		}
	}
}

function subscribeAlert(alertTopicId,alertNameValue,categoryId)
{
	var xmlHttp = getXMLHTTPObject();
	   xmlHttp.onreadystatechange=function()
	   {
	    if(xmlHttp.readyState==4)
	     { 		
	      if(trim(xmlHttp.responseText) != "")
	      {
		var alertResult =  eval("eval("+xmlHttp.responseText+")");
		if(alertResult.loggedIn != 0)
		{
			if((typeof(alertResult.state) != 'undefined') && (alertResult.result == 0))
			{
				if(alertResult.state == 'on')
				{
				document.getElementById('subscribeButton').innerHTML = '<button class="btn-submit13 w3" onClick="javascript:subscribeAlert('+alertTopicId+',\'off\');" style="margin-left:115px" id="subscribeAlertBtn">\
										<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Unsubscribe</p></div>\
									</button>'; 
				document.getElementById('status_msg').innerHTML = 'You have successfully subscribed this alert';
				var alertLabel = 'You are registered to receive an email alert if a new event is posted for category '+completeCategoryTree[currentCategoeySelected][0]+'. Press Unsubscribe button below to remove this alert.';
				}
				else
				{
				document.getElementById('subscribeButton').innerHTML = '<button class="btn-submit13 w3" onClick="javascript:subscribeAlert('+alertTopicId+',\'on\');" style="margin-left:115px" id="subscribeAlertBtn">\
										<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Subscribe</p></div>\
									</button>';
				document.getElementById('status_msg').innerHTML = 'You have successfully unsubscribed this alert';
				var alertLabel =  'Press Subscribe button below to receive an email alert if a new event is posted for category '+completeCategoryTree[currentCategoeySelected][0]+'.';
				}
			}
			
		   window.setTimeout("emptyInnerHtml('status_msg');", 5000);
		   if( document.getElementById('alertLabel'))
			document.getElementById('alertLabel').innerHTML = alertLabel;
			
		   }	
		   else
		   {
	        showuserLoginOverLay(document.getElementById('subscribeAlertBtn'),'EVENTS_EVENTSHOME_RIGHTPANEL_SUBSCRIBEALERT','jsfunction','setEventAlertOverlay',categoryId);
		   }			
	      				
	       }	
	     
	    }
	   };
	
	   if(categoryId=='undefined')
	   {		
	   alertNameValue = -1;	
	   url = SITE_URL +'/alerts/Alerts/setCommentAlert/'+alertTopicId+'/'+alertNameValue+'/'+categoryId;		
	   }
	   else
	   {	
	   url = SITE_URL +'/alerts/Alerts/updateState/'+alertTopicId+'/'+alertNameValue;			
	   }				
	   xmlHttp.open("POST",url,true);
	   xmlHttp.send(null);		
}

function emptyInnerHtml(eleId)
{
	document.getElementById(eleId).innerHTML = '';
}
updateWidget('byCategory',1);
getCitiesForCountry('',2,'Al');
//createCategoryCombo(document.getElementById("country"),"categoryPlaceForAlert");
</script>
