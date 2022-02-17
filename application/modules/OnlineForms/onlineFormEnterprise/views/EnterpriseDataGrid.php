<?php
	$headerComponents = array(
			'css'   =>  array('online-styles','mainStyle','headerCms'),
			'js'    =>  array('common','ajax-api','onlinetooltip','onlineForms','json2','onlineFormsEnterpriseDataGrid'),
			'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
			'tabName'   =>  '',
			'taburl' => site_url('enterprise/Enterprise'),
			'metaKeywords'  =>'',
			'title' => 'Enterprise User Dashboard'
	);
	$this->load->view('enterprise/headerCMS', $headerComponents);
	$this->load->view('enterprise/cmsTabs');
?>

<div id="appsFormWrapper">

<div id="app-dashboard-wrap">
	<h3><?=$institute->getName()?></h3>
	<div class="confirm-box" style="display:none">
	Correspondense Address is Deleted. <a href="#">Save data</a>
	</div>
	<div style="float:right">
		<button class="orange-button" onclick="window.location = '/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?courseId=<?=$course->getId()?>'">Back to Form Dashboard</button>
	</div>
	<div class="app-dashboard-content">
		<?php
			$this->load->view('onlineFormEnterprise/EnterpriseDataGridTabs');
			$this->load->view('onlineFormEnterprise/EnterpriseDataGridFilters');
		?>
