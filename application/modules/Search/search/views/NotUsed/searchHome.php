<?php
	$shikshaCriteria = array();
	if ($regularSearch=="Yes") {  
	  $keyword = isset($_REQUEST['keyword'])  ? $_REQUEST['keyword'] : '';
	  $shikshaCriteria = array('keyword'=>urlencode($_REQUEST['keyword']),'location'=>urlencode($_REQUEST['location']));
	  $title = 'Shiksha.com- Search Results – Education – College – University – Study Abroad – Scholarships – Education Events – Admissions - Notifications -'.htmlspecialchars($keyword);
	  $metaTitle = "";
	  $metDescription = 'Search Shiksha.com for Colleges, University, Institutes, Foreign Education programs and information to study in India. Find course / program details, admissions, scholarships of universities of India and from countries all over the world -'.htmlspecialchars($keyword);
	  $metaKeywords = 'Shiksha, Study, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships -'.htmlspecialchars($keyword);
	} else {
	  if(($collagePresentFlag == 1) && (strtolower(trim($location)) != "india")) {
		$headerTag = strtolower(htmlspecialchars($keyword)." in ".$location);
		$title = strtolower(htmlspecialchars($keyword).' in '.$location.', '.htmlspecialchars($keyword).' Courses in '.$location.', '.htmlspecialchars($keyword).' Institutes in '.$location.'. '.htmlspecialchars($keyword).' Diploma, Distance/Part Time-Correspondence Degree & Certificate courses in India.');
		$metDescription = htmlspecialchars($keyword).' in '.$location.', '.htmlspecialchars($keyword).' Courses in '.$location.', '.htmlspecialchars($keyword).' Institutes in '.$location.'. List of '.htmlspecialchars($keyword).' Diploma, Distance/Part Time-Correspondence Degree & Certificate courses. Best '.$location.' '.htmlspecialchars($keyword).' Colleges details with contact number.';
		$metaKeywords = htmlspecialchars($keyword).' , '.htmlspecialchars($keyword).' Institutes, '.htmlspecialchars($keyword).' Universities, Institutes of '.htmlspecialchars($keyword).',  '.htmlspecialchars($keyword).' colleges in '.$location.', Top '.htmlspecialchars($keyword).' colleges, Best '.htmlspecialchars($keyword).' colleges, College of '.htmlspecialchars($keyword).', Institutes of '.htmlspecialchars($keyword).', '.htmlspecialchars($keyword).' Colleges list,  list of '.htmlspecialchars($keyword).' colleges';
	  } else if(strtolower(trim($location)) != "india") {
		$headerTag = strtolower(htmlspecialchars($keyword)." Colleges in ".$location);
		$title = strtolower(htmlspecialchars($keyword).' Colleges in '.$location.', '.htmlspecialchars($keyword).' Courses in '.$location.', '.htmlspecialchars($keyword).' Institutes in '.$location.'. '.htmlspecialchars($keyword).' Diploma, Distance/Part Time-Correspondence Degree & Certificate courses in India.');
		$metDescription = htmlspecialchars($keyword).' Colleges in '.$location.', '.htmlspecialchars($keyword).' Courses in '.$location.', '.htmlspecialchars($keyword).' Institutes in '.$location.'. List of '.htmlspecialchars($keyword).' Diploma, Distance/Part Time-Correspondence Degree & Certificate courses. Best '.$location.' '.htmlspecialchars($keyword).' Colleges details with contact number.';
		$metaKeywords = htmlspecialchars($keyword).' colleges, '.htmlspecialchars($keyword).' Institutes, '.htmlspecialchars($keyword).' Universities, Institutes of '.htmlspecialchars($keyword).',  '.htmlspecialchars($keyword).' colleges in '.$location.', Top '.htmlspecialchars($keyword).' colleges, Best '.htmlspecialchars($keyword).' colleges, College of '.htmlspecialchars($keyword).', Institutes of '.htmlspecialchars($keyword).', '.htmlspecialchars($keyword).' Colleges list,  list of '.htmlspecialchars($keyword).' colleges';
	  } else {
		$headerTag = strtolower(htmlspecialchars($keyword).' Colleges in India');  
		$title = strtolower(htmlspecialchars($keyword).' Colleges in India, '.htmlspecialchars($keyword).' Courses in India, '.htmlspecialchars($keyword).' Institutes in India. '.htmlspecialchars($keyword).' Diploma, Distance/Part Time-Correspondence Degree & Certificate courses in India.');
		$metDescription = htmlspecialchars($keyword).' Colleges in India, '.htmlspecialchars($keyword).' Courses in India, '.htmlspecialchars($keyword).' Institutes in India. List of '.htmlspecialchars($keyword).' Diploma, Distance/Part Time-Correspondence Degree & Certificate courses. Best '.htmlspecialchars($keyword).' Colleges details with contact number.';
		$metaKeywords = htmlspecialchars($keyword).' colleges, '.htmlspecialchars($keyword).' Institutes, '.htmlspecialchars($keyword).' Universities, Institutes of '.htmlspecialchars($keyword).',  '.htmlspecialchars($keyword).' colleges in India, Top '.htmlspecialchars($keyword).' colleges, Best '.htmlspecialchars($keyword).' colleges, College of '.htmlspecialchars($keyword).', Institutes of '.htmlspecialchars($keyword).', '.htmlspecialchars($keyword).' Colleges list,  list of '.htmlspecialchars($keyword).' colleges';
	  }
	}
	$headerComponents = array(
							//'css'	=>	array(
							//			'raised_all',
							//			'header',
							//			'mainStyle',
                            //            'modal-message'
							//		),
							'css'	=> array('search'),
							'js'	=>	array('common','search','cityList','EduList','lazyload','multipleapply','customCityList'),
							'title'	=>	$title,
                            'taburl' =>  site_url() ,
							'tabName'	=>	'Shiksha',
							'bannerProperties' => array('pageId'=>'SEARCH', 'pageZone'=>'HEADER','shikshaCriteria'=>$shikshaCriteria),
							'product'	=>	'search',
							'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
							'metaDescription'	=>	$metDescription,
							'metaKeywords'	=>	$metaKeywords,
							'metaTitle' => ''
						);
    if(isset($partnerPage))
    {
        $headerComponents['partnerPage'] = $partnerPage;
    }
	$this->load->view('common/homepage', $headerComponents);
