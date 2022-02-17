<?php
$headerComponents = array(
	'css'               => array('studyAbroadCommon', 'studyAbroadCategoryPage'),
	'canonicalURL'      => $seodata["canonical"],
	'title'             => ucfirst($seodata["title"]),
	//'metaDescription'   => ucfirst($seodata["description"]), // this line is commented so as to add New computed Seo-Description from controller
	'metaDescription'   => ucfirst($seoDescription),
	'metaKeywords'      => ucfirst($metaKeywords),
	'pgType'	        => 'countryPage',
	'pageIdentifier'	=> $beaconTrackData['pageIdentifier']
	);
$this->load->view('common/studyAbroadHeader', $headerComponents);
echo jsb9recordServerTime('SA_COUNTRY_PAGE',1);
?>
<style>
.font-15{font-size:15px;}
.all-univ-table{width:100%; margin-top:20px;}
.all-univ-table tr th{background:#ebebeb; border-bottom:2px solid #4573b1; color:var(--Charcoal-Black); padding:15px 10px; text-align:left;font-size: 14px;}
/*.all-univ-table tr{-moz-transition:background-color 500ms ease-out; -webkit-transition:background-color 500ms ease-out; transition:background-color 500ms ease-out;}
 *.all-univ-table tr:hover{background-color:#f1f1f1}
 **/
.all-univ-table tr td{vertical-align:top; padding:12px;line-height:18px; color:var(--Charcoal-Black); border-bottom:1px solid var(--Smoke)}
.popular-row td{border-top: none !important;}
.view-btn{border:1px solid #ccc; -moz-border-radius:2px; -webkit-border-radius:2px;  border-radius:2px;padding:4px 7px; color:#333 !important; text-decoration:none !important; display:block; margin:5px 0 0 0; width:158px; background:#fff; -moz-transition-property:background-color, color,; -moz-transition-duration:500ms; -moz-transition-timing-function:ease-out;-webkit-transition-property:background-color, color,; -webkit-transition-duration:500ms; -webkit-transition-timing-function:ease-out; transition-property:background-color, color,; transition-duration:500ms; transition-timing-function:ease-out;}
.view-btn:hover{color:#fff !important; background:#F78640;}
.all-univ-table tr td span{display:block;}
.all-univ-table tr td.last{ vertical-align: middle;}
.view-btn span{font-size:18px; float:right;}
/* .alt-rowbg{background:#fafafa} */
.number-bg{font-weight:normal; border-top:0 none !important;}
/* .all-univ-table tr.popular-row-border td{border-bottom:0 none;} */
.all-univ-table tr.popular-row td{border-bottom:1px solid #ebebeb; border-right:1px solid #ebebeb}
.all-univ-table tr.popular-row td.number-bg{border:0 none !important}
/*.all-univ-table tr.popular-row, .all-univ-table tr.popular-row-border{-moz-transition:none; -webkit-transition:none; transition:none;}
.all-univ-table tr.popular-row:hover, .all-univ-table tr.popular-row-border:hover{background-color:transparent}*/
.country-dwn-arrow{background-position:-236px -50px;width:15px; height:11px;}
.popular-courses{width:475px;border:1px solid #ebebeb;}
.popular-courses tr th{background:#ebebeb; border:1px solid #ebebeb; color:var(--Charcoal-Black); padding:3px 5px; text-align:left;}
.popular-courses tr td{vertical-align:top; padding:6px 8px; border:0 none !important; border-bottom:1px solid #ebebeb !important; line-height:18px; color:var(--Charcoal-Black); background:#fff;}
.course-pointer{background-position:-235px -106px; width:18px; height:15px;top:0; left:10px;}
.photos-icon, .videos-icon{background-position:-213px -91px; width:17px; height:12px; margin-right:4px; top:-1px}
.videos-icon{background-position:-233px -91px; margin-left:3px}
.all-countries-layer{border:1px solid #d3d3d3; width:390px; position:absolute; top:47px; left:200px; z-index:1; -moz-box-shadow:0px 0px 3px #ababab; -webkit-box-shadow:0px 0px 3px #ababab; box-shadow:0px 0px 3px #ababab; -moz-border-radius:0 0 3px 3px; -webkit-border-radius:0 0 3px 3px; border-radius:0 0 3px 3px; background:#fff; display:none;}
.all-countries-layer.disblk{display:block;}
.all-country-title{background:#666; padding:12px 8px; color:#fff;}
.all-country-list{padding:10px;}
.all-country-list ul{width:99%; float:left}
.all-country-list ul li{width:45%; margin-bottom:12px}
.all-country-list ul a strong{margin:10px 0 0 61px; font-weight:normal; display:block}
.all-countries-layer .pointer{background-position:-235px -66px;width:20px; height:12px; position:absolute; top:-11px; left:10px;}
.mtb2{margin:2px 0}
.hideDiv{ display: none;}
a.button-style.dnd-brch {
    border-radius:  2px;
    width: 155px;
    text-align:  center;
    font-size: 14px;
    font-weight:  600;
    font-family: 'open sans';
}
a.vw-crsLink {
    display: inline-block;
    margin-left: 6px;
    text-decoration: none;
}
a.vw-crsLink span{float:right;margin-left:9px;font-size:16px; color:#0065de;}
</style>
<?php $this->load->view('categoryList/abroad/widget/categoryPageBreadCrumb');?>
<div class="content-wrap clearfix">
	<div class="course-title" style="position:relative">
		<h1>Top Universities <?php echo ($countryName == "Abroad" ? "" :"in")?> <a href="javascript:void(0);" onclick = "showCountryDropDown();" id="showCountryDropdownLink" class="country-dropdwn"><?php echo $countryName?><i class="common-sprite country-dwn-arrow"></i></a></h1>
		<?php
			$pageTitle = "All Universities".($countryName == "Abroad" ? "" :"in").$countryName;
		?>
		<script>
			var rmcPageTitle = "<?php echo base64_encode($pageTitle)?>";
		</script>
		<div class="course-country-help" id="countrySelectHelp" style="display: none">
		     <i class="cate-sprite help-arrow-1"></i>
		     <p>Use this dropdown to change country</p>
		</div>
        <div class="all-countries-layer" >
	    <i class="common-sprite pointer"></i>
	    <div class="all-country-title">Select a country (<?php echo count($abroadCountries)?>)</div>
	    <div class="all-country-list">
		<div id= "scrollDivCountryDropdown" class="scrollbar1">
		    <div class="scrollbar">
			<div class="track" style="height:227px;">
			    <div class="thumb"></div>
			</div>
		    </div><!-- end : scrollbar-->
		    <div class="viewport" style="height:227px">
			    <div class="overview" style="position:relative">
			    <ul>
			    <?php for($i=0;$i<count($abroadCountries);$i=$i+2){ // started with second because first is all countries ?>
				<?php if($abroadCountries[$i]) { ?>
				<li class="flLt">
					<a href="<?php echo $abroadCountries[$i]['url']?>"><span class="flags flLt <?php echo strtolower(implode('',explode(' ',$abroadCountries[$i]['name'])))?>"></span> <strong><?php echo $abroadCountries[$i]['name']?></strong></a>
				</li>
				<?php } ?>
				<?php if($abroadCountries[$i+1]) { ?>
				<li class="flRt">
					<a href="<?php echo $abroadCountries[$i+1]['url']?>"><span class="flags flLt <?php echo strtolower(implode('',explode(' ',$abroadCountries[$i+1]['name'])))?>"></span> <strong><?php echo $abroadCountries[$i+1]['name']?></strong></a>
				</li>
				<?php } ?>
			    <?php } ?>
			    </ul>
			    </div>
		    </div>
                </div>
            </div>
        </div>
    </div>
    <?php /*$this->load->view('categoryList/abroad/widget/categoryPageLinksOnCountryPage'); */?>
    <table cellpadding="0" cellspacing="0" class="all-univ-table flLt">
	<tr>
	    <th width="5%">S.No.</th>
	    <th width="35%">University name</th>
	    <th width="16%">Location</th>
	    <th width="28%">University Info</th>
	    <th width="15%"></th>
	</tr>
	<?php
	    $currentPage = $paginationArr["pageNumber"];
	    $resultsPerPage = $paginationArr["limitRowCount"];
	    $startingResultOfCurrentPage = ($resultsPerPage * ($currentPage - 1)) + 1;

	    $i = $startingResultOfCurrentPage - 1;

	    $count_BMS_Banner=0; //To display BMS banner in middle

	    foreach($universityList as $universityObj){
			/*$snapshotCourseCount = count($universityObj['snapshot_courses']);
			if($snapshotCourseCount == 1) {
				//$snapId = $universityObj['snapshot_courses'][0];
				if(empty($universityObj['snapshot_courses'][0])) {
				    $snapshotCourseCount = 0;
				}
			}*/
		$courseCount = $universityObj["courseCount"];// + $snapshotCourseCount;
		//Sorted order of course
		$sortOrderOfCourses = $universityObj['coursesToShow'];
		//_p($sortOrderOfCourses);
		$universityObj['photos'];
		$universityObj['videos'];
		$showPhotoVideoSection = false;
		if(count($universityObj['photos'])>0){
		    $photoLinkCaption = "Photos (".count($universityObj['photos']).")";
		    $showPhotoVideoSection = true;
		}
		if(count($universityObj['videos'])>0){
		    $videoLinkCaption .= "Videos (".count($universityObj['videos']).")";
		    $showPhotoVideoSection = true;
		}
		// combine the photo & video arrays
		$photoVideoArray = array_merge($universityObj['photos'],$universityObj['videos']);
		$class = ($i%2 == 0) ? '' : 'alt-rowbg popular-row-border';
	?>
		<tr class="<?php echo $class?>">
		    <td class="font-18 number-bg" align="right"><?php echo ++$i;?></td>
		    <td>
			<p><a href="<?php echo $universityObj['url']?>" onclick="studyAbroadTrackEventByGA('ABROAD_COUNTRY_PAGE', 'universityPageLink');" class="font-15"><strong><?php echo $universityObj['university_name']?></strong></a></p>
			<?php if(reset($sortOrderOfCourses)){?>
			    <a class="smlr-course-btn" onclick="toggleExamDiv(this)" href="javascript:void(0)"><i class="cate-sprite plus-icon"></i>Popular Courses</a>
			    <a href="<?php echo $universityObj['url']?>" onclick="studyAbroadTrackEventByGA('ABROAD_COUNTRY_PAGE', 'viewAllCoursesLink');" class="vw-crsLink">View All <?php echo ($courseCount == 1) ? $courseCount." Course" : "all ".$courseCount." Courses"?><span>&rsaquo;</span></a>
			<?php }?>
		    </td>
			<?php
					$location = $universityObj['cityName'];
					if($universityObj['stateName']){
							if($universityObj['cityName']){
								$location .= ', ';
							}
						$location .= $universityObj['stateName'];
					}
			?>
		    <td><?php echo $location?></td>
		    <td>
			<?php
					$universityInfoText = '';
					if($universityObj['university_type'] != 'private' && $universityObj['university_type'] != 'public'){
							$universityInfoText = 'Non-profit university';
					}else{
							$universityInfoText = ucfirst($universityObj['university_type']).($universityObj['university_type2'] == 'college'?' college':' university');
					}
					if($universityObj['establishment_year']){
							$universityInfoText .= ', Estd '.$universityObj['establishment_year'];
					}
			?>
			<p><?php echo $universityInfoText?></p>
			<p class="mtb2">
			    <?php if(count($universityObj['photos'])>0){ ?>
			    <a href = "javascript:void(0);" onclick="initiatePhotoVideoOverlay(<?php echo $universityObj['university_id']?>,'photoOverLay');" class=""><i class="common-sprite photos-icon"></i>Photos (<?php echo count($universityObj['photos'])?>)</a>
			    <?php }
				  if(count($universityObj['videos'])>0){ ?>
			    <a href = "javascript:void(0);" onclick="initiatePhotoVideoOverlay(<?php echo $universityObj['university_id']?>,'videoOverLay');" class=""><i class="common-sprite videos-icon"></i>Video (<?php echo count($universityObj['videos'])?>)</a>
			    <?php } ?>
			</p>
			<?php
			    if($universityObj['website']){
				    if(strpos($universityObj['website'],'http://') != 0){
					    $universityWebsite = 'http://'.$universityObj['website'];
					    }else{
					    $universityWebsite = $universityObj['website'];
					    }
			?>
			    <a target="_blank" rel="nofollow" href="<?php echo $universityWebsite?>">International student website<i class="common-sprite ex-link-icon"></i></a>
			<?php }//END :: if($universityObj['website'])?>
		    </td>
		    <td class="last">
			<?php if(!empty($courseCount) && !empty($universityObj['url'])) { ?>
			     <a href="javascript:void(0);" class="button-style dnd-brch" onclick="loadBrochureDownloadForm('<?=base64_encode(json_encode($universityObj['response_data']))?>');">Download Brochure</a>
			<?php }else
				  echo "-";
			?>
		    </td>
		</tr>

		<?php if(count($sortOrderOfCourses)){?>
		<tr class="<?php echo $class?> popular-row exam-div hideDiv">
				<td class="number-bg"></td>
				<td colspan="4" style="padding-top:0;">
				<div class="exam-data hideDiv">
				<div class="exam-data hideDiv">
				    <i class="common-sprite course-pointer"></i>
					     <table cellpadding="0" cellspacing="0" class="popular-courses">
					    <tr>
						    <th width="37%"></th>
							    <th width="18%">Eligibility </th>
							    <th width="37%">1st Year Total Fees </th>
							    <th width="20%">Duration</th>
					    </tr>
					    <?php foreach($sortOrderOfCourses as $courseObj){?>
					    <tr>
						<td><a href="<?php echo $courseObj->getUrl()?>"><?php echo $courseObj->getName()?></a></td>
						<?php
						     $eligiblePriority = 99;
						     $eligibleExam = "";
						     foreach($courseObj->getEligibilityExams() as $exam){
						     if($exam->getId() == -1)
						     {
							continue;
						     }
						     else{
						        if($exam->getListingPriority() < $eligiblePriority){
						            $eligiblePriority = $exam->getListingPriority();
									$eligibleExam = $exam->getName().":".(($exam->getCutOff()=="N/A")?"Accepted":$exam->getCutOff());
							}
						     }
						    } ?>
						<td>
							<p <?php if(strpos($eligibleExam,'Accepted')!=FALSE){ echo "onmouseover='showAcceptedMessage(this)' onmouseout='hideAcceptedMessage(this)' style='position:relative'"; } ?> >
								<?php if(strpos($eligibleExam,'Accepted')!=FALSE){$this->load->view('listing/abroad/widget/examAcceptedTooltip',array('examName'=>strtok($eligibleExam,':')));} ?>
								<?php echo $eligibleExam?>
							</p>
						</td>
						<td><?php echo str_replace("Lac","Lakh",$courseFeeDetails[$courseObj->getId()]['feesVal'])?></td>
						<td><?php echo $courseObj->getDuration()->getDisplayValue()?></td>
					   </tr>
					    <?php }?>
					</table>
				</div>
				</div>
				</td>
		</tr>
		<?php }?>

	<!--	For Showing BMS Banner in middle -->

		<?php  $count_BMS_Banner++;
		if(($count_BMS_Banner == 7)||(($count_BMS_Banner == count($universityList)&&($count_BMS_Banner<7)))) {	?>



			<tr>

			<td colspan=5 style= "border: 0 none; background: #fff" width="100%" align="middle">
			    <div class="banner-cont2">
				<!--<p>Advertisements</p>-->
				<?php
					$bannerProperties = array('pageId'=>'SA_COUNTRY', 'pageZone'=>'MIDDLE','shikshaCriteria' => $criteriaArray);
					$this->load->view('common/banner',$bannerProperties);	?>
			    </div>

			</td>
		        </tr>

				<?php
		}?>


	    <?php } // end :foreach($universityList as $universityObj)?>
    </table>
    	<?php

		     // Load Pagination
		     $this->load->view('categoryList/abroad/countrypage/countryPagePagination');
		?>
		<!-- Disclaimer-->
		<div class='clearwidth rnking-dsclmr'>
		The above ranking for top universities in <?php echo $countryName ?> is based on university popularity on <a href="<?php echo SHIKSHA_HOME; ?>">Shiksha.com</a>
    	</div>
		<!--End Disclaimer-->

        	<a href="#" class="backtop-btn"><i class="common-sprite bcktop-icon"></i><span>Back to top</span></a>
</div>

    	<!--</div>-->
        <div id = "mediaForStudyAbroadPage"></div>
    <!--</div>-->
<!--</div>-->
<script>

    function toggleExamDiv(obj) {
		    if ($j(obj).find("i").hasClass('plus-icon')) {
				    //$j(obj).find('i').removeClass('plus-icon').addClass('minus-icon');
				    //$j(obj).closest('tr').next('tr').show();
				    //$j(obj).closest('tr').next('tr').find('.exam-data').slideDown("slow");
				    $j(obj).closest('tr').next('tr').find('.exam-data').slideDown(200,"swing",function(){
						$j(obj).closest('tr').next('tr').show();
						});
				    $j(obj).find('i').delay(200).queue(function(){
						$j(this).removeClass('plus-icon').addClass('minus-icon').dequeue();
						});
		    }else{
				    $j(obj).closest('tr').next('tr').find('.exam-data').slideUp(200,"swing",function(){
						$j(obj).closest('tr').next('tr').hide();
						});
				    $j(obj).find('i').delay(200).queue(function(){
						$j(this).removeClass('minus-icon').addClass('plus-icon').dequeue();
						});
		    }
    }

    function initiatePhotoVideoOverlay(universityId,layerType) {
	$j('#mediaForStudyAbroadPage').load('/listing/abroadListings/getMediaForAbroadListing/'+universityId+'/university/1',function(){
	    openPhotoVideoOverLay(layerType,0);
	    });

    }
    function showCountryDropDown(){
	$j(".all-countries-layer").addClass('disblk');
	$j("#scrollDivCountryDropdown").tinyscrollbar();
    }
</script>

<?php
	$footerComponents = array(
			'js'                => array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
            'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
			'isCountryPage'		=>1
		);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<script>
    $j(document).ready(function($j){
		$j('#countrySelectHelp').css('left',($j('h1').width())+70);
		$j('#countrySelectHelp').show();
		setTimeout(function(){ $j("#countrySelectHelp").fadeOut(3000); },20000);
    });
</script>
