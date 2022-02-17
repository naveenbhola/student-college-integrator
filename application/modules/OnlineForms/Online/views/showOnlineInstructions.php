<?php
				$categoryIdForBanner = isset($CategoryList[array_rand($CategoryList)])?$CategoryList[array_rand($CategoryList)]:1;
				$criteriaArray = array(
                                    'category' => $categoryIdForBanner,
                                     'country' => '',
                                     'city' => '',
                                     'keyword'=>'');
				   $bannerProperties = array('pageId'=>'DISCUSSION_DETAIL', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);
				   $headerComponents = array(
				      'css'	=>	array('online-styles','header','raised_all','mainStyle','cal_style'),
				      'js' 	=>	array('common','ana_common','myShiksha','onlinetooltip','prototype','CalendarPopup','imageUpload','user','onlineForms'),
				      'title'	=>	'',
				      'tabName' =>	'Discussion',
				      'taburl' =>  site_url('messageBoard/MsgBoard/discussionHome'),
				      'metaDescription'	=>'',	
				      'metaKeywords'	=>'',
				      'product'	=>'online',
				      'bannerProperties' => $bannerProperties,
				      'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""), 
				      'callShiksha'=>1,
				      'notShowSearch' => true,
                                      'postQuestionKey' => 'ASK_ASKDETAIL_HEADER_POSTQUESTION',
                                      'showBottomMargin' => false,
				      'showApplicationFormHeader' => false
				   );

   $this->load->view('common/header', $headerComponents);
   $this->load->view('common/calendardiv');

?>
<script>
var isUserLoggedInSystem = '<?php echo $userId;?>';
</script>
<div id="appsFormWrapper">

    <!--Starts: breadcrumb-->
    <?php $this->load->view('OnlineT/showBreadCrumbs'); ?>
    <!--Ends: breadcrumb-->
    
    <div id="appsFormInnerWrapper">

    <div id="appsFormHeader">
	<!--Starts: Institute Header -->
	<?php $this->load->view('OnlineT/instituteHeader'); ?>
	<!--Ends: Institute Header-->

	<div class="formSubject">
	      <h4>&nbsp;You are applying for Shiksha Mock Tests 2013<?php //echo date("Y");?></span></h4> 
	</div>
    </div>

    <div class="formContentWrapper">


	<!-- Form starts -->
        <form action="/OnlineT/OnlineTests/loadTest" id="OnlineTest" accept-charset="utf-8" method="post" enctype="multipart/form-data" onsubmit="removeHelpText(this); if(validateFields(this) != true){ return false;} storeUserFunc(this); return false;" novalidate="novalidate">

		<iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
		<input type="hidden" name="testId" id="testId" value=""/>
		<input type="hidden" name="duration" value="<?php echo $duration;?>"/>
		<input type="hidden" name="exam" value="<?php echo $examtype;?>"/>
		<input type="hidden" name="level" value="<?php echo $level;?>"/>
		<input type="hidden" name="section" value="<?php echo $section;?>"/>

		<table border=2 cellspacing=5 cellpadding=10 width="100%" align="center">
			<tr bgcolor='#999999'>
				<td align='center'><b><?php echo $examtype;?> MOCK TEST</b></td>
			</tr>

			<tr bgcolor="#CCCCCC" cellpadding=30>
				<td>
					<?php if(isset($test_array)){
						foreach ($test_array as $key=>$test){
							if($key==$examtype)
								echo $test['instructions'];
						}
					 } ?>
				</td>
			</tr>
		</table>
		<div class="clearFix"></div>
		<div class="buttonWrapper">
			<div class="buttonsAligner" style="margin-left:416px;">
			    <input type="submit" title="Start Test" value="Start Test" class="saveContButton" />
			</div>
		</div>

	</form>
	<!-- Form ends -->

	</div>
	<div class="clearFix"></div>

   </div>


</div>
<div class="clearFix"></div>
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 

<script>
try{
    //var cal = new CalendarPopup("calendardiv");
    addOnFocusToopTipOnline(document.getElementById('OnlineTest'));
    addOnBlurValidate(document.getElementById('OnlineTest'));


} catch (ex) {
    // throw ex;
}    
</script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormCommon"); ?>"></script>
