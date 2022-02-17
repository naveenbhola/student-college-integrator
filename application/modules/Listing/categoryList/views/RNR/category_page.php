<?php

	$CI_INSTANCE->config->load('categoryPageConfig');
	$subcategoriesForRnR = $CI_INSTANCE->config->item('CP_SUB_CATEGORY_NAME_LIST');
	$requestUrl = clone $request;
	$requestUrl->setData(array('naukrilearning'=>0));
	
	global $appliedFilters;
	global $filters;
    global $sortingCriteria;
	$filters 	= $categoryPage->getFilters();
	$appliedFilters = $request->getAppliedFilters();
	$sortingCriteria = $request->getSortingCriteria();
	
	
	//$canonicalurl = $requestUrl->getCanonicalURL($requestUrl->getPageNumberForPagination());
	/*
	 * Next page & Previous page URL generation for rel next and rel prev tags..
	 */
	$totalResults = $categoryPage->getTotalNumberOfInstitutes();
	$currentPage 	= $request->getPageNumberForPagination();
	$totalPages 	= ceil($totalResults/$request->getSnippetsPerPage());
	$previousURL 	= "";
	$nextURL 		= "";
	if($currentPage < $totalPages) {
		$nextURL = $requestUrl->getUrl($currentPage+1);
        if($requestUrl->getHideLocationLayer()){
                $nextURL = preg_replace('/\?.*/', '', $nextURL);
        }

	}
	if($currentPage > 1) {
		$previousURL = $requestUrl->getUrl($currentPage-1);
        if($requestUrl->getHideLocationLayer()){
                $previousURL = preg_replace('/\?.*/', '', $previousURL);
        }
	}
	
	$mediaData = $request->getMetaData($totalResults);
	$titleText = $mediaData['title'];
	
	/* Criteria for Banners start ------------------- */
	global $criteriaArray;
	$criteriaArray = array(
							'category' => $request->getCategoryId(),     
							'country'  => $request->getCountryId(),      
							'city'     => $request->getCityId(),  
							'keyword'  => strtoupper(preg_replace("/[^a-z0-9_]+/i", "_",$categoryPage->getSubCategory()->getName()))
						);
	if($request->getSubCategoryId() > 1) {
		if($request->isMultilocationPage()) {
			/*
			 * Before Multilocation :
			 * $criteriaArray['keyword'] = 'BMS_ALL_CITIES_'.$request->getSubCategoryId();
			 * Now, this situation is for multilocation situations.
			 * Step 1: Get list of selections
			 */
			$multiLocationLayerCookie = $request->getUserPreferredLocationOrder(true);
			
			/* Step 2: Select a random value from selections */
			$locationSelected = $multiLocationLayerCookie[array_rand($multiLocationLayerCookie)];
			$locationSelected = explode('_',$locationSelected);
			$csFlag = $locationSelected[0];
			$locationSelected = $locationSelected[1];
			
			/*
			 * Step 3: Is selection city or state?
			 * $criteriaArray['city'] = selection_city_id
			 * $criteriaArray['Keyword'] = BMS_STATE_[selection_state_id]_CATEGORY_$request->getSubCategoryId()
			 */
			if($csFlag == 'c') {
				$criteriaArray['city'] = $locationSelected;
			}
			else if($csFlag == 's') {
				$criteriaArray['city'] = 1;
				$criteriaArray['keyword']= 'BMS_STATE_'.$locationSelected.'_CATEGORY_'.$request->getSubCategoryId();
			}
		} else if($request->getCityId() == 1) {
			$criteriaArray['keyword'] = 'BMS_STATE_'.$request->getStateId().'_CATEGORY_'.$request->getSubCategoryId();
		}
	}
	/* Criteria for Banners end ------------------- */
	
	$product = 'RNRCategoryPage';
	$headerComponents = array(
							'js'					=> array('multipleapply', 'user', 'customCityList','ajax-api','category','json2'),
							'jsFooter' 		=> array('common', 'processForm'),
							'product'			=> $product,
							'taburl' 			=>  site_url(),
							'bannerProperties' => array(
														'pageId'=>'CATEGORY',
														'pageZone'=>'TOP',
														'shikshaCriteria' => $criteriaArray
														),
							'title'						=>	$titleText,
							'searchEnable' 		=> false,
							'canonicalURL' 		=> $canonicalurl,
							'metaDescription' => $mediaData['description'],
							'metaKeywords'		=> $mediaData['keywords'],
							'previousURL' 		=> $previousURL,
							'nextURL' 				=> $nextURL,
							'showBottomMargin'=> false,
							'isCategoryPage' 	=> true
	);
	
	if($request->showGutterBanner()){
		$headerComponents['showGutterBanner'] = 1;
		$headerComponents['bannerPropertiesGutter'] = array('pageId'=>'CATEGORY', 'pageZone'=>'RIGHT_GUTTER');
		$headerComponents['shikshaCriteria'] = $criteriaArray;
	}

	if($isMarketing == 'true' && $subcategoriesForRnR[$request->getSubCategoryId()]) {
		$headerComponents['noIndexMetaTag'] = true;
	}
	
	//when filter is applied
	if($request->isAJAXCall()) {
                        $this->load->view('categoryList/categoryPageHidden');
			$this->load->view('categoryList/RNR/push_down_banner');
			$this->load->view('categoryList/RNR/page_heading');
			$this->load->view('categoryList/RNR/page_content');
			exit;
	}
	
	if($request->isNaukrilearningPage()) {
		$headerComponents['bannerProperties'] =  array('pageId'=>'NAUKRI', 'pageZone'=> $pageZone = str_replace(" ","_",$categoryPage->getSubCategory()->getName()).'_TOP', 'shikshaCriteria' => $criteriaArray);
		$this->load->view('categoryList/headernaukrilearning', $headerComponents);
	} else {
		$this->load->view('common/header', $headerComponents);
	}
	echo jsb9recordServerTime('SHIKSHA_RNR_CATEGORY_PAGE', 1);
	$this->load->view('common/headerSearch', array('page_type'=> $product));
	


