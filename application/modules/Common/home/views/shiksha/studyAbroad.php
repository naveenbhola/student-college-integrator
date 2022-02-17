<?php
global $seotabs;
global $criteriaArray;
$keyname = strtoupper($categoryUrlName.($selectedCity != '' ? 'CITYID' : 'ALL').($subCategorySelected != '' ? str_replace(' ','-',$subCategorySelected) : 'All'));
if($countryNameSelected == "UK-ireland"){
	$countryNameSelected = "UK-Ireland";
}
$titleText = 'Study Abroad in '. $countryNameSelected .' - Universities in '.$countryNameSelected.' - Colleges in '.$countryNameSelected.' - Courses in '.$countryNameSelected.' - Institutes in '.$countryNameSelected;
$metaDescriptionText = 'Study Abroad in  '.$countryNameSelected.' , Universities in  '.$countryNameSelected.' , Colleges in '.$countryNameSelected.' , Courses in '.$countryNameSelected.' , Institutes in '.$countryNameSelected.' . Find thousands of college courses, degree courses & Higher education university in '.$countryNameSelected.' Details on studying abroad in '.$countryNameSelected.' with contact details, course eligibility, course duration and related information. ';
$metaKeywordText = 'Colleges in '.$countryNameSelected.' , Universities in '.$countryNameSelected.' , Institutes in  '.$countryNameSelected.' , Courses in '.$countryNameSelected.' , study in '.$countryNameSelected.' , study abroad, higher education in '.$countryNameSelected.' , school abroad, education abroad, colleges, universities, institutes, courses';
$h1tag = $seotabs[$keyname]['h1']; 
$criteriaKeyword = 'BMS_'.strtoupper(str_replace(' ','_',$countryNameSelected));

$criteriaArray = array(     
        'category' => $categoryId,     
        'country' => $countryId,      
        'city' => $selectedCity,     
        'keyword'=>$criteriaKeyword     
        );

        $headerComponents = array('js'=>array('common','lazyload','multipleapply','category','user','discussion','cityList','studyAbroad'),
        'jsFooter' =>array('ana_common'),
        'css'=>array('modal-message','raised_all','mainStyle'),
        'product'=>'foreign',
        'taburl' =>  site_url(),
        'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),'tabName'	=>	'Event Calendar',
        'title'	=>	$titleText,
        'metaKeywords'	=>$metaKeywordText,
        'metaDescription' => $metaDescriptionText
        );
?>

<?php 
$this->load->view('common/header', $headerComponents);

$pagename = array('pagename'=>'studyabroad');
$this->load->view('home/shiksha/countryOverlay',$pagename);
$this->load->view('common/userCommonOverlay');
$this->load->view('network/mailOverlay',$data);
$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';
$quickSignUser = is_array($validateuser)?$validateuser[0]['quicksignuser']:0;
$isCmsUser = 0;
if($userGroup === 'cms')$isCmsUser = 1;
echo "<script language=\"javascript\"> ";
echo "var BASE_URL = '';";
echo "var COMPLETE_INFO = ".$quickSignUser.";"; 
echo "var URLFORREDIRECT = '".base64_encode($_SERVER["REQUEST_URI"])."';";
echo "var loggedInUserId = '".$userId."';";         
echo "var isCmsUser = '".$isCmsUser."';";          
echo "currentPageName = 'STUDY ABROAD';"; 
echo "</script> ";
?>
<script>
var userVCardObject = new Array();
LazyLoad.loadOnce([
        '/public/js/<?php echo getJSWithVersion("tooltip"); ?>',
        '/public/js/<?php echo getJSWithVersion("ajax-api"); ?>'
        ],callbackfn);
