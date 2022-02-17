<div class="wrapperFxd">
  <!--Start_SearchPanel-->
  <div class="bgAandAnsBtmLine">
	      <div class="float_L" style="width:375px">
			<!-- START OF FILE WHICH WILL BE DIFFERENT FOR EVERY MODULE Basically  this will be file and data -->
			<?php $this->load->view('messageBoard/topLeftPanelForAnA',$topLeftSearchPanelFileData); ?>
			<!-- END OF FILE WHICH WILL BE DIFFERENT FOR EVERY MODULE -->
	      </div>
		  <div style="margin-left:375px">
				  <div class="lineSpace_5">&nbsp;</div>
				  <?php 
				      //$this->load->view('home/homePageRightSearchPanel_quesDetail'); 
				      $this->load->view('common/googleSearchBar'); 
				  ?>
		  </div>
		  <div class="clear_L">&nbsp;</div>
		  <div class="lh10"></div>
  <!--End_SearchPanel-->
  </div>
</div>
<div class="lh10"></div>
