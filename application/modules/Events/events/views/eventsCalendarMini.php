	<link href="/public/css/eventsMini.css" rel="stylesheet" type="text/css"></link>
	<script language="javascript" src="/public/js/eventsMini.js"></script>
		<!--Start_Mid_Panel-->
		<div id="eventsListOverlay" class="raised_calender_popup eventsListOverlay">
            <!--<b class="b1"></b>--><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <div class="boxcontent_calender">
	            <div style="height:40px; background-color:#B6D7EA padding:10px;">
	            	<div class="inline" style="margin:10px;">
	            		<span style="float:right;margin-right:10px;">
		            		<img alt="Close" src="/public/images/close.gif" onClick="javascript:hideEventsListing();"/>
		            	</span>
		            	<span id="popupDay" class="popupDate">&nbsp;</span> &nbsp; 
		            	<span id="popupMonth" class="popupDate">&nbsp;</span> &nbsp; 
		            	<span id="popupYear" class="popupDate">&nbsp;</span>&nbsp;
		            </div>
		            
	            </div>
				<div id="eventsList" class="eventsList"></div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b> 
        </div>
 
 
 
		<div class="row" style="height:135px">
			<div align="center" style="padding: 2px 2px 2px 2px; border:1px solid #B6D7EA; width:127px; margin-left: 10px; margin-right: 10px;">
				<div style="width:127px;">
					<div style="height:20px; background-color:#B6D7EA">
						<div style="padding-top:3px;padding-left:3px; font-size:9px;text-align:left">
							<span style="width:100px; display:block; float:left;"><span id="MonthPlace" class="bld" >&nbsp;</span>
							<span id="YearPlace"  class="bld">&nbsp;</span> &nbsp;
							</span>
							<span style="display:block; float:left;">
								<img alt="Previous Year" src="/public/images/backarrow.gif" border="0" onClick="javascript:MonthChanged(-1);"/><img alt="Next Month" src="/public/images/forward.gif" border="0" onClick="javascript:MonthChanged(1);"/>
							</span>
						</div>
					</div>

					<!--Main Calendar Panel Starts-->
					<script>
						document.writeln( generateCalendarHTML() );	
					</script>
					<!-- Main Calendar Panel Ends --> 
				</div>
				
			</div>
					<!-- ADD EVENET BUTTON Starts-->				        	

<?php if(!(is_array($validateuser) && $validateuser != "false")) { ?>
<div class="buttr2" style="margin-left:10px;" name = "add" id = "add" onclick="javascript:showuserOverlay(this,'add');">
<?php } else { ?>
<div class="buttr2" style="margin-left:10px;"  name = "add" id = "add" onclick="javascript:showAddEvents(this);">
<?php } ?>  
<!--			        	<div class="buttr2"  onclick="javascript:showAddEvents(this);" style="margin-left:10px;">-->
							<button class="btn-submit5 w3" value="" type="button" >
							<div class="btn-submit5"><p class="btn-submit6">Add an Event</p></div>
							</button>
						</div>
						<!-- ADD EVENET BUTTON Ends-->
    </div>
<script>
	var SITE_URL = '<?php echo base_url() ."/";?>';
	
	var TodaysDate = new Date();
	TodaysDate.setFullYear( <?php echo $today['year']; ?>, <?php echo $today['mon']; ?> - 1, <?php echo $today['mday']; ?> );			
	var SelectedDate = TodaysDate;
	var OriginalDate = TodaysDate;
	var calendarMonth = TodaysDate.getMonth() + 1; 
	var calendarYear = TodaysDate.getYear() +1900 ;
	Initialize();
</script>
<!--<script>document.title='Event Calendar';</script>-->
