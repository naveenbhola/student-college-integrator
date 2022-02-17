<?php
            global $criteriaArray;
	        $bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_SMALLSCRAPPER', 'shikshaCriteria' => $criteriaArray);
            $panelCaption = 'Featured Institutes';
            if($categoryData['page'] == 'FOREIGN_PAGE') { $panelCaption = '<span style="font-size:11px;">Featured Consultants</span>'; }
 ?><div>
		<div class="raised_gray">					
			<b class="b2" style="background:##F7F7F7"></b><b class="b3" style="background:#FFFFFF"></b><b class="b4" style="background:#FFFFFF"></b>
				<div class="boxcontent_gray" style="line-height:25px; background:#FFFFFF">
					<div style="background:url(/public/images/bgFeaturedHeadingImg.gif) repeat-x left bottom"><span style="padding-left:10px" class="bld fontSize_12p OrgangeFont"><?php echo $panelCaption; ?></span></div>
				</div>
			<b class="b1b" style="background:#D2DAEF; margin:0"></b>
		</div>
	</div>	
	<div style="text-align:center; border:1px solid #E2E2E2;border-top:none">
        <div class="lineSpace_10">&nbsp;</div>
		<?php $this->load->view('common/banner.php', $bannerProperties);?>
	</div>