</script>
<input type="hidden" id="category_unified_id" value="<?php echo $categoryId?>"/>
<input type="hidden" id="categorypage_unified_thankslayer_identifier" value=""/>
<input id = "startOffSet" value = "0" type = "hidden"/>
<input id = "countOffset" value = "10" type = "hidden"/>
<input type = "hidden" value = "getFeaturedCollegesForCountryPages" id = "methodName"/>
<input type="hidden" id="subCategoryId" value="<?php echo $categoryId?>"/>
<input type="hidden" id="country" value="<?php echo $countryId?>"/>
<input type="hidden" id="cities" value="0"/>
<input type="hidden" id="intinstituteId" value=""/>

					<?php 
                    $this->load->view('home/category/commonheader');
                    ?>

	<div>
        <!--Start_MidPanel-->
        <div>
                <div class="float_L w759">
                	<div class="wdh100">
                    	<div class="float_L w415">
                        		<!--Start_Form-->
    <?php
        if(!(is_array($validateuser) && isset($validateuser[0]))) { ?>
                               <div class="wdh100">
                                <div class="shik_skyBorder aeroIcon">
                                <div class="mf10">
                                <div class="fs18 bld fcOrg">Let us find a Consultant for you</div>                                       
                                <div>Fill this form to get personalized advice from our partner Consultants</div>
                                <div class="lineSpace_10">&nbsp;</div>
                                <div>
                                <?php
                                $this->load->view('marketing/genralMarketingPageForm',$data); 
                                ?>
                                </div>
                                </div>
                                </div>
                                </div>
                            	<div class="lh10">&nbsp;</div>
                                <?php } ?>
                                <!--End_Form-->
                                <!--Start_Entrance_Exams_Required-->
                                <?php
                                $totalres = $collegeList['countArray']['totalcount'];
                                $countArray = $collegeList['countArray'];
if($totalres > 0)
{
    $this->load->view('home/shiksha/instituteswidget');                                
}
?>
				<?php $this->load->view('home/category/entranceexamwidget');?>
                                <!--End_Entrance_Exams_Required-->
                            	<div class="lh10">&nbsp;</div>
                        </div>
                        <div class="float_R w325">
                        	<div class="float_L wdh100">
                                <!--Start_Ask_Candian_Education_Center-->
                                <!--End_Ask_Candian_Education_Center-->                                
                                <!--Start_FAQ-->
                                <?php $this->load->view('home/category/faqwidget');?>
                                <!--End_FAQ-->                            
                                <!--Start_AnA-->
                                
                                <?php
                               $commentData['pageKeySuffixForDetail'] = 'STUDYABROAD_HOMEPAGE_MIDDLEPANEL_'; 
                                $this->load->view('home/category/asknanswerwidget.php',$commentData);?>
                                <!--Start_Articles-->
                                <?php $this->load->view('home/category/articleswidget');?>
                                <!--End_Articles-->
                            	<div class="lh10">&nbsp;</div>
                                <!--Start_Entrance_Exams_Required-->
				<?php $this->load->view('events/studyAbroadEventsWidget');?>
                                <!--End_Entrance_Exams_Required-->
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
          $bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_RIGHT1', 'shikshaCriteria' => $criteriaArray);
 $this->load->view('common/banner.php', $bannerProperties); 
          $bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_RIGHT2', 'shikshaCriteria' => $criteriaArray);
 $this->load->view('common/banner.php', $bannerProperties); 
          $bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_SKYSCRAPPER', 'shikshaCriteria' => $criteriaArray);
 $this->load->view('common/banner.php', $bannerProperties); ?>
<!--                        <div><img src="/public/images/b1.png" /></div>
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
$criteriaArr = array(     
        'productname' => 'STUDYABROAD_HOMEPAGE_LEFTPANEL_IAMINTERESTED'); 
$this->load->view('home/interested',$criteriaArr); 
$this->load->view('common/footerNew');
?>
<?php
$selectedIcon = isset($countArray['selectedIcon'])?$countArray['selectedIcon']:'';
if(isset($selectedIcon) && $selectedIcon != '')
$imageurl = $selectedIcon;
else
$imageurl = '/public/images/'.$countrySelected .'.jpg';
$iconval = 0;
if(!(isset($selectedIcon) && $selectedIcon != ''))
{
    $iconval = 1;
}
?>	
<script>

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
if(loggedInUserId == 0)
{
        addOnBlurValidate(document.getElementById("frm1"));
}
</script>