?>
<script>
	var listings_with_localities = <?php echo $listings_with_localities; ?>;
	//var categoryTreeMain = eval(<?php echo $category_tree; ?>);
    /* Multiple Apply button start */
        LazyLoad.loadOnce([
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>',
        '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("user"); ?>'
    ],callbackfn);
/* Multiple Apply button end */
function changeproductKey(id,keyname){
    try {
            if(navigator.userAgent.indexOf('MSIE')>=0) {
                if($('countOffset_DD1')) 
                document.getElementById('countOffset_DD1').parentNode.style.display = 'none';
                if($('countOffset_DD2')) 
                document.getElementById('countOffset_DD2').parentNode.style.display = 'none';
            }
            calloverlayInstitute(id,keyname);
    } catch (ex) {
        if (debugMode){
            throw ex;
        } else {
            logJSErrors(ex);
        }
    }   
}
</script>
<!--<div id="hack_ie_operation_aborted_error">
</div>-->
<?php
if($keyword != '') {
    $keyword .= ' - ';
}
if(isset($_REQUEST['searchType']) || isset($searchType))
{
	$productSetChoice = isset($_REQUEST['searchType'])?$_REQUEST['searchType']:$searchType;
    switch($productSetChoice)
    {
        case 'institute': $shikshaproduct="Institutes ";
                          break;
        case 'course': $shikshaproduct="Courses";
                       break;
        case 'Event': $shikshaproduct="Important Dates ";
                      break;
        case 'forums': $shikshaproduct="Ask & Answer";
                       break;
        case 'question': $shikshaproduct="Ask & Answer";
                       break;
        case 'ask': $shikshaproduct="Ask & Answer";
                       break;
        case 'Category':$categorySelect=$_REQUEST['cat_id'];
                        global $categoryParentMap;
                        foreach($categoryParentMap as $k=>$v)
                        {
                            if($v['id']==$categorySelect)
                            {
                                $shikshaproduct=$k." Category";
                            }
                        }
                        break;
        case 'foreign': $shikshaproduct = "Study Abroad ";
                        break;
        case 'testprep':$shikshaproduct = "Test Preparation ";
                        break;
        case 'blog':$shikshaproduct = "Articles";
                    break;
        case 'scholarship':$shikshaproduct = "Scholarships ";
                    break;
        case 'schoolgroups':$shikshaproduct = "SchoolGroups";
                    break;
        case 'collegegroup':$shikshaproduct = "CollegeGroups";
                    break;
        default: $shikshaproduct="All";
                 break;
    }
}
else
{
    $shikshaproduct="All";
}
?>
<?php if(($_REQUEST['searchType'] == 'course') || ($regularSearch=="No")) { 
 $this->load->view('search/searchHomeLeftPanel',$leftPanelData); 
}
?>
<input type="hidden" name="unified_search_identifier" id= "unified_search_identifier" value="search"/>
<!--End_Refne_Search-->
<div class="clearFix spacer15"></div>
<div class="mar_full_10p">
		<?php if($regularSearch=="No") { ?>
		  <div class="blackFont bld searchSeoHeaderTag">
			<h1><?php if(! $showHeaderTag){ echo $headerTag;} ?></h1>
		  </div>
		<?php } ?>  
    	<div>
    <?php if(($searchList['relaxedFlag']!=1 && $searchList['relaxedFlag']!=2) || $searchList['numOfRecords']==0) { ?>
        <div id="keyword_results" class="searchResultHeading highlightColor">
            <span class="blackFont bld font_size14p">Total</span>
                <label id="searchResultsCount" class="orangeFont" style="font-size:18px">
                <?php echo $searchList['numOfRecords'];?>
                </label>
                <span class="blackFont bld font_size14p">
                <?php
                if($shikshaproduct == 'Articles')
                {
                    if($searchList['numOfRecords'] == 1) 
                    {

                        echo "Article";
                    }
                    else
                    {
                        echo "Articles";
                    }
                    echo  "<span class=\"blackFont bld font_size14p\"> Found</span>";
                }
                elseif($shikshaproduct == 'Courses')
                {
                    if($searchList['numOfRecords'] == 1) 
                    {
                        echo "Institute";
                    }
                    else
                    {
                        echo "Institutes";
                    }
                    echo  "<span class=\"blackFont bld font_size14p\"> found</span>";
                }
                else
                {
                    if($searchList['numOfRecords'] == 1) 
                    {
                        echo "Result"; 
                    }
                    else
                    {
                        echo "Results"; 
                    }
                }
                    ?>
            </span>
                <?php
                if($shikshaproduct == "Courses")
                {
                    if($searchList['numOfCourse'] !=0)
                    {
                        echo "<span class=\"blackFont bld font_size14p\"> offering </span><label id=\"searchCourseCount\" class=\"orangeFont\" style=\"font-size:18px\">".$searchList['numOfCourse']."</label><span class=\"blackFont bld font_size14p\"> Courses</span>";
                    }
                }
                ?>
            <span class="blackFont bld font_size14p">
            <?php if($shikshaproduct == "Ask & Answer") {
                echo " in 'Ask & Answer'";
                }
                ?>
            </span>
            <span id="searchAlertDiv" class="fontSize_12p blackFont" style="font-weight:normal">
            </span>
        </div>
        <div class="lineSpace_22"></div>
        <div class="float_R" style="line-height:25px;">
            <input type="hidden" id="methodName" value="getAnotherPage"/>
            <div class="pagingID" id="paginataionPlace1" style="float:left"></div>
            <span style="margin-right: 180px;"> &nbsp; View:
                <select class="selectTxt" name="countOffset" id="countOffset_DD1" onChange= "updateCountOffset(this,'startOffSetSearch','countOffsetSearch');">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
                <option value="50">50</option>
                </select>
            </span>
        </div>
            <?php
           }
           else if($searchList['relaxedFlag']==1)
           {
           ?>
        <span class="blackFont fontSize_14p">Found&nbsp;no&nbsp;results</span>
        <label id="searchTitleHeading" class="fontSize_14p"><?php echo htmlspecialchars($_REQUEST['keyword']);?></label>
        <?php if(trim($_REQUEST['location'])!= "") { ?>
            <strong><span class="blackFont fontSize_14p"> in </span></strong>
                <label id="searchLocationHeading" class="fontSize_14p"><?php echo htmlspecialchars($_REQUEST['location']);?></label>
                <?php } ?>
   <?php /*if($shikshaproduct!="All") { ?>
       <span class="blackFont" style="font-weight:bold;">&nbsp;under <span id="searchTypeForHeadDisplay"><?php echo $shikshaproduct;?></span></span>
   <?php
    }*/
    ?>
        <span id="searchAlertDiv" class="fontSize_12p blueFont" style="font-weight:normal"></span>
        <div style="font-wieght:normal;font-size:15px">
        <span class="blackFont" style="font-weight:normal">However we are showing</span>&nbsp;<label id="searchResultsCount"><?php echo $searchList['numOfRecords'];?></label>&nbsp;<?php if($searchList['numOfRecords'] == 1) { echo "result"; } else { echo "results"; } ?> for any of these keywords&nbsp;<label class="OrgangeFont"><?php echo htmlspecialchars($searchList['altKeyWord']);?></label>
                        <?php
                    }
                    elseif($searchList['relaxedFlag']==2)
                    {
                    ?>
					<span class="blackFont fontSize_14p">Found&nbsp;no&nbsp;results for</span>
					<label id="searchTitleHeading" class="fontSize_14p"><?php echo htmlspecialchars($keyword);?></label>
                    <?php if(trim($location)!= "") { ?>
					<strong><span class="blackFont fontSize_14p"> in </span></strong>
					<label id="searchLocationHeading" class="fontSize_14p"><?php echo htmlspecialchars($location);?></label>
                    <?php } ?>
					<?php /*if($shikshaproduct!="All") { ?>
						<span class="blackFont fontSize_14p" style="font-weight:bold;">&nbsp;under <span id="searchTypeForHeadDisplay"><?php echo $shikshaproduct;?></span></span>
                    <?php
                        }*/
                        ?>
                        <span id="searchAlertDiv" class="fontSize_14p blueFont" style="font-weight:normal"></span>
                        <div style="font-wieght:normal;font-size:15px">
                        <span class="blackFont" style="font-weight:normal">However, we are showing</span>&nbsp;<label id="searchResultsCount"><?php echo $searchList['numOfRecords'];?></label>&nbsp;<?php if($searchList['numOfRecords'] == 1) { echo "result"; } else { echo "results"; } ?>&nbsp;<label class="OrgangeFont"><?php echo htmlspecialchars($searchList['altKeyWord']);?></label>
                        <?php
                    }
                    ?>
                    <div>
                    <?php if($searchType != 'ask') {?>
            <span class="blackFont" style="margin-right:1px">
                Keywords : 
            </span>
            <label id="searchTitleHeading" >
                <span class="OrgangeFont bld">
                    <?php echo htmlspecialchars($keyword);?>
                </span>
            </label>
            <?php if(!isset($searchType) || trim($searchType)== "course") { ?>
            <span class="blackFont" style="margin-right:1px">
                | Location : 
            </span>
            <label id="searchLocationHeading">
                <span class="OrgangeFont bld" id="searchAtBlock">
                   <?php if($location!="") {echo $location;} else echo "All";?> 
                </span>
            </label>
            <?php } ?>
            <?php if(!isset($searchType) || trim($searchType)== "course") { ?>
            <span class="blackFont" style="margin-right:1px">
    			| Course Type: 
            </span>
           <label id="course_Type">
                <span class="OrgangeFont bld">
                   <?php if($cType!=-1) {echo ucwords(implode(' ',explode('-',$cType)));} else echo "All";?> 
                </span>
            </label>
			<span class="blackFont" style="margin-right:1px">
                | Course Level: 
            </span>
	    <label id="course_Level">
                <span class="OrgangeFont bld">
                    <?php if($courseLevel!=-1) {echo ucwords(implode(' ',explode('-',$courseLevel)));} else echo "All"?> &nbsp;
            	</span>
	    </label>
            <?php } ?>
                        <?php } ?>
            <?php if($shikshaproduct!="All") { ?>
               <!-- <span class="blackFont" style="font-weight:bold;">&nbsp; <span id="searchTypeForHeadDisplay"><?php echo $shikshaproduct;?></span></span> -->
            <?php } ?> 
        </div>  

			</div>
			<div id="errorDiv"></div>
			<div class="lineSpace_1">&nbsp;</div>
			<div>
