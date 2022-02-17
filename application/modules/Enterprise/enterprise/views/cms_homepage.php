<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php if($usergroup == "cms"){ ?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php } ?>

<?php
								$middlePanelView = '-1';
								$paramArray = array();
								$js = array('CalendarPopup','common','enterprise','blog');
								$jsFooter = array();
                                                                $dontShowStartingFormBorder = 0;
                                switch($prodId){
                                    case '1':
											$middlePanelView = 'enterprise/tabular_blog';
											$js = array('CalendarPopup','common','enterprise','blog');
											$jsFooter = array('footer');
                                            break;
                                    case '2':
											$middlePanelView = 'enterprise/tabularForums';
											$js = array('CalendarPopup');
											$jsFooter = array('footer');
                                            break;
                                    case '3':
											$middlePanelView = 'enterprise/tabularAdmission';
											$js = array('CalendarPopup');
											$jsFooter = array('footer');	
                                            break;
                                    case '4':
											$middlePanelView = 'enterprise/tabularScholarship';
											$js = array('CalendarPopup');
											$jsFooter = array('footer');
                                            break;
                                    case '5':
											$middlePanelView = 'enterprise/tabularEvent';
											$js = array('CalendarPopup','common','enterprise','ana_common','events');
											$jsFooter = array('footer');	
                                            break;
                                    case '6':
											$middlePanelView = 'enterprise/tabularCollegeCourse';
											$js = array('CalendarPopup','common','enterprise');
											$jsFooter = array('footer','listing');
                                            break;
                                    case '7':
											$middlePanelView = 'enterprise/tabularCollege';
											$js = array('CalendarPopup','common','enterprise');
											$jsFooter = array('footer','listing');
                                            break;
                                    case '8':
											$middlePanelView = 'enterprise/tabularNetworkCol';
											$js = array('CalendarPopup');
											$jsFooter = array('footer');
                                            break;
                                    case '9':
                                    //        $this->load->view('enterprise/responseViewer');
                                            break;
                                    case '10':
                                    //        $this->load->view('enterprise/');
                                            break;
                                    case '11':
                                    //        $this->load->view('enterprise/');
											break;
									case '12':
											$middlePanelView = 'enterprise/entHome';
											$js = array('CalendarPopup');
											$jsFooter = array('footer','common','enterprise');
                                    		break;
                                    case '15':
											$middlePanelView = 'enterprise/showMediaData';  
											$js = array('CalendarPopup','common','enterprise');
											$jsFooter = array('footer');
                                            break;
                                    case '29':
											$middlePanelView = 'enterprise/tabularConsultants';
											$js = array('CalendarPopup','common','enterprise');
											$jsFooter = array('footer');
                                            break;
                                    case '30':
											$middlePanelView = 'enterprise/tabularAlumFeed';
											$js = array('CalendarPopup','common','enterprise');
											$jsFooter = array('footer');
                                            break;
                                    case '35':
											$middlePanelView = 'enterprise/tabular_abuse';
											$js = array('CalendarPopup','common','enterprise');
											$jsFooter = array('footer','discussion','blog','events');  
											$paramArray = $abuseReport;
                                            break;
                                    case RESPONSE_VIEWER_TAB_ID :
											$middlePanelView = 'enterprise/tabularResponseViewsForClient';
											$js = array('CalendarPopup','common','enterprise');
											$jsFooter = array('footer','discussion','blog','events','home');
                                            break;
                                    case EXAM_RESPONSE_VIEWER_TAB_ID :
                                            $middlePanelView = 'enterprise/tabularExamResponseViewsForClient';
                                            // $js = array('CalendarPopup','common','enterprise');
                                            // $jsFooter = array('footer','discussion','blog','events','home');
                                            break;
					    
									case '45':
											$middlePanelView = 'enterprise/addSpotLightEvents';
											$js = array('CalendarPopup','common','enterprise');
											$jsFooter = array('footer','events');
                                            break;
								    case '47':
											$middlePanelView = 'enterprise/powerUser';
											$js = array('CalendarPopup','common','enterprise');
											$jsFooter = array('footer','listing');
											$paramArray = $powerUser;
                                            break;
																
								    case '50':
											$middlePanelView = 'enterprise/popCourses';
											$js = array('CalendarPopup','common','enterprise');
											$paramArray = $popCourse;
                                            break;
								    case '51':
											$middlePanelView = 'enterprise/categoryPageHeader';
											$js = array('CalendarPopup','common','enterprise');
											$paramArray = $catpageHeader;
											//print_r($catpageHeader);
                                            break;
									 
                                        
                                            case '52':

                                                                if($catpageHeader['widgetType'] == "")
                                                                $catpageHeader['widgetType'] = 1;

                                                                if($catpageHeader['widgetType'] == 1)
                                                                    $middlePanelView = 'enterprise/categoryPageArticleWidgetsQuickLinks';
                                                                elseif($catpageHeader['widgetType'] == 2)
                                                                    $middlePanelView = 'enterprise/categoryPageArticleWidgetsLatestNews';
								else{
								    $middlePanelView = 'enterprise/categoryPageArticleWidgetsMustRead';							
								}
                                                                $js = array('CalendarPopup','common','enterprise');
                                                                $paramArray = $catpageHeader;
                                                                $dontShowStartingFormBorder = 1;
                                                                //print_r($catpageHeader);
                                             break;

                                             case '53':

                                                if($catpageHeader['widgetType'] == "")
                                                    $catpageHeader['widgetType'] = 1;
                                                
                                                switch($catpageHeader['widgetType']) {
                                                    case 1:
                                                            $middlePanelView = 'enterprise/studyAbroadTopWidget';
                                                    break;

                                                    case 2:
                                                            $middlePanelView = 'enterprise/studyAbroadStepsWidget';
                                                    break;

                                                    case 3:
                                                            $middlePanelView = 'enterprise/studyAbroadKnowMore';
                                                    break;

                                                    case 4:
                                                            $middlePanelView = 'enterprise/studyAbroadLatestNews';
                                                    break;
                                                    
                                                    case 5: 
                                                            $middlePanelView = 'enterprise/studyabroadPageWidget';
                                                    break;
						    
						    case 8: 
                                                            $middlePanelView = 'enterprise/studyabroadMustRead';
                                                    break;

                                                    default:
                                                            $middlePanelView = 'enterprise/studyAbroadTopWidget';
                                                    break;
                                                }
                                                /*
                                                if($catpageHeader['widgetType'] == 1)
                                                     $middlePanelView = 'enterprise/studyAbroadTopWidget';
                                                else
                                                 $middlePanelView = 'enterprise/studyAbroadStepsWidget';
                                                 */
                                                $js = array('CalendarPopup','common','enterprise','imageUpload');
                                                $paramArray = $catpageHeader;
                                                $dontShowStartingFormBorder = 1;
                                                //print_r($catpageHeader);
						break;
					case 203:
					    	$middlePanelView = 'enterprise/listingMisResult';
					    	$js = array('CalendarPopup','common','enterprise');
					    	$jsFooter = array('footer');
                                            	break;
                                         
										 
									case '56':
											$middlePanelView = 'enterprise/fatFooter';
											$js = array('CalendarPopup','common','enterprise','json2');
											$paramArray = $catpageHeader;
											//print_r($catpageHeader);
                                            break;
                                    default:
											//$this->load->view('listing/tabular_blog');
                                }
