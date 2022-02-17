<?php
        global $seotabs;
        $product = 'testprep';
        $keyname = strtoupper($categoryUrlName.($selectedCity != '' ? 'CITYID' : 'ALL').($subCategorySelected != '' ? str_replace(' ','-',$subCategorySelected) : 'All'));


        global $testprep_seo_data;
        $seo_data = get_testprep_seo_tags($params['blog_acronym'], $params['city_name']);
        if(isset($seo_data)){
        $titleText = $seo_data['Title'];
        $metaDescriptionText = $seo_data['Description'];
        $metaKeywordText = $seo_data['Keywords'];
        }
        $criteriaKeyword = $selectedCity == '' ? 'BMS_ALL_CITIES' : '';
        $h1tag = $seotabs[$keyname]['h1'];
        
        $testprepCriteriaArray = array(
                'category' => '',
                'country' => 2,
                'city' => $params['city_id'],
                'keyword'=>$params['blog_acronym']."_exams"
                );

$headerComponents = array('js'=>array('common','lazyload','multipleapply','category','user'),
'jsFooter' =>array('ana_common'),
                        'css'=>array('modal-message','raised_all','mainStyle'),
						'product'=>'testprep',
                                'taburl' =>  site_url(),
								'bannerProperties' => array('pageId'=>'TESTPREP_CATEGORY', 'pageZone'=>strtoupper($params['blog_acronym']).'_EXAMS_PAGE_TOP', 'shikshaCriteria' => $testprepCriteriaArray),'tabName'	=>	'Event Calendar',
								'title'	=>	$titleText,
								'metaKeywords'	=>$metaKeywordText,
								'metaDescription' => $metaDescriptionText
);
?>
<?php $this->load->view('common/header', $headerComponents); ?>
<script>currentPageName = 'TEST PREP PAGE';</script>
<?php
$subCategorySelected = trim($subCategorySelected) == '' ? 'All' : $subCategorySelected;
if((trim($cityNameSelected) == '') || (trim($cityNameSelected) == 'All'))
{
$cityNameSelected = 'All';
}
if($selectedCity == '' || $selectedCity == 'All' || $selectedCity == 0) 
$selectedCity = 'All';
?>
<input type="hidden" id="category_unified_id" value="-1"/>
<input type="hidden" id="categorypage_unified_thankslayer_identifier" value=""/>

<input id = "catcountry" value = "<?php echo $countrySelected1;?>" type = "hidden"/>
<input id = "catcityid" value = "<?php echo $selectedCity;?>" type = "hidden"/>

<input id = "catcity" value = "<?php echo $cityNameSelected;?>" type = "hidden"/>
<input id = "catcategory" value = "<?php echo $categoryUrlName;?>" type = "hidden"/>
<input id = "catsubcat" value = "<?php echo str_replace(" ","-",$subCategorySelected)?>" type = "hidden"/>
<input id = "catcourselevel" value = "<?php echo $selectedcourselevel?>" type = "hidden"/>
<input id = "catcourselevel1" value = "<?php echo $selectedcourselevel1?>" type = "hidden"/>
<input id = "catcoursetype" value = "All" type = "hidden"/>
<input id = "catstartoffset" value = "<?php echo $start; ?>" type = "hidden"/>
<input id = "catcountoffset" value = "<?php echo $count; ?>" type = "hidden"/>
<input id = "catpagename" value = "<?php echo $pagename?>" type = "hidden"/>
<input type = "hidden" value = "opencatpage" id = "methodName"/>

<script>
 LazyLoad.loadOnce([
        '/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
        '/public/js/<?php echo getJSWithVersion("ajax-api"); ?>'
    ],callbackfn);
</script>

<body id="wrapperMainForCompleteShiksha">
<?php
    // open location overlay if cookie not set
    if(!$city_cookie_set){
     $this->load->view('categoryList/testprep_locationlayer');
     echo $city_cookie_set;
        ?>
    <script type="text/javascript">
        showTestprepLocationlayer();
    </script>
<?php } ?>




   <?php
   $openOverlay = 0;