<!--				<div class="searchResultHeading highlightColor float_L" style="line-height:25px;font-size:13px">
					<span class="blackFont">Showing results for:&nbsp;</span>
					<?php if(isset($_REQUEST['subType']) && $_REQUEST['subType'] != '' && $_REQUEST['subType']!= '0') { ?>
					<span class="dgreencolor">&nbsp;Result type:&nbsp;</span><span style="font-weight:normal;"><span id="searchTypeBlock" class="blackFont"><?php echo $_REQUEST['subType']?></span> &nbsp; </span>
					<?php } else { ?>
					<span class="dgreencolor">&nbsp;Result type:&nbsp;</span><span style="font-weight:normal;"><span id="searchTypeBlock" class="blackFont">All</span> &nbsp; </span>
					<?php }?>
					<span class="dgreencolor">&nbsp;Location:&nbsp;</span><span style="font-weight:normal;" id="searchAtBlock" class="blackFont">All &nbsp; </span>
					<span class="dgreencolor" style="display:none">&nbsp;Category:&nbsp;</span><span style="font-weight:normal;display:none" id="searchInBlock" class="blackFont">All</span>&nbsp;
					<span id="suggestionDiv"></span>
				</div>-->
				<div class="clear_B"></div>
			</div>
			<div class="lineSpace_2">&nbsp;</div>			
		</div>
