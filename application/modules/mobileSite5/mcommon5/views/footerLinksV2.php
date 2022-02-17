<div id="chat-container"></div>
<?php 
	$this->load->view('mcommon5/footerJsCssFiles');
?>
<?php $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'footer'));?>
<?php /*$this->load->view('mcommon5/jeeMainResultBanner');*/ ?>
<footer class="main-footer" id="page-footer" data-enhance="false">
    <ul class="clearfix" id="accordian-list">
		<li>
			<a id="flC" href="javascript:void(0);" >Streams <i class="msprite footer-arr-dwn"></i></a>
			<div class="link-box" style="display:none;" id="stream-links" >
				<a href="<?php echo SHIKSHA_HOME?>/business-management-studies/colleges/colleges-india
">BUSINESS & MANAGEMENT STUDIES</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/engineering/colleges/colleges-india
">ENGINEERING</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/design/colleges/colleges-india
">DESIGN</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/hospitality-travel/colleges/colleges-india
">HOSPITALITY & TRAVEL</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/law/colleges/colleges-india
">LAW</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/animation/colleges/colleges-india
">ANIMATION</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/mass-communication-media/colleges/colleges-india
">MASS COMMUNICATION & MEDIA</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/it-software/colleges/colleges-india
">IT & SOFTWARE</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/humanities-social-sciences/colleges/colleges-india
">HUMANITIES & SOCIAL SCIENCES</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/arts-fine-visual-performing/colleges/colleges-india
">ARTS ( FINE / VISUAL / PERFORMING )</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/science/colleges/colleges-india
">SCIENCE</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/architecture-planning/colleges/colleges-india
">ARCHITECTURE & PLANNING</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/accounting-commerce/colleges/colleges-india
">ACCOUNTING & COMMERCE</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/banking-finance-insurance/colleges/colleges-india
">BANKING, FINANCE & INSURANCE</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/aviation/colleges/colleges-india
">AVIATION</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/teaching-education/colleges/colleges-india
">TEACHING & EDUCATION</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/nursing/colleges/colleges-india
">NURSING</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/medicine-health-sciences/colleges/colleges-india
">MEDICINE & HEALTH SCIENCES</a><span class="link-sep"> | </span>
				<a href="<?php echo SHIKSHA_HOME?>/beauty-fitness/colleges/colleges-india">BEAUTY & FITNESS</a><span class="link-sep"> | </span>
                <a href="<?=SHIKSHA_HOME?>/sarkari-exams/exams-st-21">SARKARI EXAMS</a>
			</div>
		</li>
		<li>
<?php
         function examYear($examName, $examArray){
             if(isset($examArray[$examName])){
                 return ' '.$examArray[$examName];
             }
         }
         $examPageLib = $this->load->library('examPages/ExamPageLib');
         $examYearArr = $examPageLib->getExamYears();