if($openOverlay == 1)
$displayflag = '';
else
$displayflag = 'none';
if($openOverlay == 1)
{?>
<?php $this->load->view('categoryList/testprep_locationlayer',array('displayflag' => $displayflag));?>
    

<?php }
   $this->load->view('categoryList/testprep_searchpanel');
   $this->load->view('categoryList/testprep_overlaycategoryholder');
   $this->load->view('categoryList/testprep_locationlayer',array('displayflag' => 'none'));
   ?>





    <div class="defaultAdd lineSpace_10">&nbsp;</div>
        
    <?php
    $labelForFeaturedColleges = '<div class="fontSize_14p" style="padding-top:7px">Featured Colleges in</div>';
    $labelForAllColleges = '<div>All Institutes &amp; Universities</div>';
	$titleForAllColleges = 'All Institutes &amp; Universities';
        switch($categoryId) {
            case 2:
            case 3:
            case 5:
            case 8:
            case 9:
            case 11:
            case 6:
                $labelForFeaturedColleges = '<div class="fontSize_14p" style="padding-top:7px">Featured Colleges in</div>';
                $labelForAllColleges = '<div>All Colleges &amp; Universities</div>';
				$titleForAllColleges = 'All Colleges &amp; Universities';
                break;
            case 4:
            case 7:
            case 10:
            case 12:
                $labelForFeaturedColleges = '<div class="fontSize_14p" style="padding-top:7px">Featured Institutes in</div>';
                $labelForAllColleges = '<div>All Institutes &amp; Universities</div>';
				$titleForAllColleges = 'All Institutes &amp; Universities';
                break;
        }
        $labelForFeaturedColleges .=  '<div style="font-size:11px"><h2>'.$categoryData['name'].'</h2></div>';
    ?>
    <!--Start_MidPanel-->
    <div class="marfull_LeftRight10">
    	<div style="width:100%">
        	<div>
            	<!--Start_FeaturedInstitute-->
            	<div class="float_L" style="width:221px">
                	<div style="width:100%">
                    	<div class="shik_roundCornerHeaderSpirit shik_roundCornerHeader_1">
						<div style="padding-left:5px" class="bld">
                                                    <div class="fontSize_14p" style="padding-top:7px">Featured Institutes in</div>
                                                    <div style="font-size:11px"><h2><?php echo $exam_categories['parent_blog_title'] ?></h2>
                                                    </div>
                                                </div>
						</div>
                        <div style="border:1px solid #e7e7e7">
                        	<div>
                            <div class="defaultAdd lineSpace_10">&nbsp;</div>
	      <?php  
          
            global $testprepCriteriaArray;
        $testprepCriteriaArray = array(
                'category' => '',
                'country' => 2,
                'city' => $params['city_id'],
                'keyword'=>$params['blog_acronym']."_exams"
                );
          $bannerProperties = array('pageId'=>'TESTPREP_CATEGORY', 'pageZone'=>strtoupper($params['blog_acronym']."_EXAMS_PAGE_SMALLSCRAPPER"), 'shikshaCriteria' => $testprepCriteriaArray,'width'=>210);
          $this->load->view('common/banner.php', $bannerProperties); ?>
          
							</div>
                        </div>
                    </div>
                </div>
                <!--End_FeaturedInstitute-->
                <div class="" style="margin-left:231px">
					<div class="float_L" style="width:100%">
                    	<div style="width:100%">
                        	<!--Start_MainTab-->
                            <?php 
                            $allclass = '';
                            $mostclass = '';
                            if($pagename == "categorypages")
                            {
                            $allclass = "selectedMainTab";
                            $showselectall = 1;
                            $showbar = 1;
                            $showviews = 0;
                            $showcoursecount = 1;
                            }
                            if($pagename == "categorymostviewed")
                            {
                            $mostclass = "selectedMainTab";
                            $showselectall = 0;
                            $showbar = 1;
                            $showviews = 1;
                            $showcoursecount = 1;
                            }
                            if($pagename == "topinstitutes")
                            {
                                $topclass = "selectedMainTab";
                                $showselectall = 0;
                                $showbar = 0;
                                $showviews = 0;
                                $showcoursecount = 0;
                            }
            $marg = 'margin-left:50px';
            $mainmarg = 'padding:4px 0 0 15px';
    if(isset($_COOKIE['client']) && $_COOKIE['client'] < 1000)
    {
            $marg = 'margin-left:-3px';
            $mainmarg = 'padding:4px 0 0 5px';
    }
                            ?>


                        	<div class="shik_roundCornerHeaderSpirit shik_roundCornerHeader_2">
                            	<div style="<?php echo $mainmarg?>">
                                    <div id="shik_catMainTab">
                                        <?php if($params['course_type'] == 'All') $params['course_type'] = '';?>
                                        <a class="<?php if($params['pagetype']==''){ echo "selectedMainTab";}?>" href="<?php echo $this->url_manager->get_testprep_url('', $params['blog_acronym'], $params['city_name'], $params['course_type'], '')?>" class="<?php echo $allclass;?>" title="<?php echo $titleForAllColleges; ?>"><span><b>All Institutes</b></span></a>
                                        <a class="<?php if($params['pagetype']=='most-viewed'){ echo "selectedMainTab";}?>" href="<?php echo $this->url_manager->get_testprep_url('most-viewed', $params['blog_acronym'], $params['city_name'], $params['course_type'], '')?>" class = "<?php echo $mostclass;?>" title="Most Viewed"><span><b>Most Viewed</b></span></a>
                                    </div>
                                </div>
                            </div>
                            <!--End_MainTab-->
                            <div id = "mainContainer"> 
                            <!--Start_MainContainer-->
                            <div id = "hack_ie_operation_aborted_error">
                            </div>

                            <div style="border:1px solid #dcdcdc; border-top:none">                            	
                           <?php
                           $this->load->view('categoryList/testprep_maindiv',array('showselectall' => $showselectall,'showviews'=>$showviews,'showbar'=>$showbar));
                           ?> 
                            </div>
                            <!--Start_MainContainer-->
                            </div>
                            <div class="defaultAdd lineSpace_10">&nbsp;</div>



                                                <div style="width:100%">
                            	<div class="shik_skyBorder">
                                    <div class="shik_roundCornerHeaderSpirit shik_skyGradient"><span class="Fnt16" style="padding-left:10px"><b>Ask &amp; Answer</b></span></div>
                                    <div class="marfull_LeftRight10">
                	<!--Start_Ask_And_Answer-->
                    <div class = "lineSpace_10">&nbsp;</div>
                           <?php
			