<div class="dottedLine" style="margin:0 10px"><img src="/public/images/dotted.gif"/></div>
<div>
	<div style="width:154px;float:right; padding-right:10px">
			<?php
				if(isset($validateuser[0]['cookiestr']))
				{
					$userEmailArray=explode("|",$validateuser[0]['cookiestr']);
					$userEmail=$userEmailArray[0];
				}
				else
				{
					$userEmail="";
				}
				if(isset($validateuser[0]['mobile']))
				{
					$usermobile=$validateuser[0]['mobile'];
				}
				else
				{
					 $usermobile="";
				}
				$overlayComponents = array(
										'userEmailAddress'=>$userEmail,
										'userMobileNumber'=>$usermobile
										);
				$this->load->view('search/searchOverlay',$overlayComponents);
				$this->load->view('search/searchRequestInfo',$overlayComponents);
				$this->load->view('search/searchHomeRightPanel');
			?>	
	</div>
	<?php
		if(TRACK_SEARCH_RESULTS) {
			$this->load->view('search/searchHomeListPanelWithTracking',$searchList);	
		} else {
			$this->load->view('search/searchHomeListPanel',$searchList);	
		}
	?>
</div>
<div class="clear_L lineSpace_5">&nbsp;</div>
<div class="mar_full_10p dottedLine" style="margin-right:164px"><img src="/public/images/dotted.gif"/></div>
<div class="lineSpace_5">&nbsp;</div>
</div>


