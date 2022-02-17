<header id="page-header" class="clearfix">
    <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
	<a id="courseOverlayClose" data-rel="back" href="javascript:void(0);"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   	
        <h3 id="courseOverlayHeading"></h3>
    </div>
</header>

<section class="layer-wrap fixed-wrap" style="height: 100%">
    <ul class="layer-list">

	<?php
		$LDBCourses = $LDBCourseRepository->getLDBCoursesForSubCategory($request->getSubCategoryId());
		$categoryFlag = 'national';
		if($request->isStudyAbroadPage()){
				$categoryFlag = 'abroad';
		}
		$selectedVal = '';
		$subCategories = $categoryRepository->getSubCategories($request->getCategoryId(),$categoryFlag);
		if(($request->getSubCategoryId() > 1)&&(!$request->isStudyAbroadPage())){
			echo '<script>$("#courseOverlayHeading").html("Select Specialisation")</script>';
			$urlRequest = clone $request;
			$courseHTML1 = "";
			$courseHTML = "";
     			$subcatSameAsldbCourseCategoryPage = 0;
			
			foreach($LDBCourses as $row){

							if(!in_array($row->getId(),$dynamicLDBCoursesList)){
								continue;
							}
		
							$liclass = "";
							if((!strcasecmp($row->getSpecialization(),"All")) && (in_array($row->getId(),array(2,13,52,53)) || $row->getCourseName() == $categoryPage->getSubCategory()->getName())){

										if(($request->getLDBCourseId()==1)){
										         		$liclass = 	"activeLink";
													$selectedVal = $row->getCourseName();
													$courseHTML1 .= '<li id="'.$row->getId().'" class="'.$liclass.'" ><a  onclick="getFiltersForIndia(\''.$row->getId().'\',\''.(string)$urlRequest->getURL().'\');">'.$row->getCourseName().'</a><i class="icon-check"></i></li>';
													$subcatSameAsldbCourseCategoryPage = 1;
										}else{
											 	
													$urlRequest->setData(array('LDBCourseId'=>$row->getId()));
													$courseHTML .= '<li id="'.$row->getId().'" class="'.$liclass.'" ><a  onclick="getFiltersForIndia(\''.$row->getId().'\',\''.(string)$urlRequest->getURL().'\');">'.$row->getCourseName().'</a></li>';
										}
							}elseif($row->getId() == $request->getLDBCourseId() && !$ifVisited){
									  $urlRequest->setData(array('LDBCourseId'=>$row->getId()));

									$liclass = 	"activeLink";
									$selectedVal = ($row->getSpecialization()!="All"?$row->getSpecialization():$row->getCourseName());
									$courseHTML1 .= '<li id="'.$row->getId().'" class="'.$liclass.'"><a onclick="getFiltersForIndia(\''.$row->getId().'\',\''.(string)$urlRequest->getURL().'\');">'.($row->getSpecialization()!="All"?$row->getSpecialization():$row->getCourseName()).'</a><i class="icon-check"></i></li>';
							}else{
										$urlRequest->setData(array('LDBCourseId'=>$row->getId()));
								
										$courseHTML .= '<li id="'.$row->getId().'" class="'.$liclass.'"  ><a  onclick="getFiltersForIndia(\''.$row->getId().'\',\''.(string)$urlRequest->getURL().'\');">'.($row->getSpecialization()!="All"?$row->getSpecialization():$row->getCourseName()).'</a></li>';
							}
							
				}
				echo $courseHTML1.$courseHTML;
		}else{
			if(!$request->isStudyAbroadPage()){	
				echo '<script>$("#courseOverlayHeading").html("Select Sub Category")</script>';
			}else{
				echo '<script>$("#courseOverlayHeading").html("Select Course")</script>';
			}
			$urlRequest = clone $request;
			$catHTML1 = "";
			$catHTML = "";
			
			foreach($subCategories as $row){
				if(!in_array($row->getId(),$dynamicCategoryList)){
					continue;
				}
				$liclass = '';
				if($row->getId() == $request->getSubCategoryId()){
							$liclass = 	"activeLink";
							$selectedVal = $row->getName();
							$urlRequest->setData(array('subCategoryId'=>$row->getId(),'LDBCourseId'=>1));
							if($request->isStudyAbroadPage())
							    $catHTML .= '<li id="'.$row->getId().'" class="'.$liclass.'" ><a  onclick="getFiltersForStudyAbroad(\''.$row->getId().'\',\''.(string)$urlRequest->getURL().'\');">'.$row->getName().'</a><i class="icon-check"></i></li>';
							else
							    $catHTML1 .=  '<li id="'.$row->getId().'" class="'.$liclass.'"><a  onclick="getFiltersForIndia(\''.$row->getId().'\',\''.(string)$urlRequest->getURL().'\');">'.$row->getName().'</a><i class="icon-check"></i></li>';
				}else{
							$urlRequest->setData(array('subCategoryId'=>$row->getId(),'LDBCourseId'=>1));
							$url = (string)$urlRequest->getURL();
							if($request->isStudyAbroadPage())
							    $catHTML .= '<li id="'.$row->getId().'" class="'.$liclass.'" ><a onclick="getFiltersForStudyAbroad(\''.$row->getId().'\',\''.(string)$urlRequest->getURL().'\');">'.$row->getName().'</a></li>';
							else
							    $catHTML .= '<li id="'.$row->getId().'" class="'.$liclass.'" ><a onclick="getFiltersForIndia(\''.$row->getId().'\',\''.(string)$urlRequest->getURL().'\');">'.$row->getName().'</a></li>';
				}	
			}
			echo $catHTML1.$catHTML;
		}
	?>
    </ul>
</section>
<a href="javascript:void(0);" onclick="closeSecondOverlay();" class="cancel-btn">Cancel</a>

<script>
var subcatSameAsldbCourseCategoryPage = '<?php echo $subcatSameAsldbCourseCategoryPage; ?>';
<?php if($selectedVal != ''){ ?>
	$('#courseOverlayOpen').html('<p><?=$selectedVal?> <i class="icon-select2"></i></p>');
<?php } ?>

</script>

