<?php
				$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
				global $onlineFormsDepartments;
				
				$criteriaArray = array(
                                    'category' => $categoryIdForBanner,
                                     'country' => '',
                                     'city' => '',
                                     'keyword'=>'');
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
				   $headerComponents = array(
				      'css'	=>	array('online-styles','header','raised_all','common'),
				      'js' 	=>	array('common','ana_common','myShiksha','onlinetooltip','CalendarPopup','onlineForms','ajax-api'),
				      'title'	=>	$onlineFormsDepartments[$department]['shortName'].' Application Forms: Apply online via Shiksha',
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>'Apply online to top '.$onlineFormsDepartments[$department]['shortName'].' colleges of your choice at Shiksha.com. Fill online application form once & apply to multiple '.$onlineFormsDepartments[$department]['shortName'].' colleges to get admission.',	
				      'metaKeywords'	=>'college admission, online '.$onlineFormsDepartments[$department]['shortName'].' application, Online '.$onlineFormsDepartments[$department]['shortName'].' application form, online application, apply online, '.$onlineFormsDepartments[$department]['shortName'].' admission, online admission form, list of colleges, engineering admission, college admission form, online admission process, Online college admission form, online application forms, Online college admission, list of institutes, online admission forms, list of online colleges, application form online',
				      'product'	=>'online',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'callShiksha'=>1,
				      'notShowSearch' => true,
                                      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
                                      'showBottomMargin' => false,
				      'showApplicationFormHomepage' => true,
				      'canonicalURL' => $current_page_url
				   );
				   
				   foreach($aspiration_array as $y=>$aspiration){
								$aspiration_array[$y]['student_aspiration'] = str_replace("MBA",$onlineFormsDepartments[$department]['shortName'],$aspiration_array[$y]['student_aspiration']);
				   }
   $this->load->view('common/header', $headerComponents); ?>
   <!--div style="padding: 15px 10px 0px 10px; clear: left;">
		  <?php                       
		       //echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $subcat_id_course_page, $course_pages_tabselected, TRUE); ?>
   </div-->
   <?php 
   //$this->load->view('common/calendardiv');
   $this->load->view('Online/showLoginForm');

?>
<script>
var isUserLoggedInSystem = '<?php echo $userId;?>';
var urlToRedirect = '';
var is_processed1 = true;
var is_processed2 = true;
var is_processed = true;
</script>



