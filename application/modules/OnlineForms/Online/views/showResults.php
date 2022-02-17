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
<div id="appsFormWrapper">
    <div id="appsFormInnerWrapper">
	    <div id="appsFormHeader">

			<div class="formSubject">
				  <h4>&nbsp;Thank you for taking a Mock Test with Shiksha<?php //echo date("Y");?></span></h4> 
			</div>
	    </div>

    <div class="formContentWrapper">
		<center><img src="http://www.goodlightscraps.com/content/congrats/congrats_2.gif" border=0 align='center'></center>
		<table border=0 align="center" cellspacing=10 width="59%">
			<tr>
				<td align='right'>Your Percentage is : </td>
				<td class="bld">98%</td>
			</tr>
			<tr>
				<td align='right'>Your Percentile is (among 100 Shiksha members): </td>
				<td class="bld">95</td>
			</tr>
		</table>

		<div class="formSubject">
			  <h4>&nbsp;Your detailed test report has been sent to your Email Id.<?php //echo date("Y");?></span></h4> 
		</div>

		<div class="buttonWrapper">
			<div class="buttonsAligner" style="margin-left:246px;">
			    <input onClick="window.location='/OnlineT/OnlineTests/showOnlineTests'" type="button" title="Online Tests Homepage" value="Online Tests Homepage" class="saveContButton" />
			    <input onClick="window.location='<?php echo SHIKSHA_HOME;?>'" type="button" title="Shiksha Homepage" value="Shiksha Homepage" class="saveContButton" />
			</div>
		</div>

	</div>
	<div class="clearFix"></div>


   </div>


</div>
<div class="clearFix"></div>
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 

