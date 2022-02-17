    <div class="row">
		<div class="raised_green_h"> 
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_green_h">			  
			  <div style="margin:0 6px">
			  		<div class="lineSpace_3">&nbsp;</div>
			  		<div class="normaltxt_11p_blk arial"><span class="OrgangeFont fontSize_13p">Explore Colleges by location</span></div>
					<div style="line-height:13px">&nbsp;</div>
              </div>
              <div style="margin-left:15px">
					
					<?php 
						global $countries;
                     	foreach($countries  as $countryId => $country) {
							$countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';
							$countryName = isset($country['name']) ? $country['name'] : '';
					?>
					<a href="/shiksha/category/studyabroad/<?php echo $countryId; ?>" class="fontSize_12p">
						<div class="f<?php echo str_replace(' ','',$countryName);  ?>"><?php echo $countryName; ?></div>
					</a>
					
					<div class="lineSpace_15">&nbsp;</div>
					<?php } ?>					
			  </div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
    </div>
