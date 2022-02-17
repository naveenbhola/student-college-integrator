				<div id="categoryCommonOverlay" style="border:1px solid #ABDB47 ;background-color:#E7FfB5; position:absolute;display:none;width:165px" onmouseover="javascript:this.style.display='inline';" onmouseout="this.style.display='none';">
					<?php 
						global $categoryParentMap;
						foreach($categoryParentMap as $categoryName => $category) {
							$categoryId = isset($category['id']) ? $category['id'] : '';
							$categoryURL = isset($category['url']) ? $category['url'] : '';
					?>
						<a href="/shiksha/category/<?php echo $categoryURL; ?>">
							<div style="line-height:20px;padding-left:5px"  >
								<?php echo $categoryName; ?>
							</div>
						</a>
						<div style="border-top:1px solid #ABDB47;"></div>
						<div class="clear_L"></div>
					<?php } ?>
				</div>
