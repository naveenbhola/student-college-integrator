	<!--End_Right_Panel-->
	<div style="display:block; width:154px; margin-left:0px; margin-right:5px; float:right">
			<!--Start_Contributed-->
			<div class="raised_sky">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b> 
				<div class="boxcontent_sky">
					<div class="lineSpace_11">&nbsp;</div>
                    <?php 
                        $this->load->view('events/eventsCalendarMini'); 
                    ?>
					<div class="lineSpace_11">&nbsp;</div> 
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>		
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			
			
			<div class="raised_sky">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b> 
				<div class="boxcontent_sky">
					<div class="lineSpace_11">&nbsp;</div>
					<div class="recentHeading">Recent Event</div>
					<div class="lineSpace_11">&nbsp;</div>
					<div class="row">
						<div class="row_blue1" id="recentEventsPlace">
				
						</div>
					</div>
					<div class="lineSpace_11">&nbsp;</div> 
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>		
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div align="center"><img src="/public/images/banner_120x240.gif" /></div>
			</div>	
			<!--End_Event_Calender-->
  </div>
    <script>
    		var recentEventsArr = eval(<?php echo $recentEvents; ?>);
    		createEventsList(recentEventsArr, 'recentEventsPlace');
    </script>	<!--End_Right_Panel-->
	
