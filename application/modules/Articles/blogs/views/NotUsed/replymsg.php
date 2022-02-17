<?php if($flag==1): ?>
		<div style="float:left; width:100%; display:inline; margin-left:<?php echo $dotCount*4; ?>%; ">
			<div class="commentImg float_L" style="display:inline;"><img src="/public/images/nitin_pic.jpg" width="57" height="57" /></div>
			<div class="commentImg2 float_L" style="display:inline"><img src="/public/images/commentArrow.gif" style="padding-top:10px; position:relative"/></div>
				<div style="margin-left:-1px; border: 0px solid #000000; float:left; width:<?php echo (80 - $dotCount*4); ?>%">
					<div class="raised_organgen"> 
						<b class="b1n"></b><b class="b2n"></b><b class="b3n"></b><b class="b4n"></b>
							<div class="boxcontent_organgen">
												
								<div class="lineSpace_11">&nbsp;</div>
								<div class="resDescription1 pd_lft_rgt" style="margin-top:0px;"><?php echo $text; ?></div>
								
								<div class="lineSpace_11">&nbsp;</div>
				<div class="btmLink">
				<span class="grayFont">&nbsp;&nbsp; 
				 <?php echo date("F j, Y, g:i a"); ?> </span>&nbsp;&nbsp;
		  				 </div>
				
							</div>				
						<b class="b4bn"></b><b class="b3bn"></b><b class="b2bn"></b><b class="b1bn"></b> 
					</div>
				</div>
			</div>
		
			<div class="clear_L"></div>
		 <div class="lineSpace_10">&nbsp;</div>	
		<div id="<?php echo $parentId;  ?>"></div>
<?php else: echo $flag; ?>
	
<?php endif; ?>
