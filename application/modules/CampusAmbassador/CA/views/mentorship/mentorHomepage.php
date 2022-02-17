<?php
$tempJsArray = array('myShiksha','user');
$headerComponents = array(
                'css'   => array('mentorship-page'),
                'js' => array('common','facebook','ajax-api','imageUpload','ana_common','processForm','mentor'),
                'jsFooter'=>    $tempJsArray,
                'title' =>      $m_meta_title,
                'metaDescription' => $m_meta_description,
                'canonicalURL' =>$canonicalURL,
                'product'       =>'mentor',
                'showBottomMargin' => false,
                'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

);

$this->load->view('common/header', $headerComponents);

?>
<script  type="text/javascript"
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script>
        $j = jQuery.noConflict();
        var topRankedInstitutes = '';
        var mostViewedInstitutes = '';
        var trendingInstitutes = '';
</script>
<?php $dataForHeaderMentor = array('collegePredictor' => true, 'course_pages_tabselected' => 'studentMentor');?>
<?php $this->load->view( 'messageBoard/headerPanelForAnA',$dataForHeaderMentor);?>

<div id="content-wrapper-div">
	<div class="wrapperFxd">
			<div class="mentorship-wrapper">
            	<div class="mentorship-widget">
				  <img src="public/images/mentorship-image.jpg" width="516" height="410" alt="mentorship-img" class="flLt" />
                  <div class="flRt mentor-details">
                  	<h1>Planning for Engineering..<br /><span>And confused?</span></h1>
                    <p class="get-mentor-title">Get a mentor</p>
                    <p>Who will guide you through the entire engineering preparation and college selection process.</p>
                    <?php if(isset($isMentee) && !$isMentee){?><a href="javascript:void(0);" onclick="goToForm('<?php echo $topTrackingPageKeyId;?>'); trackEventByGA('GET_A_MENTOR_HOMEPAGE_TOP_CLICK','GET_A_MENTOR_HOMEPAGE_TOP');" class="get-mentor-btn">GET A MENTOR</a><?php }?>
                  </div>
  				</div>
                
                <div class="mentorship-widget widget-padding">
                	<h2>Who are these mentors? </h2>
                    <div class="clear-width tac"><i class="mentorship-sprite mentor-student-icon"></i></div>
                    <h3 class="current-eng-title">
                        <?php if(isset($totalMentor['totalMentor']) && $totalMentor['totalMentor'] > 100){?><span class="current-count"><?php echo $totalMentor['totalMentor'];?></span><?php }?> Current Engineering Students</h3>
                    <ul class="mentor-count-list">
                    	<li>Studying in various branches</li>
                        <li>From top <br/>colleges like IITs, NITs..</li>
                        <li class="last">Spread <br/>across 22 states</li>
                    </ul>
                </div>
                
                <div class="mentorship-widget dedicated-mentor-widget">
                	<h2>How does it work?</h2>
                    <ul>
                    	<li>
                            <div class="mentorship-head">
                                <strong class="flLt">Enroll</strong>
                                <i class="mentorship-sprite mentor-enroll-icon flRt"></i>
                                <div class="clearfix"></div>
                             </div>
                            <div class="mentorship-info">
                            	Submit your details along <br/>with preferences
                            </div>
                        </li>
                        <li>
                            <div class="mentorship-head">
                                <strong class="flLt">Mentor Match</strong>
                                <i class="mentorship-sprite mentor-match-icon flRt"></i>
                                <div class="clearfix"></div>
                             </div>
                            <div class="mentorship-info">
                            	We will assign a mentor for <br/>your preferred branch & location
                            </div>
                        </li>
                        <li class="last">
                            <div class="mentorship-head">
                                <strong class="flLt">Connect</strong>
                                <i class="mentorship-sprite mentor-connect-icon flRt"></i>
                                <div class="clearfix"></div>
                             </div>
                            <div class="mentorship-info">
                            	 Ask questions and schedule<br/> chats with your mentor
                            </div>
                        </li>
                    </ul>
                    <div class="step-section clear-width">
                    	<div class="step-count" style="left:0">1
                        </div>
                        <div class="step-count" style="left:50%">2
                        </div>
                        <div class="step-count" style="right:0">3
                        </div>
                    </div>
                    <div class="dedicated-guidance-widget clear-width">
                    	<h2>Get guidance from a dedicated mentor :</h2>
                        <p>On everything from engineering exam prep to college, branch selection  & admission process</p>
                        <?php if(isset($isMentee) && !$isMentee){?><a class="get-mentor-btn" href="javascript:void(0);" onclick="goToForm('<?php echo $bottomTrackingPageKeyId;?>'); trackEventByGA('GET_A_MENTOR_HOMEPAGE_ABOVE_MENTOR_LIST_CLICK','GET_A_MENTOR_HOMEPAGE_ABOVE_MENTOR_LIST');" style="margin:40px 0 0;">GET A MENTOR</a><?php }?>
                    </div>
                </div>
                
                <!---get-mentor--->
                <?php $this->load->view('mentorship/getMentorForm');?>
                <!--end-get-mentor-->
		
		<!--get mentor list -->
                <?php if($totalMentor > 0) {?>  
		    <div id = "mentorList" class="mentorship-widget widget-padding">      
				<?php $this->load->view('mentorship/studentMentors',$displayData); ?>
		    <div>
		<?php } ?>
	    <!--get mentor list end-->
	    
        </div>
    </div>
    <div class="clearFix"></div>
</div>

<?php $this->load->view('common/footer');?>
<div id="mentee-opacityLayer"></div>
<script>
var regFormId = '<?php echo $regFormId;?>';
var isMentee  = '<?php echo $isMentee;?>';
var userId    = '<?php echo $userId;?>';
var topTrackingPageKeyId = '<?php echo $topTrackingPageKeyId?>';

$j(window).load(function(){
    var hashTag = window.location.hash;
        if(typeof(hashTag) != 'undefined' && hashTag =='#menteeform' && $j("#registrationForm_"+regFormId).length > 0){
                goToForm(topTrackingPageKeyId);
        }
    });
$j(document).ready(function(){
        if(!isUserLoggedIn && typeof(isUserLoggedIn) != 'undefined'){
		prepareMenteeForm(regFormId);
	}
        
        if($j('#menteeExamList').length>0)
        {
                $j("#menteeExamList li>a").on('click',function(event) {
			selectExamList($j(this).attr('refId'));
			event.stopPropagation();event.preventDefault();
                });
        }
        
        if($j('.branchlst').length>0)
        {     
                $j(".branchlst li>a>span").click(function(event) {
                        var selectedBranch = ($j(this).text().length>20) ? $j(this).text().substr(0,20)+'..': $j(this).text();
                        headingId = $j(this).attr('heading');
                        $j('#'+headingId+'H').text(selectedBranch);
                        $j('#'+headingId+'_m').attr('title',$j(this).text());
                        $j('#'+headingId+'_value').val($j(this).text());
                        validateOnClick('eng_branch_pref1');
                });
        }
        
        if($j('.examYr').length>0)
        {     
                $j(".examYr li").click(function(event) {
                        var selectedBranch = ($j(this).text().length>25) ? $j(this).text().substr(0,25)+'..': $j(this).text();
                        var ul = $j(this).parent();
                        headingId = ul.attr('heading');
                        $j('#'+headingId+'H').text(selectedBranch);
                        $j('#'+headingId+'_m').attr('title',$j(this).text());
                        $j('#'+headingId+'_value').val($j(this).text());
                        validateOnClick('menteeExamYr');
                });
        }
        $j('._mntHV').val('');
});
</script>