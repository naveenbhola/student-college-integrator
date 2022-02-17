<!--Start_Event_Calender-->
			<div class="raised_sky">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b> 
				<div class="boxcontent_sky">
					<div class="lineSpace_11">&nbsp;</div>
					<div class="quesHeading">Event Calender</div>
					<div class="lineSpace_11">&nbsp;</div>
					<!--
					<div class="question">Search Event</div>
					<div class="lineSpace_11">&nbsp;</div>
					
					<div class="row">
						<span class="search_txtBox"><input name="txtName" type="text" class="textbox w4" id="txtName" /></span>
						<div class="buttr2">
							<button class="btn-submit5 w2" value="" type="button">
								<div class="btn-submit5"><p class="btn-submit6">Go</p></div>
							</button>
						</div>
						<br clear="left" />			
					</div>
					<div class="lineSpace_10">&nbsp;</div>
					-->
					<?php
						$this->load->view('events/eventAdd'); 
                        $this->load->view('events/eventsCalendarMini'); 
                    ?>
					<div class="row">
						<a href="/events/Events/index"><div class="txtSeeall">See all</div></a>
					</div>	 
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>		
			</div>		
			<!--End_Event_Calender-->
			<div class="lineSpace_10">&nbsp;</div>