// array('home','discussion','events','blog')
$js = array_merge(array('lazyload'),$js);
$headerComponents = array(
								'css'	=>	array('headerCms','raised_all','mainStyle','articles','modal-message'),
								'js'	=> $js,
								'jsFooter' => $jsFooter,
								'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'tabName'	=>	'',
								'taburl' => site_url('enterprise/Enterprise'),
								'metaKeywords'	=>''
							);
							$this->load->view('enterprise/headerCMS', $headerComponents);

                                                        // echo "dontShowStartingFormBorder: ".$dontShowStartingFormBorder;
?>
</head>
<body>
   <div id="dataLoaderPanel" style="position:absolute;display:none">
      <img src="/public/images/loader.gif"/>
   </div>
<!--Start_Center-->

<SCRIPT>
   var SITE_URL = '<?php echo base_url() ."/";?>';
   var userGroup = '<?php echo $usergroup; ?>';
   var prodId = '<?php echo $prodId; ?>';
</SCRIPT>

<?php if($usergroup == "cms"){ ?>
<script>
        var keyidPageArr = eval(<?php echo $keyid_page_name; ?>);
        var keyPagesCount = <?php echo $totalKeyPageCount; ?>;
//        document.console(keyidPageArr);
</script>
<?php } ?>

<?php if($usergroup == "enterprise"){ ?>
<script>
        var keyidPageArr = "";
        var keyPagesCount = 0;
//        document.console(keyidPageArr);
</script>
<?php } ?>