<div class="clearFix"></div>
<div style="margin-left:10px; width:790px">
	<div style="line-height:30px; margin-right:14px;">
		<div class="lineSpace_10">&nbsp;</div>				
		<div align="right">
			<span style="margin-right:22px">
				<span class="pagingID" id="paginataionPlace2"></span>
			</span>
			<span class="normaltxt_11p_blk bld pd_Right_6p">View: 
				<select class="selectTxt" name="countOffset" id="countOffset_DD2" onChange= "updateCountOffset(this,'startOffSetSearch','countOffsetSearch');">
					<option value="10">10</option>
					<option value="15">15</option>
					<option value="20">20</option>
					<option value="25">25</option>
                    <option value="50">50</option>
				</select>
			</span>
			<div class="lineSpace_15">&nbsp;</div>
		</div>
	</div>
	<!--pagination-->
	<div  class="txt_align_c ">
		<div id="wide_ad_unit"  style="text-align:left;margin:auto;"></div>
	</div>
</div>
<?php // $this->load->view('home/category/HomeGoogleAdSense'); ?>

<div class="clearFix spacer20"></div>

<script>
    doPagination(<?php echo $searchList['numOfRecords'];?>,'startOffSetSearch','countOffsetSearch','paginataionPlace1','paginataionPlace2','methodName',4);
    var SITE_URL = '<?php echo base_url(); ?>/';
    selectComboBox(document.getElementById('countOffset_DD2'), <?php echo $countOffsetSearch;  ?>)
    selectComboBox(document.getElementById('countOffset_DD1'), <?php echo $countOffsetSearch;  ?>)
    //loadRelatedSearch();
    loadFeaturedColleges();
    <?php if($_REQUEST['searchType']!="groups" && $_REQUEST['searchType']!="collegegroup" && $_REQUEST['searchType']!="schoolgroups" &&
    $_REQUEST['searchType']!="ask"
    )
    {
        if($validateuser=="false")
        { 
            ?>
                document.getElementById('searchAlertDiv').innerHTML='[ <a style="font-weight: normal; font-size: 12px;" class="btn-submit6" id="saveSearchButtonPara" href="javascript:void(0);" onClick="showuserLoginOverLay(this,\'SHIKSHA_SEARCH_SAVE\',\'refresh\');">Save Search</a> ]';
            <?php
        }
        else
        {
            if($validateuser[0]['quicksignuser']==1)
            {
                $base64url = base64_encode($_SERVER['REQUEST_URI']);
                $quickClickAction = "javascript:location.replace(\'/user/Userregistration/index/".$base64url."/1\');";
                ?>
                    document.getElementById('searchAlertDiv').innerHTML='[ <a style="font-weight: normal; font-size: 12px;" class="btn-submit6" id="saveSearchButtonPara" href="#" onClick="<?php echo $quickClickAction?>">Save Search</a> ]';
                <?php
            }
            else
            {
                ?>
                    loadSearchAlertButton();
                <?php
            }
        }
    }
    ?>

    loadSuggestion();
<?php if($_REQUEST['searchType'] == 'course') { ?>
	if(document.getElementById('country')){
		document.getElementById('searchAtBlock').innerHTML=document.getElementById('country').value; 
	}
	if(document.getElementById('demo_from') && document.getElementById('demo_to')){
		document.getElementById('duration').innerHTML=document.getElementById('demo_from').innerHTML+'-'+document.getElementById('demo_to').innerHTML; 
	}
	if((!document.getElementById('type')) && (!document.getElementById('country')) &&(!document.getElementById('demo_from')) &&(!document.getElementById('demo_to')) && (!document.getElementById('courseLevelDiv')) && (!document.getElementById('courseTypeDiv'))){
		document.getElementById('leftPanelDiv').style.display='none';	
	} 

<?php } ?>
</script>

<?php
	$bannerProperties = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/inviteMail');
	$this->load->view('common/footer', $bannerProperties);
	$channelId = isset($_REQUEST['channelId']) ? $_REQUEST['channelId'] : 'home_page';
	$adsProperties = array('keyword'=> addslashes($_REQUEST['keyword']." ".$_REQUEST['location']), 'channelId'=>$channelId);
	$this->load->view('search/search_google', $adsProperties);
?>
