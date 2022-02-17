<?php
global $seotabs;
global $criteriaArray;
$keyname = strtoupper($categoryUrlName.($selectedCity != '' ? 'CITYID' : 'ALL').($subCategorySelected != '' ? str_replace(' ','-',$subCategorySelected) : 'All'));

$h1tag = $seotabs[$keyname]['h1'];
if($countryNameSelected == "UK-ireland"){
	$countryNameSelected = "UK-Ireland";
}
if($subName == '')
{
$titleText = 'Higher Education in  '.$countryNameSelected.'  - Study Abroad Programs in  '.$countryNameSelected.'  - Foreign Studies in  '.$countryNameSelected.'  - Courses Abroad in  '.$countryNameSelected;
$metaKeywordText = 'Higher Education in  '.$countryNameSelected.'  , Study Abroad programs in  '.$countryNameSelected.'  , Foreign Studies in  '.$countryNameSelected.'  , Courses abroad in  '.$countryNameSelected.' , colleges in  '.$countryNameSelected.' , Courses in  '.$countryNameSelected.' , Institutes in  '.$countryNameSelected.' , Universities in  '.$countryNameSelected.' , higher studies in  '.$countryNameSelected.' , college courses, degree courses, university study abroad, higher education college, higher education university, abroad studies';
$metaDescriptionText = 'Higher Education in  '.$countryNameSelected.' , Study Abroad Programs in  '.$countryNameSelected.' , Foreign Studies in  '.$countryNameSelected.' , Courses Abroad in  '.$countryNameSelected.' . Find thousands of course options & colleges abroad with details about course duration, contact information, expert advice and related information.';
}
else
{
$titleText = $subName.' Colleges in  '.$countryNameSelected.'  - '.$subName.' Courses in  '.$countryNameSelected.'  - '.$subName.' Universities in  '.$countryNameSelected.'  - '.$subName.' Institutes in  '.$countryNameSelected;
$metaDescriptionText = $subName.' Colleges in '.$countryNameSelected.' , '.$subName.' Courses in '.$countryNameSelected.' , '.$subName.' Universities in '.$countryNameSelected.' , '.$subName.' Institutes in '.$countryNameSelected.' . Complete information on Higher Education in '.$subName.' , '.$subName.' Study Abroad Programs, Scholarships, Course duration, Contact information, Expert advice and related information.';
$metaKeywordText = $subName.' Colleges in '.$countryNameSelected.' , '.$subName.' Courses in '.$countryNameSelected.' , '.$subName.' Universities in '.$countryNameSelected.' , '.$subName.' Institutes in '.$countryNameSelected.' , higher Education , '.$subName.' Study Abroad, Scholarships, colleges abroad, universities abroad,  institutes abroad, education abroad';
}
$criteriaKeyword = 'BMS_'.strtoupper(str_replace(' ','_',$countryNameSelected));
$criteriaArray = array(     
        'category' => $categoryId,     
        'country' => $countryId,      
        'city' => $selectedCity,     
        'keyword'=>$criteriaKeyword     
        );

$headerComponents = array('js'=>array('common','lazyload','multipleapply','category','user','studyAbroad','discussion'),
        'jsFooter' =>array('ana_common'),
        'css'=>array('modal-message','raised_all','mainStyle'),
        'product'=>'foreign',
        'taburl' =>  site_url(),
        'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>'FOREIGN_DETAIL_PAGE_TOP', 'shikshaCriteria' => $criteriaArray),'tabName'	=>	'Event Calendar',
        'title'	=>	$titleText,
        'metaKeywords'	=>$metaKeywordText,
        'metaDescription' => $metaDescriptionText
        );
?>

