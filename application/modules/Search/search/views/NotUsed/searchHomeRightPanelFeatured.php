		<?php if(count($results)>0) {?>
		<div class="raised_sky"> 
			<!--b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b-->
			<div class="boxcontent_skySearch">			  
			  <div class="mar_full_5p">
			  		<div class="lineSpace_5">&nbsp;</div>
			  		<div class="normaltxt_11p_blk arial" align="center">
			  			<span class="fontSize_14p">Featured Colleges</span>
			  		</div>
					<div class="lineSpace_10">&nbsp;</div>
					<?php
					
						foreach($results as $result){
					?>
					<div align="center">
						<a href="<?php echo $result['url']; ?>" ><img src="
						<?php
							echo $result['imageUrl'];
						?>" align="absmiddle" border="0"/>
						</a>
					</div>
					<div class="lineSpace_10">&nbsp;</div>
					<?php } ?>
			  </div>
			</div>
			<!--b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b-->
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<?php } ?>
