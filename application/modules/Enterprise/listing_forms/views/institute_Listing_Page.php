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
                        <ul class="lh_b">                           
                            <li style="float:right; width:250px">
                                <div style="width:250px">
                                <?php if(strlen($details['certification']) > 0 ) { ?>
                                    <div class="float_L bld" style="width:75px">Affiliated To:</div>  
                                    <div style="float:left;width:170px"><?php echo $details['certification']; ?> &nbsp; &nbsp; <span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="<?php echo $instituteEditUrlMain; ?>">Edit</a> ]</span></div>
                                <?php } ?>
								<div style="font-size:1px;clear:left">&nbsp;</div>
                                </div>
                                <?php if(strlen($details['establish_year']) > 0 ) { ?>
                                <div style="padding-top:1px"><b>Year of Establishment:</b> <?php echo $details['establish_year']; ?></div>
                                <?php } ?>
                            </li>
                            <li>
                            <div><?php if($details['institute_logo'] != ''){ ?>
                            <img src="<?php echo $details['institute_logo']; ?>"  alt="<?php echo $details['title']; ?>"/>
<?php } ?>
</div>
                            </li>
                        </ul>
                        <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
                       <div style="padding-top:10px">
                       <div style="padding-right:300px"><div><span class="fontSize_16p bld"><?php echo $details['title']; ?></span>&nbsp;&nbsp;&nbsp;<span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="<?php echo $instituteEditUrlMain; ?>">Edit</a> ]</span></div></div>
                <?php if($ListingMode == 'view'){ ?>
										<?php $srcarr = array('source'=>'LISTING_INSTITUTEDETAIL');
$this->load->view('listing_forms/listingActionBar',$srcarr); ?>
                    <?php } ?>
                            
                        </div>                        
                    </div>                    
                </div>
                <b class="b1b" style="margin:0;height:3px"></b>
            </div>
            <!--End_HeaderListing-->
            <!-- Start of toll free number -->
				<?php $this->load->view('listing_forms/listing_Toll_free_number',array('institute_id' => $type_id)); ?>
			<!-- End of toll free number -->
            <!--Start_Listing_Course and Content-->
            <div style="margin-top:10px">
				<?php $this->load->view('listing_forms/showContactInfo'); ?>
                <!--Start_Course-->
				<div id="resolutionfor800CourseListing">
				<script>
					if(document.body.offsetWidth<900){
						document.getElementById('resolutionfor800CourseListing').style.marginRight='285px';
					} else {
						document.getElementById('resolutionfor800CourseListing').style.marginRight='390px';
					}
				</script>
                    <?php $this->load->view('listing_forms/showCourseList'); ?>
                </div>
                <!--End_Course-->                
            </div>
            <!--End_Listing_Course and Content-->
			<div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
			<div class="lineSpace_10">&nbsp;</div>			
			<!-- Start of ask institute -->
			<?php
				$pageKeyForAskQuestion = 'LISTING_INSTITUTEDETAIL_ASK_INSTITUTE_POSTQUESTION';	
				$categoryId = 0;
				if(count($details['subCatArray']) == 1){
					$categoryId = $details['subCatArray'][0];
				}
				$locationId = is_array($details['locations'][0])?$details['locations'][0]['country_id']:2;
 				$instituteId = $details['institute_id'];
				$titleOfInstitute = isset($details['institute_name'])?$details['institute_name']:((isset($details['instituteArr'][0]['institute_name']))?$details['instituteArr'][0]['institute_name']:$details['title']);
				$this->load->view('listing_forms/askInstituteForm',array('categoryId' => $categoryId,'locationId' => $locationId,'instituteId' => $instituteId , 'pageName' => 'institute','titleOfInstitute' => $titleOfInstitute ,'pageKeyForAskQuestion' => $pageKeyForAskQuestion));
			?>
			<div class="lineSpace_10">&nbsp;</div>
			<!-- End of ask institute -->
            <?php
                if(!isset($cmsData)){
                    $sourcearr = array('pageKeyInfo'=>'LISTING_INSTITUTEDETAIL_BOTTOM_RELATED_QUESTION_');
                    $this->load->view("listing/relatedQns",$sourcearr);
                }
            ?>
			<?php
        		if(ALUMNI_SECTION_FLAG === true) {
		        	$this->load->view('listing_forms/alumniReviews'); 
		        }
		    ?>
            <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
            <?php if (count($detailPageComponents) > 0 ) { ?>
            <!--Start_Contents-->
            <div style="margin-top:10px">
                <div class="contentBT">
                    <div class="fontSize_14p" style="padding-bottom:5px"><b>Contents:</b></div>
                    <div class="grayLine" style="border-bottom:1px solid #d1e5f8;line-height:3px;margin-bottom:5px">&nbsp;</div>
                    <div class="row">
                        <ol type="1" class="row cOrder">
<?php 
$showGallery = -1;
$showDocs = -1;
$showVideos= -1;
for($i = 0; $i< count($detailPageComponents); $i++){ ?>

<li>
<div>
	<div style="float:left;width:20px"><?php $sectionNumber = $i+1; echo $sectionNumber;?>.</div>
	<div style="margin-left:23px">
<?php 
switch($detailPageComponents[$i]['anchor']){
    case 'gallery':
        echo '<span class="gallery"><a href="#gallery" title="'.$detailPageComponents[$i]['title'].'-'.$details['title'].'">'.$detailPageComponents[$i]['title'].'</a></span>'; 
        $showGallery = $i;
        break;
    case 'videos':
        echo '<span><a href="#'.$detailPageComponents[$i]['anchor'].'" title="'.$detailPageComponents[$i]['title'].'-'.$details['title'].'">'.$detailPageComponents[$i]['title'].'</a></span>'; 
        $showVideos = $i;
        break;
    case 'documents':
        echo '<span><a href="#'.$detailPageComponents[$i]['anchor'].'" title="'.$detailPageComponents[$i]['title'].'-'.$details['title'].'">'.$detailPageComponents[$i]['title'].'</a></span>'; 
        $showDocs = $i;
        break;
    default:
        echo '<span><a href="#'.$detailPageComponents[$i]['anchor'].'" title="'.$detailPageComponents[$i]['title'].'-'.$details['title'].'">'.$detailPageComponents[$i]['title'].'</a></span>'; 
        break;
}
?>
	</div>
</div>
</li>
<?php } ?>
<!--<li>2. <span class="aSpeak"><a href="">Alumni Speak</a></span></li>-->
                        </ol>
                        <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
                    </div>
                </div>
            </div>
            <!--End_Contents-->
            <?php } ?>
			
			<?php
				if(!isset($cmsData)) {
				if(($registerText['paid'] == "yes") && ($flagForAB != 'false')){ 
				  $googleTrackKey = 'MiddlePanel';
				  if(($flagForAB == 'true')) {
					$googleTrackKey = 'MiddlePanel_'.$details['institute_id'].'_case1';
				  }
			?>
			<div style="margin:10px 0">
				<div class="raised_10"> 
					<b class="b2"></b><b class="b3" style="background:#fff"></b><b class="b4" style="background:#fff"></b>
					<div class="boxcontent_10" style="background:#FFF5C4 url(/public/images/bgCounsel.png) repeat-x left top">
						<div class="wdh100">
							<img src="/public/images/iconCounsel.png" align="left" hspace="10" />
							<div style="padding-top:5px">
							<span style="position:relative;*top:-5px"><b>Interested in studying at <span class="OrgangeFont"><?php echo $details['title']; ?></span></b></span>&nbsp; &nbsp; &nbsp;<input type="button" class="listingBtn_1" value="I want this institute to counsel me" onclick="gotoBtmForm(2,'<?php echo $googleTrackKey; ?>');"></div>
							<div class="clear_B">&nbsp;</div>
						</div>
					</div>
					<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
			</div>
			<?php } } ?>
