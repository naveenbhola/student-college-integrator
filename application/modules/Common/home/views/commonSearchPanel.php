<!-- Search Panel-->
<div style="width:100%">
	<div class="shik_bgSearchPanel_1" style="margin-left:137px">
		<div style="width:100%;height:62px;/*overflow:hidden*/">
		<?php
		$data = array();
		$data['page_type'] = 'study_abroad';
		$this->load->view('home/homePageRightSearchPanel', $data);
		?>
        <!-- <div><div style="margin-left:-4px"><input type="radio" name="searchRadio" onClick = "setSearchType('course')"/> <span>Institutes &amp; Courses</span>&nbsp;&nbsp;&nbsp;<input type="radio" name="searchRadio" onClick = "setSearchType('question')"/>&nbsp;&nbsp;&nbsp;<span>Ask &amp; Answer</span>&nbsp;&nbsp;&nbsp;<input type="radio" name="searchRadio" onclick = "setSearchType('blog')"/> <span>Articles</span></div></div> -->                              
		</div>
     </div>
</div>
<!-- Search Panel-->