?>

			<a id="flE" href="javascript:void(0);">Top Exams <i class="msprite footer-arr-dwn" ></i></a>
			<div id="exam-links" style="display:none;" class="link-box" >
				<p class="quick-link-head" style="margin-top:10px;">MBA</p>
				<div>
					<a href="<?php echo SHIKSHA_HOME?>/mba/cat-exam">CAT<?=examYear('cat',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/mba/cmat-exam">CMAT<?=examYear('cmat',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/mba/snap-exam">SNAP<?=examYear('snap',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/mba/xat-exam">XAT<?=examYear('xat',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/mba/mat-exam">MAT<?=examYear('mat',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/mba/nmat-by-gmac-exam">NMAT by GMAC<?=examYear('nmat by gmac',$examYearArr);?></a><span class="link-sep">
				</div>

				<p class="quick-link-head" style="margin-top:10px;">Engineering</p>
				<div>
					<a href="<?php echo SHIKSHA_HOME?>/b-tech/jee-main-exam">JEE Main<?=examYear('jee main',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/b-tech/jee-main-exam-results">JEE Main<?=examYear('jee main',$examYearArr);?> Result</a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/b-tech/comedk-uget-exam">COMEDK<?=examYear('comedk uget',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/engineering/gate-exam">GATE<?=examYear('gate',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/b-tech/wbjee-exam">WBJEE<?=examYear('wbjee',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/b-tech/upsee-exam">UPSEE<?=examYear('upsee',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/b-tech/jee-advanced-exam">JEE ADVANCED<?=examYear('jee advanced',$examYearArr);?></a><span class="link-sep"> | </span>
					<a href="<?php echo SHIKSHA_HOME?>/university/lovely-professional-university/lpu-nest-exam">LPU-NEST<?=examYear('lpu nest',$examYearArr);?></a>
				</div>
			</div>
		</li>
         <?php
         $articleLib = $this->load->library('article/ArticleUtilityLib');
         $footerLinks = $articleLib->getFooterCustomizedLinks();
         if(count($footerLinks) > 0){
            $i = 0;
         ?>
                <li>
                        <a id="flIU" href="javascript:void(0);">Important Updates<i class="msprite footer-arr-dwn" ></i></a>
                        <div class="link-box" id="important-updates-links" style="display:none;">
                           <?php foreach ($footerLinks as $link){ ?>
                            <a title = "<?=$link['name']?>" href="<?=$link['URL']?>"><?=$link['name']?></a>
                           <?php
                              if(!(++$i === count($footerLinks))) {
                                  echo '<span class="link-sep"> | </span>';
                              }

                            } ?>
                            
                        </div>
                </li>
         <?php } ?>

		<li>
			<a id="flSA" href="javascript:void(0);">Study Abroad <i class="msprite footer-arr-dwn" ></i></a>	
			<div id="abroad-links" style="display:none;" class="link-box" >
				<p class="quick-link-head" style="margin-top:10px;">Courses</p>
				<div>
					<a title = "BE/BTech abroad" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/be-btech-in-abroad-dc11510">BE/Btech</a><span class="link-sep"> | </span>
					<a title = "MS" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/ms-in-abroad-dc11509">MS</a><span class="link-sep"> | </span>
					<a title = "MBA" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/mba-in-abroad-dc11508">MBA</a><span class="link-sep"> | </span>
					<a title = "Law" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/bachelors-of-law-in-abroad-cl1245">Law</a><span class="link-sep"> | </span>
					<a title = "All Courses" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>">All Courses</a>
				</div>
			
				<p class="quick-link-head" style="margin-top:10px;">Countries</p>
				<div>
					<a title = "USA" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/usa">USA</a><span class="link-sep"> | </span>
					<a title = "UK" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/uk">UK</a><span class="link-sep"> | </span>
					<a title = "Canada" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/canada">Canada</a><span class="link-sep"> | </span>
					<a title = "Australia" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/australia">Australia</a><span class="link-sep"> | </span>
					<a title = "All Countries" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/abroad-countries-countryhome">All Countries</a>
				</div>

				<p class="quick-link-head" style="margin-top:10px;">Exams</p>
				<div>
					<a title = "GRE" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/gre">GRE</a><span class="link-sep"> | </span>
					<a title = "GMAT" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/gmat">GMAT</a><span class="link-sep"> | </span>
					<a title = "SAT" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/sat">SAT</a><span class="link-sep"> | </span>
					<a title = "IELTS" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/ielts">IELTS</a><span class="link-sep"> | </span>
					<a title = "TOEFL" href="<?php echo SHIKSHA_STUDYABROAD_HOME?>/exams/toefl">TOEFL</a>
				</div>
			</div>
        </li>
		<li ><a id="flH" href="javascript:void(0);">Help <i class="msprite footer-arr-dwn" ></i></a>
			<div class="link-box" id="help-links" style="display:none;" >
			    
				<a title="About Us" href="<?php echo $this->config->item('aboutusUrl'); ?>">About us</a><span class="link-sep"> | </span>
				<a title="Careers With Us" href="https://careers.shiksha.com/">Careers With Us</a><span class="link-sep"> | </span>
				<a id="flSHB" title = "Student HelpLine" href="<?php echo $this->config->item('helplineUrl'); ?>">Student helpline</a><span class="link-sep"> | </span>
				<a title="Privacy" href="<?php echo $this->config->item('policyUrl'); ?>">Privacy policy</a><span class="link-sep"> | </span>
				<a title="Contact Us" href="<?php echo $this->config->item('contactusUrl'); ?>">Contact Us</a><span class="link-sep"> | </span>
				<a title="Terms of use" href="<?php echo $this->config->item('termUrl'); ?>">Terms of Use</a><span class="link-sep"> | </span>
				<a title="Shiksha Sitemap" href="<?php echo SHIKSHA_HOME.'/sitemap'; ?>">Sitemap</a><span class="link-sep">
			</div>
		</li>
    </ul>
    <section class="tac footer-child">
       	<nav class="partner-nav">
           	<strong>Partner Sites</strong>
			<p><a href="http://www.naukri.com" target="_blank">Jobs</a> | <a href="http://www.firstnaukri.com" target="_blank">Jobs for freshers</a> | <a href="http://www.naukrigulf.com" target="_blank">Jobs in MIddle East</a> | <a href="http://www.99acres.com" target="_blank"> Real Estate</a> | <a href="http://www.allcheckdeals.com" target="_blank" rel="nofollow">Real Estate Agents</a> |  <a href="http://www.jeevansathi.com" target="_blank">Matrimonial</a> | <a href="http://www.policybazaar.com" target="_blank" rel="nofollow"> Insurance Comparsion</a> |  <a href="http://www.meritnation.com" target="_blank" rel="nofollow">School Online</a> | <a href="http://www.brijj.com" target="_blank" rel="nofollow">Brijj </a> | <a href="http://www.zomato.com" target="_blank" rel="nofollow"> Zomato </a> | <a href="http://www.mydala.com/" target="_blank" rel="nofollow">mydala - Best deals in India</a> | <a href="http://www.ambitionbox.com/" target="_blank">Ambition Box</a></p>
		</nav>
	    <aside>
		    Trade Marks belong to the respective owners.<br >Copyright &copy; <?php echo date('Y'); ?> Infoedge India Ltd. All rights reserved
	    </aside>
    </section>


</footer>

<div class="select-Class" data-enhance='false'>
    <select name="hamburgerLocationDiv" max-limit="15" show-search="1" id="hamburgerLocationDiv" style="display:none;"></select>
</div>

<?php
global $isWebViewCall;
if(!$isWebViewCall){ ?>
<script>	
	$(window).load(function()
	{
		getCookieBanner();
	});
</script>	
<?php } ?>

<script>	
var pageName = '<?php echo $boomr_pageid;?>';
<?php if($boomr_pageid == 'Mobile5ExamPage') { ?>
	if(typeof pageTracker != "undefined") {
		//set tracking params
		var gaTrackCategory 	 = '<?php if(is_object($examPageData)){ echo $examPageData->getCategoryName(); }?>';
		var gaTrackExamName 	 = '<?php echo $trackMainExamName?>';
		var gaTrackSection	 	 = '<?php echo $pageType?>';
		var trackingParam = gaTrackCategory + "/" + gaTrackExamName + "/" + gaTrackSection + "_MOBILE";
		//var object = new showTrackingMessage("GA tracking Started tracking for " + trackingParam);
		//logJSErrors(object);
		pageTracker._setCustomVar(1, "NationalExamPageTrack", trackingParam, 2);
		pageTracker._setCustomVar(5, "NationalExamPageTrack", trackingParam, 3);
		pageTracker._trackEventNonInteractive('dummyExamPageTracking', gaTrackCategory, gaTrackExamName + '-' + gaTrackSection, 0, true);
		//var object = new showTrackingMessage("GA tracking Ended tracking for " + trackingParam);
		//logJSErrors(object);
	}
<?php } 
if(isset($boomr_pageid) && ($boomr_pageid == "mobilesite_AnA_QDP" || $boomr_pageid == "mobilesite_AnA_DDP")){?>
	var doPagination = false, reviewPaginationFlag = false;
<?php } ?>
window.paginationCallUnderway = false;
$(document).on('touchmove',function(){
<?php if(isset($boomr_pageid) && ($boomr_pageid == "mobilesite_AnA_QDP" || $boomr_pageid == "mobilesite_AnA_DDP"))  { ?>
	if(doPagination && reviewPaginationFlag && $(window).scrollTop() > $("#lazyLoadDiv").offset().top - 1000){
		reviewPaginationFlag = false
		var start = $('#startIndex').val();
		var countIndex = $('#countIndex').val();	
		var startIndex = parseInt(start) + parseInt(countIndex);
		$("#startIndex").val(startIndex);
		var totalCount = $('#commentCount').val();
		var totalPages = Math.ceil(totalCount/countIndex);
		var parentId = $('#parentId').val();
		var entityType = $('#entityType').val();
		if(doPagination && (pageNo < totalPages)){
			$("#loader-id").show();
				$.ajax({
				        url:'/mAnA5/AnAMobile/getCommentDetails',
				        type: "POST",
				        data: {'parentId':parentId, 'startIndex':startIndex,'countIndex':countIndex,'entityType':entityType},
				        success: function(result){
							    pageNo++;
							    $("#loader-id").hide();
							    $('#commentContainer > div.last-new-cards').removeClass('last-new-cards').addClass('new-cards');
							    $('#commentContainer').append(result);
               					$('#commentContainer > div.new-cards').last('div').addClass('last-new-cards').removeClass('new-cards');
							    
							    reviewPaginationFlag = true;
							    
							    if(pageNo == totalPages) {
									reviewPaginationFlag = true;	
									doPagination = false;
							    }
		
							},
					    });		
		}
	}
	
	if(doPagination && UserListPaginationFlag && $(window).scrollTop() > $("#userListLazyLoadDiv").offset().top - 900){
		UserListPaginationFlag = false;
		var startIndex = $('#startUserList').val();
		var count = $('#countUserList').val();	
		var start = parseInt(startIndex) + parseInt(count);
		$("#startUserList").val(start);
		var totalCount = $('#userTotalCount').val();
		var totalPages = Math.ceil(totalCount/count);
		var entityId = $('#entityIdUserList').val();
		var entityType = $('#entityTypeUserList').val();
		var actionType = $('#actIonForUserList').val();
		if(pageNoUserList < totalPages){
				$("#loader-id").show();
				$.ajax({
				        url:'mAnA5/AnAMobile/getListOfUsersBasedOnAction',
				        type: "POST",
				        data: {'entityId':entityId, 'start':start,'count':count,'entityType':entityType,'actionType':actionType},
				        success: function(result){
							    pageNoUserList++;
							    $("#loader-id").hide();
							    $('#userListContainer').append(result);
							    
							    UserListPaginationFlag = true;
							    
							    if(pageNoUserList == totalPages){
									doPagination = false;
							    }
		
							},
					    });		
		} else {
			UserListPaginationFlag = true;
		}

		
	}
 <?php } ?>
 <?php if(in_array($boomr_pageid, array('college_predictor_rank'))) { ?>

	 	if(typeof(startingPoint) =='undefined') {
			startingPoint = 0;
		}
		if(searchCall){ /*check added to disable search for cases where user is present on landing page */
			if(startingPoint>=0 && !window.paginationCallUnderway && $('#wrapper').is(":visible") == true){
				if($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
				    $('#loading').show();
		            window.paginationCallUnderway = true;  
				    trackEventByGAMobile('HTML5_College_Predictor_LoadMore');
				    if(typeof searchCollegePredictor1 != 'undefined' && logged_in_userid != -1 && parseInt(totalResults) > startingPoint+10){
				    	searchCollegePredictor1(startingPoint+10, 1,'','',examName);
					}
				}		
		    }
		}
<?php } ?>
 <?php if(in_array($boomr_pageid, array('college_cutoff_page'))) { ?>

	 	if(typeof(start) =='undefined') {
			start = 20;
		}
		 /*check added to disable search for cases where user is present on landing page */
		if(start>=0 && !window.paginationCallUnderway && $('#wrapper').is(":visible") == true){
			if($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
		        window.paginationCallUnderway = true; 
			    if(parseInt(totalResults) > start){
			    	lazyLoadCutoffData();
				}
			}		
		}
		
<?php } ?>
});

</script>
<input type="hidden" id="footerExecuted" value="true">
<?php   

    //code for sticky registration layer at bottom (article pages)
    global $pagesToShowBtmRegLyr;
    if($_REQUEST['test'] != '1234'){
    	if( $validateuser['userid']<1 && in_array($beaconTrackData['pageIdentifier'],$pagesToShowBtmRegLyr) ){
        	$this->load->view('mcommon5/bottomStickyRegistrationLayer'); 
   		 }
    }
?>
