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
?>
<div id="appsFormWrapper">
	<div style='margin-left:336px; margin-top:50px; margin-bottom:20px;'><img src='/public/images/loading39.gif' border=0 /></div>
	<div style="margin-left:320px;margin-bottom:50px;" class='bld'>Loading your test. This might take a few seconds...</div>
</div>
<?php
	$bannerProperties1 = array('pageId'=>'', 'pageZone'=>'');
	$this->load->view('common/footer',$bannerProperties1);
?> 

<!-- Now, make an AJAX call to the function getTest and get the test data and load the test -->
<script>
	var url= '/OnlineT/OnlineTests/getTestandStart';
	var data = 'exam=<?php echo $exam?>&level=<?php echo $level; ?>&section=<?php echo $section;?>&duration=<?php echo $duration; ?>';
	new Ajax.Request (url,{method:'post',parameters: data,onSuccess:function (request) {
			if(request.responseText!=''){
				//alert(request.responseText);
			}
			window.location = '<?php echo SHIKSHA_HOME?>/public/index.html';
		}
	});
</script>
