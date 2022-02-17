	</div>
	<!--Start_Footer-->
	<div class="wrapperFxd">
    <div style="width:100%" class="homeShik_footerBg">
		<div style="width:100%;padding-top:15px" id="homeShik_FooterLink">
        	<div class="float_L" style="width:15%;margin-left:10px">
            	<div class="marfull_LeftRight10">
            		<div class="Fnt14" style="padding-bottom:8px"><b>Shiksha Cafe</b></div>
                    <div style="padding-left:8px">
						<div class="pb10"><a href="<?php echo SHIKSHA_ASK_HOME; ?>" title="Ask A Question">Ask A Question</a></div>
						<div class="pb10"><a href="<?php echo SHIKSHA_ASK_HOME; ?>" title="Answer Questions">Answer Questions</a></div>
                    </div>
                </div>
            </div>
			<?php
			function showSubFooter($id,$cat){
				global $categoryTree;
				global $courseTree;
				foreach($courseTree as $course) {
					if($course['category_id'] == $id){
					echo '<div class="pb10"><a href="'.
					getSeoUrlCourse(constant('SHIKSHA_'.strtoupper($cat).'_HOME'),$cat,$course['url']).
					'">'.
					$course['course_title'].
					'</a></div>';
					}
				}
				foreach($categoryTree as $category) {
					if($category['parentId'] == $id && $category['others'] != 1) {
						if(strpos(constant('SHIKSHA_'.strtoupper($cat).'_HOME'), "getCategoryPage/colleges") === false)
							echo '<div class="pb10"><a href="'.
							constant('SHIKSHA_'.strtoupper($cat).'_HOME').
							'/getCategoryPage/colleges/'.$cat.'/All/All/';
						else{
							echo '<div class="pb10"><a href="'.
							constant('SHIKSHA_'.strtoupper($cat).'_HOME').'/All/All/';
						}
							echo $category['urlName'].
							'" title="'.
							$category['categoryName'].
							'Courses">'.
							$category['categoryName'].
							' Courses</a></div>';
					}
				}
			}
			?>
            <div class="float_L" style="width:21%">
            	<div class="marfull_LeftRight10">
            		<div class="Fnt14" style="padding-bottom:8px"><b>Career Options</b></div>
                    <div style="padding-left:8px">
                        <div>
							<div class="ftrPSgn" onclick="toggleMe(this)" id="mbaCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_MANAGEMENT_HOME; ?>" title="MBA Colleges">MBA Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="mbaCollegesContainer">
						   <?=showSubFooter(3,"management");?>
                        </div>
                        <div>
							<div class="ftrPSgn" onclick="toggleMe(this)" id="scienceCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_SCIENCE_HOME; ?>" title="Science and Engineering Colleges">Science and Engineering Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="scienceCollegesContainer">
                           <?=showSubFooter(2,"science");?>
                        </div>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="itCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_IT_HOME; ?>" title="Information Technology Colleges">Information Technology Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="itCollegesContainer">
                           <?=showSubFooter(10,"it");?>
                        </div>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="animationCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_ANIMATION_HOME; ?>" title="Animation &amp; Multimedia Colleges">Animation &amp; Multimedia Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="animationCollegesContainer">
							<?=showSubFooter(12,"animation");?>
						</div>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="medicalCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_MEDICINE_HOME; ?>" title="Medicine &amp; Health Care Colleges">Medicine &amp; Health Care Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="medicalCollegesContainer">
                           <?=showSubFooter(5,"medicine");?>
						</div>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="bankingCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_BANKING_HOME; ?>" title="Banking &amp; Finance Colleges">Banking &amp; Finance Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="bankingCollegesContainer">
                           <?=showSubFooter(4,"banking");?>
						</div>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="mediaCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_MEDIA_HOME; ?>" title="Media, Films &amp; Mass Communications Colleges">Media, Films &amp; Mass Communications Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="mediaCollegesContainer">
                           <?=showSubFooter(7,"media");?>
						</div>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="professionalCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_PROFESSIONALS_HOME; ?>" title="Professional Courses">Professional Courses</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="professionalCollegesContainer">
                           <?=showSubFooter(8,"professionals");?>
						</div>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="artsCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_ARTS_HOME; ?>" title="Arts &amp; Law Colleges">Arts &amp; Law Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="artsCollegesContainer">
                           <?=showSubFooter(9,"arts");?>
						</div>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="hospitalityCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_HOSPITALITY_HOME; ?>" title="Hospitality &amp; Tourism Colleges">Hospitality &amp; Tourism Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="hospitalityCollegesContainer">
                           <?=showSubFooter(6,"hospitality");?>
						</div>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="retailCollegesSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo SHIKSHA_RETAIL_HOME; ?>" title="Retail Colleges">Retail Colleges</a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="retailCollegesContainer">
                           <?=showSubFooter(11,"retail");?>
						</div>
                    </div>
                </div>
            </div>
            <div class="float_L" style="width:15%">
            	<div class="marfull_LeftRight10">
	            	<div class="Fnt14" style="padding-bottom:8px"><b>Study Abroad</b></div>
                    <div style="padding-left:8px">
                        <div class="pb10"><a href="<?php echo SHIKSHA_AUSTRALIA_HOME; ?>" title="Study in Australia">Study in Australia</a></div>
                        <div class="pb10"><a href="<?php echo SHIKSHA_CANADA_HOME; ?>" title="Study in Canada">Study in Canada</a></div>
                        <div class="pb10"><a href="<?php echo SHIKSHA_NEWZEALAND_HOME; ?>" title="Study in New Zealand">Study in New Zealand</a></div>
                        <div class="pb10"><a href="<?php echo SHIKSHA_SOUTHEASTASIA_HOME; ?>" title="Study in South East Asia">Study in South East Asia</a></div>
                        <div class="pb10"><a href="<?php echo SHIKSHA_MIDDLEEAST_HOME; ?>" title="Study in Middle East">Study in Middle East</a></div>
                        <div class="pb10"><a href="<?php echo SHIKSHA_UKIRELAND_HOME; ?>" title="Study in UK-Ireland">Study in UK-Ireland</a></div>
                        <div class="pb10"><a href="<?php echo SHIKSHA_USA_HOME; ?>" title="Study in United States">Study in United States</a></div>
                        <div class="pb10"><a href="<?php echo SHIKSHA_EUROPE_HOME; ?>" title="Study in Europe">Study in Europe</a></div>
                    </div>
                </div>
            </div>
            <div class="float_L" style="width:15%">
            	<div class="marfull_LeftRight10">
	            	<div class="Fnt14" style="padding-bottom:8px"><b>Test Preparation</b></div>
                    <div style="padding-left:8px">
                        <?php $this->load->library('category_list_client');
		            $categoryClient = new Category_list_client();
                        $testprep_menu_tree = $categoryClient->get_testprep_menu_tree();
                        foreach($testprep_menu_tree as $id => $blog_cat) {
                        ?>
                        <div>
                            <div class="ftrPSgn" onclick="toggleMe(this)" id="<?php echo $blog_cat['acronym']?>ExamsSign">&nbsp;</div>
                            <div class="pbml"><a href="<?php echo $this->url_manager->get_testprep_url('',$blog_cat['acronym'],'','','');?>" title="<?php echo $blog_cat['blogTitle'] ?>"><?php echo $blog_cat['blogTitle'] ?></a></div>
							<div class="clear_L">&nbsp;</div>
                        </div>
                        <div style="display:none;padding:0 0 10px 25px" id="<?php echo $blog_cat['acronym']?>ExamsContainer">
                            <?php foreach($blog_cat['children'] as $blog_exam) { ?>
                           <div class="pb10"><a href="<?php echo $this->url_manager->get_testprep_url('',$blog_exam['acronym'],'','','');?>" title="<?php echo $blog_exam['blogTitle']?>"><?php echo $blog_exam['acronym']?></a></div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="float_L" style="width:15%">
            	<div class="marfull_LeftRight10">
            		<div class="Fnt14" style="padding-bottom:8px"><b>Help</b></div>
                    <div style="padding-left:8px">
                        <div class="pb10"><a href="<?php echo SHIKSHA_ABOUTUS_HOME; ?>" title="About Us">About Us</a></div>
		                <div class="pb10"><a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/privacyPolicy')" title="Privacy Policy">Privacy Policy</a></div>
		                <div class="pb10"><a href="javascript:void(0);" onclick="return showFeedBack();" title="Feedback">Feedback</a></div>
                        <div class="pb10"><a href="<?php echo SHIKSHA_FAQ_HOME; ?>" title="FAQs">FAQs</a></div>
		                <div class="pb10"><a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/contactUs" title="Contact Us">Contact Us</a></div>
						<div class="pb10"><a href="<?php echo SHIKSHA_HOME; ?>/search/top-Education-Searches" title="Top Education Searches">Top Education Searches</a></div>
		                <div class="pb10"><a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/siteMap" title="Site Map">Site Map</a></div>
		                <div class="pb10"><a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/browseByCategory/browse-colleges-career-option-listings" title="Browse Institutes and Career">Browse Institutes and Career</a></div>
		                <div class="pb10"><a href="javascript:void(0);" onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/termCondition');" title="Terms &amp; Conditions">Terms &amp; Conditions</a></div>
                    </div>
                </div>
            </div>
            <div class="float_L" style="width:15%">
            	<div class="marfull_LeftRight10">
            		<div class="Fnt14" style="padding-bottom:8px"><b>Enterprise</b></div>
                    <div style="padding-left:8px">
                    	<div class="pb10"><a href="<?php echo ENTERPRISE_HOME; ?>" title="Add courses and institutes here">Add courses and institutes here</a></div>
						<div class="pb10"><a href="mailto:sales@shiksha.com" title="Advertise with us">Advertise with us</a></div>
                    </div>
                </div>
            </div>
            <div class="clear_L">&nbsp;</div>
        </div>
        <div align="center" style="margin-top:30px">
        	<div style="color:#959393"><span class="blackColor">Partner Sites:</span>
            <a href="http://www.naukri.com" target="_blank" title="Jobs">Jobs</a> |
    		<a href="http://www.firstnaukri.com" target="_blank" title="Jobs for Freshers">Jobs for Freshers</a> |
	    	<a href="http://www.naukrigulf.com" target="_blank" title="Jobs in Middle East">Jobs in Middle East</a> |
			<a href="http://www.99acres.com" target="_blank" title="Real Estate">Real Estate</a> |
	    	<a href="http://www.allcheckdeals.com" target="_blank" title="Real Estate Agent">Real Estate Agent</a> |
		    <a href="http://www.jeevansathi.com" target="_blank" title="Matrimonials">Matrimonials</a> |
    		<a href=" http://www.policybazaar.com" target="_blank" title="Insurance Comparison">Insurance Comparison</a> |
    		<a href=" http://www.meritnation.com" target="_blank" title="School Online">School Online</a> |
    		<a href=" http://www.brijj.com" target="_blank" title="Professional Networking">Brijj</a> |
    		<a href=" http://www.zomato.com" target="_blank" title="Restaurant Reviews">Zomato</a>

        </div>
        <div style="padding-top:15px;margin-bottom:20px">
            Trade Marks belong to the respective owners.<br />
            Copyright © <?php echo date('Y'); ?> Info Edge India Ltd. All rights reserved.
	    </div>
        </div>
    </div>
	<div class="lineSpace_10">&nbsp;</div>
	</div>
	<!--End_Footer-->
    <div id="onLoadOverlayContainer"></div>
    <?php
    if (!(isset($search) && $search=="false")) {
        if(!is_array($validateuser) || !isset($validateuser[0])) {
            if(!isset($calendarDivLoaded) || $calendarDivLoaded ==0){ ?>
                <script>try{ overlayViewsArray.push(new Array('common/calendardiv','calendardivId')); }catch(e){ }</script>
                    <?php  }
        }

    }
    if(!isset($commonOverlayDivLoaded) || $commonOverlayDivLoaded ==0){ ?>
        <script>try{ overlayViewsArray.push(new Array('network/commonOverlay','addRequestOverlay')); }catch(e){ }</script>
   <?php } ?>
<?php
$alreadyAddedJsFooter = array('footer','user');
if(!isset($jsFooter)){
	$jsFooter = array();
}
$jsFooter = getJsToInclude(array_unique(array_merge($alreadyAddedJsFooter, $jsFooter)));
    if(isset($jsFooter) && is_array($jsFooter)) {
        foreach($jsFooter as $jsFile) {
?>
                <script language="javascript" src="<?php echo $jsFile;?>"></script>
<?php
        }
    }
?>
<?php
	$cvsJsIncludedOnPage = '';
	if(is_array($js)){
		$cvsJsIncludedOnPage = implode(",",$js);
	}
	if(is_array($jsFooter)){
		if(strlen($cvsJsIncludedOnPage) > 0)
			$cvsJsIncludedOnPage .= ','.implode(",",$jsFooter);
		else
			$cvsJsIncludedOnPage .= implode(",",$jsFooter);
	}
?>
<input type="hidden" name="cvsJsIncludedOnPage" id="cvsJsIncludedOnPage" value="<?php echo $cvsJsIncludedOnPage; ?>" />
<?php global $clientIP;
if(strpos($clientIP,"shiksha")!==false) { ?>
<?php $this->load->view('common/ga'); ?>
<?php } ?>
<?php
if(PAGETRACK_BEACON_FLAG)
{
    $this->load->view('common/pageTrack_beacon.php');
}
global $serverStartTime;
$trackForPages = isset($trackForPages)?$trackForPages:false;
$endserverTime =  microtime(true);
$tempForTracking = ($endserverTime - $serverStartTime)*1000;
?>
</body>
</html>
<?php
    echo getTailTrackJs($tempForTracking,true,$trackForPages,'http://track.99acres.com/images/zero.gif');
?>
<!-- Begin comScore Tag -->
<script>
  var _comscore = _comscore || [];
  _comscore.push({ c1: "2", c2: "6035313" });
  (function() {
    var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
    s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
    el.parentNode.insertBefore(s, el);
  })();
</script>
<noscript>
<img src="http://b.scorecardresearch.com/p?c1=2&c2=6035313&cv=2.0&cj=1" />
</noscript>
<!-- End comScore Tag -->
<script>
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent(function() {
publishBanners();
});
</script>
