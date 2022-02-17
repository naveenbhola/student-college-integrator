<?php
	$LDBCourses = $LDBCourseRepository->getLDBCoursesForSubCategory($request->getSubCategoryId());
	$categoryFlag = 'national';
	if($request->isStudyAbroadPage()){
			$categoryFlag = 'abroad';
	}
	$subCategories = $categoryRepository->getSubCategories($request->getCategoryId(),$categoryFlag);
	
	if(($request->getSubCategoryId() > 1)&&(!$request->isStudyAbroadPage())){
		echo '<li><strong>Course</strong></li>';
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
												$courseHTML1 .= '<li class="'.$liclass.'"><b>'.$row->getCourseName().'</b></li>';
                                                                                                $subcatSameAsldbCourseCategoryPage = 1;
									}else{
										
										if(in_array($urlRequest->getSubCategoryId(),array(23,56)))
										{
											$URLData['LDBCourseId'] = 1;
											$URLData['examName'] = "";
											$URLData['feesValue'] = "";
											$URLData['affiliation'] = "";
											$urlRequest->setData($URLData);
										}
										else 
										{
											$urlRequest->setData(array('LDBCourseId'=>1));
										}
										      
										
												
												$courseHTML .= '<li class="'.$liclass.'"><a href="'.$urlRequest->getURL().'#page-top">'.$row->getCourseName().'</a></li>';
									}
						}elseif($row->getId() == $request->getLDBCourseId()){
								$liclass = 	"activeLink";
								$courseHTML1 .= '<li class="'.$liclass.'"><b>'.($row->getSpecialization()!="All"?$row->getSpecialization():$row->getCourseName()).'</b></li>';
						}else{
								 if(in_array($urlRequest->getSubCategoryId(),array(23,56)))
									 {
									 	$URLData['LDBCourseId'] = $row->getId();
									 	$URLData['examName'] = "";
									 	$URLData['feesValue'] = "";
									 	$URLData['affiliation'] = "";
									 	$urlRequest->setData($URLData);
									 }
									  else 
									  {
									  	$urlRequest->setData(array('LDBCourseId'=>$row->getId()));
									  }
									$courseHTML .= '<li class="'.$liclass.'"><a href="'.$urlRequest->getURL().'#cateSearchBlock">'.($row->getSpecialization()!="All"?$row->getSpecialization():$row->getCourseName()).'</a></li>';
						}
						
			}
			echo $courseHTML1.$courseHTML;
	}else{
		if(!$request->isStudyAbroadPage()){	
			echo '<li><strong>Sub Category</strong></li>';
		}else{
			echo '<li><strong>Courses</strong></li>';
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
						$catHTML1 .=  '<li class="'.$liclass.'"><b>'.$row->getName().'</b></li>';
			}else{
						$urlRequest->setData(array('subCategoryId'=>$row->getId(),'LDBCourseId'=>1));
						$catHTML .= '<li class="'.$liclass.'"><a href="'.$urlRequest->getURL().'#cateSearchBlock">'.$row->getName().'</a></li>';
			}	
		}
		echo $catHTML1.$catHTML;
	}
?>
<script>
var subcatSameAsldbCourseCategoryPage = '<?php echo $subcatSameAsldbCourseCategoryPage; ?>';
</script>
