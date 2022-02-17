<?php
        global $criteriaArray;
        $criteriaKeyword ="";
        $criteriacategoryId = $categoryId;
        if(isset($categoryData['page']) && $categoryData['page'] == "ENTRANCE_EXAM_PREPARATION_PAGE"){
            $criteriaKeyword = "BMS_TEST_PREP";
            if(strlen(trim($categoryId)) > 1){
                echo strlen(trim($categoryId));
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
    if(isset($pageName) && $pageName == "ENTRANCE_EXAM_PREPARATION_PAGE")
    {
       $titleText = $examName .' Preparation Institutes .';
       $metaDescriptionText = "Find ".$examName ." Preparation Institutes, ".$examName." Entrance Coaching institutes. Shiksha lists ".$examName." coaching institutes and along with information for ".$examName." preparation";
       $metaKeywordText = $examName." Preparation Institutes Coaching Institute, ".$examName ." Institutes List";
    }
    else
    {
    if($subCategorySelected != '') {
       $titleText = strip_tags($categoryData['displayName']).' - ' .$subCategorySelected ." Institutes . ". strip_tags($categoryData['displayName']) . " Business Scholarships Important Dates";
       $metaDescriptionText = strip_tags($categoryData['displayName']) .' Institutes, '. $subCategorySelected  .' Institutes. Shiksha lists '. strip_tags($categoryData['displayName']) .' institutes along with information for management business, '.$subCategorySelected.', scholarships important dates, articles and question answers ';
       $metaKeywordText = strip_tags($categoryData['displayName']) .",". $subCategorySelected .", Entrance Coaching Institute, ".strip_tags($categoryData['displayName']) . " Institutes List, ".strip_tags($categoryData['displayName'])." admission institutes , Institutes, Scholarship, important dates, ".$subCategorySelected ." institutes, articles, question answer";       
       }
       else
       {
       $titleText = strip_tags($categoryData['displayName'])." Institutes . ". strip_tags($categoryData['displayName']) . " Business Scholarships Important Dates";
       $metaDescriptionText = strip_tags($categoryData['displayName']) .' Institutes,. Shiksha lists '. strip_tags($categoryData['displayName']) .' institutes along with information for management business, scholarships important dates, articles and question answers ' ;
       $metaKeywordText = strip_tags($categoryData['displayName']) .", Entrance Coaching Institute, ".strip_tags($categoryData['displayName']) . " Institutes List, ".strip_tags($categoryData['displayName'])." admission institutes , Institutes, Scholarship, important dates, institutes, articles, question answer";       
       }
    }
if(isset($pageName) && $pageName == "ENTRANCE_EXAM_PREPARATION_PAGE")
$product = "testprep";
else
$product = "categoryHeader";
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
											'TestPrepPage',
                                            'categoryList',
                                            'exams',
										),
								'title'	=>	$titleText,
                                'taburl' =>  site_url(),
								'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),'tabName'	=>	'Event Calendar',
                               'product'   =>  $product,
							    'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'metaKeywords'	=>$metaKeywordText,
								'metaDescription' => $metaDescriptionText
							);
		$this->load->view('common/homepage', $headerComponents);
?>
<div class="wrapperFxd">
<?php 
if(isset($pageName) && $pageName == "ENTRANCE_EXAM_PREPARATION_PAGE")
$this->load->view('home/category/TestcategoryHeader');
else
$this->load->view('home/category/categoryHeader') ?>
		<div class="lineSpace_5">&nbsp;</div>
<script>
var SITE_URL = '<?php echo base_url(); ?>';
var collegeList = eval(<?php echo $collegeList; ?>);
</script>
<!--Start_Mid_Container-->
<!--Start_Center-->
<div class="mar_full_10p">
<input type="hidden" id="categoryId" value="<?php echo $categoryId?>"/>
<input type="hidden" id="countryId" value="<?php echo $countryId?>"/>
	<?php 
                $category = '';
if(isset($pageName) && $pageName == "ENTRANCE_EXAM_PREPARATION_PAGE")
{
    $category = array('categoryData'=>array('page'=>'ENTRANCE_EXAM_PREPARATION_PAGE'));
}
    $this->load->view('home/category/HomeLeftPanel',$category);?>
	
	
	<!--Start_Mid_Panel-->
	<div id="mid_Panel_inbox">
		<!--Start_courses_category_Box-->
	    <div class="float_L" style="width:100%">
			<div class="float_R" style="width:305px;">
			<?php 

			$this->load->view('home/category/HomeRightPanel',$category);?>
			</div>
			<div style="margin-right:315px">
				<div style="float:left; width:100%">
<?php if(isset($pageName) && $pageName == "ENTRANCE_EXAM_PREPARATION_PAGE")
				$this->load->view('home/category/TestPrep', $categoryData);
else
				$this->load->view('home/category/HomeCatPanel', $categoryData);?>
				</div>
			</div>			
		</div>
		<!--End_courses_category_Box-->		
		
		<div class="lineSpace_10">&nbsp;</div>		

		<div class="lineSpace_15">&nbsp;</div>	
		<div class="lineSpace_15">&nbsp;</div>	
		<div class="lineSpace_15">&nbsp;</div>	
		<div class="lineSpace_15">&nbsp;</div>	
		<!--End_Test_Preperation_Box-->		
		<div id="narrow_ad_unit" style="display:none;">&nbsp;</div>
		<div id="wide_ad_unit" class="row">
		</div>
  	</div>
	<!--End_Mid_Panel-->
<br clear="all" />
</div>
</div>
<!--End_Center-->
<!--End_Mid_Container-->
<script>
	var SITE_URL = '<?php echo base_url(); ?>';
	var collegeList = eval(<?php echo $collegeList; ?>);
	
	var pageName = '<?php echo  $categoryData['page']; ?>';
    populatecities(<?php echo isset($selectedCity)?$selectedCity:''?>);
</script>
<?php
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer', $bannerProperties);
?>
