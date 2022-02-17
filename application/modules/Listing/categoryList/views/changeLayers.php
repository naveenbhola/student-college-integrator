<?php 
	$categories = $categoryRepository->getSubCategories(1,'');
	$subCategories = $categoryRepository->getSubCategories($request->getCategoryId(),'national');
	$LDBCourses = $LDBCourseRepository->getLDBCoursesForSubCategory($request->getSubCategoryId());
	$urlRequest = clone $request;
	$height['category'] = 0;
	$height['subcategory'] = 0;
	$height['course'] = 0;
	foreach($subCategories as $row){
		if(!in_array($row->getId(),$dynamicCategoryList)){
			continue;
		}
		$height['subcategory'] += 20;
		$urlRequest->setData(array('subCategoryId'=>$row->getId(),'LDBCourseId'=>1));
		$style = '';
		
		if($request->getSubCategoryId() == $row->getId()){
			$style="style = 'font-weight:bold";
			if($request->getLDBCourseId() == 1){
				$style.=";color:#000";
			}
			$style .= "'";
		}else{
			$subCatHTML .= '<a href="'.$urlRequest->getURL().'">'.$row->getName().'</a><br/>';
		}
	}
	if($request->getSubCategoryId() == 1){
		$urlRequest = clone $request;
		foreach($categories as $row){
			
			$urlRequest->setData(array('categoryId'=>$row->getId(),'subCategoryId'=>1,'LDBCourseId'=>1));
			$style = '';
			if($request->getCategoryId() == $row->getId()){
				continue;
			}else{
				$height['category'] += 20;
				$catHTML .= '<a href="'.$urlRequest->getURL().'">'.$row->getName().'</a><br/>';
			}
		}
	}
	$urlRequest = clone $request;
	
	// to reset the filter parameters
	$urlRequest->setData(array("affiliation" => "",
				   "feesValue"	 => "",
				   "examName"	 => "",
				   "localityId"	 => 0));
	
	foreach($LDBCourses as $row){
			if(!in_array($row->getId(),$dynamicLDBCoursesList)){
				continue;
			}
			$height['course'] += 20;
			$liclass = "";
			if((!strcasecmp($row->getSpecialization(),"All")) && (in_array($row->getId(),array(2,13,52,53)) || $row->getCourseName() == $categoryPage->getSubCategory()->getName())){
				if(($request->getLDBCourseId()==1)){
					$liclass = 	"activeLink";
					$courseHTML1 .= '<b class="'.$liclass.'">All</b>';
					$courseHTML1 .= '<br/>';
				}else{
					$urlRequest->setData(array('LDBCourseId'=>1));
					$courseHTML .= '<a href="'.$urlRequest->getURL().'">All</a>';
					$courseHTML .= '<br/>';
				}
			}elseif($row->getId() == $request->getLDBCourseId()){
					$liclass = 	"activeLink1";
					$courseHTML1 .= '<b class="'.$liclass.'">'.($row->getSpecialization()!="All"?$row->getSpecialization():$row->getCourseName()).'</b>';
					$courseHTML1 .= '<br/>';
			}else{
					$urlRequest->setData(array('LDBCourseId'=>$row->getId()));
					$courseHTML .= '<a href="'.$urlRequest->getURL().'">'.($row->getSpecialization()!="All"?$row->getSpecialization():$row->getCourseName()).'</a>';
					$courseHTML .= '<br/>';
			}
			
	}
	$mouseover = "this.style.display=''; overlayHackLayerForIE('overlayCategoryHolder', document.getElementById('overlayCategoryHolder'));" ;
	$mouseout = "dissolveOverlayHackForIE();this.style.display='none'";
	if($request->getLDBCourseId() > 1) {
		$divWidth = "670px";
	}
	$layerHeight = min(max($height['category'],$height['subcategory'],$height['course']),200);
?>
<div id = 'overlayCategoryHolder'  style="background:#fff;border:1px solid #aaa;display:none;width:<?=$divWidth?>" onmouseover="<?=$mouseover?>" onmouseout="<?=$mouseout?>">
    <?php
		if($request->getLDBCourseId() > 1) {
			$height['course'] += 20;
			$height['subcategory'] += 20;
			$layerHeight = min(max($height['category'],$height['subcategory'],$height['course']),220);
	?>
	<div class="float_L">
			<div style="padding:10px">
					<h2 style="color:black">
						<?=$categoryPage->getSubCategory()->getName()?>
					</h2>
					<div style="line-height:20px;padding-left:10px">
						<div style="height:<?=($layerHeight-30)?>px;overflow-y:auto;width:300px" id = "cityli1">
							<?php
								echo $courseHTML1.$courseHTML;
							?>
						</div>
					</div>
			</div>
	</div>
	<?php
	}
	?>
	<div class="float_L">
		<div style="padding:10px">
				<?php
					if($request->getLDBCourseId() > 1) {
						$newHeight = $layerHeight-30;
				?>
				<h2 style="color:black">
						Other Courses
				</h2>
				<?php
					}else{
						$newHeight = $layerHeight-25;
					}
				?>
				<div style="line-height:20px;padding-left:10px">
					<div style="height:<?=$newHeight?>px;overflow-y:auto;width:300px" id = "cityli1">
						<?php
							if($request->getSubCategoryId() > 1) {
								echo $subCatHTML;
							}else{
								echo $catHTML;
							}
						?>
					</div>
				</div>
		</div>
	</div>

</div>
<style>
#overlayCategoryHolder{height:<?=$layerHeight?>px !important;position:absolute;z-index:9999999 !important}
</style>