<!-- Start: For calendar display -->
<?php $this->load->view('common/calendardiv'); ?>
<SCRIPT LANGUAGE="JavaScript">
    var calMain = new CalendarPopup("calendardiv");

<?php if($usergroup == "cms"){ ?>
    var cal = new Array();
    for(i=0;i<keyPagesCount;i++)
    {
            cal[i] = new CalendarPopup("calendardiv");
    }
<?php } ?>
</SCRIPT>
<!-- End: For calendar display -->

<?php if($usergroup == "cms"){ ?>
<script>
    var tmp = new Object();
    tmp = keyidPageArr;
    function changeAllFromDates(val) {
            var elems = new Object();
            for(var i in keyidPageArr)
            {
                    if(typeof(tmp[i]) != 'undefined'){
                            elems[i] = "from_"+i;
                            document.getElementById(elems[i]).value = val;
                        }else{
                            continue;
                    }
            }
    }

    function changeAllToDates(val) {
            var elems = new Object();
            for(var i in keyidPageArr)
            {
                    if(typeof(tmp[i]) != 'undefined'){
                            elems[i] = "to_"+i;
                            document.getElementById(elems[i]).value = val;
                        }else{
                            continue;
                    }
            }
    }
</script>
<?php } ?>

<div class="mar_full_10p">
        <?php $this->load->view('enterprise/cmsTabs'); ?>
        <div style="float:left; width:100%">
        
        <?php if($prodId == RESPONSE_VIEWER_TAB_ID) { ?>
            <div class="featured-article-tab">
                <ul>
                    <li onclick="clickTab('activatedTab');" <?php if($tabStatus == 'live') echo 'class="active"'; ?>>
                        <a id="activatedTab" href="/enterprise/Enterprise/getListingsResponsesForClient/live">Active Listings</a>
                    </li>
                    <li onclick="clickTab('deletedTab');" <?php if($tabStatus == 'deleted') echo 'class="active"'; ?>>
                        <a id="deletedTab" href="/enterprise/Enterprise/getListingsResponsesForClient/deleted">Deleted Listings</a>
                    </li>
                </ul>
            </div>
        <?php } ?>

        <?php if($prodId == EXAM_RESPONSE_VIEWER_TAB_ID) { ?>
            <div class="featured-article-tab">
                <ul>
                    <li onclick="clickTab('activatedTab');" <?php if($tabStatus == 'live') echo 'class="active"'; ?>>
                        <a id="activatedTab" href="/enterprise/ResponseViewerEnterprise/getExamResponsesForClient/live">Active Subscriptions</a>
                    </li>
                    <li onclick="clickTab('deletedTab');" <?php if($tabStatus == 'expired') echo 'class="active"'; ?>>
                        <a id="deletedTab" href="/enterprise/ResponseViewerEnterprise/getExamResponsesForClient/expired">Inactive / Expired Subscriptions</a>
                    </li>
                </ul>
            </div>
        <?php } ?>


            <div <?php echo (($dontShowStartingFormBorder == 1) ? '' : 'class="raised_lgraynoBG"'); ?>>

                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
                <div id="replacePage" name="replacePage" class="boxcontent_lgraynoBG">
                    <div class="row">


  <?php if(!isset($prodId)){
          $prodId = 6;
     }
 ?>

                <!--Pagination Related hidden fields Starts-->
                                    <input type="hidden" id="startOffSet" value="0"/>
                                    <input type="hidden" id="countOffset" value="7"/>
                                    <input type="hidden" id="country" value="2"/>
                                    <input type="hidden" id="category" value="1"/>
                                    <input type="hidden" id="startOffSet1" value="0"/>

                <!--Pagination Related hidden fields Ends  -->

