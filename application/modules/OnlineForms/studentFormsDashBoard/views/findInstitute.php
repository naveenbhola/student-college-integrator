<?php
$headerComponents = array(
        'js'=>array('homePage'),
	    'css'=>array('shiksha_common','online-styles'),
        'jsFooter'=>array('ana_common','user','common'),
        'title'	=>	'Application Form - Dashboard',
        'metaDescription' => '',
        'metaKeywords'	=>'',
        'product' => 'online',
        'bannerProperties' => array('pageId'=>'HOME', 'pageZone'=>'TOP'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
        );    
?>
<?php $this->load->view('common/header', $headerComponents); ?>
<div id="appsFormWrapper">
	<!--Starts: breadcrumb-->
	<?php echo $breadCrumbHTML;?>
    <!--Ends: breadcrumb-->
    <div id="contentWrapper">
    <!--Starts: Left Column-->
    <div id="appsLeftCol">
    	<ul>
        	<li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/StudentDashBoard/index';?>" title="Home">Home</a></li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/StudentDashBoard/myProfile';?>" title="My profile">My Profile</a></li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/MyDocuments/index';?>" title="My Documents">My Documents</a></li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/MyForms/index';?>" title="My forms">My Forms</a></li>
            <li class="active wCurve">Find Institutes</li>
        </ul>
    </div>
    <!--Ends: Left Column-->
    
    <div id="appsRightCol">
    	<?php if(!empty($validateuser[0]['displayname'])):?><h2 class="welcome">Welcome <span><?php echo $display_name = isset($validateuser[0]['displayname'])? $validateuser[0]['displayname']:"";?></span></h2><?php endif;?>
        <div class="spacer10 clearFix"></div>
        <h3>Find Institutes</h3>
        <div class="formChildWrapper">
        <form action="<?php echo SHIKSHA_HOME?>/studentFormsDashBoard/FindInstitute/index" method="post" id="onlineSearchFrom" onsubmit="OnlineForm.setKeywordInCookie();">
        	<ul>
            	<li><label style="float:left" class="labelAutoWidth">By keyword:</label> 
            	<div class="float_L">
            	<input  maxlength="100"  type="text" class="textboxLarge" name="keyWord" id="tempkeyword" default="For e.g. Skyline" value="For e.g. Skyline" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur');countNumberOFFields();" autocomplete="off"/>
					    <script>
						document.getElementById("tempkeyword").style.color = "#ADA6AD";
					    </script>

            	<br/>
            	<span id="keywordError" style="display:none;color:red">You have reached the limit</span>
            	</div></li>
                </ul>
            <div class="buttonWrapper">
            	<div class="searchBtnAlign">
                	<input class="searchButton" type="submit" value="Search" title="Search"/>
                </div>
            </div>
        </form>
        </div>
        <?php if(!empty($instituteList) && is_array($instituteList)):?>
        <h3>We have found the following colleges you can Apply </h3>
        <div>
            <div class="pagingID txt_align_r" id="paginataionPlace1" style="line-height:23px;">&nbsp;<?php echo $paginationHTML;?></div>
        </div>

        <?php $this->load->view('studentFormsDashBoard/common_template_across');?>
        <?php else:?>
               <h3 class="searchMsg">No results found, Please refine your search criteria</h3>
        <?php endif;?>
    </div>
    </div>
</div>
<?php $this->load->view('common/footerNew'); ?>
<script>
var OnlineForm = {};
OnlineForm.displayAdditionalInfoForInstitute = function (style,divId) {
	if($(divId)) {
		$(divId).style.display = style;
	}
}
OnlineForm.setKeywordInCookie = function() {
	setCookie('institute_finder_key',$('tempkeyword').value);
	//setCookie('institute_categoryId_key',$('categoryId').value);
}
if(getCookie('institute_finder_key')!='')
$('tempkeyword').value = getCookie('institute_finder_key');
//$('categoryId').value = getCookie('institute_categoryId_key');
String.prototype.boxTrim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
function countNumberOFFields() {
	var value = $('tempkeyword').value;
	value = value.boxTrim();
	value = value.length;
	if(value>=100) {
    	$('keywordError').style.display ="block";
    	//$('keywordError').style.color ="red";
    	//$('keywordError').style.left ="80px";
    	//$('keywordError').style.position ="relative";
    	$('keywordError').innerHTML = "You have reached the maximum limit accepted by this field which is 100.";
    } else {
    	$('keywordError').style.display ="none";
    }
}

</script>
