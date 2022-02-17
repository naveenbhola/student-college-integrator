<?php
    $bannerProperties = array('pageId'=>'CAREER_CENTRAL_HOME', 'pageZone'=>'HEADER');
    $headerComponents = array(
		'title'	=>	'Career Guidance &amp; Career Options after 12th - Shiksha.com',
		'js'=>array('common'),
		'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
		'metaDescription'	=>'Find the information about career planning and development at Shiksha.com. Select courses after 12th from various career options like management, engineering, IT, medical, arts and more.',	
		'showBottomMargin' => false,
		'product'=>'CareerProduct',
		'bannerProperties' => $bannerProperties,
		'articleImage'=>SHIKSHA_HOME.'/public/images/dp.jpg',
		'canonicalURL' => $current_page_url
   );

   $this->load->view('common/header', $headerComponents);

?>
	
    	<!--Code Starts form here-->
    	<div class="career-wrapper">
    	<div class="career-lt-pnl flLt">
    		<?php
                $this->load->view('Careers/careerPageBreadcrumb');
                ?>
			<ul class="home-sections">
	            	<li class="slice-1"></li>
	                <li class="slice-2"></li>
	                <li class="slice-3"></li>
	                <li class="slice-4">
				<form method="post" id="form1" >
				
	                	<div class="choose-options">
					<?php for($i=0;$i<$streamArrayLength;$i++) { ?>
						<label style="cursor:pointer"><input type="radio" name=stream id="<?php echo $streamArray[$i];?>" value="<?php echo $streamArray[$i]; ?>" class="_remrbc"/><?php if('Humanities'==$streamArray[$i]){ echo 'Humanities/Arts';}else{ echo $streamArray[$i];} ?></label>
					<?php } ?>
					<div class="spacer10 clearFix"></div>				
					<div style="height: 25px"><div id= "stream_homepage_error" class="errorMsg" style="text-align: center; font-size: 14px"></div></div>
					<div class="clearFix"></div>
					<div class="tac"><input type="submit" value="Continue" class="orange-button _cobtn"/></div>
					
		                </div>
				</form>
	                </li>
		
			
	                <li class="slice-5">
	                	<div class="explore-option">
	                    	<h1>Find your careers stream</h1>
	                        <div class="search-outer">
	                        	<span class="search-icn"></span>
	                        	<input type="text" style="color:#929292" name='searchCareer' id='searchCareer' value="E.g. Engineer, Teacher, Lawyer, Pilot etc." default="E.g. Engineer, Teacher, Lawyer, Pilot etc."  onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' autocomplete="off"  />
					
	                        </div>
				<div class="spacer5 clearFix"></div>
				<div id= "career_error" class="errorMsg" style="display:none;">No matching career is found.</div>
				<div id= "career_empty_error" class="errorMsg" style="display:none;">Please enter a career name.</div>
				</div>
				</li>		
	    	</ul>
	    	 <div class="career-note">
		         <p class="source-by">Content on this page is by Career Expert</p> 
		         <p class="source-name">Mrs. Kum Kum Tandon</p> 
		         <p class="source-dtls">MA (Psychology), M.Ed, Diploma in Educational Psychology, Vocational Guidance & Counseling (NCERT, Delhi) | <a href='<?=SHIKSHA_HOME?>/shikshaHelp/ShikshaHelp/kumkum'>View Complete Proﬁle</a></p>     
		    </div>
		</div>
		<div class="career-rt-pnl flRt">
			<?php
						$bannerProperties1 = array('pageId'=>'CAREER_CENTRAL_HOME', 'pageZone'=>'RIGHT1');
						$this->load->view('common/banner',$bannerProperties1); 
        			?>
<!--Start code added for board right widget by ESHA-->
		
		<?php 
			$rightBoardWidgetPage = array('pageId'=>"CAREER_CENTRAL_HOME");
			$this->load->view('Careers/boardPageWidget',$rightBoardWidgetPage)?>
<!--End code added for board right widget by ESHA-->
		</div>
<div id="careerProductHomePageId"></div>

	</div>

<script id="_webengage_script_tag" type="text/javascript">
  var _weq = _weq || {};
  _weq['webengage.licenseCode'] = '311c4922';
  _weq['webengage.widgetVersion'] = "4.0";
  (function(d){
    var _we = d.createElement('script');
    _we.type = 'text/javascript';
    _we.async = true;
    _we.src = (d.location.protocol == 'https:' ? "https://ssl.widgets.webengage.com" : "http://cdn.widgets.webengage.com") + "/js/widget/webengage-min-v-4.0.js";
    var _sNode = d.getElementById('_webengage_script_tag');
    _sNode.parentNode.insertBefore(_we, _sNode);
  })(document);
</script>

<?php $this->load->view('common/footer');?>

<script type="text/javascript" src="/public/js/<?php echo getJSWithVersion("jquery.autocomplete"); ?>"></script>
<script type="text/javascript" src="/public/js/<?php echo getJSWithVersion("careers"); ?>"></script>
<script>

var vals = [<?php echo $stringOfCareerTitles;?>];
var datavals = [<?php echo $stringOfCareerUrl;?>];
$j(function(){
	$j('._remrbc').on('click',function(){
		if(typeof(removeErrorMsgOnRadioButtonclick) == 'function'){removeErrorMsgOnRadioButtonclick();}
	});
	$j('._cobtn').on('click',function(){
		if(typeof(submitHomePage) == 'function'){return submitHomePage();}
	});
});
</script>