<div id="appsFormWrapper">
	
    <div id="homeContentWrapper">
				
				<!--paytm-message-widget-->
				<?php
				global $usersForPaytmTesting;
				if(OF_PAYTM_INTEGRATION_FLAG == 1 || in_array($userId,$usersForPaytmTesting)){
					echo Modules::run('Online/OnlineForms/paytmWidget');
				}
				?>
				<!--end-widget-->
				
				
				<div class="create-profile-sec clearFix">
								<strong class="best-college-title">The best way to apply to <?=$onlineFormsDepartments[$department]['shortName']?> colleges</strong>
					<ul>
								<li>
												<div class="create-profile-img flLt">
													<i class="bestCollege"></i>			
												</div>
												<div class="create-profile-col">
																<strong>Best Colleges</strong>
																<p>Top <?php echo $onlineFormsDepartments[$department]['shortName']?> colleges of your choice, all at one place</p>
												</div>
								</li>
								<li>
												<div class="create-profile-img flLt">
													<i class="multipleSubmissions"></i>			
												</div>
												<div class="create-profile-col">
																<strong>Multiple Submissions</strong>
																<p>Fill once, apply to multiple colleges</p>
												</div>
								</li>
								<li>
												<div class="create-profile-img flLt">
													<i class="saveTime"></i>			
												</div>
												<div class="create-profile-col">
																<strong>Save time</strong>
																<p>Cut the queue, submit forms online conveniently</p>
												</div>
								</li>
								<li style="margin-right:0">
												<div class="create-profile-img flLt">
													<i class="trackSubmissions"></i>			
												</div>
												<div class="create-profile-col">
																<strong>Track Submissions</strong>
																<p>Know which stage of application process your form is at</p>
												</div>
								</li>
					</ul>
					<div style="width:100%; float:left; text-align: center"><a href="javascript:void(0);" onClick="var formData = {'trackingKeyId':'<?php echo $regTrackingPageKeyId;?>'}; registrationForm.showRegistrationForm(formData);" uniqueattr="CreateProfiletoApplyButtonOnlineHomepage/<?php echo $department?>" class="create-profile-btn">Create Profile to Apply</a></div>
				</div>
				
				
   		
		
		
		
		<!--<div id="homeBanner">
        	<div class="figure"></div>
            <div class="aside">
            	<h1 style="font-size:30px">Apply <span>to</span> Top <?=$onlineFormsDepartments[$department]['shortName']?> Colleges Online</h1>
                
                <ul>
                	<li>
                    	<div class="bestCollege"></div>
                    	<h2>Best Colleges</h2>
                        <p>Top <?=$onlineFormsDepartments[$department]['shortName']?> colleges of your choice, all at one place</p>
                    </li>
                    <li>
                    	<div class="saveTime"></div>
                    	<h2>Save time</h2>
                        <p>Cut the queue, submit forms <br />online conveniently</p>
                    </li>
                    <li>
                    	<div class="multipleSubmissions"></div>
                    	<h2>Multiple Submissions</h2>
                        <p>Fill once, apply to multiple <br />colleges</p>
                    </li>
                    <li>
                    	<div class="trackSubmissions"></div>
                    	<h2>Track submissions</h2>
                        <p>Know which stage of application <br />process your form is at</p>
                    </li>
                </ul>
                
                <div class="createProfileBox">
                	<input class="createProfileBtn" type="button" title="Create Profile to Apply" value="Create Profile to Apply" onClick="checkUserLogin();" uniqueattr="CreateProfiletoApplyButtonOnlineHomepage/<?=$department?>"/>
			<?php if(!($userId>0)){ ?><a href="javascript:void(0);" onClick="checkUserLogin(0,true);">Already Registered. Sign In</a><?php } ?>
                </div>
                
                <div class="howItWorks">
		   <a href="javascript:void(0);" id="handleLayerHide">How it Works</a>
                    <!--How it Works Layer Starts here-->
                    <!--<div class="howitWorksLayerWrap" id="howitWorksLayerDiv" style="display:none">
                        <span class="howitWorksPointer" id="howitWorksPointer"></span>
                        <div class="howitWorksLayerContent" id="howitWorksLayerContent">
                        	<div>
                                <div class="selectCollege selectCollegeAlign"></div>
                                <div class="horArrow1"></div>
                                <div class="submitForm submitFormAligner"></div>
                                <div class="horArrow2"></div>
                                <div class="receiveForm receiveFormAligner"></div>
                                <div class="horArrow1"></div>
                                <div class="getUpdates getUpdatesAligner"></div>
                                <div class="horArrow2"></div>
                                <div class="onlineResult"></div>
                            </div>    
                                <ul class="howWorksLayerDetail">
                                    <li class="firstItem">
                                        <strong>Select Colleges </strong>
                                        <p>Compare and shortlist colleges that you wish to apply</p>
                                        
                                    </li>
                                    
                                    <li class="secItem">
                                        <strong>Submit form</strong>
                                        <p>Fill the application form once and use for multiple college applications. Also, attach documents and make online payment</p>
                                        
                                    </li>
                                    
                                    <li class="thirdItem">
                                        <strong>Institute receives form</strong>
                                        <p>Institute receives and reviews your form. You get instant update as soon as institute reviews the form</p>
                                        
                                    </li>
                                    
                                    <li class="fourthItem">
                                        <strong>Get <?=$onlineFormsDepartments[$department]['gdPiName']?> Updates</strong>
                                        <p>Institute sends the <?=$onlineFormsDepartments[$department]['gdPiName']?> updates. You also track your application status at all the stages of admission process</p>
                                        
                                    </li>
                                    
                                    <li class="fifthItem">
                                        <strong>Know your result online</strong>
                                        <p>Get updated about the final decision of the institute towards your admission application</p>
                                    </li>
                                </ul>
				<div class="clearFix"></div>
				<div class="studentNotice">Shiksha.com facilitates application form submission and tracking throughout online process. It does not, however, guarantees admissions. The final decision lies with the <br />institute itself.</div>
				
				<div class="howitWorkBtn"><input type="button" value="Start Now" title="Start Now" class="startNowBtn" onClick="checkUserLogin();"/></div>
                                
                        </div>
                    </div>
                    <!--How it Works Layer Ends here-->
                <!-- </div>
            </div>
		</div>--> 
        <div class="clearFix spacer20"></div>
        <div id="appHomeLeftCol">
        	<div class="shadedBox">
            	<h3>How it Works?</h3>
                <ul class="howWorksDetail">
                	<li>
                    	<strong>Select Colleges </strong>
                        <p>Compare and shortlist colleges that you wish to apply</p>
                        <div class="selColImgBox">
                            <div class="selectCollege"></div>
                            <div class="vertArrow1"></div>
                        </div>
                    </li>
                    
                    <li>
                    	<strong>Submit form and make payment</strong>
                        <p>Fill the application form once and use for multiple college applications. Also, attach documents and make online payment</p>
                        <div class="submitImgBox">
                            <div class="vertArrow2"></div>
                            <div class="submitForm"></div>
                            
                        </div>
                    </li>
                    
                    <li>
                    	<strong>College receives form</strong>
                        <p>College receives and reviews your form. You get instant update as soon as college reviews the form</p>
                        <div class="receiveImgBox">
                            <div class="receiveForm"></div>
                            <div class="vertArrow1"></div>
                        </div>
                    </li>
                    
                    <li>
                    	<strong>Get  <?php echo $onlineFormsDepartments[$department]['gdPiName']?> Updates</strong>
                        <p>Colleges sends the  <?php echo $onlineFormsDepartments[$department]['gdPiName']?> updates. You also track your application status at all the stages of admission process</p>
                        <div class="getUpdateImgBox">
                            <div class="vertArrow2"></div>
                            <div class="getUpdates"></div>
                            
                        </div>
                    </li>
                    
                    <li>
                    	<strong>Know your result online</strong>
                        <p>Get updated about the final decision of the college towards your admission application</p>
                        <div class="onlineImgBox">
                            <div class="onlineResult"></div>
                        </div>
                    </li>
                    
                    
                </ul>
                <div class="clearFix"></div>
                <div class="studentNotice">Shiksha.com facilitates application form submission and tracking throught online process. It does not, however, guarantees admissions. The final decision lies with the college itself.</div>
                
                <div class="howitWorkBtn"><input type="button" value="Start Now" title="Start Now" class="startNowBtn" onClick="var formData = {'trackingKeyId':'<?php echo $bottomregTrackingPageKeyId;?>'}; registrationForm.showRegistrationForm(formData);"/></div>
            </div>
            
            <div class="quickGuide">
            	<div class="pin"></div>
            	<h3>Quick Start Guides</h3>
                <ul>
                	<li><a onclick="showFaqLayer();" href="javascript:void(0);">What is Shiksha Online Application Forms?</a></li>
                    <li><a onclick="showFaqLayer();" href="#actualForm">Are these the actual forms? </a></li>
                    <li><a onclick="showFaqLayer();" href="#HowDoIStart">How do I get started?</a></li>
                    <li><a onclick="showFaqLayer();" href="#onlinePayment">What is online payment?</a></li>
                    <li><a onclick="showFaqLayer();" href="#creditCardQues">What If I don't have a credit card?</a></li>
                </ul>
            </div>
            
        </div>
		
        <div id="appHomeRightCol">
				<div id="page-tabs">
					<ul>
				<?php
								foreach($onlineFormsDepartments as $key=>$departments){
												//if($key=='Engineering') continue;
												if($key == $department){
																$class = 'active';
												}else{
																$class = '';
												}
				
				?>
				       <?php if($tab_to_hide != $key):?>
						<li class="<?php echo $class?>"><a href="<?php echo SHIKSHA_HOME.'/'.$departments['url']?>"><?php echo $departments['shortName']?> Forms</a></li>
						<?php endif;?>
				<?php
								}
				?>
					</ul>
				</div>
		<?php if(!empty($instituteList) && is_array($instituteList)):?>
		<div class="recommendedItems2">
		<h1 style="margin-bottom:0 !important">Apply Online to <?php echo $onlineFormsDepartments[$department]['shortName']?> Colleges</h1>
		</div>

		<!-- Search and Filter options -->
		<div class="app-search-cont">
			<div class="form-exp-col">
			            <label>Forms Expiring :</label> <a href="javascript: void(0);" onClick="getInstituteListForHomepage('thisWeek');" id="thisWeekAnchor">This Week</a><span id='thisWeekSpan' style="display:none;">This Week</span> | <a href="javascript: void(0);" onClick="getInstituteListForHomepage('nextWeek');" id="nextWeekAnchor">Next Week</a><span id='nextWeekSpan' style="display:none;">Next Week</span> | <a href="javascript: void(0);" onClick="getInstituteListForHomepage('all');" id="allAnchor">All</a><span id='allSpan' style="display:none;">All</span></div>
			<div class="clearFix"></div>
		</div>
		<div id="instituteList" style="margin-top:10px;">
			<?php if($paginationHTML!=''){ ?>
        		<div>
		            <div class="pagingID txt_align_r" id="paginataionPlace1" style="line-height:23px;">&nbsp;<?php echo $paginationHTML;?></div>
		        </div>
			<?php } ?>

		        <?php $tracking['trackingPageKeyId']=$trackingPageKeyId;
                 $this->load->view('studentFormsDashBoard/common_template_across',$tracking);?>
		</div>
	        <?php endif;?>        
        </div>
    </div>
    <div class="clearFix"></div>
