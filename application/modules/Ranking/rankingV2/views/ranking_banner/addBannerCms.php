<?php 	
	$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_cms_header');
?>

	<div id="ranking-cms-content">
		<h3 class="flLt">Banners</h3>
		<div class="flRt">
			<input type="button" class="gray-button" value="Manage Ranking Pages" onclick="window.location.href='/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/index/'"/>
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
		<div class="clearFix"></div>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="cms-ranking-table">
			<tr>
				<th width="700">Banner Details</th>
				<th>Expire Time</th>
				<th>Action</th>
			</tr>
			<?php
			$rowCount = 0;
			if(count($banner_data) > 0){
				foreach($banner_data as $bd) {
					$rowCount++;
					$trClassName = "";
					if($rowCount % 2 == 0){
						$trClassName = "alt-rows";
					}
				?>
				<tr class="<?php echo $trClassName;?>">
					<td>
						<?php echo $bd['ranking_page_text'];?>
						<br/><br/>
						<?php
						if(!empty($bd['file_path'])){
						?>
							<span class="fntgrey">Banner: </span><a target="_blank" href="<?php echo $bd['file_path'];?>">Click here to see banner</a><br/>
						<?php
						}
						?>
						<?php
						if(!empty($bd['landing_url'])){
						?>
							<span class="fntgrey">Landing URL: </span><a target="_blank" href="<?php echo $bd['landing_url'];?>"><?php echo $bd['landing_url'];?></a><br/>
						<?php
						}
						?>
						<span class="fntgrey">Ranking page id: <b><?php echo $bd['ranking_page_id'];?></b></span><span class="fntgrey">&nbsp;|&nbsp;</span><span class="fntgrey">Banner id: <?php echo $bd['id'];?></span><span class="fntgrey">&nbsp;|&nbsp;</span><span class="fntgrey">Client: <?php echo $bd['client_id'];?></span>
						<?php
						if(!empty($bd['city_id'])){
						?>
							<span class="fntgrey">&nbsp;|&nbsp;</span><span class="fntgrey">City: <?php echo $bd['city_name'];?></span>
						<?php
						}
						?>
						<?php
						if(!empty($bd['state_id'])){
						?>
							<span class="fntgrey">&nbsp;|&nbsp;</span><span class="fntgrey">State: <?php echo $bd['state_name'];?></span>
						<?php
						}
						?>
					</td>
					<td><span class="fntgrey"><?php echo $bd['subscription_expire_time'];?></span></td>
					<td>
						<a href="/<?=RANKING_PAGE_MODULE?>/RankingBanner/edit/<?php echo $bd['id'];?>">Edit</a><br/>
						<a href="javascript:void(0);" onclick="deleteBanner(<?php echo $bd['id'];?>);">Delete</a>
					</td>
					
				</tr>
				<?php
				}
			} else {
				?>
				<tr><td>No Banner Information Available.</td><td></td><td></td></tr>
				<?php
			}
			?>
		</table>
		<div class="spacer10 clearFix"></div>
		<style>
			#pageNumbers {margin: 0px 2px 0px 5px;}
		</style>
		<div id="pagingIDc" style="text-align:right;margin-right:8px;">
			<?php
			echo $pagination;
			?>
		</div>
		<div class="spacer10 clearFix"></div>
		<div class="add-new-inst">
			<h5>Add Banners</h5>
			<form id ='bannerCmsForm' method ='post' action = "" enctype="multipart/form-data">
				<ul>
					<li>
						<label>Client ID :</label>
						<div class="add-field-box">
							<input class="add-txt-field" id ="client_id" name = "client_id" type="text" />&nbsp; <input type="button" value="GO" class="gray-button" onclick = "getEnterpriseUserDetail();" />
							<span id="loader-cont" style="display:none;"></span>
							<div id="client_id_error" style="color:red"> </div>
						</div>
						
					</li>
					<ul id ="client_info" style="display:none;">
						<li>
							<label>Client display email:</label>
							<div class="add-field-box" name = "client_email" id ="client_email" style="padding-top:4px;"></div>
						</li>
						<li>
							<label>Client display name :</label>
							<div class="add-field-box" id ="client_name" name ="client_name" style="padding-top:4px;"></div>
						</li>
						<li>
							<label style="padding-top:0px;">Choose Ranking Page :</label>
							<div class="add-field-box">
								<select class="select-style" id = "ranking_page_id" name = "ranking_page_id" onchange = "getCityAndState()">
									<option value="select">Select</option>
									<?php
									$selected = " ";
									foreach($rankingPageArray as $key => $rankingPage){
										if(!empty($rankingPage)){
											$rankingPageId 		= $rankingPage['id'];
											$rankingPageName 	= $rankingPage['ranking_page_text'];
											$selected = "";
											if($rankingPageId == $ranking_page_id){
												$selected = "selected = selected";
											}
											?>
											<option <?php echo $selected;?> value="<?php echo $rankingPageId?>"><?php echo $rankingPageName;?></option>
											<?php	
										}
									} 
									?>
								</select>
								<span id="citystate-loader-cont" style="display:none;"></span>
							</div>
						</li>
					</ul>
					<ul id="city_state" style="display:none;">
						<li>
							<label>Choose City :</label>
							<div class="add-field-box">
								<select class="select-style" id="select1" name = "city_id" onchange = "showSubscription()">
									<option>Select</option>
								</select>
							</div>
						</li>
						<li>
							<label>Choose State :</label>
							<div class="add-field-box">
								<select class="select-style" id = "select2" name = "state_id" onchange = "showSubscription()">
									<option>Select</option>
								</select>
								<span id="subscription-loader" style="display:none;"></span>
								<div id ="city_state_error" style="color:red"></div>
							</div>
						</li>
					</ul>
					<ul id ="subscription" style="display:none;">
						<li>
							<label>Choose Subscription:</label>
							<div class="add-field-box">
								<select class="select-style" name ="subscription_id" id = "select3" onchange = "showSubscriptionDetails();"><option>Select</option></select>
								<div id ="subscription_error" style="color:red;display:none;"></div>
							</div>
						</li>
					</ul>
					<ul id ="banner_details" style = "display:none;">
						<li>	
							<div style="width:13.5%;float:left;">
								<span>Subscription Details :</span>
							</div>
							<div style="width:75%;float:left;color:#666;padding:7px;border: 1px solid #E8E8E8;background: #FBFBFB;">
									<div style="width:25%;float:left;font-weight:bold;">Subscription Id</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_sub_id"></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Base Product Category</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_bpc"></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Base Product SubCategory</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_bpsc"></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Total Quantity</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_tbpq"></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Remaining Quantity</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_tbrq"></div>
									
									<div style="width:25%;float:left;font-weight:bold;">Start Date</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_ssd"></div>
									
									<div style="width:25%;float:left;font-weight:bold;">End Date</div>
									<div style="float:left;width:1%;margin-right:5px;">:</div>
									<div style="width:70%;float:left;" id="sh_sed"></div>
							</div>
						</li>
					<!-- 	<li>
							<label>Upload Banner:</label>
							<div class="add-field-box" >
								<input  type="file" class="add-txt-field" name="myImage[]" id="myImage"  />
								<div id ="upload_error" style="color:red"></div>
							</div>
						</li> -->
												<li><div id ="upload_error" style="color:red"></div></li>

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
								<input class="add-txt-field" id ="landing_url" type="text" name="landing_url" />
								<div id ="landing_url_error" style="color:red"></div>
							</div>
						</li>-->
						<li>
							<div id ="landing_url_error" style="color:red"></div>
						</li>
						<li>
							<label>&nbsp;</label>
							
							<input type="hidden" name="guid" value="<?php echo $guid;?>"/>
							<input type="hidden" name="bannerformpost" value="1"/>
							<div class="add-field-box"><input type="button" value="Submit" class="orange-button" id="rankingBannerSubmit" onclick="checkBannerDetails();"></input></div>
						</li>
					</ul>
				</ul>
			</form>
		</div>
		<div class="spacer10 clearFix"></div>
	</div>
	<?php $this->load->view('common/footer');?>
	<?php if($flag ==1){ ?>
		<script>
			preFillData(dataArray);
		</script>
	<?php } ?>
