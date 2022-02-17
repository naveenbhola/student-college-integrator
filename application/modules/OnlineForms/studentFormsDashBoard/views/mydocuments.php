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
    <div id="breadcrumb">
    	<?php echo $breadCrumbHTML;?>
    </div>
    <!--Ends: breadcrumb-->
    <div id="contentWrapper">
    <!--Starts: Left Column-->
    <div id="appsLeftCol">
    	<ul>
        	<li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/StudentDashBoard/index';?>" title="Home">Home</a></li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/StudentDashBoard/myProfile';?>" title="My profile">My Profile</a></li>
            <li class="active wCurve">My Documents</li>
            <li><a href="<?php echo SHIKSHA_HOME.'/studentFormsDashBoard/MyForms/index';?>" title="My forms">My Forms</a></li>
        </ul>
    </div>
    <!--Ends: Left Column-->
    
    <div id="appsRightCol">
    	<?php if(!empty($validateuser[0]['displayname'])):?><h2 class="welcome">Welcome <span><?php echo $display_name = isset($validateuser[0]['displayname'])? $validateuser[0]['displayname']:"";?></span></h2><?php endif;?>
        <div class="spacer10 clearFix"></div>
        <div id="userDocumentsList">
        <?php echo $documentsDetails;?>
        </div>
        <div class="attachMoreDocBlock">
        <h3>Attach More Documents</h3>
        <div id="formsList">
        <form name="uploadDocuments" id="uploadDocuments">
		    <input  type="text" class="textboxLarge" name="docTitle" id="docTitle" default="Document Title" value="Document Title" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur');" />&nbsp;
		    <script>
			document.getElementById("docTitle").style.color = "#ADA6AD";
		    </script>
                    <input type="file" class="file" size="45" name="datafile[]"/>&nbsp;
                    <input type="button" value="Upload" title="Upload" onClick="fileUpload($('uploadDocuments'),'upload','upload'); return false;"/>
                    <div id="upload"></div>
            <div class="spacer15 clearFix"></div>
        </form>
        </div>
         <ul>
         	<li>
           		<a href="javascript:void(0);" title="+ Add More" onclick="if(typeof(OnlineFormStudentDashboard)!='undefined') {OnlineFormStudentDashboard.addMoreFields();}">+ Add More</a>
            </li>
        </ul>
        </div>
    </div>
    </div>
</div>
<?php $this->load->view('common/footerNew'); ?>
<script>
var doc_url = '<?php echo SHIKSHA_HOME."/studentFormsDashBoard/MyDocuments/updateDocumentDetails/status/deleted/Ajax";?>'+'/'+'<?php echo $documntr_id;?>';
</script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("onlineFormStudentDashboard"); ?>"></script>