<?php
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
//$this->load->view('listing_forms/showAlumSpeak');
?>    
            
                        
<?php 
for($i = 0;$i < count($detailPageComponents); $i++){
    if($i == $showGallery || $i == $showDocs || $i == $showVideos){
        continue;
    }
$tempArray['wikiAnchor'] = $detailPageComponents[$i]['anchor'];
$tempArray['wikiTitle'] = $detailPageComponents[$i]['title'];
$tempArray['wikiDesc'] = $detailPageComponents[$i]['value'];
$tempArray['editUrllink'] = $instituteEditUrlwiki;
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
<div class="lineSpace_10">&nbsp;</div>
<div style="display:none">
    <a href="<?php echo (!strpos($details['url'],'//')  ? 'http://'. $details['url'] : $details['url']); ?>" title="<?php echo $details['title']; ?>" target="_blank" ><b><?php echo ($details['url']);?></b></a>
</div>

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
        if($registerText['paid'] == "yes"){
            $this->load->view("listing_forms/get_free_alerts_details_page");
        }
}
?>
</div>
<div class="graycolor Fnt11">
<?php if(trim($details['url']) != "") {
	echo "**This information has been collected from ".$details['url'].". Trade Marks belong to the respective owners. The listing was last modified on ".date("m-d-Y",strtotime($details['lastModifyDate'])).".";
} else {
	echo "**This information has been collected from the institute's website and brochures. Trade Marks belong to the respective owners.The listing was last modified on ".date("m-d-Y",strtotime($details['lastModifyDate'])).".";
}
?>
</div>
<div class="lineSpace_28">&nbsp;</div>
<div class="">
<?php  
/*if(!isset($cmsData)){
   $sourcearr = array('pageKeyInfo'=>'LISTING_INSTITUTEDETAIL_BOTTOM_RELATED_QUESTION_');
   $this->load->view("listing/relatedQns",$sourcearr);
}*/
?>
</div>
<div class="lineSpace_28">&nbsp;</div>
    <div class="">
        <?php
        if ( $ListingMode == 'view' ) {
            if(!isset($cmsData)){
                $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
                $this->load->view('common/banner',$bannerProperties);
            }
        }
        ?>
    </div>
    
        </div>
        <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>    
    </div>
    <!--End_Left_Panel-->

