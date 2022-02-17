<?php
		
		$CI_INSTANCE->config->load('categoryPageConfig');
		$subcategoriesForRnR = $CI_INSTANCE->config->item('CP_SUB_CATEGORY_NAME_LIST');
		$totalResults = $categoryPage->getTotalNumberOfInstitutes();
		$mediaData = $request->getMetaData($totalResults);
		$titleText = $mediaData['title'];
		$requestUrl = clone $request;
		$requestUrl->setData(array('naukrilearning'=>0));		
		//$canonicalurl = $requestUrl->getCanonicalURL($requestUrl->getPageNumberForPagination());
		/*
		 * Next page & Previous page URL generation for rel next and 
		rel prev tags..
		 */
		$currentPage = $request->getPageNumberForPagination();
		$totalPages = ceil($totalResults/$request->getSnippetsPerPage());
		$previousURL = "";
		$nextURL = "";
		if($currentPage < $totalPages) {
       		    $nextURL = $requestUrl->getUrl($currentPage+1);
		}
		if($currentPage > 1) {		    
		    $previousURL = $requestUrl->getUrl($currentPage-1);
		}		
		global $criteriaArray;
		$criteriaArray = array(     
                'category' => $request->getCategoryId(),     
                'country' => $request->getCountryId(),      
                'city' => $request->getCityId(),     
                'keyword'=> strtoupper(preg_replace("/[^a-z0-9_]+/i", "_",$categoryPage->getSubCategory()->getName()))
				);
		//echo $request->getCityId();
		//echo '<br/>'.$request->getSubCategoryId();
		if($request->getCityId() == 1 && $request->getSubCategoryId() > 1){
				if($request->getStateId() == 1){
						// Before Multilocation : 
						// $criteriaArray['keyword'] = 'BMS_ALL_CITIES_'.$request->getSubCategoryId();
						//Now, this situation is for multilocation situations.
						//Step 1: Get list of selections
						//echo $request->getCategoryId();
						$multiLocationLayerCookie =$request->getUserPreferredLocationOrder(true);
						//echo '<br/>'.print_r($multiLocationLayerCookie);
						//Step 2: Select a random value from selections
						$locationSelected = $multiLocationLayerCookie[array_rand($multiLocationLayerCookie)];
						$locationSelected = explode('_',$locationSelected);
						$csFlag = $locationSelected[0];
						$locationSelected = $locationSelected[1];
						//Step 3: Is selection city or state?
						//Step 3[city]: $criteriaArray['city'] = selection_city_id
						//Step 3[state]: $criteriaArray['Keyword'] = BMS_STATE_[selection_state_id]_CATEGORY_$request->getSubCategoryId()
						if($csFlag == 'c'){
								$criteriaArray['city'] = $locationSelected;
						}
						else if($csFlag == 's'){
								$criteriaArray['keyword']= 'BMS_STATE_'.$locationSelected.'_CATEGORY_'.$request->getSubCategoryId();
						}
				}else{
						$criteriaArray['keyword'] = 'BMS_STATE_'.$request->getStateId().'_CATEGORY_'.$request->getSubCategoryId();
				}		
		}
		$product = 'categoryHeader';
		if(in_array($request->getSubCategoryId(),array(23,24,25,26,27))){
			$product = "MBA";	
		}
		if(in_array($request->getSubCategoryId(),array(28,56,100)) || in_array($request->getLDBCourseId(),array(264))){
		    $product = "gradHeader";
		}
		if(in_array($request->getCategoryId(),array(14))){
		    $product = "testprep";
		}
		$headerComponents = array(
								'js'=>array('multipleapply','category','user','customCityList','ajax-api'),
								'jsFooter' =>array('common','lazyload','onlinetooltip','processForm','json2'),
								'product'=> $product,
                                'taburl' =>  site_url(),
								'bannerProperties' => array(
															'pageId'=>'CATEGORY',
															'pageZone'=>'TOP',
															'shikshaCriteria' => $criteriaArray
															),
								'title'	=>	$titleText,
								'searchEnable' => false,
								'canonicalURL' => $canonicalurl,
								'metaDescription' => $mediaData['description'],
								'metaKeywords'	=> $mediaData['keywords'],
								'previousURL' => $previousURL,
								'nextURL' => $nextURL,
								'showBottomMargin'=>false,
								'isCategoryPage' => true
								
		);
		if($request->showGutterBanner()){
                $headerComponents['showGutterBanner'] = 1;
                $headerComponents['bannerPropertiesGutter'] = array('pageId'=>'CATEGORY', 'pageZone'=>'RIGHT_GUTTER');
                $headerComponents['shikshaCriteria'] = $criteriaArray;
        }

		if($isMarketing == 'true' && $subcategoriesForRnR[$request->getSubCategoryId()])
			$headerComponents['noIndexMetaTag'] = true;

		if($request->isAJAXCall()){
				$instituteCount = 0;
				if(!empty($institutes)) {
					$instituteCount = count($institutes);
				}
				if(!empty($filteredBanner) && $filteredBanner->getURL()) {
				?>
				<style>
						.shikFL object{ position:absolute; right:0; }
				</style>
				<script>
						var adBlockEle = document.getElementById("ad-displayBox");
						if(adBlockEle) {
							adBlockEle.style.height = "170px";
							adBlockEle.style.display = "block";
							shoshkeleUrl = '<?=$filteredBanner->getURL()?>';
							shoshkeleType = 'India';
							if(typeof(shoshkeleUrl) != 'undefined') {
								showShohkele(shoshkeleUrl, shoshkeleType);
						    }
						}
						$categorypage.instituteCountOnPage = "<?=$instituteCount;?>";
				</script>
				<?php
				}
                                $this->load->view('categoryList/categoryPageHidden');
				$this->load->view('categoryList/categoryPageListings');
				exit;
		}	
		if($request->isNaukrilearningPage()){
				$headerComponents['bannerProperties'] =  array('pageId'=>'NAUKRI', 'pageZone'=> $pageZone = str_replace(" ","_",$categoryPage->getSubCategory()->getName()).'_TOP', 'shikshaCriteria' => $criteriaArray);
				$this->load->view('categoryList/headernaukrilearning', $headerComponents);
		}else{ 
				$this->load->view('common/header', $headerComponents);
		}
		//echo jsb9recordServerTime('SHIKSHA_NEW_CATEGORY_PAGE',1);
		$this->load->view('common/headerSearch', array('page_type'=>$product));
