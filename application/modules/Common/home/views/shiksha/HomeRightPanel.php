	<!--Start_Right_Panel-->
	<div id="right_Panelhome" style="width:300px">
		<?php 
			if($validateuser == "false") { 
            if(JOINONHOME == "yes")
				$this->load->view('home/shiksha/joinPanel'); 
            else
				$this->load->view('home/shiksha/HomeRightPanelWhy'); 
			} else {
				$this->load->view('home/shiksha/HomeRightPanelWhyLogin'); 
			}
		?>
		<?php if($validateuser != "false") $this->load->view('home/shiksha/HomeRightPanelAd');?>
		<?php //$this->load->view('home/shiksha/HomeRightPanelTestPrepAds');?>
		<div class="lineSpace_10">&nbsp;</div>	
		<?php $this->load->view('home/shiksha/HomeRightPanelKumKumSection');?>
		<div class="lineSpace_10">&nbsp;</div>	
		<div class="lineSpace_10">&nbsp;</div>	
		<div class="lineSpace_10">&nbsp;</div>	
	</div>
	<!--End_Right_Panel-->
	
	
