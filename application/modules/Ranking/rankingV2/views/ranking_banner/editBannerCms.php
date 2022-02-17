<?php 	
	$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_cms_header');
?>
<div id="ranking-cms-content">
	<h3 class="flLt">Edit banner</h3>
	<div class="flRt">
		<input type="button" class="gray-button" value="Manage Banners" onclick="window.location.href='/<?=RANKING_PAGE_MODULE?>/RankingBanner/index/'"/>
	</div>
	<?php
	$style = "display:none;";
	if(!empty($afterPost)){
		$errorMessages 		= array();
		$successMessages 	= array();
		if(array_key_exists('error', $afterPost)){
			$errorMessages = $afterPost['error'];
		}
		if(array_key_exists('success', $afterPost)){
			$successMessages = $afterPost['success'];
		}
		$style = "";
		$messageString = "";
		if(count($successMessages) > 0){
			$style = "background:#FBFBFB;border: 1px solid #E8E8E8;display:block;";
			$messageString = "<b>Success:</b><br/>";
			foreach($successMessages as $message){
				$messageString .= "- " . $message . "<br/>";
			}
		}
		if(count($errorMessages) > 0){
			$style = "background:pink;border: 1px solid red;display:block;";
			$messageString = "<b>Error:</b><br/>";
			foreach($errorMessages as $message){
				$messageString .= "- " . $message . "<br/>";
			}
		}
	}
	?>
	<div class="ranking-grey-cont" id="ranking-grey-cont" style="<?php echo $style;?>">
		<div class="floatL" id="ranking-grey-value-cont">
			<?php
			echo $messageString;
			?>
		</div>
	</div>
	<div class="add-new-inst">
		<div>
			<div> <?php echo $error; ?></div>
			<form id ='bannerCmsForm' method ='post' action = "" enctype="multipart/form-data">
				<ul>
					<li>
						<label>Client ID:</label>
						<div class="add-field-box">
							<input class="add-txt-field" id ="client_id" name = "client_id" type="text" readonly value = "<?php echo $client_id;?>"/>
						</div>
					</li>
					<ul id="client_info" style="margin-top:10px;">
						<li>
							<label>Client display email :</label>
							<div class="add-field-box">
								<input class="add-txt-field" name = "client_email" id ="client_email" readonly value ="<?php echo $userDetails[$client_id]['email'];?>"/>	
							</div>
						</li>
						<li>
							<label>Client display name :</label>
							<div class="add-field-box">
							<input id ="client_name" name ="client_name" readonly value = "<?php echo $userDetails[$client_id]['displayname']?>"/>
								
							</div>
						</li>
						<li>
							<label>Ranking Page :</label>
							<div class="add-field-box">
								<select class="select-style" id = "ranking_page_id" name = "ranking_page_id" onchange = "" readonly>
									<option><?php echo $ranking_page_name;?></option>
								</select>
							</div>
						</li>
					</ul>
					<ul id='city_state'>
						<li>
							<label>City :</label>
							<div class="add-field-box">
								<select class="select-style" id="select1" name = "city_id" onchange = "" readonly>
									<option><?php echo $city_name;?></option>
								</select>
							</div>
						</li>
						<li>
							<label>State :</label>
							<div class="add-field-box">
								<select class="select-style" id = "select2" name = "state_id" onchange = "" readonly>
									<option><?php echo $state_name;?></option>
								</select>
							</div>
						</li>
					</ul>
					<li id ="subscription">
						<label>Subscription:</label>
						<div class="add-field-box">
							<select class="select-style" name ="subscription_id" id = "select3" onchange = "" readonly><option><?php echo $subscriptionDetails['BaseProdSubCategory'];?></option></select>
						</div>
						<div id ="subscription_error" style="color:red"></div>
					</li>
					<ul id ="banner_details">
						<li style="margin-top:10px;">	
							<div style="width:13.5%;float:left;">
								<span>Subscription Details</span>:
							</div>
							<div style="width:75%;float:left;color:#666;padding:7px;border: 1px solid #E8E8E8;background: #FBFBFB;">
									<div style="width:25%;float:left;font-weight:bold;">Subscription Id</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_sub_id"><?php echo $subscriptionDetails['SubscriptionId'];?></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Base Product Category</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_bpc"><?php echo $subscriptionDetails['BaseProdCategory'];?></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Base Product SubCategory</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_bpsc"><?php echo $subscriptionDetails['BaseProdSubCategory'];?></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Total Quantity</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_tbpq"><?php echo $subscriptionDetails['TotalBaseProdQuantity'];?></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Remaining Quantity</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_tbrq"><?php echo $subscriptionDetails['BaseProdRemainingQuantity'];?></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Start Date</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_ssd"><?php echo $subscriptionDetails['SubscriptionStartDate'];?></div>
									
									<div style="width:25%;float:left;font-weight:bold;">End Date</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_sed"><?php echo $subscriptionDetails['SubscriptionEndDate'];?></div>
							</div>
						</li>
						<!-- <li>
							<label>Upload Banner:</label>
							<div class="add-field-box" >
								<input  type="file" class="add-txt-field" name="myImage[]" id="myImage"  />
								<div id ="upload_error" style="color:red"></div>
							</div>
						</li> -->
						<li>
							<label>Banner URL:</label>
							<div class="add-field-box">
								<input class="add-txt-field" id ="banner_url" type="text" name="banner_url" value="<?php echo $banner_url;?>"/>
								<div id ="banner_url_error" style="color:red"></div>
							</div>
						</li>
						<!--<li>
							<label>landing URL:</label>
							<div class="add-field-box">
								<input class="add-txt-field" id ="landing_url" type="text" name="landing_url" value="<?php echo $landingpage_url;?>"/>
								<div id ="landing_url_error" style="color:red"></div>
							</div>
						</li>-->
						<li>
							<div id ="landing_url_error" style="color:red"></div>
						</li>
						<li>
							<label>&nbsp;</label>
							<input type="hidden" name="banner_id" value="<?php echo $banner_id;?>"/>
							<input type="hidden" name="guid" value="<?php echo $guid;?>"/>
							<input type="hidden" name="bannereditformpost" value="1"/>
							<div class="add-field-box">
								<input type="button" value="Submit" class="orange-button" id="rankingBannerSubmit" onclick="checkBannerDetails('edit');"></input>
							</div>
						</li>
					</ul>
				</ul>
			</form>
		</div>
	</div>
	<div class="spacer10 clearFix"></div>
</div>
<?php $this->load->view('common/footer');?>
