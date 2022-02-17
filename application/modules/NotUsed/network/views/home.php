<?php
$groupurl = "/index.php/network/Network/index/college/2";
$schoolgroupurl = "/index.php/network/Network/index/school/2";
		$headerComponents = array(
								'css'	=>	array('header','raised_all','mainStyle','footer'),
								'js'	=>	array('commonnetwork','common','user'),
								'jsFooter'	=>	array('prototype'),
								'tabName'	=>	'College Network',
								'taburl' =>  site_url('network/Network/showAllGroups'),
								'product' => 'Network',
								'title'	=>	'Shiksha.com - Groups of College, University, Institute, Community, Forum, Discussion - Education & Career Options -',
								'metaDescription' => '',
								'metaKeywords'	=>'',
								'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
'bannerProperties' => array('pageId'=>'GROUP', 'pageZone'=>'HEADER'),
								'callShiksha'=>1
							);
$this->load->helper('form');
$this->load->view('common/homepage', $headerComponents);
?>
<style>
.raised_graynoBG {background: transparent; } 
.raised_graynoBG .b1, .raised_graynoBG .b2, .raised_graynoBG .b3, .raised_graynoBG .b4, .raised_graynoBG .b1b, .raised_graynoBG .b2b, .raised_graynoBG .b3b, .raised_graynoBG .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_graynoBG .b1, .raised_graynoBG .b2, .raised_graynoBG .b3, .raised_graynoBG .b1b, .raised_graynoBG .b2b, .raised_graynoBG .b3b {height:1px;} 
.raised_graynoBG .b2 {background:#DBE5E7; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b3 {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b4 {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b4b {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b3b {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b2b {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_graynoBG .b1b {margin:0 5px; background:#DBE5E7;} 
.raised_graynoBG .b1 {margin:0 5px; background:#FFFFFF;} 
.raised_graynoBG .b2, .raised_graynoBG .b2b {margin:0 3px; border-width:0 2px;} 
.raised_graynoBG .b3, .raised_graynoBG .b3b {margin:0 2px;} 
.raised_graynoBG .b4, .raised_graynoBG .b4b {height:2px; margin:0 1px;} 
.raised_graynoBG .boxcontent_graynoBG {display:block; background-color:#FFFFFF; background-position:bottom; background-repeat:repeat-x; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
</style>
<div class="mar_full_10p normaltxt_11p_blk_arial">
		<div style="width:248px; float:right">
			  		<!-- Search Bar -->

                    <div class="normaltxt_11p_blk arial txt_align_r" >
                    <input id="searchinGroups" class="searchTP" style = "width:140px;position:relative;top:1px" type="text" onkeyup="if(event.keyCode == 13) searchInSubProperty(document.getElementById('searchinGroups').value,'groups','');" onblur="enterEnabled=false;if(this.value =='') this.value='Search In Groups';" onfocus="enterEnabled=true;if(this.value =='Search In Groups') this.value = '';" value="Search In Groups" title = "Search In Groups"/> &nbsp; 
					<button class="smallSearchBtn" value="" type="submit" onclick="if(trim(document.getElementById('searchinGroups').value) != 'Search In Groups'){ searchInSubProperty(document.getElementById('searchinGroups').value,'groups',''); }"  style="position:relative;top:1px">Search
					</button>
                    <!--<button class="btn-submit5" style="width:60px;"onclick="if(trim(document.getElementById('searchinGroups').value) != 'Search In Groups'){ searchInSubProperty(document.getElementById('searchinGroups').value,'groups',''); }" type="button">
                    <div class="btn-submit5">
                    <p class="btn-submit6">Search</p>
                    </div>
                    </button>-->
                    </div>
			  		<div class="lineSpace_7">&nbsp;</div>
                    <!-- Search Bar ends -->
                    <?php 
			
                         $base64url = base64_encode(SHIKSHA_GROUPS_HOME_URL.'/network/Network/showAllGroups');
			if(!(is_array($validateuser) && $validateuser != "false")) {
                        $onClick = "showuserLoginOverLay(this,'GROUPS_GROUPSHOME_RIGHTPANEL_INVITEFRIEND','jsfunction','showInviteFriends');";
                    }else{
                        if($validateuser[0]['quicksignuser'] == 1)
                        {
                            $onClick = "window.location = '/user/Userregistration/index/$base64url/1'";
                        }
                        else
                        {
                            $onClick = "showInviteFriends();";
                        }} ?>
        <!-- Invite Friends -->
        <div class="raised_greenGradient">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_greenGradient">
			  <div class="mar_full_5p">
			  		<div class="normaltxt_11p_blk arial"><span class="fontSize_13p bld float_L">Invite Friends From</span><br clear="left" /></div>
					<div class="lineSpace_12">&nbsp;</div>
					<div class="normaltxt_11p_blk_arial">
                      <div>
                        <div>
                            <a href="#" onClick = "<?php echo $onClick?>">
								<img hspace="5" border="0" src="/public/images/invite.jpg"/>
                            </a>
                        </div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="fontSize_12p bld">&amp; Earn Shiksha Point</div>
                      </div>
					  <div class = "clear_L"></div>
                    </div>
			  </div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
        <!-- Invite Friends Ends -->
       <!-- Recent Members -->
        <?php $arr = array(
				'pageNm'=> 'GROUPSHOME',
			    );	

         $this->load->view('network/recentMembers',$arr);?>
        <!-- Recent Members ENds -->

		<!-- Benefits of Joining -->
        <div class="raised_graynoBG">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_graynoBG">
			  <div class="mar_full_5p">
			  		<div class="lineSpace_5">&nbsp;</div>
						<div class="normaltxt_11p_blk arial"><span class="fontSize_13p bld float_L">Benefits of Joining</span><br clear="left" /></div>
						<div class="lineSpace_5">&nbsp;</div>
						<div class="normaltxt_11p_blk_arial">
							<ul style="margin-top:0;margin-bottom:0">
								<li style="list-style-image:url(/public/images/eventArrow.gif); margin-left:-23px" class = "fontSize_12p">Find your connections <div class="lineSpace_10">&nbsp;</div></li>
								<li style="list-style-image:url(/public/images/eventArrow.gif); margin-left:-23px" class = "fontSize_12p">Meet old friends <div class="lineSpace_10">&nbsp;</div></li>
								<li style="list-style-image:url(/public/images/eventArrow.gif); margin-left:-23px" class = "fontSize_12p">Share your comments <div class="lineSpace_10">&nbsp;</div></li>
							</ul>
						</div>
			  </div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
        <!-- Benefits of joining Ends -->
		</div>
        <!-- Right Panel Ends -->
		<div style="margin-right:258px">
			<div>
				<div class="OrgangeFont fontSize_14p bld mar_bottom_10p" style="line-height:25px;position:relative; top:-4px">Institute &amp; School Groups</div>

				<!--Who_Can_Join-->
				<div>
					<div class="raised_sky">
					<b class="b1"></b><b class="b2"></b><b class="b3" style="background:#FFFFFF"></b><b class="b4" style="background:#FFFFFF"></b>
					<div class="boxcontent_skyCollegeGrp" style="height:70px">
						<div class="mar_full_10p">
							<div style="float:left; width:60%;border-right:1px dotted #77C8DB;height:60px">
								<div class="fontSize_12p bld">Who can join?</div>
								<div class="lineSpace_10">&nbsp;</div>
								<div class="fontSize_12p">
									<span style="margin-right:23px"><img src="/public/images/shikshaCheck.gif" /> Student </span>
									<span style="margin-right:23px"><img src="/public/images/shikshaCheck.gif" /> Alumni</span>
									<span style="margin-right:23px"><img src="/public/images/shikshaCheck.gif" /> Prospective Student</span>
									<span style="margin-right:23px"><img src="/public/images/shikshaCheck.gif" /> Faculty</span>
								</div>
							</div>
							<div style="float:left;width:33%">
									<div class="mar_left_10p">
										<div class="fontSize_12p bld">Statistics</div>
										<div class="lineSpace_5">&nbsp;</div>
										<div class="arrowBullets fontSize_12p" id = "groupcount"> Institute Groups</div>
										<div class="lineSpace_5">&nbsp;</div>
										<div class="arrowBullets fontSize_12p" id = "membercount"> Institute Group Members</div>
									</div>
							</div>
							<div class="clear_L"></div>
						</div>
					</div>
					<b class="b1b" style="margin:0;"></b>
					</div>
				</div>
				<!--End_Who_Can_Join-->

				<!--My_Group-->
							<?php if(is_array($networkList)){?>
					<div style="line-height:15px">&nbsp;</div>
					<div class="OrgangeFont fontSize_14p mar_bottom_10p bld" >My Groups</div>
                    <div id = "MyGroups">
								<?php for($i = 0;$i < count($networkList);$i++) {
									if($networkList[$i]['logo'] == '' || $networkList[$i]['logo'] == 'NULL')
							$imageurl = "/public/images/noPhoto.gif";
							else
							$imageurl = $networkList[$i]['logo'];

							if($networkList[$i]['membercount'] == 1)
							$mycollegemember = "1 member";
							else
							$mycollegemember = $networkList[$i]['membercount'] . " members";

							if($networkList[$i]['messagecount'] == 1)
							$mycollegemsg = "1 discussion topic";
							else
							$mycollegemsg = $networkList[$i]['messagecount'] . " discussion topics";

								?>
								<?php
                                $MycollegeName = $networkList[$i]['name'];
                                if(isset($_COOKIE['client']) && $_COOKIE['client'] == 800)
                                {
                                if(strlen($networkList[$i]['name'])>18)
                                $MycollegeName = substr($networkList[$i]['name'],0,18).'..';
                                }
				if($i%2 == 0) { 
				?>
						<div style="width:99%">
							<div class="float_L" style="width:49%">
								<div style="width:117px; height:78px" class="float_L"><img src="<?php echo $imageurl?>"  onClick = "window.location = '<?php echo $networkList[$i]['url']?>'" alt="<?php echo $networkList[$i]['name'];?>" title="<?php echo $networkList[$i]['name']?>" /></div>
								<div style="margin-left:122px; margin-right:10px">
									<div style="margin-bottom:5px;"><a class="fontSize_12p" href="<?php echo $networkList[$i]['url']?>"  title="<?php echo $networkList[$i]['name']?>"><?php echo $MycollegeName?></a></div>
									<div><img src="/public/images/shikshaMember.gif" align="absmiddle" /><?php echo $mycollegemember?><a href="<?php echo $networkList[$i]['url']?>">&nbsp;<?php echo $mycollegemsg?></a></div>
								</div>
							</div>
								<?php } else {?>
							<div class="float_L" style="width:49%">
								<div style="width:117px; height:78px" class="float_L"><img src="<?php echo $imageurl?>"  onClick = "window.location = '<?php echo $networkList[$i]['url']?>'"  alt="<?php echo $networkList[$i]['name']?>" title="<?php echo $networkList[$i]['name']?>" /></div>
								<div style="margin-left:122px">
									<div style="margin-bottom:5px;"><a href="<?php echo $networkList[$i]['url']?>" class="fontSize_12p"  title="<?php echo $networkList[$i]['name']?>"><?php echo $MycollegeName?></a></div>
									<div><img src="/public/images/shikshaMember.gif" align="absmiddle" /><?php echo $mycollegemember?><a href="<?php echo $networkList[$i]['url']?>">&nbsp;<?php echo $mycollegemsg?></a></div>
								</div>
							</div>
					<?php } ?>
								<?php if($i%2 == 1 || ($i + 1)== count($networkList)) { ?>
							<div class="clear_L"></div>
							<div class="lineSpace_15">&nbsp;</div>
						</div>
								<?php } ?>
							<?php } ?>
                            </div>
                            <input type = "hidden" value = "0" id = "startMyOffset"/>
                            <input type = "hidden" value = "6" id = "countMyOffset"/>
                            <input type = "hidden" value = "MyGroupsHome" id = "methodName"/>
                    <?php if($myCount > 6) {?>
                   <div class="mar_full_5p">
								<div  align = "right">
                        <div class="pagingID" id="paginataionPlace1" style="position:relative; top:5px">
                        <script>
	                    setStartOffset(0,'startMyOffset','countMyOffset');
                        doPagination(<?php echo $myCount ?>,'startMyOffset','countMyOffset','paginataionPlace1');	
                        </script>
                        </div>
                        </div>
								<div class="clear_L"></div>
                        <div class="lineSpace_20">&nbsp;</div>
                    </div>           
                    <?php } ?>
				<!--End_My_Group-->
				<?php } ?>
                
				<!--BrowseCollegeGroupCategory-->
					<div style="background:url(/public/images/dottedLine.gif);"><img src="/public/images/dottedLine.gif"  /></div>
					<div class="lineSpace_15">&nbsp;</div>
					<div class="OrgangeFont fontSize_14p mar_bottom_10p bld" >Browse Institute Groups by category</div>
					<div style="width:99%">
						<?php
							global $Groupcategory;
							foreach($Groupcategory as $categoryId=>$category){
							if(isset($category['groupimage']))
							{
								$categoryimg = isset($category['groupimage']) ? $category['groupimage'] : '';
								$categoryName = isset($category['name']) ? $category['name'] : '';
								$categoryId = isset($category['id']) ? $category['id'] : '';
								for($j = 0 ;$j<count($categoryStats);$j++)
								{
								if($categoryId == $categoryStats[$j]['categoryId'])
								{
									$collegeCount = $categoryStats[$j]['collegecount'];
									$membercount = $categoryStats[$j]['membercount'];

								}
                                if($categoryStats[$j]['categoryId'] == 0)
                                {
                                  echo "<script>";
                                  echo "document.getElementById('groupcount').innerHTML = '".$categoryStats[$j]['collegecount']." Institute Groups';";
                                  echo "document.getElementById('membercount').innerHTML = '".$categoryStats[$j]['membercount']." Institute Group Members';";
                
                                  echo "</script>";
                                  
                                }

								}
							?>
							<div class="float_L <?php echo $categoryimg?>" style="width:49.5%; background-position:0 0; height:55px" onClick = "window.location = '<?php echo $groupurl."/".$categoryName?>'" title="<?php echo $categoryName?>">
								<div style="margin-left:97px; margin-right:10px">
									<div><a href="<?php echo $groupurl."/".$categoryName?>" class="fontSize_14p" title="<?php echo $categoryName?>"><?php echo $categoryName?></a></div>
									<div style="margin-top:5px">
										<span><img src="/public/images/shikshaCollegeGroup.gif" align="absmiddle" /> <a href="<?php echo $groupurl ."/" .$categoryName?>" title="<?php echo $categoryName.", ".$collegeCount." institute groups"; ?>"><?php echo $collegeCount?> institute groups</a></span>
										<span style="padding-left:15px"><img src="/public/images/shikshaMember.gif" align="absmiddle" /><?php echo $membercount ?> Members </span>
									</div>
								</div>
							</div>
									<?php }
									}?>
						<div class="clear_L"></div>
					</div>
					<div style="background:url(/public/images/dottedLine.gif);"><img src="/public/images/dottedLine.gif"  /></div>
				<!--End_BrowseCollegeGroupCategory-->

				<!--BrowseSchoolGroupCategory-->
				<div class="lineSpace_15">&nbsp;</div>
				<div class="OrgangeFont fontSize_14p mar_bottom_10p bld" >Browse School Groups by location</div>
				<div class="lineSpace_5">&nbsp;</div>
				<div style="width:99%">
					<div class="quesAnsBullets1 float_L" style="width:22%;margin-bottom:10px"><a href="<?php echo $schoolgroupurl ."/0" ?>" class="fontSize_12p">All</a></div>
					<div class="quesAnsBullets1 float_L" style="width:23%;margin-bottom:10px"><a href="<?php echo $schoolgroupurl ."/74" ?>" class="fontSize_12p">Delhi</a></div>
					<div class="quesAnsBullets1 float_L" style="width:23%;margin-bottom:10px"><a href="<?php echo $schoolgroupurl. "/1359" ?>" class="fontSize_12p">Calcutta</a></div>
					<div class="quesAnsBullets1 float_L" style="width:22%;margin-bottom:10px"><a href="<?php echo $schoolgroupurl ."/64"?>" class="fontSize_12p">Chennai</a></div>
					<div class="quesAnsBullets1 float_L" style="width:22%;margin-bottom:10px"><a href="<?php echo $schoolgroupurl."/278"?>" class="fontSize_12p">Bangalore</a></div>
					<div class="quesAnsBullets1 float_L" style="width:23%;margin-bottom:10px"><a href="<?php echo $schoolgroupurl."/702"?>" class="fontSize_12p">Hyderabad</a></div>
					<div class="quesAnsBullets1 float_L" style="width:23%;margin-bottom:10px"><a href="<?php echo $schoolgroupurl."/174"?>" class="fontSize_12p">Pune</a></div>
					<div class="quesAnsBullets1 float_L" style="width:22%;margin-bottom:10px"><a href="<?php echo $schoolgroupurl."/151"?>" class="fontSize_12p">Mumbai</a></div>
					<div class="quesAnsBullets1 float_L" style="width:22%"><a href="<?php echo $schoolgroupurl ."/758"?>" class="fontSize_12p">Goa</a></div>
					<div class="quesAnsBullets1 float_L" style="width:23%"><a href="<?php echo $schoolgroupurl ."/171"?>" class="fontSize_12p">Patna</a></div>
					<div class="quesAnsBullets1 float_L" style="width:23%"><a href="<?php echo $schoolgroupurl ."/45"?>" class="fontSize_12p">Aurangabad</a></div>
					<div class="quesAnsBullets1 float_L" style="width:22%"><a href="<?php echo $schoolgroupurl."/161"?>" class="fontSize_12p">Noida</a></div>
					<div class="clear_L"></div>
				</div>
				<!--End_BrowseSchoolGroupCategory-->


				<!--<div style="line-height:30px; width:100%">&nbsp;</div>-->
			</div>
		</div>
</div>
<!--Start_Footer-->
<?php
$footer = array('pageId'=>'', 'pageZone'=>'');
$this->load->view('common/footer',$footer);
?>
<!--End_Footer-->
<?php
 echo "<script language=\"javascript\"> ";
    if(isset($validateuser[0]['quicksignuser']) && $validateuser[0]['quicksignuser'] == 1)
    {
     echo "var URLFORREDIRECT = '".base64_encode(site_url('network/Network/showAllGroups'))."';";
     echo "var COMPLETE_INFO = 1;";
    }
    else
    echo "var COMPLETE_INFO = 0;";
if(!(is_array($validateuser) && $validateuser != "false"))
{
echo "var VALIDATE_USER = false;";
}
else
{
echo "var VALIDATE_USER = true;";
}
 echo "var BASE_URL = '".site_url()."';";
echo "</script>";
?>
<?php $this->load->view('network/mailOverlay');?>
<script>
function MyGroupsHome()
{
	xmlHttp = getXMLHTTPObject();
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{
			var response = "";	 	
			var totalCount = 0;
            document.getElementById('paginataionPlace1').innerHTML = '';
        if(trim(xmlHttp.responseText) != "")
			{
				var network = eval("eval("+xmlHttp.responseText+")"); 
				response = createMyNetworkList(network.results);
				var totalCount = network.totalCount;
				document.getElementById('MyGroups').innerHTML = response;
			} 
            doPagination(totalCount,'startMyOffset','countMyOffset');	
		}
	};

	var countOffset = document.getElementById('countMyOffset').value;
	var startFrom = document.getElementById('startMyOffset').value;
	var responseurl = BASE_URL + '/network/Network/showCollegeNetworkList/'+ startFrom +'/'+ countOffset +'/' +  '0/3';

	xmlHttp.open("POST",responseurl,true);
	xmlHttp.send(null);	
	updatePaginationMethodName('MyGroupsHome','methodName');
	return false;
}
function createMyNetworkList(networkList)
{
response = '';
								for(i = 0;i < networkList.length;i++) {
									if(networkList[i].logo == '' || networkList[i].logo == 'NULL')
							imageurl = "/public/images/noPhoto.gif";
							else
							imageurl = networkList[i].logo;

							if(networkList[i].membercount == 1)
							mycollegemember = "1 member";
							else
							mycollegemember = networkList[i].membercount + " members";

							if(networkList[i].messagecount == 1)
							mycollegemsg = "1 message";
							else
							mycollegemsg = networkList[i].messagecount + " messages";

                                mycollegeName = networkList[i].name;
	                            var userCookieValue = getCookie('client');
                                if(userCookieValue == 800)
                                {
                                if((networkList[i].name).length>25)
                                mycollegeName = (networkList[i].name).substring(0,25) + '..';
                                }
						if(i%2 == 0) { 
                        response = response + '<div><div class="float_L" style="width:49%"><div style="width:117px; height:78px" class="float_L"><img src="'+imageurl+'"  /></div><div style="margin-left:122px; margin-right:10px"><div style="margin-bottom:5px"><a href="'+networkList[i].url+'">'+ mycollegeName+'</a></div><div><img src="/public/images/shikshaMember.gif" align="absmiddle" />'+mycollegemember+'<a href="'+networkList[i].url+'">&nbsp;'+mycollegemsg+'</a></div></div></div>';
							 } else {
						response =response + '<div class="float_L" style="width:49%"><div style="width:117px; height:78px" class="float_L"><img src="'+imageurl+'"/></div><div style="margin-left:122px"><div style="margin-bottom:5px"><a href="'+networkList[i].url+'">'+mycollegeName+'</a></div><div><img src="/public/images/shikshaMember.gif" align="absmiddle" />'+mycollegemember+'<a href="'+networkList[i].url+'">&nbsp;'+mycollegemsg+'</a></div></div></div><div class="lineSpace_15">&nbsp;</div>';
					} 
							if(i%2 == 1 || (i + 1)== networkList.length) { 

response = response + '<div class="clear_L"></div><div class="lineSpace_15">&nbsp;</div></div>';
} 
							 }
return response;
}
</script>
