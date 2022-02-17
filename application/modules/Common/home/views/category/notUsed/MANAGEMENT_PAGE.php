<?php
        global $criteriaArray;
        $criteriaKeyword = '';
        $criteriacategoryId = $categoryId;
        if(isset($categoryData['page']) && $categoryData['page'] == "FOREIGN_PAGE"){
            $criteriaKeyword = "BMS_STUDY_ABROAD";
            if(strlen(trim($categoryId)) > 1){
                $criteriacategoryId = (trim($categoryId));
            }
            else{
                $criteriacategoryId = '3';

            }
        }
        $criteriaArray = array(
                'category' => $criteriacategoryId,
                'country' => $countryId,
                'city' => $selectedCity,
                'keyword'=>$criteriaKeyword
                );
    $locationSelected =  ucwords($cityNameSelected == 'All' ? '' : $cityNameSelected) .' '. ucwords($countryNameSelected) ;
    $abroadText = '';
    if($countryNameSelected == 'India' && $cityNameSelected == ''){
        $abroadText = '& Abroad';
    }

$titleText =  $countryNameSelected."- Colleges Universities ".$countryNameSelected . "- Search Colleges- Visa Consultants - ".$countryNameSelected." Career Information";
$metaDescriptonText = $countryNameSelected . " College Universities and Institutes. Shiksha lists " .$countryNameSelected ." institutes along with career information and visa consultants in ".$countryNameSelected;
$metaKeywordsText = $countryNameSelected .", study abroad, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, ".$countryNameSelected. "institutes, courses, coaching, technical education, ".$countryNameSelected." Visa Consultants, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships, ".$countryNameSelected .", shiksha   " ;

		$headerComponents = array(
								'css'	=>	array(
											'raised_all',
											'header',
											'mainStyle',
											'footer',
											'events'
										),
								'js'	=>	array(
											'common',
											'cityList',
											'countryHomePage',
                                           // 'search',
										),
								'title'	=>	$titleText,
                                'taburl' =>  site_url(),
								'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),'tabName'	=>	'Event Calendar',
								'product'	=>	'foreign',
							    'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'metaKeywords'	=>$metaKeywordsText,
								'metaDescription' => $metaDescriptionText
							);
		$this->load->view('common/homepage', $headerComponents);
?>
<?php $this->load->view('home/category/countryHeader') ?>
		<div class="lineSpace_5">&nbsp;</div>
<script>
var SITE_URL = '<?php echo base_url(); ?>';
var collegeList = eval(<?php echo $collegeList; ?>);
</script><!--Start_Mid_Container-->
<!--Start_Center-->
<div class="mar_full_10p">
<input type="hidden" id="categoryId" value="<?php echo $categoryId?>"/>
<input type="hidden" id="countryId" value="<?php echo $countryId?>"/>
	<?php $this->load->view('home/category/HomeLeftPanel');?>
	
	
	<!--Start_Mid_Panel-->
	<div id="mid_Panel_inbox">
		<!--Start_courses_category_Box-->
	    <div class="float_L" style="width:100%">
			<div class="float_R" style="width:305px;">
				<div style="height:275px">
					<?php $this->load->view('home/category/HomeRightPanel');?>
				</div>
				<div class = "brd careerOptionPanelShowInfo" style = "padding:10px">
					<?php 
						$resultKey = $countryId;
						$criteria = array('countryId'=>$resultKey);
						$productsCount = array('resultKey'=>$resultKey, 'productsCount' => $productsCountForCountry, 'criteria' => $criteria, 'pageType' => 'country', 'countryName'=>$countryNameSelected);
						$this->load->view('home/category/HomeMainPagesProductsCountWidget',$productsCount);?>
				</div>
			</div>

			<div style="margin-right:315px">
				<div style="float:left; width:100%">
				<?php $this->load->view('home/category/HomeCountryPanel', $categoryData);?>
				</div>
			</div>			
		</div>
		<!--End_courses_category_Box-->		
		<div class="lineSpace_10">&nbsp;</div>			
		<div style="display:inline; float:left; width:100%">
        </div>
        
		<div class="lineSpace_10">&nbsp;</div>		

		<div class="lineSpace_15">&nbsp;</div>	
		<div class="lineSpace_15">&nbsp;</div>	
		<div class="lineSpace_15">&nbsp;</div>	
		<div class="lineSpace_15">&nbsp;</div>	
		<!--End_Test_Preperation_Box-->		
		<!--<div class="lineSpace_10">&nbsp;</div> -->
		<div id="narrow_ad_unit" style="display:none;">&nbsp;</div>
		<div id="wide_ad_unit" class="row">
		</div>
  	</div>
	<!--End_Mid_Panel-->
<br clear="all" />
</div>
<!--End_Center-->
<!--End_Mid_Container-->
<script>
	var SITE_URL = '<?php echo base_url(); ?>';
	var collegeList = eval(<?php echo $collegeList; ?>);
	var pageName = '<?php echo  $categoryData['page']; ?>';
    var selectedCategoryId = '<?php echo $categoryId; ?>';	
  document.getElementById('startOffSet').value = 0;    
doPagination(<?php echo $collegeList[0]['total'] ; ?>, 'startOffSet', 'countOffset', 'paginataionPlace1', 'paginataionPlace2', 'methodName', <?php echo ($_COOKIE['client']< 1000) ? 5 : 10;?>);
	selectCategoryTab(document.getElementById('countrySelect'),selectedCategoryId);
</script>
<?php
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