?>
	
<div style="position:relative; clear:left;">
  	<!--div style="padding: 10px 8px 0px 8px; clear: left;">
		<?php //echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $request->getSubCategoryId(), "Institutes", TRUE,array(),TRUE); ?>
		<div class="clearFix"></div>
	</div-->

		
	<div id="mainWrapper">
			
			<div id="mainPage">
				
					<!-- Left Column Starts-->
					<?php 
					$stime = microtime(true);
					
					$this->load->view('categoryList/categoryPageHidden'); 
					if(EN_LOG_FLAG) error_log("\narray( section => 'Preparing_View :categoryPageHidden', timetaken => ".(microtime(true) - $stime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
					
					
					?>
					<?php
					$stime = microtime(true);
						$data = Array();
						$data['output'] = "";
						$data['output'] = Modules::run('categoryList/CategoryList/pageSourceInfo');
						$data['h1Title']= $mediaData['h1Title'];
						//Modules::run('categoryList/CategoryList/forTrialDummyController');
						$this->load->view('categoryList/categoryPageLeft', $data);
						if(EN_LOG_FLAG) error_log("\narray( section => 'Preparing_View :categoryPageLeft', timetaken => ".(microtime(true) - $stime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
							
					?>
					<!-- Left Column Ends-->
					<!-- Right Column Starts-->
					<?php 
					$stime = microtime(true);
					
					$this->load->view('categoryList/categoryPageRight'); 
					
					if(EN_LOG_FLAG) error_log("\narray( section => 'Preparing_View :categoryPageRight', timetaken => ".(microtime(true) - $stime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
					
					?>
					<!-- Right Column Ends-->
					<div class="clearFix"></div>
			</div>
			
			<!--Add-----to----compare---->
			
			<?php 
			$stime = microtime(true);
			
			if(EN_LOG_FLAG) error_log("\narray( section => 'Preparing_View :generateCollegeCompareTool', timetaken => ".(microtime(true) - $stime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
					
			
			?>
                        
			<!--------end------------>
			
			<div id="comparePage">
					<?php 
					$stime = microtime(true);
					
					$this->load->view('categoryList/categoryPageCompare');
					if(EN_LOG_FLAG) error_log("\narray( section => 'Preparing_View :categoryPageCompare', timetaken => ".(microtime(true) - $stime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
			
					?>
			</div>
	</div>
	<?php
		$this->load->library('user_agent');
		if (!($this->agent->browser() == 'Internet Explorer' and $this->agent->version() <= 6)){
	?>
	<div id="adsense" style="position:absolute;width:300px;bottom:0px;left:670px">
		<?php
			$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'FOOTER');
			$this->load->view('common/banner',$bannerProperties);
		?>
	</div>
	<?php
		}
	?>
		
</div>
<?php
		//$this->load->view('categoryList/locationlayer');
$stime = microtime(true);
		$this->load->view('categoryList/multiLocationLayer');
	
		if(EN_LOG_FLAG) error_log("\narray( section => 'Preparing_View :multiLocationLayer', timetaken => ".(microtime(true) - $stime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
			
		$stime = microtime(true);
		$this->load->view('categoryList/changeLayers');
		
		if(EN_LOG_FLAG) error_log("\narray( section => 'Preparing_View :changeLayers', timetaken => ".(microtime(true) - $stime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		
?>
<?php         
                global $filters;
		global $locationname;
		if($request->getCategoryId() != 2) {
				$stime = microtime(true);
				echo Modules::run('categoryList/CategoryList/showFatFooter', $request);
				if(EN_LOG_FLAG) error_log("\narray( section => 'Preparing_View :showFatFooter', timetaken => ".(microtime(true) - $stime).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);
		
		}
		$this->load->view('common/footerNew',array('loadJQUERY' => 'YES'));
		$this->load->view('categoryList/categoryPageJquerySlider');
?>
<script>
var focusInMultiLayer = false;
$j(document).ready(function(){
		$j('.scrollbar1').tinyscrollbar();
		$j(document).on('click',function(e) {
		if (!$j('#dim_bg').is(':visible') && focusInMultiLayer == false && $j('#multiLocationLayer').is(':visible'))
		{
				if(alertExceptionToShowLocationLayer == false)
				{
				 dissolveOverlayHackForIE();
				 $j('#multiLocationLayer').hide();
				}
		}
		});

// added by akhter
// desc: suffle fatfooterlink if citylist < statelist
if($j('#_ctyLst ul>li').length>0 && ($j('#_ctyLst ul>li').length < $j('#_stLst ul>li').length)){
	var cityHtml = $j('#_ctyLst').html();
	var stateHtml = $j('#_stLst').html();
    $j('#_ctyLst').html(stateHtml);
    $j('#_stLst').html(cityHtml);
    $j('#_ctyLst div').css('width',$j('#_stLst div').css('width'));		
}
 
});
</script>
<script>
        var isSourceRegistration = <?php if($isSourceRegistration && $subcat_id_course_page == 23) { echo 'true'; } else { echo 'false'; } ?>;
	
	if (isSourceRegistration) {
		$('changeLocationdiv').style.visibility='visible';
		$('dim_bg').style.display = 'none';
		$('multiLocationLayer').style.display = 'none';
		$('multiLocationLayer').onmouseover = function(){this.style.display=''; overlayHackLayerForIE('multiLocationLayer', document.getElementById('multiLocationLayer'));}
		$('multiLocationLayer').onmouseout = function(){dissolveOverlayHackForIE();this.style.display='none';}
	}
	
	showHideResetLinks();
</script>