<?php
$this->load->view('common/header', $headerComponents); 
$pagename = array('pagename'=>'studyabroaddetail');
$this->load->view('home/shiksha/countryOverlay',$pagename); 
echo "<script>currentPageName = 'STUDY ABROAD';</script>";
?>
<input type="hidden" id="category_unified_id" value="<?php echo $categoryId?>"/>
<input type="hidden" id="categorypage_unified_thankslayer_identifier" value=""/>
<input id = "catstartoffset" value = "<?php echo $start;?>" type = "hidden"/>
<input id = "catcountoffset" value = "<?php echo $count;?>" type = "hidden"/>
<input type="hidden" id="subCategoryId" value="<?php echo $categoryId?>"/>
<input type="hidden" id="country" value="<?php echo $countryId?>"/>
<input type="hidden" id="cities" value="0"/>
<input type="hidden" id="intinstituteId" value=""/>
<input type="hidden" id="catcategory" value="studyabroaddetail"/>
<input type="hidden" id="catsubcat" value="<?php echo $subCategorySel;?>"/>
<input type="hidden" id="catcityid" value="All"/>
<input type="hidden" id="catcity" value="All"/>
<input type="hidden" id="catcourselevel" value="All"/>
<input type="hidden" id="catcourselevel1" value="All"/>
<input type="hidden" id="catcoursetype" value="All"/>
<input type="hidden" id="catpagename" value="countrypage"/>
<input type="hidden" value="opencatpage" id = "methodName"/>
<input type="hidden" value="<?php echo $countSelected;?>" id = "countryname"/>

					<?php $this->load->view('home/category/commonheader');?>

	<div>
        <!--Start_MidPanel-->
        <div class="mlr10">
            <div>                
                <div class="float_L w738">
                    <!--Start_Entrance_Exams_Required-->
                                <?php
                                $totalres = $collegeList['countArray']['totalcount'];
                               // if($totalres > 0) 
                               // {
                                ?>
                    <div class="wdh100">
                        <div class="shik_skyBorder">                                	
                            <div class="shik_roundCornerHeaderSpirit shik_skyGradient">
								<div class="float_L">
								<h2><span class="Fnt14 fcblk" style="padding-left:10px"><b>Institutes &amp; Universities in <?php echo $countryNameSelected;?></b></span></h2>
								</div>
								<div class="float_R">
									<h3><span class="Fnt12 float_L fcblk">
										<?php echo ($subName == 'All' || $subName == '')? 'All Categories &nbsp;':$subName.' &nbsp;'; ?>
									</span>
                                    </h3>
                                    <h3>
									<span class="Fnt12 fcGya float_L fcblk">
									   <b>[</b> <a href="#" style="" onClick = "drpdwnOpen(this, 'overlayCategoryHolder',6);document.getElementById('overlayCategoryHolder').style.left = obtainPostitionX(this); + 'px';return false;">Refine Category <span class="orangeColor">&#9660;</span></a> <b>]</b>
									</span>&nbsp; &nbsp;
                                    </h3>
								</div>
							</div>
                            <div class="mlr10">
                            <?php if($totalres > 0) { ?>
                                <div class="mtb10" id = "categoryCollegesCountLabel">Showing <?php echo $start + 1;?>-<?php echo ($totalres >  $count + $start)  ? ($count + $start) : $totalres ?> of <?php echo $totalres ?></div>
                                <?php } 
                                else
                                {
?>
                                <div class="mtb10" id = "categoryCollegesCountLabel">No institutes found for the selection</div>
  <?php                              }
                                
                                ?>
                                <!--Start_Repeating_Data_Container-->
                                <?php
                                $countArray = $collegeList['countArray'];
                                $collegeList = $collegeList['institutesarr']; ?>
                                <div id = "collegeListPlace">
									<ul class="faqSA_ul">
										<?php
                                        $k = 0;
										for($i = 0;$i<count($collegeList);$i++) {
                                               $classNamein = 'mtb10 brdbottom pb15';
                                for($mk = 0;$mk <count($countArray['mainids']);$mk++)
                                {
                                    // if(isset($institutearr['isSponsored']) && $institutearr['isSponsored'] == 'true')
                                    if($countArray['mainids'][$mk] == $collegeList[$i]['institute']['id'])
                                    {
                                               $classNamein = 'mtb10 pb15 brdbottom chk';
                                        break;
                                    }
                                }
                                    for($mn = 0;$mn < count($countArray['categoryselector']);$mn++)
                                    {
                                        if($countArray['categoryselector'][$mn] == $collegeList[$i]['institute']['id'])
                                        {
                                           // $back="background:#fefde8";
                                               $classNamein = 'mtb10 pb15 brdbottom chk';
                                            break;
                                        }

                                    }
                                               ?>
											<li class="<?php echo $classNamein;?>">
												<div class="wdh100">
													<div class="float_L w580">
														<div class="wdh100">
															<a href="<?php echo $collegeList[$i]['institute']['url']?>" title="<?php echo $collegeList[$i]['institute']['institute_Name']?>"><?php echo $collegeList[$i]['institute']['institute_Name']?>,</a><br />
															<?php if($collegeList[$i]['institute']['id']!='33211' && $collegeList[$i]['institute']['id']!='32469' && $collegeList[$i]['institute']['id']!='32383' && $collegeList[$i]['institute']['id']!='32645' ){ ?><span class="fs11 fcGrn"><?php echo $collegeList[$i]['institute']['city']?>,<?php echo $collegeList[$i]['institute']['countryName']?></span><br /><?php } ?>
															<a href = "<?php echo $collegeList[$i]['courses'][$k]['courseurl'];?>" class="fs11" title="<?php echo $collegeList[$i]['courses'][$k]['course_title']?>"><?php echo $collegeList[$i]['courses'][$k]['course_title']?></a>                                                                
														</div>
													</div>
													<div class="float_R w110">
													<?php if($collegeList[$i]['institute']['isSendQuery']) { ?>														
														<div class="float_L wdh100"><input type="button" onClick = "openOverlayInterested(this,<?php echo $collegeList[$i]['institute']['id']?>,'<?php echo str_replace("\"","",$collegeList[$i]['institute']['institute_Name']);?>','<?php echo $collegeList[$i]['institute']['url']?>');" class="btn_Im" /></div>
													<?php } else { ?>
													&nbsp;
													<?php } ?>
													</div>
													<div class="clear_B"></div>
												</div>
												<div class="fcGya fs11">
													<?php
													if($collegeList[$i]['institute']['mediadata']['photo'] == 0){
														$collegeList[$i]['institute']['mediadata']['photo'] = NULL;
													}
													
													if($collegeList[$i]['institute']['mediadata']['video'] == 0){
														$collegeList[$i]['institute']['mediadata']['video'] = NULL;
													}
	
													
													if(isset($collegeList[$i]['institute']['mediadata']['photo'])) {
													?><a href="<?php echo $collegeList[$i]['institute']['url'] . '#gallery'?>" class="phtoA"> <?php echo $collegeList[$i]['institute']['mediadata']['photo'] . " Photos" ?></a> &nbsp; <?php  
													} 
													if(isset($collegeList[$i]['institute']['mediadata']['photo']) && (isset($collegeList[$i]['institute']['mediadata']['video']) || isset($collegeList[$i]['institute']['mediadata']['rating']))) 
													{ ?> | &nbsp;
													<?php } 
													if(isset($collegeList[$i]['institute']['mediadata']['video'])) 
													{ ?><a href="<?php echo $collegeList[$i]['institute']['url'].'#videos'?>" class="vidoA"><?php echo $collegeList[$i]['institute']['mediadata']['video']. " Videos"?></a> <?php } ?>
													<?php 
													if(isset($collegeList[$i]['institute']['mediadata']['rating']) && isset($collegeList[$i]['institute']['mediadata']['video']) && $collegeList[$i]['institute']['mediadata']['rating'] > 0)
													{ ?>
													&nbsp; |
													<?php  }
													if(isset($collegeList[$i]['institute']['mediadata']['rating']) && $collegeList[$i]['institute']['mediadata']['rating'] > 0)
													{ ?>
													&nbsp; <a href="<?php echo $collegeList[$i]['institute']['url'].'#alumrating'?>" class="starA"><?php echo $collegeList[$i]['institute']['mediadata']['rating']?>/5 Rating</a>
													<?php } ?>
												</div>
											</li>                                                    
										<?php } ?>   
									</ul>	
								</div>
                                <!--End_Repeating_Data_Container-->
                                <div class="wdh100">
                                    <div class="float_L">
												<div id="pagingIDc">
													<div class="txt_align_r pagingID" style="padding:5px" id="paginataionPlace1" >
                                                            <?php  if($totalres > 15) { echo $paginationHTML; } ?>
													</div>
												</div>
                                    </div>
                                    <div class="clear_B"></div>
                                </div>
                            </div>
                            <div class="lh10"></div>
                        </div>
                    </div>
                    <!--End_Entrance_Exams_Required-->
                    <?php //} ?>
                    <div class="lineSpace_10">&nbsp;</div>
                	<div class="wdh100">
                    	<div class="float_L w370">
                                <!--Start_Impotent_Dates-->
                                <!--Start_Entrance_Exams_Required-->
				<?php $this->load->view('events/studyAbroadEventsWidget');?>
                                <?php $this->load->view('home/category/entranceexamwidget');?>
                                <!--End_Entrance_Exams_Required-->
                        </div>
                        <div class="float_R w355">
                        	<div class="float_L wdh100">                                                                
                                <!--Start_FAQ-->
                                <?php $this->load->view('home/category/faqwidget');?>
                                <!--End_FAQ-->                            
                                <div class="lh10"></div>
                                <!--Start_AnA-->
                                <?php
                               $commentData['pageKeySuffixForDetail'] = 'STUDYABROAD_DETAILPAGE_MIDDLEPANEL_'; 
                                $this->load->view('home/category/asknanswerwidget.php',$commentData);?>
                                <!--Start_Articles-->
                                <?php $this->load->view('home/category/articleswidget');?>
                                <!--End_Articles-->
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            <div class="float_R w200">
            	<div class="float_L w200">
	      <?php  
          
            global $criteriaArray;
        $criteriaArray = array(
                'category' => $categoryId,
                'country' => $countryId,
                'city' => $selectedCity,
                'keyword'=>$criteriaKeyword
                );
          $bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_RIGHT', 'shikshaCriteria' => $criteriaArray);
 $this->load->view('common/banner.php', $bannerProperties);?> 
          <!--              <div><img src="/public/images/b1.png" /></div>
                        <div class="lh10"></div>
                        <div><img src="/public/images/b2.png" /></div>
                        <div class="lh10"></div>
                        <div><img src="/public/images/b3.png" /></div>
                        <div class="lh10"></div>
                        <div><img src="/public/images/b4.png" /></div>-->
                    </div>
                </div>
            </div>
            <div class="clear_B"></div>
        </div>
        <!--End_MidPanel-->
	</div>
    <div style="height:20px">&nbsp;</div>