?>

<div id="cate-wrapper">
	<?php $this->load->view('categoryList/RNR/course_page_header'); ?>
	<div id="rnr_dynasmic_content">
                <?php $this->load->view('categoryList/categoryPageHidden'); ?>
		<?php $this->load->view('categoryList/RNR/push_down_banner'); ?>
		<?php $this->load->view('categoryList/RNR/page_heading'); ?>
		<?php $this->load->view('categoryList/RNR/page_content'); ?>
	</div>
	<div class="clearFix"></div>
</div>
<!-- course review avgRaing div -->
 <div class="avgRating-tooltip" style="display:none;">
    <i class="common-sprite avg-tip-pointer"></i>
    <p>Rating is based on actual reviews of students who studied at this college</p>
</div>
    
<?php
		$multiLocPref = json_decode(base64_decode($_COOKIE['userMultiLocPref-MainCat-'.$request->getCategoryId()]));
		if($request->getCityId() == 1 && $request->getStateId() == 1 && in_array($request->getSubCategoryId(), array(23,56)) && count($multiLocPref) < 1){
		$sTimeRNRView = microtime(true);	
		$this->load->view('categoryList/multiLocationLayer');
		if(EN_LOG_FLAG) error_log("\narray( section => 'RNR View generation : multilocation Layer ', timetaken => ".(microtime(true) - $sTimeRNRView).", url => '".$_SERVER['SCRIPT_URI']."', session => '".sessionId()."', date => '".date("d-m-y h:i:s")."'),",3,EN_CP_LOG_FILENAME);

		}
		
		$this->load->view('myShortlist/shortlistOnHoverMsg');
		
		//$this->load->view('categoryList/changeLayers');
		$this->load->view('common/footerNew',array('loadJQUERY' => 'YES'));
		
?>


<?php if($mmp_details['display_on_page'] == 'newmmpcategory') {			?>
			<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.tinyscrollbar.min.v3"); ?>"></script>
			<!-- <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('nationalCourses'); ?>" type="text/css" rel="stylesheet" /> -->
			<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('newmmp'); ?>" type="text/css" rel="stylesheet" />
<?php } ?>

