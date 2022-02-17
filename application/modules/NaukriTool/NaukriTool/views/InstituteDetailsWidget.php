<?php
		if(is_array($institutedetailsdata) && count($institutedetailsdata)) { ?>
		<div class="naukri-tool-widget clear-width">
                        <strong class="font-18">More Choice of MBA college for you </strong>
			<div class="choices-criteria">
			<?php $i = 0; ?>
			<?php foreach($institutedetailsdata as $sectionNo => $sections) { $i++;	 ?>
                        <div class="choice-col <?php if($i == count($institutedetailsdata)){?> last <?php } ?>">
			
				<?php
					$cntvar = 0;
					foreach($sections as $key => $sectionInfo) {
						
						$section_url = urldecode($sectionInfo['landinURL']);
						$sections_landing_url = urldecode($sectionInfo['sectionURL']);
						
						if(strpos($section_url,"http://") === FALSE && strpos($section_url,"https://") === FALSE) {
							$section_url = "http://".$section_url;									
						}
						
						if(strpos($sections_landing_url,"http://") === FALSE && strpos($sections_landing_url,"https://") === FALSE) {
							$sections_landing_url = "http://".$sections_landing_url;									
						}
						
						if($sectionInfo['open_new_tab'] == "YES") {
							$targetTag = 'target="_blank"';
						} else {
							$targetTag = '';
						}
						
						if($cntvar++ == 0) { ?>
							<h3><?=$sectionInfo['sectionHeading']?></h3>
							<ul><?php
						}
						?>
						<li><a href="<?=$section_url?>" <?=$targetTag?>><?=$sectionInfo['linkTitle']?></a></li>
						<?php						
					} ?>
                            		</ul>

                                        <?php if(!empty($sectionInfo['sectionURL'])): ?>
	    				<a target="_blank" style="margin-right:15px;" class="flRt font-12" href="<?=$sections_landing_url;?>" title="<?php echo $sectionInfo['sectionHeading'];?>">More >></a>
					<?php endif; ?> 

                            
				</div>
			<?php } ?>
                 </div>
	</div>		

		
<?php } ?>
