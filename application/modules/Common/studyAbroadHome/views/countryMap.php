<?php
$this->load->library('categoryList/AbroadCategoryPageRequest');
$this->abroadCategoryPageRequest   = new AbroadCategoryPageRequest();
$categoryPageRequest = $this->load->library('categoryList/AbroadCategoryPageRequest');
$categoryBuilder = new CategoryBuilder;
$categoryRepository = $categoryBuilder->getCategoryRepository();

?>
	 <div class="map-section">
		  <h2>Top Study Abroad Destinations for Indian Students </h2>
		  <p class="map-guideline">Just take your <span>mouse over the country name</span>
		  </p>
            
		  <div class="map">
		      
			   <div id="pins">
				    <div class="usa-pin" onmouseover="showCountryBlock('USA');" onmouseout="hideCountryBlock('USA');" id="pinUSA"><i class="home-sprite map-pin"></i><p>USA</p></div>
				    <div class="canada-pin" onmouseover="showCountryBlock('Canada');" onmouseout="hideCountryBlock('Canada');" id="pinCanada"><i class="home-sprite map-pin"></i><p>Canada</p></div>
				    <div class="uk-pin" onmouseover="showCountryBlock('UK');" onmouseout="hideCountryBlock('UK');" id="pinUK"><i class="home-sprite map-pin"></i><p>UK</p></div>
				    <div class="germany-pin" onmouseover="showCountryBlock('Germany');" onmouseout="hideCountryBlock('Germany');" id="pinGermany"><i class="home-sprite map-pin"></i><p>Germany</p></div>
				    <div class="singapore-pin" onmouseover="showCountryBlock('Singapore');" onmouseout="hideCountryBlock('Singapore');" id="pinSingapore"><i class="home-sprite map-pin"></i><p>Singapore</p></div>
				    <div class="aus-pin" onmouseover="showCountryBlock('Australia');" onmouseout="hideCountryBlock('Australia');" id="pinAustralia"><i class="home-sprite map-pin"></i><p>Australia</p></div>
				    <div class="newzealand-pin" onmouseover="showCountryBlock('NewZealand');" onmouseout="hideCountryBlock('NewZealand');" id="pinNewZealand"><i class="home-sprite map-pin"></i><p>New Zealand</p></div>
			   </div>
		  
			   <?php foreach($countryMap as $key=>$value){
				    $countryName = $key;
				    //remove space from key
				    $key = str_replace(' ','',$key);
				    ?>
			   
				    <div class="flag-popup" id="country<?=$key?>" style="display: none;" onmouseover="showCountryBlock('<?=$key?>');" onmouseout="hideCountryBlock('<?=$key?>');">   
					     <div class="content">
						      <div class="country-name">
							       <div class="country-flag"><span class="flags <?=strtolower($key)?>"></span></div>
							       <p><?=$key?></p>
						      </div>
						      <div class="country-details">
							       <ul>
									<li>
										 Popular Courses:<br />
										 <?php
											  $i = 0;
											  foreach($countryMap[$countryName]['topCourses'] as $topCourses){
												   $params = array();
												   if(isset($topCourses['subCategoryId']) && $topCourses['subCategoryId']>0){
													    $categoryObject  = $categoryRepository->find($topCourses['subCategoryId']);
													    $params['categoryId'] = $categoryObject->getParentId();
													    $params['courseLevel'] = strtolower($topCourses['courseLevel']);
													    if($params['courseLevel']==1){
														     $params['courseLevel'] = "";
													    }
													    $params['subCategoryId'] = $topCourses['subCategoryId'];
													    $params['countryId'] = array($countryMap[$countryName]['countryId']);
													    $params['LDBCourseId'] = 1;

													    $categoryPageRequest->setData($params);
													    $url = $categoryPageRequest->getUrl();
													    $name = $categoryObject->getName();
													    if($i>0){
														     echo " | ";
													    }
													    echo "<a onClick='studyAbroadTrackEventByGA(\"ABROAD_HOME_PAGE\", \"CountryMap_PopularCourses\" , \"$countryName\");' href='$url'>".$topCourses['courseLevel']." in ".$name."</a>";
													    $i++;
												   }
												   else if(isset($topCourses['SpecializationId']) && $topCourses['SpecializationId']>0){
													    $params['countryId'] = array($countryMap[$countryName]['countryId']);
													    $params['LDBCourseId'] = $topCourses['SpecializationId'];
													    $params['courseLevel'] = "";
													    $params['categoryId'] = 1;
													    $params['subCategoryId'] = 1;
													    $categoryPageRequest->setData($params);
													    $url = $categoryPageRequest->getUrl();
													    if($i>0){
														     echo " | ";
													    }
													    echo "<a onClick='studyAbroadTrackEventByGA(\"ABROAD_HOME_PAGE\", \"CountryMap_PopularCourses\" , \"$countryName\");' href='$url'>".$topCourses['CourseName']."</a>";
													    $i++;
												   }
											  }
										 ?>
									</li>
									
									<?php if(isset($countryMap[$countryName]['universityCount']) && $countryMap[$countryName]['universityCount']>0){ ?>
									<li>
										 Number of Universities:<br />
										 <?php
											  $countryId = $countryMap[$countryName]['countryId'];
											  $universityURL = $this->abroadCategoryPageRequest->getURLForCountryPage($countryId);
										 ?>
										 <a onClick="studyAbroadTrackEventByGA('ABROAD_HOME_PAGE', 'CountryMap_Universities' , '<?$countryName?>');" href="<?=$universityURL?>"><?=$countryMap[$countryName]['universityCount']?> universities</a>
									</li>
									<?php } ?>
									
									<?php if(isset($countryMap[$countryName]['guideURL']) && $countryMap[$countryName]['guideURL']!=''){ ?>
									<li>
										 Country Guide:<br />
										 <a onClick="studyAbroadTrackEventByGA('ABROAD_HOME_PAGE', 'CountryMap_Guides' , '<?$countryName?>');" href="<?=$countryMap[$countryName]['guideURL']?>">Read more</a>
									</li>
									<?php } ?>
							       </ul>
						      </div>
					     </div>				    
				    </div>
				    
			   <?php }?>
			   
		  </div>
        </div>