<?php if(($usergroup == "cms") && ($prodId != 7) && ($prodId != 29) && ($prodId != 35) && ($prodId!=45) && ($prodId!=47)&& ($prodId!=50)&& ($prodId!=51) && ($prodId!=52) && ($prodId!=53)&& ($prodId!=56) && ($prodId!=203)){ ?>
    <div style="width:74%" class="float_L">
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<div style="width:99%;">
<?php } ?>
                    <?php if($prodId != EXAM_RESPONSE_VIEWER_TAB_ID){?>
                            <div class="normaltxt_11p_blk_arial bld">
                    <?php } else { ?>
                        <div>
                    <?php } ?>
			       <!-- Webservice FETCHED DATA FORMATTED HERE -->                          
			    <?php
				 if($middlePanelView != '-1') { 
					$this->load->view($middlePanelView,$paramArray); 
				 } 
			    if($prodId != '30' && $prodId != EXAM_RESPONSE_VIEWER_TAB_ID){
			    ?>
                            <div class="bgsplit"></div>
                            <div name="prod_detail" id="prod_detail" class="normaltxt_11p_blk" style="border-bottom:1px solid #CCCCCC">
                            <div style='width:100%' align='center'><img src='/public/images/space.gif' width='115' height='25' />
                            </div> 
			    <?php
			    }
			    ?>
<?php //$this->load->view('enterprise/editCourCol'); ?>
                            </div>
			    <!-- AJAXIFIED DATA DETAIL HERE -->
                        </div>

