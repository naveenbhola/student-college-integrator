<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("personalise-nav"); ?>" type="text/css" rel="stylesheet" />
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('personalizedHeader'); ?>"></script>
<?php
$this->load->library('categoryList/categoryPageRequest');
$URLRequest = new CategoryPageRequest;
$URLRequest->setData(array('categoryId' => $personalizedArray['category']->getParentId(),'subCategoryId'=>$personalizedArray['category']->getId()));
?>
     <!--Starts: Header-->
     
            	<div id="header">
    				
                    <div id="to-nav-tire-1">
                    	<ul>
			
			
			    <li id="home-active" >
				<a onclick="trackEventByGA('topnavhometabclick-personalized',this.innerHTML)" class="home-nav-tab" id="personalizedhometab" href="<?php echo SHIKSHA_HOME; ?>" tabindex="8" >Home</a>
			    </li>
			    <li id="category_name" class="course-tab">
				<a onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" tabindex="9"  href="<?=$URLRequest->getURL()?>"><?=$personalizedArray['category']->getName()?></a>
			    </li>
                            <li id="all_subcategory_courses" class="viewall-course-tab">
				<a  id="all_mgmt_courses" href="javascript:void(0);" tabindex="10"  class = "<?php echo getClass("categoryHeader");?>"  onmouseover="$('all_course').className='viewall-course-link'; drpdwnOpen(this, 'personalCoursesOption')" onmouseout="MM_personal_showHideLayers('personalCoursesOption','','hide');"> View all courses in Management</a>
				<span></span>
			    </li>
                            <li id="all_course" class="viewall-course-link" onmouseover="  this.className='viewall-course-link-active';  drpdwnOpen(this, 'personalCareerOption');showSubCatagories(0,3);return false;" onmouseout="this.className='viewall-course-link'; MM_personal_showHideLayers('personalCareerOption','','hide');" >
				<a onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)"  href="javascript:void(0);"tabindex="11"   >View all courses</a>
				<span></span>
				<div id="white-brdr" class="white-brdr" ></div>
			    </li>
                   
                        </ul>
			
                    </div>
                    
                    <div id="top-nav" class="personalise-nav">
                        <ul>
                            <li>
				<a id="categoryAbout" onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" href="#" tabindex="12" onmouseover="drpdwnOpen(this, 'AboutSubCategory')" onmouseout="MM_personal_showHideLayers('AboutSubCategory','','hide');"  ><strong>About <?=$personalizedArray['shortName']?></strong>
				    <span></span>
				</a>
			    </li>
			    
                            <li>
				<a id="categoryInstitutes" onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" href="#" tabindex="13" onmouseover="drpdwnOpen(this, 'SubCategoryLocations')" onmouseout="MM_personal_showHideLayers('SubCategoryLocations','','hide');" ><strong><?=$personalizedArray['shortName']?> Institutes</strong>
				    <span></span>
				</a>
			    </li>
                            
			    <li class="">
				<a  id="categoryAbroad" onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" href="javascript:void(0);" tabindex="14"  onmouseover="drpdwnOpen(this, 'personalSubCatagories_country');" onmouseout="MM_personal_showHideLayers('personalSubCatagories_country','','hide');"><strong><?=$personalizedArray['shortName']?> Abroad</strong>
				<span></span>
				</a>
			    </li>
                            <li >
				<a id="categoryRankings" onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" href="<?=SHIKSHA_HOME?>/mba/ranking/top-mba-colleges-india/2-2-0-0-0" tabindex="15" ><strong><?=$personalizedArray['shortName']?> Rankings</strong>
				</a>
			    </li>
			    
                            <li>
				<a id="categoryTestPrep" onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" tabindex="16" href="<?php echo SHIKSHA_TESTPREP_HOME;?>/mba-entrance-exams-india-categorypage-14-47-1-0-0-1-1-2-0-none-1-0" ><strong>Test Prep</strong>
				</a>
			    </li>
			    
                            <li>
				<a id="categoryAsk" onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" tabindex="17"onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" href="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/discussionHome/<?=$personalizedArray['category']->getParentId()?>/0/1/answer/"><strong>Ask Experts</strong>
				</a>
			    </li>
			    
                            <li >
				<a id="categoryDiscussion" onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" tabindex="18" href="<?php echo SHIKSHA_ASK_HOME_URL; ?>/messageBoard/MsgBoard/discussionHome/<?=$personalizedArray['category']->getParentId()?>/6/1/answer/" >
				    <strong>Top Discussions</strong>
				</a>
			    </li>
			  
                            <li>
				<a id="categoryNewsUpdates" onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)" tabindex="19" href="#"  onmouseover="drpdwnOpen(this, 'NewsWidget');" onmouseout="MM_personal_showHideLayers('NewsWidget','','hide');" ><strong>News & Updates</strong>
				<span></span>
				</a>
			    </li>
			    
				<li class="last">
				<a id="categoryApplicationForms" tabindex="20" onclick="trackEventByGA('topnavlinkclick-personalized',this.innerHTML)"  href="<?php echo SHIKSHA_ONLINE_FORMS_HOME; ?>"  tabindex="15" >
				    <strong><?=$personalizedArray['shortName']?> Application Forms</strong>
				</a>
			    </li>
                        </ul>
			
                    </div>
                    
                    <div class="reset-default-box">Showing you only <?=$personalizedArray['shortName']?> content, as per your selection. Click here to <span onclick="$j(this).html('Reseting...').load('/NavigationWidgets/restorePersonalizationToDefault/')"><a href="#">view all courses</a></span>.</div>        	                
                    
    		</div>
                <!--Ends: Header-->        	
        <div class="clearFix"></div>


<?php $moduleName=$this->router->fetch_class(); $methodName=$this->router->fetch_method();
$highlightFlag='false';
    if($moduleName == "CareerProduct" || $moduleName == "CareerController"){
	$highlight = "About";
	$highlightFlag='true';
    }
    if($moduleName == "CategoryList" && $methodName == 'categoryPage'){
		$highlightFlag='true';		
		$highlight = "Institutes";
		if($request->isTestPrep()){
			$highlight = "TestPrep";	
		}
		if($request->isStudyAbroadPage()){
			$highlight = "Abroad";	
		}
		
    }
	if($moduleName == "ListingPage"){
	    $highlightFlag='true';
		$highlight = "Institutes";
	}

 if($moduleName == "CategoryList" && $methodName == 'recommendations'){
		$highlightFlag='true';		
		$highlight = "Institutes";
    }
    if($moduleName == "RankingMain"){
	$highlightFlag='true';
	$highlight = "Rankings";	
    }
	if($moduleName == "MsgBoard"){
	    $highlightFlag='true';
		$highlight = "Ask";	
		if($this->uri->slash_segment(5) == '6/'){
			$highlight = "Discussion"; 
		}
	}
	if($moduleName == "shikshaBlog"){
	    $highlightFlag='true';
		$highlight = "NewsUpdates";
	}
	if($moduleName == "OnlineForms" || ($this->uri->slash_segment(1) == 'studentFormsDashBoard/')){
	    $highlightFlag='true';
		 $highlight = "ApplicationForms";
	}
	   if($highlightFlag=='false'){
	    ?><script> $('home-active').className = 'home-active'; </script> <?php
	   }
?>

<script>
if($('category<?=$highlight?>')){
$('category<?=$highlight?>').className = 'tabSelected';
}
</script>

