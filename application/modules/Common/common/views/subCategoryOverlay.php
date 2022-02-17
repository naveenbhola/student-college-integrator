<div style="width:250px; display:none;position:absolute;z-index:100000000;" id="subCategoryOverlay"  onmouseout="dissolveOverlayHackForIE();this.style.display='none'" onmouseover="this.style.display=''; overlayHackLayerForIE('subCategoryOverlay', document.getElementById('subCategoryOverlay'));">
	<div id="shadow-container">
	  <div class="shadow1">
		 <div class="shadow2">
		    <div class="shadow3">
		       <div class="container subCatgatoryOverlaybrd">
				  <div class="bgCareerOverLay">
				     <div style="margin-top:3px">
						<div class="row" >
						   <div class="lineSpace_10">&nbsp;</div>
							<div class="anchorClass" style="padding:0 5px 5px 10px;" onClick="updateSubCategoryDD('<?php echo $allCategoryId; ?>', 'All Categories');">
							 	<a href="#" onclick="return false;" title="All Categories">All Categories</a>
							</div>


						   <?php
                            $otherElementId = '';
							global $selectedSubCategoryText ;
						      	foreach($subCategories as $subCategory) { 
								$subCategoryId = $subCategory['boardId'];
								$subCategoryName = $subCategory['name'];
								
                                if($subCategoryId == $categoryId) {
									$selectedSubCategoryText = $subCategoryName;
								}
								if(strpos($subCategoryName,'Others..') !== false){
									$otherElementId = $subCategoryId ;
									continue;
								}
					      	?>
						      <div class="anchorClass" style="padding:0 5px 5px 10px;" onClick="updateSubCategoryDD('<?php echo $subCategoryId; ?>', '<?php echo $subCategoryName; ?>');">
							 	<a href="#" onclick="return false;" title="<?php echo $subCategoryName; ?>"><?php echo $subCategoryName; ?></a></div>
						    <?php 
							    } 
    							if($selectedSubCategoryText == '') {
							    	$selectedSubCategoryText = 'All Categories'; 
	    						}
                                if($otherElementId != '') {
					      ?>
						      <div class="anchorClass" style="padding:0 5px 5px 10px;" onClick="updateSubCategoryDD('<?php echo $otherElementId; ?>', 'Others...');">
							 	<a href="#" onclick="return false;" title="Others...">Others...</a></div>
                          <?php
                                }
                          ?>
						      <div class="clear_L"></div>
						   </div>
						</div>
				     </div>
				  </div>
		       </div>
		    </div>
		 </div>
	  </div>
</div>
