<!--Start_Right_Panel-->
<div style="float:right; width:305px;">
	<div>
		<?php
        global $criteriaArray;
			$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_MIDDLE', 'shikshaCriteria' => $criteriaArray);
		?>
		<?php 
			$this->load->view('common/banner.php',  $bannerProperties);
		?>
		<div align="right" style="color:#cccccc;margin-top:2px;">Advertisement</div>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
</div>
<!--End_Right_Panel-->
