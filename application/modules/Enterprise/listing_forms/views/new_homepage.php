<?php 
$pageTitle = stripslashes($pageTitle);
if(strlen($pageTitle) <=0){ ?>
    <?php if($usergroup == "cms"){ 
        $title = 'CMS Control Page';
    } ?>
    <?php if($usergroup == "enterprise"){ 
        $title = 'Enterprise Control Page';
    } 
    } else { ?>
        <?php $title =  $pageTitle; ?>
<?php } 
	$headerComponents = array(
		'css'   => array('headerCms','mainStyle','raised_all','footer'),
		'js'    => array('header','common','cityList','newcommon','listing','utils','tooltip','CalendarPopup','enterprise','instituteForm','user','imageUpload','mediaListing','listing_detail'),
		'taburl' => site_url('enterprise/Enterprise'),
		'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                'title'=>$title
	);
	$this->load->view('enterprise/headerCMS', $headerComponents);
	$this->load->view('common/calendardiv');
?>
<!-- ToDo:- add a view for configuration vars like packSelection etc. -->
<div><?php $this->load->view('enterprise/cmsTabs'); ?></div>
<div style="margin:0 10px">
    <div>
        <div >
            <strong>
                <div class="normaltxt_11p_blk fontSize_16p OrgangeFont float_L" id="inst_title">
                    <?php if ( ($viewType==1) && ($flow=='add')){ ?>
                    Add Institute
                    <?php } ?> 
                    <?php if ( ($viewType==1) && ($flow=='edit')){ ?>
                    Edit Institute
                    <?php } ?> 
                </div>
                <div id="inst_title_location" class="normaltxt_11p_blk fontSize_16p float_L"></div>
                <div style="clear:left">&nbsp;</div>
            </strong>
        </div>
    </div>
    <div class="lineSpace_10">&nbsp;</div>
    <div class="row" style="position:relative; top:1px">
        <?php
            $insti_tab = 'listingUnSelTab';
            $course_tab = 'listingUnSelTab';
            $media_tab = 'listingUnSelTab';
            $preview_tab = 'listingUnSelTab';
                
            switch($viewType){
                case 1:
                $insti_tab = 'listingSelTab';
                break;

                case 2:
                $course_tab = 'listingSelTab';
                break;

                case 3:
                $media_tab = 'listingSelTab';
                break;
		default:
            		$preview_tab = 'listingSelTab';
            }
        ?>
        <a href="#" style="cursor: default;text-decoration:none" class="<?php echo $insti_tab; ?>">1. Institute Information</a>
        <a href="#" style="cursor: default;text-decoration:none" class="<?php echo $course_tab; ?>">2. Course Offered</a>
        <a href="#" style="cursor: default;text-decoration:none" class="<?php echo $media_tab; ?>">3. Photos &amp; Videos</a>
        <a href="#" style="cursor: default;text-decoration:none" class="<?php echo $preview_tab; ?>">4. Preview &amp; Publish</a>
        <div style="line-height:1px;clear:both"></div>
    </div>
    <div class="raised_lgraynoBG"> 
        <b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <div class="boxcontent_lgraynoBG">
			<?php
			if ($viewType == 1) {
				if($instituteType==1){
                	$this->load->view('listing_forms/instituteCrudForm');
				}elseif($instituteType==2){
					$this->load->view('listing_forms/testPrepInstituteCrudForm');
				}
			} else if ($viewType == 2) {
				if($instituteType==1){
					$this->load->view('listing_forms/CourseCrudForm');
				}elseif($instituteType==2){
					$this->load->view('listing_forms/testPrepCourseCrudForm');
				}
			} else if ($viewType == 3) {
                            $this->load->view('listing_forms/listingMediaForm');
			} else if ($viewType == 4) {
                            $this->load->view('listing_forms/previewPageContainer');
			}

			?>
            </div>
        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
    </div>
</div>
</div>
<div class="clearFix spacer20"></div>
<?php  $this->load->view('enterprise/footer'); ?>
