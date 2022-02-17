<!--Start_of_Top_Contributes-->
<div class="cafe-star-pannel">
	<div class="spacer10 clearFix"></div>
    <div class="Fnt16 bld mlr10">Ask & Answer stars <img src="/public/images/cafeStar.gif" border=0 align="absmiddle"/></div>
        <div class="spacer10 clearFix"></div>
		<div class="boxcontent_skyWithBGW">	  
			<?php if($tc == "1")
			{ ?>
				<div class="pt5" style="background:#ececec;border-top:1px solid #EDEDED; border-bottom:2px solid #fff;padding:7px;*height:0.01%">
					<div class="Fnt12 bld float_L" style="">Top Contributors</div>
					<?php 
						$topContributtos = (isset($topContributtingAndExpertPanel[0]) && is_array($topContributtingAndExpertPanel[0]))?$topContributtingAndExpertPanel[0]['mostcontributing']:array();
						if(count($topContributtos)==0) {$showMessage = "No top contributors"; $showLink = false;}
						else if(count($topContributtos)>0 && count($topContributtos)<3) {$showMessage = "1 - ".count($topContributtos); $showLink = false;}
						else {$showMessage = "1 - 3"; $showLink=true;}
						$categoryId = (isset($catId))?$catId:1;
					?>
					<div class="float_R Fnt11" id="topContributorsPage"><span id='topCWaitingDiv' style='display:none'><img src='/public/images/working.gif' border='0' align='absmiddle'/></span> <?php echo $showMessage;?> <?php if($showLink && $categoryId==1){ ?><a href="javascript:void(0);" onClick="showExpertContributorsList(1,<?php echo $categoryId;?>);">Next &#187;</a><?php } ?></div>
					<div class="clear_L">&nbsp;</div>
				</div>
				<div>
					<div id="contributorList">
						<?php
							$data['showContributor'] = 1;
							$data['showParticipant'] = 0;
							$data['topContributtingAndExpertPanel'] = 	$topContributtingAndExpertPanel;							
							$this->load->view('messageBoard/topExpertContributorsList',$data); 
						?>
					</div>
					<input type="hidden" id="startTopContributorList" name="startTopContributorList" value="0"/>
					<input type="hidden" id="isWeekly" name="isWeekly" value="1"/>
				</div>
				<?php 
			} ?>
		</div>
        
        <div class="clearFix"></div>
	</div>
	

<!--End_of_Top_Contributes-->