<?php if(($usergroup == "cms") && ($prodId == 7)){ ?>
<?php
       $attribute = array('id'=>'updateCMSdates', 'name' => 'updateCMSdates','method' => 'post');
       echo form_open_multipart('enterprise/Enterprise/updateCmsItem',$attribute);
  ?>
                                    <input type="hidden" name="selectTabId" id="selectTabId" value="<?php echo $prodId; ?>"/>
                                    <input type="hidden" name="totalKeyPages" id="totalKeyPages" value="<?php echo $totalKeyPageCount; ?>"/>
                                    <input type="hidden" name="totalKeyPages1" id="totalKeyPages1" value=""/>
                                    <input type="hidden" id="searchLucene" value="searchLuceneCMS"/>
                        <div id="productInfo"></div>
<?php } ?>
<?php if(($usergroup == "cms") && ($prodId != 7) && ($prodId!=29) && ($prodId!=35) && ($prodId!=45) && ($prodId!=47) && ($prodId!=50)&& ($prodId!=51)&& ($prodId!=52)&& ($prodId!=53) && ($prodId!=56)&& ($prodId!=203)){ ?>
  <?php
       $attribute = array('id'=>'updateCMSdates', 'name' => 'updateCMSdates','method' => 'post');
       echo form_open_multipart('enterprise/Enterprise/updateCmsItem',$attribute);
  ?>
                                    <input type="hidden" name="selectTabId" id="selectTabId" value="<?php echo $prodId; ?>"/>
                                    <input type="hidden" name="totalKeyPages" id="totalKeyPages" value="<?php echo $totalKeyPageCount; ?>"/>
                                    <input type="hidden" name="totalKeyPages1" id="totalKeyPages1" value=""/>
                                    <input type="hidden" id="searchLucene" value="searchLuceneCMS"/>

                        <div id="productInfo"></div>
                        <div style="width:25%; background-color:#F6F6F6;" class="float_L">
                                <div class="normaltxt_11p_blk_arial bld">Select global date</div>
                               <div class="lineSpace_5">&nbsp;</div>
                            <div class="mar_full_10p normaltxt_11p_blk_arial bld">
                                From: <input type="text" name="from_date_main" id="from_date_main" value="YYYY-MM-DD" readonly onblur="changeAllFromDates(document.getElementById('from_date_main').value);" /> <img name="from_date_main_img" id="from_date_main_img" src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','yyyy-MM-dd'); return false;"  />
                            </div>
                               <div class="lineSpace_5">&nbsp;</div>
                            <div class="mar_full_10p normaltxt_11p_blk_arial bld">
                                To: <input type="text" name="to_date_main" id="to_date_main" value="YYYY-MM-DD" readonly style="margin-left:15px" onblur="changeAllToDates(document.getElementById('to_date_main').value);" /> <img name="to_date_main_img" id="to_date_main_img" src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');disableDatesTill(calMain,document.getElementById('from_date_main').value);calMain.select(document.getElementById('to_date_main'),'to_date_main_img','yyyy-MM-dd',document.getElementById('from_date_main').value); return false;"/>
                            </div>
                            <div class="lineSpace_10">&nbsp;</div>
                            <?php if ($prodId==3 || $prodId==4 || $prodId==5 || $prodId == 6 || $prodId == 7): ?>
                            <div class="mar_full_10p normaltxt_11p_blk_arial bld" id="sponsoredDiv" style="display:none">
			       <input id="chkSetSponsored" type="checkbox" onclick="saveSearchKeyword();">Set as Sponsored
			    </div>
                            <div class="lineSpace_10">&nbsp;</div>
                            <?php endif; ?>
                            <?php if ($prodId == 7): ?>
                            <div class="mar_full_10p normaltxt_11p_blk_arial bld" id="featuredDiv" style="display:none">
                                <input id="chkFeatured" type="checkbox" onclick="setAsFeatured(this);">Set as Featured
                            </div>
                            <div class="lineSpace_10">&nbsp;</div>
                            <?php endif; ?>

                            <div class="mar_full_5p normaltxt_11p_blk_arial grayDarkFont bld">
                               <input type="checkbox" id="selectAllKeyId" name="selectAllKeyId" onClick="selectAllKeyIds();" /> Select All
                            </div>
                            <div class="lineSpace_5">&nbsp;</div>
                            <div name="keypage_dates" id="keypage_dates" class="mar_full_5p"  >
                            </div>


                        </div>
                        <div class="clear_L"></div>
<!--                        <input type="submit" name="updateCms" id="updateCms" value="UpdateCMS" onClick="return validateFields(this.form);">
                            <input type="submit" name="updateCms" id="updateCms" value="UpdateCMS" /> -->

                            <input type="hidden" name="updateCms" value="UpdateCMS"/>
	<div style="display: inline; float:left; width:100%">
                <div class="float_L" style="width:35%">&nbsp;</div>
		<div class="buttr3">
			<button class="btn-submit7 w9" type="button" onClick="document.updateCMSdates.submit();">
                            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Set/Unset on Pages</p></div>
			</button>
		</div>
		<div class="clear_L"></div>
	</div>
    <?php echo '</form>'; ?>


<?php } ?>
<?php if($prodId == 7):?></form><?php endif;?>

                    </div>
                                <div class="lineSpace_10">&nbsp;</div>
                </div> <!-- End div id=replacePage -->
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
				</div>

<div class="loaderImg" id="loadingImage" style="position:absolute;display:none;z-index:9999;"><img src="/public/images/loader.gif" /> Loading</div>
<div id="dim_bg2"></div>
<div class="spacer10 clearFix"></div>
<!--End_Center-->
<script>
<?php if(($usergroup == "cms") && ($prodId != 7)&& ($prodId != 29)&& ($prodId!=35) && ($prodId!=45) && ($prodId!=47)){ ?>
    showKeypageDatePanel(keyidPageArr);
<?php } ?>
</script>
<script>
var blacklistWords = new Array(<?php echo $newA;?>);
</script>
<div style="line-height:50px">&nbsp;</div>
</div></div>
<?php $this->load->view('enterprise/footer'); ?>

