<?php
$locations = array();
$optionalArgs = array();
for($i = 0; $i < count($details['locations']); $i++){
//    $locations[$i]  = $details['locations'][$i]['address'];
    if(isset($locations[$i]) && (strlen($locations[$i]) >0)) {
        $locations[$i] .= ', '.$details['locations'][$i]['city_name'].', '.$details['locations'][$i]['country_name'];
        }
        else {
            $locations[$i] = $details['locations'][$i]['city_name'].', '.$details['locations'][$i]['country_name'];
        }
        $optionalArgs['location'][$i] = $details['locations'][$i]['city_name']."-".$details['locations'][$i]['country_name'];
}
$instituteUrl = getSeoUrl($details['institute_id'],"naukrishikshainstitute",$details['institute_name'],$optionalArgs);
?>
<script>
function gotoBtmForm(val){	
	var hht = eval(document.getElementById('gotoBottom').offsetTop)-400;
	window.scrollTo(0, hht);
	var objFormDiv = document.getElementById('before_get_free_alerts_detail');	
	if(objFormDiv.style.display!="none"){		
		document.getElementById('reqInfoDispName_foralert_detail').focus();
		if(val==1){			
			trackEventByGA('ContactDetailsAnchor','TopBottomClick');
		} else {		
			trackEventByGA('MiddlePanel','MiddleClick');
		}
	}
}
</script>
<!--Start_LeftPanel-->
<style>
#forBr br{line-height:15px};
</style>
    <div id="forBr" style="margin-right:275px">
        <div style="float:left;width:100%">
            <!--Start_HeaderListing-->
            <div class="raised_blueFull"> 
                <b class="b2"></b><b class="b3"></b><b class="b4"></b>
                <div class="boxcontent_blueFull">
                    <div style="padding:10px">                      
                        <div><b style="font-size:18px;"><?php echo $details['title']; ?> </b><span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>" style="padding-left:10px;">[ <a href="<?php echo $courseEditUrlMain; ?>" class="fontSize_12p">Edit</a> ]</span></div>
                        <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
                        <div style="padding-top:10px">
                        <div style="padding-right:300px;"><a class="fontSize_14p bld" href="<?php echo (($ListingMode == 'view') ?$instituteUrl:'#'); ?>"><?php echo $details['institute_name']; if($details['institute_id']!='32469'){ echo ", ".$details['locations'][0]['city_name'];} ?></a><span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>" style="padding-left:10px;">[ <a href="<?php echo $instituteEditUrlMain; ?>" class="fontSize_12p">Edit</a> ]</span></div>
<?php if($ListingMode == 'view') { ?>
										<?php $srcarr = array('source'=>'NAUKRISHIKSHA_COURSEDETAIL');
$this->load->view('listing_forms/listingActionBar',$srcarr); ?>
<?php } ?>
                        </div>                        
                    </div>                    
                </div>
                <b class="b1b" style="margin:0;height:3px"></b>
            </div>
            <!--End_HeaderListing-->
            
            <!--Start_Listing_Course and Content-->
            <div style="margin-top:10px">
                <!--Start_Content-->
                <div class="float_R" style="width:370px">
