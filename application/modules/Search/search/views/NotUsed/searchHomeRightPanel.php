	<!--End_Right_Panel-->
	<div id="right_Panel_n">
			<?php 
				//$this->load->view('search/searchHomeRightPanelPoll'); 
				//$this->load->view('search/searchHomeRightPanelEvents');
				//$this->load->view('search/searchHomeRightPanelFeatured');
			?><div class="lineSpace_10">&nbsp;</div>
			<div id="featuredColleges" style="height:340px; border: 1px solid rgb(172, 172, 172);"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div align="center">
				<?php
					$bannerProperties = array('pageId'=>'SEARCH', 'pageZone'=>'BIGSCRAPPER','shikshaCriteria'=>array('keyword'=>urlencode($_REQUEST['keyword']),'location'=>urlencode($_REQUEST['location'])));
					$this->load->view('common/banner', $bannerProperties);
				?>
				</div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div id="narrow_ad_unit" class="narrow_ads"></div>
			</div>		
			
                
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div align="center">
				<?php
					$bannerProperties = array('pageId'=>'SEARCH', 'pageZone'=>'MEDIUMSCRAPPER');
					//$this->load->view('common/banner', $bannerProperties);
				?>
				</div>				
			</div>			
	</div>
	<!--End_Right_Panel-->
	
