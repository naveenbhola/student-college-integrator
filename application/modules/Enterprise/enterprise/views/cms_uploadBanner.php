<?php
//echo "<pre>".print_r($categoryList,true)."</pre>";
$headerComponents = array(
        'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'	=>	array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','header','imageUpload','cityList'),
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
       <form id = "searchshoskeles" onsubmit = "validateclient('categorysponsorclientid','banner');return false;">
       <div style="float:left; width:100%">
        <input type = "hidden" value = "asc" id = "sortorder" name = "sortorder" value = "<?php echo $sortorder;?>"/>
       <div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
        Enter Client Id:
	    <input type = "text" id = "categorysponsorclientid" value = "<?php echo $clientId;?>"/>
				<span id="categorysponsorclientid_error" class="errorMsg">
                <?php
                if(!is_array($arrforbanners))
                {
                    echo $arrforbanners;
                }
                ?>
                </span>

        <button class="btn-submit7 w6" name="filterMedia" id="filterMedia" value="Filter_MediaData_CMS" type="button" onClick = "return validateclient('categorysponsorclientid','banner');" style="margin-left:10px;width:150px">
              <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search Shoshkeles</p></div>
	   </button>
       </div>
       </div>
       </form>

			  
			  <?php
                          if(is_array($arrforbanners) && count($arrforbanners) > 0) { ?>
                          <div class="lineSpace_10">&nbsp;</div>
                                                        <div class="row normaltxt_11p_blk">
                                    <input type="hidden" id="methodName" value="getPopularNetworkCMSajax"/>
<div id="paginataionPlace1" style="display:none;"></div>
								<div class="boxcontent_lgraynoBG bld">
									<div class="float_L" style="background-color:#D1D1D3; width:19%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4"><a href = "#" onClick = "changetheorder()"; style="padding-left:10px">Shoshkele Name</a>
                                    <img border="0" align="absmiddle" width="16" height="16" alt="<?php echo $sortorder;?>" src="<?php echo $imgname;?>" id = "ordername">
                                    </div>
									<div class="float_L" style="background-color:#EFEFEF; width:8%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;"><span style="padding-left:10px">Status</span></div>
									<div class="float_L" style="background-color:#EFEFEF; width:12%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;"><span style="padding-left:10px">Category</span></div>
									<div class="float_L" style="background-color:#EFEFEF; width:15%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;"><span style="padding-left:10px">Location</span></div>
									<div class="float_L" style="background-color:#EFEFEF; width:12%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;"><span style="padding-left:10px">Subscription Id</span></div>
									<div class="float_L" style="background-color:#EFEFEF; width:15%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;"><span style="padding-left:10px">Change Shoshkele</span></div>
									<div class="float_L" style="background-color:#EFEFEF; width:15%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4; word-wrap: break-word;"><span style="padding-left:10px">Use Shoshkele</span></div>
									<div class="clear_L"></div>
								</div>
							</div>
                            <?php  }
                            else
                            {
                            if($clientId > 0 && is_array($arrforbanners)) {
                            ?>
                          <div class="lineSpace_20">&nbsp;</div>
				    <div class="boxcontent_lgraynoBG bld">
				       <div style="background-color:#D1D1D3;padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4" align="center">No results found for the mentioned client id.
				       </div>
                                    </div>

                            <?php  }}
                            ?>
			<?php if($clientId > 0){ ?>

<div id="cmsNetworkTable" name="cmsNetworkTable" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:290px; overflow:auto;">
		     <div class="float_L" style="padding-left:80%;width:18%;">
                    <div class="lineSpace_10">&nbsp;</div><?php
		    if($arrforbanners != 'Please enter a valid client id') { ?>
                        <a href = "#" onClick = "changeshoshkele('Add Shoshkele','<?php echo $bannerid;?>','all');">Add Shoshkele</a>
			<?php } ?>
                </div>
		<div class="clear_L"></div>
   
		<?php } ?>
                          <?php
                          if(is_array($arrforbanners))
                          {
                            for($i = 0;$i<count($arrforbanners);$i++)
                            {
                                                ?>
                                                <span>
                                                <?php
                                                if(count($arrforbanners[$i]) > 1)
                                                        $showimg = 1;
                                                        else
                                                        $showimg = 0;
                                                echo showarray($arrforbanners[$i][0],1,'all',$i,$showimg,1);
                                                ?>
                                                </span>
                                                <div id = "<?php echo 'shoshid'.$i?>" style = "display:none">
                                                <?php
                                                for($j = 1;$j<count($arrforbanners[$i]);$j++)
                                                echo showarray($arrforbanners[$i][$j],0,'specific',0,0,0); ?>
                                                </div>
                           <?php }
                           }

                          function showarray($arrforbanners,$showselectnuse,$keyword,$i,$showimg,$showname)
                            {
        $bannerid = $arrforbanners['bannerid'];
        $banner = isset($arrforbanners['bannerlinkid']) ? $arrforbanners['bannerlinkid'] : $bannerid;

        $shoshkeleName = $arrforbanners['shoshkeleName'];
        $category = $arrforbanners['category'];
        $location = $arrforbanners['location'];
        $subscriptionId = $arrforbanners['subscriptionId'];
        $bannerurl = $arrforbanners['bannerurl'];
	$courselevel = $arrforbanners['course_level'];
            $sql = '<div style="border-bottom:1px solid #999999;cursor:pointer" onClick="" > <div class="float_L" style="width:19%;">'.'
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
            <div>';
            $status = '';
            if($showimg == 1)
            {
                $sql .= '<img src="/public/images/plusSign.gif" onClick = "showshosdivs('. $i.',\'show\');" id="imgsign'.$i.'"/>&nbsp;<a style = "color:#0065DE" onClick = "openwindow(\''.$bannerurl.'\');return false;">'.$shoshkeleName.'</a>';
            }
            else
            {
                if($showname)
                {
                    $sql .= '&nbsp;&nbsp;&nbsp;&nbsp;<a style = "color:#0065DE" onClick = "openwindow(\''.$bannerurl.'\');return false;">'.$shoshkeleName.'</a>';
                    $status = 'Unused';
                }
                else
                {
                    $sql .= '&nbsp;&nbsp;&nbsp;';
                    $status = 'Used On';
                }
            }
            $sql .= '</div>
            <div class="clear_L"></div>
            </div>
            </div>
            <div class="float_L" style="width:8%; word-wrap: break-word;">
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
            <div>'.$status.'</div>
            <div class="clear_L"></div>
            </div>
            </div>
            <div class="float_L" style="width:12%; word-wrap: break-word;">
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
            <div>'.$category.'<br/>'.($category!=""?'('.$courselevel.')':'').'</div>
            <div class="clear_L"></div>
            </div>
            </div>
            <div class="float_L" style="width:15%; word-wrap: break-word;">
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
            <div>'.$location.'</div>
            <div class="clear_L"></div>
            </div>
            </div>
            <div class="float_L" style="width:12%; word-wrap: break-word;">
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
            <div>'.$subscriptionId.'</div>
            <div class="clear_L"></div>
            </div>
            </div>';
            

            if($showselectnuse == 1) {
			$sql .= '<div class="float_L" style="width:15%; word-wrap: break-word;">
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
            <a href = "#" onClick = "changeshoshkele(\'Change Shoshkele\',\''.$banner.'\',\''.$keyword.'\',\''.$shoshkeleName.'\');">Change Shoshkele</a>
            <div class="clear_L"></div>
            </div>
            </div>';
            $sql .= '<div class="float_L" style="width:15%; word-wrap: break-word;">
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
            <a href = "#" onClick = "selectanduse(\''.$bannerid.'\',\''.$shoshkeleName.'\');">Select &amp; Use</a>
            </div>
            </div>';
            $sql .= '<div class="float_L" style="width:8%; word-wrap: break-word;">
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
            <a href = "#" onClick = "removeshoshkele(\''.$banner.'\',\'tbanners\');">Remove</a>
            </div>
            </div>';
            }
            else {
						$sql .= '<div class="float_L" style="width:15%; word-wrap: break-word;">
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
			&nbsp;
            <div class="clear_L"></div>
            </div>
            </div>';
            $sql .= '<div class="float_L" style="width:8%; word-wrap: break-word;">
            <div class="mar_full_10p">
            <div class="lineSpace_10">&nbsp;</div>
            <a href = "#" onClick = "removeshoshkele(\''.$banner.'\',\'tbannerlinks\');">Remove</a>
            </div>
            </div>';
            }

            $sql .= '<div class="clear_L"></div>
            <div class="lineSpace_5">&nbsp;</div>
            </div>';
            return $sql;
             }
			 ?>


</div>

<script>
   
function checkIfShosheleIsLiveOrNot(bannerid,bannername) {
	var xmlHttp = getXMLHTTPObject();
    var url = '/enterprise/Enterprise/checkIfShosheleIsLiveOrNot/'+ bannerid;
    xmlHttp.open("POST",url,true);
    xmlHttp.onreadystatechange=function() {
        if(xmlHttp.readyState==4 || xmlHttp.readyState=="complete") {
            response = xmlHttp.responseText;
			if(parseInt(response) == 1) {
        	showOverlay(535,600,'Select & use shoshkele',document.getElementById('selectnduseshoshkele').innerHTML);
            document.getElementById('shoshkelenametobeused').innerHTML = bannername;
            document.getElementById('bannerIdsnu').value = bannerid;
            document.getElementById('dim_bg').style.height = '100%';
			} else {
  			if(confirm("Shoshkele you want to use is deleted. Want to refresh page ?")){
					  window.refreshPage();
					}              
			}
        	
        }
    }
    xmlHttp.send(null);
	
}

function removeshoshkele(bannerid,bannertag)
{
    var xmlHttp = getXMLHTTPObject();
    xmlHttp.onreadystatechange=function() {
        if(xmlHttp.readyState==4) {
            var response = xmlHttp.responseText;
            validateclient('categorysponsorclientid','banner');
        }
    };
	if(!confirm("Are you sure you want to remove this Shoshkele Listing(All the coupled banners to this listing will also become decoupled)?")){
	  return;
	}
    var url = '/enterprise/Enterprise/cmsremoveshoshkele/'+ bannerid + '/' + bannertag;
    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader("Content-length", 0);
    xmlHttp.setRequestHeader("Connection", "close");
    xmlHttp.send(null);
	alert("Shoshkele Listing Removed!");
    return false;
}


function selectanduse(bannerid,bannername)
{
	checkIfShosheleIsLiveOrNot(bannerid,bannername);
	/*
  	showOverlay(535,600,'Select & use shoshkele',document.getElementById('selectnduseshoshkele').innerHTML);
    document.getElementById('shoshkelenametobeused').innerHTML = bannername;
    document.getElementById('bannerIdsnu').value = bannerid;
    document.getElementById('dim_bg').style.height = '100%';
	*/
}

function changeshoshkele(headertext,bannerid,keyword,shoshkelename)
{
    var divY = parseInt(screen.height)/2;
    //var top1 = parseInt(divY - parseInt(document.getElementById('uploadshoshkele').offsetHeight)/2)) - 70;
    var top1 = divY - 200;
    var h = document.body.scrollTop;
    var h1 = document.documentElement.scrollTop;
    h = h1 > h ? h1 : h;
    top1 += h;
    var left = screen.width/2 - 300;
    showOverlay(535,250,headertext,document.getElementById('uploadshoshkele').innerHTML,false,left,top1 - 2*h);
    if(headertext == "Change Shoshkele")
    {
        document.getElementById('bannerId').value = bannerid;
        document.getElementById('bannername').value = shoshkelename;
    }
    document.getElementById('shoshkeyword').value = keyword;
    //alert(1);
    document.getElementById('dim_bg').style.height = $j(window).height();
    //alert(document.getElementById('dim_bg'));
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
    validateclient('categorysponsorclientid','banner');
}

function showshosdivs(id,show)
{
    if(show == "show")
    {
        document.getElementById('imgsign' + id).setAttribute('src','/public/images/closedocument.gif');
        document.getElementById('shoshid' + id).style.display = '';
//        document.getElementById('imgsign' + id).setAttribute("onClick","showshosdivs('" + id + "','hide')");
        document.getElementById('imgsign' + id).onclick = function () { showshosdivs(id,'hide') ;return false;};
//        document.getElementById('imgsign' + id).onclick = function() { showshosdivs(id,'hide'); };
        //document.getElementById('imgsign' + id).onClick = function(){showshosdivs("' + id + '","hide")};
    }
    else
    {
        document.getElementById('imgsign' + id).setAttribute('src','/public/images/plusSign.gif');
        document.getElementById('shoshid' + id).style.display = 'none';
        //document.getElementById('imgsign' + id).setAttribute("onClick","showshosdivs('" + id + "','show')");
        document.getElementById('imgsign' + id).onclick = function () { showshosdivs(id,'show') ;return false;};
        //document.getElementById('imgsign' + id).setAttribute('onClick','showshosdivs("' + id + '","show")');
  //      document.getElementById('imgsign' + id).onClick = function(){showshosdivs(id,'show');};
    }
}

function validateclient(id,keyword)
{
    document.getElementById(id + '_error').innerHTML = '';
    var clientid = document.getElementById(id).value;
    if(clientid == '')
    {
	 document.getElementById(id + '_error').innerHTML = 'Please enter a client id';
	 return false;
    }

    var filter = /^(\d)+$/;
    if(!filter.test(clientid))
    {
        document.getElementById(id + '_error').innerHTML = 'Please enter a valid client id';	       
        return false;
    }
    window.location = '/enterprise/Enterprise/cmsuploadbanner/' + keyword + '/' + clientid + '/' + document.getElementById('sortorder').value;
    return true;
}
</script>
</div>
<?php
   $this->load->view('enterprise/footer');
   $this->load->view('enterprise/uploadshoshkele');
   $this->load->view('enterprise/selectnduseshoshkele');
?>