<?php if($mmp_details['page_id'] != '') { ?>

<iframe name="iframe_div1" id="iframe_div1" style="width: 99%; position:absolute; display: none; top: 0; left: 0;  z-index: 1000; background-color: rgba(0, 0, 0, 0.3);" scrolling="no" allowtransparency="true"></iframe>

<div id="mmpOverlayForm" class="Overlay" style="display:none; position: fixed; top:20px;"></div>

<style>
    html.noscroll {
    position: fixed; 
    overflow-y: scroll;
    width: 100%;
}    
</style>

<script>
	
	var mmp_form_id_on_popup = '<?php echo $mmp_details['page_id']?>';
	var mmp_display_on_page = '<?php echo $mmp_details['display_on_page'];?>';
	var showpopup = '<?php echo $showpopup;?>';

	if((mmp_form_id_on_popup != '') && (($j('#multiLocationLayer') == 'undefined') || ($j('#multiLocationLayer').html() == null) || ($j('#multiLocationLayer').html() == ''))) {
	
		if(mmp_display_on_page == 'newmmpcategory') {
	
			var mmp_form_heading = '<?php echo $mmp_details['form_heading']?>';
			var displayName = '';
			var user_id = '';
			
			<?php
			if(is_array($validateuser)) {?>
			   displayName = escape("<?php echo addslashes($validateuser[0]['displayname']); ?>");
			   user_id = '<?php echo $validateuser[0]['userid'];?>';
			<?php }  ?>
			
			$j(document).ready(function(){
				disable_scroll();
				setTimeout(loadmmpform,1000);				
			});
		}
	}
	
	function loadmmpform() {
		var form_data = '';
		form_data += 'mmp_id='+mmp_form_id_on_popup;
		form_data += '&mmp_form_heading='+mmp_form_heading;
		form_data += '&isUserLoggedIn='+isUserLoggedIn;
		form_data += '&displayName='+displayName;
		form_data += '&user_id='+user_id;
		form_data += '&mmp_display_on_page='+mmp_display_on_page;
		form_data += '&exam_name='+'<?php echo $examName;?>';
		form_data += '&showpopup='+showpopup;

		$j.ajax({
			url: "/registration/Forms/loadmmponpopup",
			type: 'POST',
			async:false,
			data:form_data,
			success:function(result) {
				showMMPOverlay('530','860','',result);
				ajax_parseJs($('mmpOverlayForm'));
				setTimeout(enable_scroll,1000);
			}
		});
	}

    function showMMPOverlay(overlayWidth, overlayHeight, overlayTitle, overlayContent, modalLess, left, top) {
            
        if(trim(overlayContent) == '')
                return false;
        
        var body = document.getElementsByTagName('body')[0];
        
        $('iframe_div1').style.height = body.offsetHeight+'px';
        $('iframe_div1').style.width = body.offsetWidth+20+'px';
		$('iframe_div1').style.display = 'block';            
        
        $('mmpOverlayForm').innerHTML = overlayContent;
        $('mmpOverlayForm').style.width = overlayWidth + 'px';
        $('mmpOverlayForm').style.height = overlayHeight + 'px';

        var divX;                
        if(typeof left != 'undefined') {
           divX = left;
        } else {
           divX = (parseInt(body.offsetWidth)/2) - (overlayWidth/2);
        } 

        $('mmpOverlayForm').style.left = divX + 'px';
        $('mmpOverlayForm').style.top =  '20px';

        overlayHackLayerForIE('mmpOverlayForm', body);
        $('mmpOverlayForm').style.display = 'block';
    }

</script>

<?php } ?>

<script>
		$j(document).ready(function(){

			setScrollbarForMsNotificationLayer();
			$j('.scrollbar1').tinyscrollbar();
			<?php 
			$sortingOptions = $request->getSortingCriteria();
			if($sortingOptions['sortBy'] == 'examscore') {
		    $sortType = 'examscore';
		    $sortOrder = $sortingOptions['params']['exam']."_".strtolower($sortingOptions['params']['order']); 
		    echo "loadUserSelectedSortersOnCategoryPage('".$sortType."','". $sortOrder."');";
			} else {
            echo "loadUserSelectedSortersOnCategoryPage();";
            }
          	?>
          	$j("#sort_exam_layer").hide();   
			
		});
    var isSourceRegistration = <?php if($isSourceRegistration && $subcat_id_course_page == 23) { echo 'true'; } else { echo 'false'; } ?>;
    
		/*if (isSourceRegistration) {
			if($j('#changeLocationdiv').length > 0) { 
			$('changeLocationdiv').style.visibility='visible';
			}
			if($j('#dim_bg').length > 0) {
			$('dim_bg').style.display = 'none';
			}
			if($j('#multiLocationLayer').length > 0) {
			$('multiLocationLayer').style.display = 'none';
			$('multiLocationLayer').onmouseover = function(){this.style.display=''; overlayHackLayerForIE('multiLocationLayer', document.getElementById('multiLocationLayer'));}
			$('multiLocationLayer').onmouseout = function(){dissolveOverlayHackForIE();this.style.display='none';}
			}
		}*/
		
		//showHideResetLinks();
		// code added for new inline
		var new_rnr_cat_page = "yes";
		var categoryPage_SubCat = "<?=$request->getSubCategoryId();?>";
		$j(document).ready(function($j) {
			
		var reco_layer_show_cookie = getCookie('show_inline_reco');
		
		if(reco_layer_show_cookie) {	
				var temp_array = reco_layer_show_cookie.split("*****");
				var div_id = document.getElementById(temp_array[0]);	
				//console.log(temp_array);
				$j.post(temp_array[1], function(recoHTML) {		
					//console.log('insideajex');
					//console.log(recoHTML);
					if (recoHTML.search('div') >= 0) {
						
						if(typeof hideSimilarCoursesTupleIfInlineRecoComes == 'function') {
							hideSimilarCoursesTupleIfInlineRecoComes(temp_array[2]);
						}
					
						div_id.innerHTML = recoHTML;
						ajax_parseJs(div_id);
						div_id.style.display = 'block';
					}
					
				});
				setCookie('show_inline_reco',""); 	
		}
		
	   });	
		
</script>
