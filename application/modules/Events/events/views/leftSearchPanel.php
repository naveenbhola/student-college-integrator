	<!--Start_Left_Panel-->
	<div style="display:block; width:154px; margin-left:5px; margin-right:0px; float:left;">
				
			<!--Course_TYPE-->
			<div class="raised_blue_nL"> 
				<b class="b2"></b>
				<div class="boxcontent_nblue">
					<div class="row_blue tpBrd_nblue"><img src="/public/images/related_reviewIcon.jpg" align="absmiddle" width="28" height="24"/> Categories</div>
					<div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
					<div class="row">
						<div class="row_blue1" id="categoryTreeSpace">
							<input type="hidden" name="category_id" id="category_id" value="1"/>
							<?php $treeRoot = 'Root'; ?>
							<div class="deactiveselectCategory
" id="<?php echo $treeRoot;?>">
								<script>
									document.writeln(generateTree(categoryTreeMain,'<?php echo $treeRoot;?>','<?php echo $treeRoot;?>')); 
								</script>
							</div>
						</div>
					</div>
					<div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>             
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>			
			</div>
			<!--End_Course_TYPE-->
	</div>
	<!--End_Left_Panel-->