$this->load->view('home/homePageRightPanelAnA',array('categoryId' => $category_id,'percentageOfPage' =>0.503,'title' => 'Ask &amp; Answer','showCategorySelection' =>false,'pageKeyInfo'=>'HOME_HOMEPAGE_RIGHTPANEL_','showHeaderOfWidget'=>false,'validateuser'=>$validate_user));
?>
                                    </div>
                                    </div>
                                    </div>
                    <div class = "lineSpace_10">&nbsp;</div>


<?php
$this->load->view('categoryList/articles', array('countryId'=>2));
 ?>



                    <div class = "lineSpace_10">&nbsp;</div>
                    <div class = "lineSpace_10">&nbsp;</div>
                            

							<div class="defaultAdd lineSpace_10">&nbsp;</div>
									</div>                                    
                                </div>
                            </div>
                            <!--End_Find_And_Browse_Articles_Box-->                     
                        </div>
                    <div class="clear_L">&nbsp;</div>

        </div>
                </div>
                <div class="clear_L">&nbsp;</div>
                            </div>
        </div>
    </div>
    <!--End_MidPanel-->
    <div style="line-height:50px">&nbsp;</div>
</div>


<?php $this->load->view('common/footerNew'); ?>

                                                <script type="text/javascript">
                    <?php if(isset($banner) && $banner['bannerurl'] != '' && $banner['bannerurl'] != NULL) { ?>
                       document.getElementById('imagespace').innerHTML  = '<div id = "floatad1" style = "position:absolute;z-index:1001;overflow:hidden;margin-left:10px"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="760" height="100" id = "flashcontent"><param name="movie" value="<?php echo $banner['bannerurl'];?>"/><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="wmode" value="transparent" /><embed src="<?php echo $banner['bannerurl'];?>" width="760" height="100" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" allowScriptAccess = "always" wmode = "transparent" name = "flashcontent1" id = "flashcontent1"></embed></object></div>';
                                       <?php } else { if($exam_categories['parent_blog_acronym']!=''){ ?>
                                           document.getElementById('imagespace').innerHTML = '<div style = "position:absolute;bottom:1px;"><img src="/public/images/<?php echo $exam_categories['parent_blog_acronym'];?>-exams.jpg" border="0" hspace="5"/></div>';
                                           <?php }} ?>
<?php if(!$city_cookie_set) {?>

var body=document.getElementsByTagName("body")[0];
    document.getElementById('dim_bg').style.display = 'inline';

    document.getElementById('dim_bg').style.width = screen.width +  'px';
document.getElementById('dim_bg').style.height = body.offsetHeight +  'px';
        overlayHackLayerForIE('testprep_locationlayer', body);

<?php }?>
</script>



