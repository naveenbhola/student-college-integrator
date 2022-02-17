<?php
	$courseCodeFlag = false;
	if(isset($_COOKIE['collegePredictor_mobile_examName']) && !empty($_COOKIE['collegePredictor_mobile_examName'])) {
		$examName = $_COOKIE['collegePredictor_mobile_examName'];
	}
	
	if($examName == 'JEE-Mains') {
		$couresCodeText = 'CSAB Course code';
		$courseCodeFlag = true;
	}
    /*else if($examName == 'MPPET'){
     $couresCodeText = 'Course code';   
    }*/
    //else if($examName == 'CGPET' ){
        //$couresCodeText = 'Course code';    
    //}
    else if($examName != 'JEE-Mains') {
        $courseCodeFlag = true;
    }
    $shortlistedCoursesOfUser = array();
    if(isset($validateuser[0]['userid'])) {
    $shortlistedCoursesOfUser =  Modules::run('myShortlist/MyShortlist/getShortlistedCourse',$validateuser[0]['userid']); 
    }
?>

<?php
if(count($resultInformation)<=0 && $start==0 && $totalResultsApplyFilter=="-1"){
    setcookie('collegepredictor_search','',time() + 2592000,'/',COOKIEDOMAIN);
    if($rankPredictor == '1'){
?>
    <section id="no-result-found" class="content-wrap2 clearfix">
        <article class="req-bro-box serach-result clearfix">
            <div class="details">
                <div class="institute-name" style="margin-bottom: 30px;margin-top: 30px;"><span>No Colleges found.</span></div>
            </div>
        </article>
    </section>
<?php
    }
    else{
?>
    <section id="no-result-found" class="content-wrap2 clearfix" onclick="showSearchForm('<?php echo $examName;?>');">
        <article class="req-bro-box serach-result clearfix">
            <div class="details">
                <div class="institute-name" style="margin-bottom: 30px;margin-top: 30px;"><span><a href="javascript:void(0);" >Please modify your search by clicking here</a></span></div>
            </div>
        </article>
    </section>
    <?php } ?>
<?php
}else if(count($resultInformation)<=0 && $start==0 && $totalResultsApplyFilter!="-1"){
?>
<script>displayNoMore = 'false';</script>
<nav id="no-result-filter" class="clearfix" style="margin-top:5px;padding-top:0.6em">
<p>Sorry, no results were found. <br>
<a href="javascript:void(0);" onclick="showSearchForm('<?php echo $examName;?>')">Modify Search</a>
                                                 / <a onclick="$('#examFilterOverlayOpen').click();" href="javascript:void(0);">Modify Filters</a>
                                                .</p>
</nav>

<?php
}
else if(count($resultInformation)<=0){
?>    <!-- No more results Div -->
    <nav id="no-result" class="clearfix"><p>No more results</p></nav>
<?php
}

if(isset($_COOKIE['MOB_A_C'])){
    $appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);
}
$cmpDataStr     = $_COOKIE['mob-compare-global-data'];
            $comparedCourseIdsFromCookie = array();
            if(!empty($cmpDataStr)){
            $cookieArr = explode('|',$cmpDataStr);
                for($i = 0; $i<count($cookieArr); $i++){
                    if($cookieArr[$i] != ''){ 
                        $strArr      = explode('::',$cookieArr[$i]);
                        $comparedCourseIdsFromCookie[] = $strArr[0];
                    }
                }
            }

