<?php
$headerComponents = array(
        'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'	=>	array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','header','cityList','imageUpload'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'	=>	'',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'	=>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
   <div id="dataLoaderPanel" style="position:absolute;display:none">
      <img src="/public/images/loader.gif"/>
   </div>
<!--Start_Center-->
<div class="mar_full_10p">
       <?php $this->load->view('enterprise/cmsTabs'); ?>
       <?php $this->load->view('enterprise/subtabs'); ?>

                            <div class="lineSpace_20">&nbsp;</div>
       <form id = "searchshoskeles" onsubmit = "validateclient('categorysponsorclientid','listing');return false;">
       <div style="float:left; width:100%">
       <div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
        Enter Client Id: 
	    <input type = "text" id = "categorysponsorclientid" value = "<?php echo $clientId;?>"/>
        <input type = "hidden" id = "sortorder" name = "sortorder" value = "<?php echo $sortorder;?>"/>
	    <span id = "categorysponsorclientid_error" style = "color:red">
                <?php
                if(!is_array($arrofinstitutes))
                {
                    echo $arrofinstitutes;
                }
                ?>
        
        
        </span>
        <button class="btn-submit7 w6" name="filterMedia" id="filterMedia" value="Filter_MediaData_CMS" type="button" onClick = "return validateclient('categorysponsorclientid','listing');" style="margin-left:10px;width:150px">
              <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search Sticky Listing</p></div>
	   </button>
       </div>
       </div>
       </form>
                          <?php if(is_array($arrofinstitutes))
                          {?>

                            <div class="lineSpace_10">&nbsp;</div>
                                                        <div class="row normaltxt_11p_blk">	
                                    <input type="hidden" id="methodName" value="getPopularNetworkCMSajax"/>             
<div id="paginataionPlace1" style="display:none;"></div>
								<div class="boxcontent_lgraynoBG bld">
									<div class="float_L" style="background-color:#D1D1D3; width:10%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;<a href = "#" onClick = "changetheorder()";>Listing Id</a>
                                    <img border="0" align="absmiddle" width="16" height="16" alt="<?php echo $sortorder;?>" src="<?php echo $imgname;?>" id = "ordername">
                                    </div>
									<div class="float_L" style="background-color:#EFEFEF; width:30%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Category (Course Level)</div>
									<div class="float_L" style="background-color:#EFEFEF; width:15%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Subscription Id</div>
									<div class="float_L" style="background-color:#EFEFEF; width:20%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Location</div>
									<div class="float_L" style="background-color:#EFEFEF; width:23%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Remove Listing</div>
									<div class="clear_L"></div>
								</div>
							</div>
                            <?php } ?>
							<div id="cmsNetworkTable" name="cmsNetworkTable" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:290px; overflow:auto">
                          <?php
                          if(is_array($arrofinstitutes))
                          {
                          for($i = 0;$i<count($arrofinstitutes);$i++) 
{
    $instituteid = $arrofinstitutes[$i]['institute_id'];
    $category = $arrofinstitutes[$i]['name'];
    $subscriptionId = $arrofinstitutes[$i]['subscriptionId'];
    $listingsubsId = $arrofinstitutes[$i]['listingsubsid'];
    $city_id= $arrofinstitutes[$i]['cityid'];
    $state_id= $arrofinstitutes[$i]['stateid'];
    $country_id= $arrofinstitutes[$i]['countryid'];

    $course_level = $arrofinstitutes[$i]['course_level'];
    ?>
            <div class="normaltxt_11p_blk" style="border-bottom:1px solid #999999;cursor:pointer" onClick="" >
                <div class="float_L" style="width:10%;">
                    <div class="mar_full_10p">
                    <div class="lineSpace_10">&nbsp;</div>
                    <div><?php echo $instituteid;?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:30%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div><?php echo $category.' ('.$course_level.')';?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:15%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div style="margin-left:10px;"><?php echo $subscriptionId;?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
		
		<div class="float_L" style="width:20%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div style="margin-left:10px;"><?php
			
			$location_found=0;
			for($j = 0;$j<count($cities)&&($location_found===0)&&($city_id!=0);$j++)
			{
			   if  ($cities[$j]['cityId'] === $city_id) {
			   echo  $cities[$j]['cityName'];
			  $location_found=1;
			  }
			}
			
			for($j = 0;$j<count($states)&&($location_found===0)&&($state_id!=0);$j++)
			{
			  if($states[$j]['stateId'] === $state_id) {
			   echo  $states[$j]['stateName'];
			   $location_found=1;
			  
			   }
			}
		     
			for($j = 0;$j<count($countries)&&($location_found===0)&&($country_id!=0) ;$j++)
			{
			  if($countries[$j]['countryId'] === $country_id) {
			   echo   $countries[$j]['name']  ;
			  $location_found=1;
			  }
			 
			}
			
			
			?></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="float_L" style="width:23%;">
                    <div class="mar_full_10p">
                        <div class="lineSpace_10">&nbsp;</div>
                        <div style="margin-left:15px;"><a href ="#" onClick = "removelisting('<?php echo $listingsubsId; ?>','tlistingsubscription');">Remove Listing</a></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
                <div class="clear_L"></div>
                <div class="lineSpace_5">&nbsp;</div>
            </div>
            <?php } ?>
                <div class="txt_align_r">
                    <div class="lineSpace_10">&nbsp;</div>
                        <a href = "#" onClick = "addstickylisting();">Add New Sticky Listing</a>
                </div>
            <?php } $this->load->view('enterprise/addstickylisting');
             ?>
                                                        </div>
						<!-- code for pagination start -->

                        <script>
function addstickylisting(bannerid)
{
    showOverlay(535,600,'Add Sticky Listing',document.getElementById('addstickylisting').innerHTML);
    document.getElementById('bannerIdasl').value = bannerid; 
    document.getElementById('dim_bg').style.height = "100%";
}


function changeshoshkele(headertext,bannerid)
{
    showOverlay(535,600,headertext,document.getElementById('uploadshoshkele').innerHTML);
    if(headertext == "Change Shoshkele")
    {
        document.getElementById('bannerId').value = bannerid; 
    }
}
function changetheorder()
{
if(document.getElementById('ordername').alt == 'asc')
{
document.getElementById('sortorder').value = 'desc';
document.getElementById('ordername').setAttribute('alt','desc');
document.getElementById('ordername').setAttribute('src','/public/images/arrow_down.png');
}
else
{
document.getElementById('sortorder').value = 'asc';
document.getElementById('ordername').setAttribute('alt','asc');
document.getElementById('ordername').setAttribute('src','/public/images/arrow_up.png');
}
validateclient('categorysponsorclientid','listing');
}
function validateclient(id,keyword)
{
    document.getElementById(id + '_error').innerText = '';
    var clientid = document.getElementById(id).value;
    if(clientid == '')
    {
        document.getElementById(id + '_error').innerText = 'Please enter a client id';
        return false;
    }

    var filter = /^(\d)+$/;
    if(!filter.test(clientid))
    {
        document.getElementById(id + '_error').innerText = 'Please enter a valid client id';
        return false;
    }
    window.location = '/enterprise/Enterprise/cmsuploadbanner/' + keyword + '/' + clientid + '/' + document.getElementById('sortorder').value;
    return true;
}

function removelisting(listingid,tablename)
{
    var xmlHttp = getXMLHTTPObject();
    xmlHttp.onreadystatechange=function() {
        if(xmlHttp.readyState==4) {
            var response = xmlHttp.responseText;
            validateclient('categorysponsorclientid','listing');
        }
    }
    if(!confirm("Are you sure you want to remove this Sticky Listing(All the coupled banners to this listing will also become decoupled)?")){
	  return;
	}
    var url = '/enterprise/Enterprise/cmsremoveshoshkele/'+ listingid + '/' + tablename;
    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader("Content-length", 0);
    xmlHttp.setRequestHeader("Connection", "close");
    xmlHttp.send(null);
	alert("Sticky Listing successfully removed!");
    return false;
}

</script>
</div>
<?php $this->load->view('enterprise/footer');?>