<?php 
$displayCourseSection = 'display:none';
if(($ListingMode == 'view' && 
    (
        (strlen(trim($details['contact_name'])) > 0) || 
        (strlen(trim($details['contact_main_phone'])) > 0 || 
        strlen(trim($details['contact_cell'])) > 0 || 
        strlen(trim($details['contact_alternate_phone'])) > 0 || 
        strlen(trim($details['contact_fax'])) > 0) || 
        (strlen(trim($details['contact_email'])) > 0)/* || 
        (
            (strlen(trim($details['locations'][0]['address']))> 0) || 
            (strlen(trim($details['locations'][0]['city_name'])) > 0)  || 
            (strlen(trim($details['locations'][0]['pincode'])) > 0) || 
            (strlen(trim($details['locations'][0]['country_name'])) > 0) 
        )*/
    )) || $ListingMode != 'view')
    { 
	$displayCourseSection = '';
}
?>

          <div style="width:100%;<?php echo $displayCourseSection; ?>">
                    <div class="contactBT" style="padding-top:5px;padding-bottom:17px">
                        <div class="fontSize_14p"><b>Contact Details:</b> <span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>" style="padding-left:90px">[ <a href="<?php echo $courseEditUrlContact; ?>" class="fontSize_12p">Edit</a> ]</span></div>
                        <div class="grayLine" style="line-height:3px;margin-bottom:5px">&nbsp;</div>
                        <div style="line-height:18px">
                            <?php if(strlen($details['contact_name']) > 0){ ?><b>Name of the Person :</b><?php echo $details['contact_name']; ?><br /><?php } ?>
                            <?php if(strlen($details['contact_main_phone']) > 0 || strlen($details['contact_cell']) > 0 || strlen($details['contact_alternate_phone']) > 0 || strlen($details['contact_fax']) > 0){ ?><b>Contact No. :</b> <?php if(strlen($details['contact_main_phone'])> 0) echo nl2br_Shiksha(insertWbr($details['contact_main_phone'],22)); ?>
                            <?php if(strlen($details['contact_cell'])> 0) echo  ", " . $details['contact_cell'] ; ?>
                            <?php if(strlen($details['contact_alternate_phone'])> 0) echo "," . $details['contact_alternate_phone'] . "<br/>";
                            
                            if (!empty($details['contact_fax'])) {
                            ?>
                            <br/>
                            <b>Fax number:</b>
                            <?php
                            echo $details['contact_fax']; ?><br />
                            <?php
                                }
                            } 
                            ?>
                            <?php if(strlen($details['contact_email']) > 0){ ?><b>Email :</b> <?php echo insertWbr($details['contact_email'],15); ?><br /> <?php } ?>
							<?php
								if(!isset($cmsData)) {
								if($registerText['paid'] == "yes" ){ 
							?>
								<div class="listingBtn" style="margin-top:10px"><a href="javascript:void(0)" onclick="gotoBtmForm(1)" style="color:#000;text-decoration:none">I want this insitute to counsel me</a></div>
							<?php } } ?>
                        </div>
                    </div>
                    <div class="lineSpace_13">&nbsp;</div>
                    <?php if (count($detailPageComponents) > 0 ) { ?>
                    <div class="contentBT" style="padding-top:2px;padding-bottom:17px">
                        <div class="fontSize_14p float_L" style="border-bottom:1px solid #EAEEED;width:100%;line-height:23px"><b>Contents:</b> <span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>" style="padding-left:133px">[ <a href="<?php echo $courseEditUrlContact; ?>" class="fontSize_12p">Edit</a> ]</span></div>                        
                        <div style="line-height:18px;clear:left">
							<div style="line-height:6px">&nbsp;</div>
                            <ul class="cBT">
<?php 
$showGallery = -1; 
$showDocs = -1;
$showVideos= -1;
for($i = 0; $i< count($detailPageComponents); $i++){ ?>

<li style="float:left;width:48%;line-height:16px">
<div>
	<div style="float:left;width:15px"><?php $sectionNumber = $i+1; echo $sectionNumber;?>.</div>
	<div style="float:left;margin-left:3px">
<?php 
$shortTitle = (strlen($detailPageComponents[$i]['title']) > 20)?substr($detailPageComponents[$i]['title'],0,20)."...":$detailPageComponents[$i]['title'];
switch($detailPageComponents[$i]['anchor']){
    case 'gallery':
        echo '<span class="gallery"><a href="#gallery" title="'.$detailPageComponents[$i]['title'].'">'.$shortTitle.'</a></span>'; 
        $showGallery = $i;
        break;
    case 'videos':
        echo '<span><a href="#'.$detailPageComponents[$i]['anchor'].'" title="'.$detailPageComponents[$i]['title'].'">'.$shortTitle.'</a></span>'; 
        $showVideos = $i;
        break;
    case 'documents':
        echo '<span><a href="#'.$detailPageComponents[$i]['anchor'].'" title="'.$detailPageComponents[$i]['title'].'">'.$shortTitle.'</a></span>'; 
        $showDocs = $i;
        break;
    default:
        echo '<span><a href="#'.$detailPageComponents[$i]['anchor'].'" title="'.$detailPageComponents[$i]['title'].'">'.$shortTitle.'</a></span>'; 
        break;
}
?>
	</div>
</div>
</li>
<?php } ?>

                            </ul>
                            <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
                        </div>
                    </div>
                    <div class="lineSpace_16">&nbsp;</div>
                    <?php } ?>
                    <div>
                                <?php 
                                $tmpCourList = array();
                                $totalCount=count($courseList);
                                // $tmpCourList array to contain all course EXCEPT the current course
                                for($i=0;$i< $totalCount; $i++){ 
                                    if($courseList[$i]['course_id']== $details['course_id']){
                                        continue;
                                    }
                                    array_push($tmpCourList,$courseList[$i]);
                                }
                                $totalCount=count($tmpCourList);
                                if($totalCount > 0){
                                ?>
                        <div class="fontSize_14p"><b>Other Courses from this Institute</b></div>
                        <div class="grayLine" style="line-height:3px;margin-bottom:5px">&nbsp;</div>
                        <div style="line-height:18px">
                                <?php                                     
                                    for($i=0;$i< $totalCount; $i++){ 
                        $tempOptionalArgs = $optionalArgs;
                        $tempOptionalArgs['institute'] = $details['title'];
                        $courseUrl = getSeoUrl($tmpCourList[$i]['course_id'],"naukrishikshacourse",$tmpCourList[$i]['courseTitle'],$tempOptionalArgs);

                                        if($i==3){
                                ?>
                                        <div id="expandCourse" style="display:none;">
                                        <?php } ?>
                                            <div>
                                                <span class="float_R <?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="/enterprise/ShowForms/showCourseEditForm/<?php echo $tmpCourList[$i]['course_id']; ?>" class="fontSize_12p">Edit</a> ]</span>
                                                <div style="background:url(/public/images/blkdotted.gif) no-repeat left top;padding-left:18px"><a href="<?php echo (($ListingMode == 'view') ?$courseUrl:'#'); ?>"><?php echo $tmpCourList[$i]['courseTitle']; ?></a></div>
                                            </div>
                                <?php
                                }
                                if(($totalCount > 3)) {
                                ?>
                            </div>
                            <?php } ?>                           
                            <!--</ul>
                            <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>-->
                            <!-- TODO -->
                            <?php if($totalCount>3) { ?>
                            <div id='showAllDiv' style="padding-left:6px"><img src="/public/images/plusSign.gif" /> <a href="javascript:void(0);" onClick="$('expandCourse').style.display='inline'; $('hideAllDiv').style.display='inline';$('showAllDiv').style.display='none';">Show All Courses</a></div>
                            <div id='hideAllDiv' style="padding-left:20px;display:none;"><font color="blue">- </font><a href="javascript:void(0);" onClick="$('expandCourse').style.display='none';$('showAllDiv').style.display='inline';$('hideAllDiv').style.display='none';window.scrollTo(0,150);">Hide Expanded List</a></div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
              </div>
                <!--End_Content-->
                <!--Start_Course-->
                <div style="margin-right:380px">
<?php
        $this->load->view('listing_forms/showCourseDetails');
?>
                </div>
                <!--End_Course-->                
				<div style="clear:both;font-size:1px;line-height:1px">&nbsp;</div>
            </div>
            <!--End_Listing_Course and Content-->
            <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
            <!--Start_Contents-->
          <!--End_Contents--> 
	    <div class="lineSpace_10">&nbsp;</div>
	<?php
	    if(ALUMNI_SECTION_FLAG === true) {
		    $this->load->view('listing_forms/alumniReviews'); 
	    }
	?>	
<?php
	if(!isset($cmsData)) {
	if($registerText['paid'] == "yes" ){ 
?>
<div style="margin:0 0 10px">
	<div class="raised_10"> 
		<b class="b2"></b><b class="b3" style="background:#fff"></b><b class="b4" style="background:#fff"></b>
		<div class="boxcontent_10" style="background:#FFF5C4 url(/public/images/bgCounsel.png) repeat-x left top">
			<div class="wdh100">
				<img src="/public/images/iconCounsel.png" align="left" hspace="10" />
				<div style="padding-top:5px">
				<span style="position:relative;*top:-5px"><b>Interested in studying at <span class="OrgangeFont"><?php echo $details['title']; ?></span></b></span>&nbsp; &nbsp; &nbsp;<input type="button" class="listingBtn_1" value="I want this institute to counsel me" onclick="gotoBtmForm(2)"></div>
				<div class="clear_B">&nbsp;</div>
			</div>
		</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>
<?php } } ?>
<?php
$tempArr = array();
    if(isset($showGallery) && $showGallery >= 0){
        $tempArr['imgArr'] = $detailPageComponents[$showGallery]['value'];
        $this->load->view('listing_forms/ListingImgGallery',$tempArr);
    }
?>    

<?php
    if(isset($showVideos) && $showVideos >= 0){
        $tempArr['videoArr'] = $detailPageComponents[$showVideos]['value'];
        $this->load->view('listing_forms/showVideos',$tempArr);
    }
?>    

<?php 
for($i = 0;$i < count($detailPageComponents); $i++){
    if($i == $showGallery || $i == $showDocs || $i == $showVideos){
        continue;
    }
    $tempArray['wikiAnchor'] = $detailPageComponents[$i]['anchor'];
    $tempArray['wikiTitle'] = $detailPageComponents[$i]['title'];
    $tempArray['wikiDesc'] = $detailPageComponents[$i]['value'];
    $tempArray['editUrllink'] = $courseEditUrlwikki;
    $this->load->view('listing_forms/showWikiSection',$tempArray);
}
?>

<?php
    if(isset($showDocs) && $showDocs >= 0){
//        pa($detailPageComponents[$showDocs]);
        $docArray =array();
        $docArray['data'] = $detailPageComponents[$showDocs];
        $this->load->view('listing_forms/showBrochures',$docArray);
    }
?>
<!-- -->
<div class="lineSpace_28">&nbsp;</div>
<?php
if(!isset($cmsData)){
	if($registerText['paid']!="yes"){			
		$this->load->view('listing_forms/widgetConnectInstituteBtm');
	}
}
?>
<div>
<?php
if(!isset($cmsData)) {
        if($registerText['paid'] == "yes" ){
            $this->load->view("naukrishiksha/get_free_alerts_details_page");
        }
}
?>
    </div>
<div class="lineSpace_28">&nbsp;</div>
<div class="lineSpace_28">&nbsp;</div>
    <div class="">
    <?php
    if(!isset($cmsData)){
        $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
        $this->load->view('common/banner',$bannerProperties);
    }
    ?>
    </div>

        </div>
        <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>    
    </div>
    <!--End_Left_Panel-->