<?php
	$formWidth = count($headings)*150;
	if($sorterService->getNoOfForms() > 0){
		if($tab->getName() != "Analytics"){
			
?>
		<div class="result-box">
			<p class="found-res">
				<?php if($tabId != 0){ ?>
				<input type="checkbox" onchange="checkAllForms(this);" onclick="checkAllForms(this);" />
				<?php } ?>
			Showing <?=(($page-1)*$limit+1)?>-<?=min((($page-1)*$limit+$limit),$sorterService->getNoOfForms())?> of <?=$sorterService->getNoOfForms()?> results:</p>
			<?php if($tabId == 0){
					$this->load->view('onlineFormEnterprise/AddColumn');	
			?>
				<span class="add-imp-data"><a href="#" onclick="addColumn(); return false;">+ Add new column</a> &nbsp;|&nbsp; <a href="#" onclick="onlineFormDataGrid=1; importExternalOnlineFormInfo('<?=$course->getId()?>'); return false;">Import Data</a></span>
			<?php } ?>
			<div class="clearFix spacer5"></div>
			<div class="result-scrolled-data" id="doublescroll">
			<div class="delete-col" onclick="deleteColumn(); return false;" style="display:none" onmouseout="hideDeleteColumn();" onmouseover="showDeleteColumn(0);">
				<strong>X</strong> <a href="#">Delete Column</a>
			</div>
			<table width="<?=$formWidth?>px" cellpadding="7" cellspacing="0" border="1" bordercolor="#d6d6d6">
					<tr onmouseout="hideDeleteColumn();">
					<?php
						$first = 1;
						foreach($headings as $heading){
							if($first && $tabId){
								$style = 'colspan="2"';
								$first = 0;
							}else{
								$style = "";
							}
					?>
					<th <?=$style?> width="150" id="<?=$heading['id']?>" style="cursor:pointer;" title="<?=html_escape($heading['name'])?>" onmouseover="showDeleteColumn(this)"; onclick="sortForms('<?=$heading['id']?>');">
						<div style="width:150px;word-wrap:break-word"><?=html_escape($heading['name'])?><span class="up-arr up-<?=$heading['id']?>" style="display:none">&nbsp;</span><span class="dwn-arr down-<?=$heading['id']?>" style="display:none">&nbsp;</span>
						</div>
					</th>
					<?
						}
					?>
					</tr>
				<?php
					$i = 0;
					foreach($forms as $key=>$form){
						$i++;
						if($i%2 == 0){
							$class = "alt-row";
						}else{
							$class = "";
						}
						
						if($form['type'] == "external"){
							$class = "active-row";
				?>
					
				<tr class="<?=$class?>" id="<?=$key?>" onmouseout="hidetip();" onmouseover="showTipOnline('This row is imported from <?=html_escape($form['sourceName'])?>',this,70,$j(this).height()-40);">
				<?php
						}else{
				?>
				<tr class="<?=$class?>" id="<?=$key?>">
					
				<?php
					}
				?>
					
				<?php
					$first = 1;	
					foreach($headings as $fieldId=>$field){
						if($first && $tabId){
				?>
				<td valign="top" style="border-right:none;padding-top:4px">
					<input type="checkbox" value="<?=$key?>" name="forms">
				</td>
				<?php
							$style = 'style="border-left:none;"';
							$first = 0;
						}else{
							$style = "";
						}
				?>
					<td <?=$style?> width="150" valign="top">
						<div style="width:150px;word-wrap:break-word; float:left">
					
					<span id="<?=$fieldId?>-<?=$key?>-value">
					<?=html_escape(trim($fm->getFieldValue($form,$fieldId,true)))?>
					</span>
					<?php
						if($field['type'] == "custom" && $tabId == 0){
					?>
					<input type="text" value=""  id="<?=$fieldId?>-<?=$key?>-input" style="display:none;width:140px" maxlength="1800"/>
					<a href="#" id="<?=$fieldId?>-<?=$key?>-save" onclick="saveField(this); return false;" style="display:none">Save</a>
					<a href="#" id="<?=$fieldId?>-<?=$key?>-edit" onclick="editField(this); return false;">Edit</a>
					<?
						}
					?>
						</div>
					</td>
					<?
						}
					?>
				</tr>
				<?php
					}
				if($limit > 5 && count($forms) > 5){
				?>
				<tr onmouseout="hideDeleteColumn();">
				<?php
					$first = 1;
					foreach($headings as $heading){
						if($first && $tabId){
							$style = 'colspan="2"';
							$first = 0;
						}else{
							$style = "";
						}
				?>
				<th <?=$style?> width="150" id="<?=$heading['id']?>" style="cursor:pointer;" title="<?=html_escape($heading['name'])?>" onmouseover="showDeleteColumn(this)"; onclick="sortForms('<?=$heading['id']?>');">
					<div style="width:150px;word-wrap:break-word"><?=html_escape($heading['name'])?><span class="up-arr up-<?=$heading['id']?>" style="display:none">&nbsp;</span><span class="dwn-arr down-<?=$heading['id']?>" style="display:none">&nbsp;</span>
					</div>
				</th>
				<?
					}
				?>
				</tr>
				<?php
				}
				?>
			</table>
			</div>
			<div class="spacer20 clearFix"></div>
<?php

	$this->load->view('onlineFormEnterprise/enterpriseDashPagination');

?>
			<div class="spacer20 clearFix"></div>
			<div align="center">
				<input type="button" value="Refresh Data" class="orange-button" onclick="window.location.reload()" />&nbsp;&nbsp;
				<input type="button" value="Download Data" class="orange-button"  onclick="exportForms();"  />&nbsp;&nbsp;
				<?php
				if($tabId){
				?>
				<input type="button" value="Send Mail" class="orange-button" onclick="sendMail();" />&nbsp;&nbsp;
				<input type="button" value="Delete Forms" class="orange-button" onclick="deleteForms(<?=$tabId?>);" />
				<?php
				}
				?>
			</div>
		</div>
<?php
		}else{
?>
			<div class="result-box">
			<div class="found-res" style="font-size:20px">
			<span style="color:#F9A046"><?=$sorterService->getNoOfForms()?></span> results found
			</div>
			<div class="spacer20 clearFix"></div>
<?php
			$this->load->view('onlineFormEnterprise/EnterpriseDataGridAnalytics');
?>
			<div class="spacer20 clearFix"></div>
			</div>
<?php
		}
	}else{
?>
<div  class="result-box">
	<p class="found-res">No Records Found</p>
	<?php if($tabId == 0){
		$this->load->view('onlineFormEnterprise/AddColumn');	
	?>
		<span class="add-imp-data"><a href="#" onclick="addColumn(); return false;">+ Add new column</a> &nbsp;|&nbsp; <a href="#" onclick="onlineFormDataGrid=1; importExternalOnlineFormInfo('<?=$course->getId()?>'); return false;">Import Data</a></span>
	<?php } ?>
</div>
<?php
	}
?>
	<div class="clearFix"></div>
	</div>
	
	
</div>
</div>

<?php
	$this->load->view('enterprise/footer');
	$this->load->view('onlineFormEnterprise/AddTab');
	$this->load->view('onlineFormEnterprise/Confirm');
	$this->load->view('onlineFormEnterprise/MailerOverlay');
	$this->load->view('onlineFormEnterprise/ExportOverlay');
?>
<script>
	var currentFilter = -1;
	var url = '<?=$url?>';
	var homeUrl = '<?=$homeUrl?>';
	var tab = <?=($tabId?$tabId:0)?>;
	var courseId = <?=$course->getId()?>;
	var filterKey = '<?=$filterKey?>';
	var filters = '';
	reloadFilters();
	<?php
	if($sorterService->getField()){
		if($sorterService->getOrder() == "ASC"){
	?>
			$j('.up-<?=$sorterService->getField()?>').show();
	<?php
		}else{
	?>
			$j('.down-<?=$sorterService->getField()?>').show();
	<?php
			
		}
	}
	?>
	if(typeof(analyticsData) != 'undefined'){
		makeAllGraphs();
	}
</script>