</div>

<div class="clearFix"></div>
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.autocomplete"); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormCommon"); ?>"></script>
<script>
        $j(document).ready(function() {
        	setScrollbarForMsNotificationLayer();

            })            

</script>

<script>
var OnlineForm = {};
OnlineForm.displayAdditionalInfoForInstitute = function (style,divId) {
	if($(divId)) {
		$(divId).style.display = style;
	}
}
</script>

<?php if(isset($showRegistrationLayer) && $showRegistrationLayer=='true'){ ?>
<script>
checkUserLogin('<?php echo $onlineCourseId;?>');
</script>
<?php } ?>
<script>
	if (window.addEventListener){
		window.addEventListener('click', handleLayerHide, false); 
	} else if (window.attachEvent){
		document.attachEvent('onclick', handleLayerHide);
	}
function handleLayerHide(e) {
	var srcElem = e.target || e.srcElement;
	if(typeof(srcElem.id) !="undefined" && srcElem.id == 'handleLayerHide') {
		showHowItWorksLayer();
	} else {
		while(srcElem) {
	        if(srcElem.id == 'howitWorksLayerContent' || srcElem.id == "howitWorksPointer") {
	            return false;
	        }
	        srcElem = srcElem.parentNode;
	    }
		hideHowItWorksLayer();
	}
}
var institute_array = '<?php echo implode(",",$caraousel_array);?>';
function pushConversionCodeForOnlineForm() {
/* Google conversion code start */
	var ifm = document.createElement("iframe");
        ifm.setAttribute("src", "/public/conversion/conversionforOnlineForm.html");
        ifm.setAttribute("height",0);
        ifm.setAttribute("width",0);
        ifm.setAttribute("border",0);
        document.body.appendChild(ifm);
/* Google conversion code end */
}
</script>
<?php
	$val = '';
	$dataval = '';
	foreach ($instituteTitlesList as $inst){
		$title = addslashes($inst['listing_title']);
		$val .= ($val=='')?"'$title'":",'$title'";
                $id = $inst['instituteId'];
                $dataval .= ($dataval=='')?"'$id'":",'$id'";
	}
?>
<script>
var department = '<?php echo $department?>';
</script>
