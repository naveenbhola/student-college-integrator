	<!--Start_Right_Panel-->
	<div id="right_Panel_n" style="width:447px">
		<div style="position:absolute;margin-left:-23px;margin-top:95px;">
    		<img src="/public/images/calenderArrow.gif" border="0"/>
	    </div>	
		<!--Start_Widget_1-->
		<div>
			<?php $this->load->view('events/eventCalendarPanel'); ?>
		</div>
		<!--End_Widget_1-->
		<div class="lineSpace_16">&nbsp;</div>
		<?php $this->load->view('events/eventUpcomingPanel'); ?>
		<div class="lineSpace_16">&nbsp;</div>
        <?php  $this->load->view('common/inviteFriendsWidget'); ?>
		<div class="lineSpace_16">&nbsp;</div>

		<!--Start_Widget_2-->
		<div>
			<?php $this->load->view('events/eventAlertPanel');	?>		
		</div>
		<!--End_Widget_2-->
		<div class="lineSpace_10">&nbsp;</div>
	</div>
	<!--End_Right_Panel-->