<?php 
$this->load->view('home/interested'); 
$this->load->view('common/footerNew');
?>
<?php
$selectedIcon = isset($countArray['selectedIcon'])?$countArray['selectedIcon']:'';
$countryName = strtolower(str_replace(' ','',$countrySelected));
if(isset($selectedIcon) && $selectedIcon != '')
$imageurl = $selectedIcon;
else
$imageurl = '/public/images/'.$countryName.'.jpg';
$iconval = 0;
if(!(isset($selectedIcon) && $selectedIcon != ''))
{
    $iconval = 1;
}
?>	
<script>
LazyLoad.loadOnce([
        '/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
        '/public/js/<?php echo getJSWithVersion("ajax-api"); ?>'
        ],callbackfn);
<?php if($iconval) { ?>
    document.getElementById('imagespace').innerHTML = '<img src="<?php echo $imageurl;?>" border="0" hspace="5"/>';
    <?php } else { ?>
document.getElementById('imagespace').innerHTML  = '<div id = "floatad1" style = "z-index:1001;overflow:hidden"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="760" height="100" id = "flashcontent"><param name="movie" value="<?php echo trim($imageurl);?>"/><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="wmode" value="transparent" /><embed src="<?php echo trim($imageurl);?>" width="760" height="100" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" allowScriptAccess = "always" wmode = "transparent" name = "flashcontent1" id = "flashcontent1"></embed></object></div>';
        <?php } ?>
if(<?php echo $openOverlay ?>)
{
    document.getElementById('dim_bg').style.height = document.body.offsetHeight +  'px';
    document.getElementById('dim_bg').style.display = 'inline';
}
</script>