$count = 0;
$displayedTupleCount = 0;
foreach ($resultInformation as $college){
    
    $roundsInfo = $college->getRoundsInfo();
    foreach ($roundsInfo as $roundData) {
        $closingRank = $roundData['closingRank'];
        $roundNumber = $roundData['round'];
        if($closingRank == 0) {
            continue;
        }
?>

    <section class="content-wrap2 clearfix">
        <article class="req-bro-box serach-result clearfix">

            <?php
            $shikshaCourseId = (int) $college->getShikshaCourseId();

            $urlAvailable = true;
            if($shikshaCourseId>0){
                    $course = $courseRepository->find($shikshaCourseId);
                    $url = $course->getURL();
                    $nofollow = false;
            }
            else {
            	if($college->getCollegeName() == 'Department of Collegiate Education (DCE)') {
            		error_log('HERE2');
            	}
            	 
            	$nofollow = true;
            	$url = $college->getInstCourseLink();
            	if(empty($url) || $url == 'NULL') {
            		$url = $college->getInstLink();
            	}
            	
            	 
            }
            
            if($url == 'NULL') {
            	$urlAvailable = false;
            }else{
		      if (!preg_match("~^(ht)tps?://~i", $url)) {
		          $url = "https://" . $url;
    		  }
	    }
            	
            ?>

            <div class="details">
		<div class="comp-detail-item">
            	<?php if($urlAvailable):?>
                <a <?php if($nofollow){echo 'rel="nofollow"';} ?> href="<?=$url?>" onClick="trackEventByGAMobile('HTML5_College_Predictor_URLRedirect');" style="color: #000000;">
                    <div class="institute-name" style="margin-bottom: 0px;"><h3><?=$college->getCollegeName()?><span>, <?=$college->getCityName()?></span></h3></div>
                    <p class="course-name"><?=$college->getBranchName()?></p>
                    <ul>
			<?php if(!$courseCodeFlag){ ?>
                        <li>
                            <i class="icon-eligible"></i>
                            <p><label><?php echo $couresCodeText;?>:</label> <?=$college->getCourseCode()?></p>
                        </li>
                        <?php } ?>
                        <li>
                            <i class="sprite icon-closerank"></i>
                            <p><label>Closing <?php echo $inputType; ?>:</label> <?=$closingRank;?></p>
                            </li>
			<?php
			      $roundNumArr = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5', '6'=>'6');
			      if($round=='all'){
      			?>
			<li>
                            <i class="sprite icon-round"></i>
                            <p><label>Round:</label> <?="Round ".$roundNumber;?></p>
                        </li>
				<?php } ?>
                    </ul>
                </a>
                <?php else:?>
	                    <div class="institute-name" style="margin-bottom: 0px;"><h3><?=$college->getCollegeName()?><span>, <?=$college->getCityName()?></span></h3></div>
	                    <p class="course-name"><?=$college->getBranchName()?></p>
	                    <ul>
				<?php if(!$courseCodeFlag){ ?>
				<li>
	                            <i class="icon-eligible"></i>
	                            <p><label><?php echo $couresCodeText;?>:</label> <?=$college->getCourseCode()?></p>
	                        </li>
	                        <?php } ?>
	                        <li>
	                            <i class="sprite icon-closerank"></i>
	                            <p><label>Closing <?php echo $inputType ;?>:</label> <?=$closingRank;?></p>
	                            </li>
				<?php
				      $roundNumArr = array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5', '6'=>'6');
				      if($round=='all'){
	      			?>
				<li>
	                            <i class="sprite icon-round"></i>
	                            <p><label>Round:</label> <?="Round ".$roundNumber;?></p>
	                        </li>
					<?php } ?>
	                    </ul>
                <?php endif;?>
		
		               
		</div>
		
				<!----shortlist-course---->
				
				<?php
				if($shikshaCourseId>0){
					$data['courseId'] = $course->getId();
					$data['pageType'] = 'mobileCollegePredictorPage';
                    $data['tracking_keyid']=$shortlistTrackingPageKeyId;
                    if($data['courseId']>0){
                        //$this->load->view('/mcommon5/mobileShortlistStar',$data);
                        if(in_array($data['courseId'], $shortlistedCoursesOfUser)){
                            $class = 'sprite shortlisted-star';
                            $Shortlist = 'Shortlisted';
                        }else{
                            $class = 'sprite shortlist-star';
                            $Shortlist = 'Shortlist';
                        }
                    ?>
                    <div class="side-col" id="shortlistDiv<?php echo $data['courseId'];?>" onclick="var customParam = {'shortlistCallback':'shortlistCallbackCP', 'shortlistCallbackParam':{'obj':this}, 'trackingKeyId':'<?php echo $shortlistTrackingPageKeyId;?>', 'pageType':'mobileCollegePredictorPage'}; myShortlistObj.checkCourseForShortlist('<?php echo $data['courseId'];?>', customParam); event.preventDefault(); event.stopPropagation();">
                        <span class="<?php echo $class;?> <?php echo 'allChkShortlisted'.$data['courseId'];?>" id="shortlistedStar<?php echo $data['courseId'];?>"></span>
                        <span id="shortlistedText<?php echo $data['courseId'];?>" class="<?php echo 'allChkShortlistedText'.$data['courseId'];?>"><?php echo $Shortlist;?></span>
                    </div>
                    <?php 
                    }
				}
				?>
				
				<!-----end-shortlist------>
		
            </div>
            
            <?php if($shikshaCourseId>0){ ?>
                                        <!-- Request Brochure begins -->
                                        <div id= "thanksMsg<?=$shikshaCourseId?>_<?=$roundNumber;?>" class="thnx-msg" <?php if(!in_array($shikshaCourseId,$appliedCourseArr)){?>style="display:none"<?php } ?>>
                                                <i class="icon-tick"></i>
                                                <p>Thank you for your request. You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</p>
                                        </div>

                                        <?php
                                                $institute = $course->getInstituteName();
                                                $institute_id = $course->getInstituteId();
                                                $courseList =  Modules::run('mranking5/RankingMain/getCourses',$institute_id);
                                                if($courseList){
                                                        $institute = reset($instituteRepository->findWithCourses(array($institute_id => $courseList)));
                                                        $courses = $institute->getCourses();
                                                }
                                                $pageName = 'COLLEGE_PREDICTOR_PAGE';
                                                $from_where = 'MOBILE5_COLLEGE_PREDICTOR_PAGE';
						if($rankPredictor == '1'){
	                                                $pageName = 'RANK_PREDICTOR_PAGE';
        	                                        $from_where = 'MOBILE5_RANK_PREDICTOR_PAGE';
						}
                                        ?>
                                        <p>
                                            <?php 
					if(in_array($shikshaCourseId,$appliedCourseArr)){?>
                                            <a class="button blue small disabled" href="javascript:void(0);" id="request_e_brochure<?php echo $shikshaCourseId;?>_<?php echo $roundNumber;?>"><i class="icon-pencil" aria-hidden="true"></i><span style="color:#ffffff;font-size:1.0em">Request Brochure</span></a>
                                            <?php }else{ 
                                                $trackingPageKeyId = ($downloadtrackingPageKeyId) ? $downloadtrackingPageKeyId  : $trackingPageKeyId;
                                            ?>
                                            <a  class="button blue small" href="javascript:void(0);" id="request_e_brochure<?php echo $shikshaCourseId;?>_<?=$roundNumber?>" onClick="setValueOfRoundForREB('<?=$roundNumber?>');trackReqEbrochureClick('<?php echo $shikshaCourseId;?>');responseForm.showResponseForm('<?php echo $shikshaCourseId;?>','<?php echo $from_where;?>','course',{'trackingKeyId': <?php echo $trackingPageKeyId;?>,'callbackObj':'predictorPageObj','callbackFunction': 'downloadBrochurePredictorPage','callbackFunctionParams': {'courseId':'<?php echo $shikshaCourseId; ?>','roundNumber':'<?=$roundNumber;?>','shikshaSiteCurrentURL':'<?php echo $shiksha_site_current_url;?>'}},{})
;setCookie('hide_recommendation','yes',30); "><i class="icon-pencil" aria-hidden="true"></i><span style="color:#ffffff;font-size:1.0em">Request Brochure</span></a>
                                            <?php } 
					?>
					       
					        <!--Add-to-compare-->
						<?php
            if(count($comparedCourseIdsFromCookie)>0 && in_array($shikshaCourseId,$comparedCourseIdsFromCookie))
            {
                $cmBtn = 'style="display: none;"';
                $addBtn = 'style="display: block;"';
                }else{
                $cmBtn = 'style="display: block;"';
                $addBtn = 'style="display: none;"';
            }

                            if(($course->getInstituteId() > 0)&& ($course->getId() > 0)){
                        ?>

<input type="hidden" name="compare" id="compare<?php echo $course->getInstituteId();?>-<?php echo $shikshaCourseId;?>" value="<?php echo $course->getInstituteId().'::'.$shikshaCourseId;?>"/>
    <a class="button gray small flRt btnCmpGlobal<?php echo $shikshaCourseId;?>"  
   href="javascript:void(0);" 
    onclick="myCompareObj.addToCompare({'courseId' : '<?php echo $shikshaCourseId;?>' ,'instituteId':'<?php echo $course->getInstituteId();?>','tracking_keyid' :'<?php echo $comparetrackingPageKeyId;?>','customCallBack':'collegePredictorCourseCompare.compareCallBackForPredictor'}, this, {'obj':this});
 return false;" id="compare<?php echo $course->getInstituteId();?>-<?=$shikshaCourseId;?>lable" <?php echo $cmBtn;?> >
    <strong id="plus-icon<?=$shikshaCourseId?>" class="plus-icon"></strong>
    <span style="margin-left: 24px; position: relative; top: 1px;">Compare</span>
    </a>
    <a href="javascript:void(0);" 
    id="compare<?php echo $course->getInstituteId();?>-<?=$shikshaCourseId;?>added" 
    onclick="
    myCompareObj.addToCompare({'courseId' : '<?php echo $shikshaCourseId;?>' ,'instituteId':'<?php echo $course->getInstituteId();?>','tracking_keyid' :'<?php echo $comparetrackingPageKeyId;?>','customCallBack':'collegePredictorCourseCompare.removeItem'}, this, {'obj':this});
    return false;
    " class="button gray small flRt btnCmpGlobalAdded<?=$shikshaCourseId;?>" <?php echo $addBtn;?> > <i class="sprite added-icn"></i><span style="relative; top: 1px;">Added</span></a>
                           <?php } ?>
        </p>
            <?php } ?>
        </article>
    </section>
<?php
$displayedTupleCount++;
    if($displayedTupleCount == 3)
    {
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA'));
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'LAA1'));
    }
    if($displayedTupleCount == 6)
    {
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON'));
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'AON1'));
    }
}
}
?>
<?php if($totalResults>=0){
if(isset($_COOKIE['collegepredictor_filterTypeValueData_mobile5_'.$examName]) && $_COOKIE['collegepredictor_filterTypeValueData_mobile5_'.$examName]!=''){
echo "<script>var filterTypeDataStatus  = 'true';</script>";
}else{
echo "<script>var filterTypeDataStatus  = 'false';</script>";    
}    
?>
<script>
function setTotalResultsAJAX(){
$('#totalResultsSection').html('<?php echo $totalResults; ?> options available');
$("#totalResultsSection").show();
}
if(callFilters && (typeof(showFilters) != "undefined" && showFilters=='YES') ){
    getFilterByAjax('examsFilterDiv',filterTypeDataStatus);
    callFilters = false;
}
</script>
<?php }
if($showFiltersOnMobile=='YES'){
if($totalResults<=0 && $totalResultsApplyFilter=="-1"){ ?>
    <script>setTimeout(function(){$('#showFilterButton').hide();},300);</script>    
<?php }else{
    echo "<script>$('#showFilterButton').show();</script>";    
}
}
?>
<script>
    var predictorPageObj = new predictorPageClass();
    var totalResultsApplyFilter = '<?php echo $totalResultsApplyFilter;?>';
    if(displayNoMore=='false'){
	$('#no-result').hide();
	$('#emailDiv').hide();
    }else{
	$('#no-result').show();
    }
</script>
