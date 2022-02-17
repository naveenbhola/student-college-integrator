<?php global $shiksha_site_current_url;global $shiksha_site_current_refferal ;?>
<div id="wrapper" data-role="page" style="min-height: 413px;" >
	<?php
		//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
        $displayHamburger = false;
        if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
                $displayHamburger = true;
        }else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
                $displayHamburger = true;
        }

        //Put a check that if Hash value is added, we have to show the Hamburger
        if(strpos($_SERVER["REQUEST_URI"], 'showHam') > 0){
            $displayHamburger = true;
        }

        if($displayHamburger){
                echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
        }
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
	?>
    <header id="page-header" class="header ui-header ui-bar-inherit slidedown ui-header-fixed" data-role="header" data-tap-toggle="false" style="height:auto;" role="banner">
       <div id="page-header-container" style=""><?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'','mobilesite_LDP',$isShowIcpBanner);?></div>
       <div class="nav-tabs" id="fixed-card" style="display:none">
            <?php $this->load->view('mobile_listing5/course/widgets/courseTabSection'); ?>
        </div>
    </header>
	
    <div data-role="content" id="pageMainContainerId">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>
	<div data-enhance="false">
		<?php $this->load->view('mobile_listing5/course/widgets/courseDetailTopWidget'); ?>
		<div class="nav-tabs" id="tab-section">
            <?php $this->load->view('mobile_listing5/course/widgets/courseTabSection'); ?>
        </div>
		<div class="new-container panel-pad">
			<?php
                $displayedSectionCount = 0;
				foreach($navigationSection as $section){
					switch($section){
						case 'Eligibility':
                        if(!empty($eligibility) && $eligibility['showEligibilityWidget'])
                        {
							$this->load->view('mobile_listing5/course/widgets/courseEligibilityWidget');
                            $displayedSectionCount++;
                        }
                    break;
                    case 'Highlights':
                        if(!empty($highlights)) {
                            $this->load->view('mobile_listing5/course/widgets/courseHighlightsWidget');
                            $displayedSectionCount++;
                        }
                    break;
                    case 'Fees':
                        if(!empty($fees))
                        {
                            $this->load->view('mobile_listing5/course/widgets/courseFeesWidget');
                            $displayedSectionCount++;
                        }
                    break;
                    case 'Gallery':
                        $this->load->view('mobile_listing5/course/widgets/courseGalleryWidget');
                        $displayedSectionCount++;
                    break;
                    case 'Structure':
                        if(!empty($courseStructure))
                        {
                        	$this->load->view('mobile_listing5/course/widgets/courseStructureWidget');
                            $displayedSectionCount++;
                        }
                    break;
                    case 'Admissions':
                        if(!empty($admissions) || !empty($importantDatesData['importantDates']))
                        {
                        	$this->load->view('mobile_listing5/course/widgets/courseAdmissionWidget');
                            $displayedSectionCount++;
                        }
                        $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON"));
                        $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "AON1"));
                    break;
                    case 'Placements':
                        if(!empty($placements) || !empty($placementsCompanies) || !empty($internships) && $internships->getReportUrl()){
                            $this->load->view('mobile_listing5/course/widgets/coursePlacementWidget');
                            $displayedSectionCount++;
                        }
                    break;
                    case 'Seats':
                        if(!empty($seats))
                        {
                            $this->load->view('mobile_listing5/course/widgets/courseSeatWidget');
                            $displayedSectionCount++;
                        }
                    break;
					case 'AnA':
                        if(trim($anaWidget['html'])) {
                            echo $anaWidget['html'];
                            $displayedSectionCount++;
                        }
                        $this->load->view('mobile_listing5/course/widgets/courseANAWidget');

                    break;
                    case 'Contact':
                        echo $schemaContact;
                        echo $contactWidget;
                        $displayedSectionCount++;
                        // $this->load->view('mobile_listing5/course/widgets/courseContactDetailsWidget');
                    break;
                    case 'Reco1':
                            //recommended listing widget
                        echo modules::run('mobile_listing5/InstituteMobile/getRecommendedListingWidget',$courseId,'course', 'alsoViewed');
                        $displayedSectionCount++;

//                        $this->load->view('mobile_listing5/course/widgets/courseAlsoViewedWidget');
                    break;
                    case 'Reco2':
                     //group listing widget
                        echo modules::run('mobile_listing5/InstituteMobile/getRecommendedListingWidget',$courseId,'course', 'similar');
                        $displayedSectionCount++;
                        //$this->load->view('mobile_listing5/course/widgets/courseSimilarCollegesWidget');
                    break;
                    case 'Reviews':
                        if(!empty($reviewWidget['html'])){
                            echo $reviewWidget['html'];
                            $displayedSectionCount++;
                        }
                    break;
		            case 'naukriSalary':
                        $this->load->view('mobile_listing5/course/widgets/courseNaukriSalaryWidget');
                        $displayedSectionCount++;
                        break;
                    // case 'EntryChances':
                    	
                    // 	break;	
                    case 'ApplyNow':
                    	$this->load->view('mobile_listing5/course/widgets/courseApplyNowWidget');
                        $displayedSectionCount++;
                    	break;
                    case 'Scholarship':
                    	$this->load->view('mobile_listing5/course/widgets/courseScholarshipWidget');
                        $displayedSectionCount++;
                    	break;
                    case 'ExpertGuidance':
                    	// $this->load->view('mobile_listing5/course/widgets/courseGuidanceWidget');
                    	break;
                    case 'Articles':
                    	$this->load->view('mobile_listing5/course/widgets/courseArticlesWidget');
                        $displayedSectionCount++;
                    	break;
                    case 'FurtherDetails':
                    	$this->load->view('mobile_listing5/course/widgets/courseFurtherDetailsWidget');
                        $displayedSectionCount++;
                    	break;
                    case 'CategoryPageLinks':                    
                        if(!empty($interLinkingLinks)){
                            $this->load->view('mobile_listing5/course/widgets/courseCategoryPageLinkingWidget');
                            $displayedSectionCount++;
                        }
                        break;
                    case 'Partner':
                        if($partners)
                        {
                            $this->load->view('mobile_listing5/course/widgets/coursePartnerWidget');
                            $displayedSectionCount++;
                        }
                    default:
                    break;
					}
                    if($displayedSectionCount==1)
                    {
                        $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA"));
                        $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA1"));
                        $displayedSectionCount=5;
                    }
				}
                
                if($isMultilocation){
                    echo modules::run('mobile_listing5/InstituteMobile/getMultiLocationLayerForCourse',$courseObj,$currentLocationObj);
                }
			?>
		</div>
        <div class="popup_layer" id="commonDetail" style="display:none"></div>
        <?php $this->load->view('/mobile_listing5/course/widgets/courseStickyCTA'); ?>
		<?php $this->load->view('/mcommon5/footerLinks'); ?>
    </div>
    </div>
</div>
    <img id = 'beacon_img' src="<?php echo SHIKSHA_HOME?>/public/images/blankImg.gif" width=1 height=1 >
    <script>
        var img = document.getElementById('beacon_img');
        var randNum = Math.floor(Math.random()*Math.pow(10,16));
        img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?=$courseObj->getId()?>+course';
    </script>