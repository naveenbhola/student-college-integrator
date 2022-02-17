<?php
        global $criteriaArray;
        global $seotabs;
        $keyname = strtoupper($categoryUrlName.($selectedCity != '' ? 'CITYID' : 'ALL').($subCategorySelected != '' ? str_replace(' ','-',$subCategorySelected) : 'All'));
        $titleText = str_replace('CITYNAME',$cityNameSelected,$seotabs[$keyname]['title']);
        $metaDescriptionText = str_replace('CITYNAME',$cityNameSelected,$seotabs[$keyname]['metadescription']);
        $metaKeywordText = str_replace('CITYNAME',$cityNameSelected,$seotabs[$keyname]['metakeyword']);
        $h1tag = $seotabs[$keyname]['h1']; 


        $criteriaKeyword = $selectedCity == '' ? 'BMS_ALL_CITIES' : '';
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
    $locationSelected =  ucwords($cityNameSelected == 'All' ? '' : $cityNameSelected) .' '. ucwords($countryNameSelected);
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
/*    else
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
    }*/
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
											'events',
                                            'modal-message'   /* file added for multiple apply */                              
										),
								'js'	=>	array(
											'common',
											'cityList',
											'categoryHomePage',
                                            'categoryList',
                                            'lazyload', /* file added for multiple apply */ 
                                            'multipleapply' /* file added for multiple apply */ 
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
        if(is_array($validateuser))
            $this->load->view('search/searchRequestInfo');
?>
<style>h1{display:none}</style>
<h1><?php echo $h1tag;?></h1>
<?php 
if(isset($pageName) && $pageName == "ENTRANCE_EXAM_PREPARATION_PAGE")
$this->load->view('home/category/TestcategoryHeader');
else
$this->load->view('home/category/categoryHeader') ?>
		<div class="lineSpace_5">&nbsp;</div>
<script>
<?php if($openOverlay) { ?>
showUserPreferenceOverlay();
<?php }?>
var SITE_URL = '<?php echo base_url(); ?>';
var collegeList = eval(<?php echo $collegeList; ?>);
/* Multiple Apply button start */
LazyLoad.loadOnce([
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax"); ?>',
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("modal-message"); ?>',
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-dynamic-content"); ?>',
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>'
    ],callbackfn);
/* Multiple Apply button end */
</script>
<!--Start_Mid_Container-->


<div>
        <input type="hidden" id="category_unified_id" value="<?php echo $categoryId?>"/>
	<input type="hidden" id="categorypage_unified_thankslayer_identifier" value=""/>
	<input type="hidden" id="categoryId" value="<?php echo $categoryId?>"/>
	<input type="hidden" id="countryId" value="<?php echo $countryId?>"/>
	<div style="width:154px;float:left">
		<?php 
			$category = '';
			if(isset($pageName) && $pageName == "ENTRANCE_EXAM_PREPARATION_PAGE"){
				$category = array('categoryData'=>array('page'=>'ENTRANCE_EXAM_PREPARATION_PAGE'));
			}
			$this->load->view('home/category/HomeLeftPanel',$category);
		?>			
	</div>
	<div style="margin-left:164px;">
		<div style="float:right;width:240px">
			<?php $this->load->view('home/category/HomeBlogsPanel', $categoryData); ?>
			<?php $this->load->view('home/shiksha/HomeMsgBoardPanel', $categoryData); ?>
			<div class="lineSpace_10">&nbsp;</div>
			<?php // $this->load->view('home/shiksha/HomeGroupsPanel', $categoryData); ?>
		</div>
		<div style="margin-right:250px">
				<div style="float:left;width:99.5%">
					<?php if(isset($pageName) && $pageName == "ENTRANCE_EXAM_PREPARATION_PAGE")
						$this->load->view('home/category/TestPrep', $categoryData);
					else
						$this->load->view('home/category/HomeCategoryCollegePanel', $categoryData); 
					?>
					<div class="lineSpace_10">&nbsp;</div>
					<?php $this->load->view('home/shiksha/HomeEventsPanel', $categoryData);?>
				</div>
				<div class="clear_L" style="line-height:1px">&nbsp;</div>
		</div>
		<div class="clear_R" style="line-height:1px">&nbsp;</div>
	</div>
	<div class="clear_L" style="line-height:1px">&nbsp;</div>
	<div class="lineSpace_10">&nbsp;</div>
	<div id="narrow_ad_unit" style="display:none;">&nbsp;</div>
	<div id="wide_ad_unit" class="row"></div>
</div>

<!--End_Mid_Container-->
<script>
	var SITE_URL = '<?php echo base_url(); ?>';
	var collegeList = eval(<?php echo $collegeList; ?>);
	
	var pageName = '<?php echo  $categoryData['page']; ?>';
    //populatecities(<?php echo (isset($selectedCity) && !empty($selectedCity))?$selectedCity:'-1'; ?>);
</script>
<?php
            $bannerProperties = array('pageId'=>'', 'pageZone'=>'');
            $this->load->view('common/footer', $bannerProperties);
?>
<?php if($openOverlay) { ?>
<script>
showUserPreferenceOverlay();
try {
    var chkAll = document.getElementById('checkAll');
    chkAll.checked = false;
} catch (ex) {

}
</script>
<?php }?>
