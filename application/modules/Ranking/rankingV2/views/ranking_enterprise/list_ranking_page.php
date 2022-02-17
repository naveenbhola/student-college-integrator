<?php
	$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_cms_header');
?>
<div id="ranking-cms-content">
	<h3 class="flLt">Rankings</h3>
	<div class="flRt">
		<input type="button" class="gray-button" value="Manage Ranking Sources" onclick="window.location.href='/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/sourcesIndex/'"/>
		<input type="button" class="gray-button" value="Manage Banners" onclick="window.location.href='/<?=RANKING_PAGE_MODULE?>/RankingBanner/index/'"/>
		<input type="button" class="gray-button" value="Add New Page" onclick="window.location.href='/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/create/'"/>
	</div>
	
	<?php
	$message = "";
	if(!empty($message_params)){
		$op 	= $message_params['op'];
		$status = $message_params['status'];
		$id  	= $message_params['id'];
		$selectedPage = array();
		foreach($ranking_pages as $page){
			if($page['id'] == $id){
				$selectedPage = $page;
				break;
			}
		}
		switch($op){
			case 'status':
				$message = "<h5>Success:</h5><br/>";
				$message .= "- Status for <h5>". $selectedPage['ranking_page_text']. "</h5> ranking page successfully changed to <h5>". $selectedPage['status']. "</h5>.";
				break;
			case 'create':
				$message = "<h5>Success:</h5><br/>";
				$message .= "- <h5>". $selectedPage['ranking_page_text']. "</h5> ranking page created successfully.";
				break;
		}
	}
	if(!empty($message)){
		?>
		<div class="ranking-grey-cont" id="ranking-grey-cont" style="display:block;">
			<div class="floatL" id="ranking-grey-value-cont">
				<?php echo $message;?>	
			</div>
		</div>
		<?php
	}
	?>
	<div class="clearFix"></div>
	<table cellpadding="0" cellspacing="0" width="100%" border="0" class="cms-ranking-table2">
		<tr>
			<th >Id</th>
			<th width="700">Name</th>
			<th>Status <br/>
				<select name="rp_status" id="rp_status" style="width:100px;" onchange="listByStatus();">
					<option value="all" <?php if($selected_status == "all"){echo "selected='selected'";}?>>All</option>
					<option value="live" <?php if($selected_status == "live"){echo "selected='selected'";}?>>Live</option>
					<option value="draft" <?php if($selected_status == "draft"){echo "selected='selected'";}?>>Draft</option>
					<option value="disable" <?php if($selected_status == "disable"){echo "selected='selected'";}?>>Disable</option>
				</select>
			</th>
			<th width="200">Action</th>
		</tr>
		<?php
		if(count($ranking_pages) > 0){
			$count = 1;
			foreach($ranking_pages as $page){
				$pageId 			= $page['id'];
				$name 				= $page['ranking_page_text'];
				$status		 		= $page['status'];
                                $pageDataCount = 0;
				if(array_key_exists($pageId, $page_data_count)){
					$pageDataCount = $page_data_count[$pageId];
				}
                                if($status == "delete"){
					continue;
				}
                                $trClassName = "";
                                if($count % 2 == 0){
					$trClassName = "alt-rows";
				}
				
//                                $categoryId 		= $page['category_id'];
//				$subCategoryId 		= $page['subcategory_id'];
//				$specializationId 	= $page['specialization_id'];
//				
//				
				
//				$categoryName = $category_details[$categoryId]['name'];
//				$subCategoryName 	= "";
//				$specializationName = "";
//				
//				
//				if(array_key_exists("subcategory", $category_details[$categoryId])){
//					$subcategoryDetails = $category_details[$categoryId]['subcategory'];
//					if(array_key_exists($subCategoryId, $subcategoryDetails)){
//						$subCategoryName = $subcategoryDetails[$subCategoryId]['name'];
//					}
//					if(array_key_exists($subCategoryId, $specialization_details)){
//						$specializationDetails = $specialization_details[$subCategoryId];
//						foreach($specializationDetails as $specialization){
//							if($specialization['SpecializationId'] == $specializationId){
//								$specializationName = $specialization['SpecializationName'];
//							}
//						}
//					}
//				}
				$count++;
			?>
			<tr class="<?php echo $trClassName;?>">
				<td><?php echo $pageId;?></td>
				<td>
				<?php
				$statusString = "";
				if($status != "live"){
					$statusString = "?skipstatuscheck=true";
				}
				$url = $page['url'] . $statusString;
				?>
				<a target="_blank" href="<?php echo $url;?>"><?php echo $name;?></a><br/>
				<br/>
				<span class="fntdarkgrey">Total courses added: <b><?php echo $pageDataCount;?></b></span><br/>
				</td>
				<td><?php echo ucfirst($status);?></td>
				<td>
					<?php
					if($status != "live"){
					?>
						<a href="/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/action/<?php echo $pageId;?>/live">Make live</a><br/>
					<?php
					}
					?>
					<a href="/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/edit/<?php echo $pageId;?>">Edit</a><br/>
					<a href="/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/editSource/<?php echo $pageId;?>">Edit Source</a><br/>
					<?php
					if($status != "disable"){
						?>
						<a href="/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/action/<?php echo $pageId;?>/disable">Disable</a><br/>
						<?php
					}
					?>
					<a href="/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/action/<?php echo $pageId;?>/delete">Delete</a><br/>
					<?php
					if($pageDataCount > 0) {
					?>
						<a href="/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/manage_meta_details/<?php echo $pageId;?>/">Meta Details location Wise</a><br/>
					<?php
					}
					?>
					
					
				</td>
			</tr>
			<?php
			}
		}
		?>
	</table>
</div>
<div class="spacer10 clearFix"></div>
<style>
	#pageNumbers {margin: 0px 2px 0px 5px;}
</style>
<div id="pagingIDc" style="text-align:right;margin-right:8px;">
	<?php
	echo $pagination;
	?>
</div>
<div class="spacer20 clearFix"></div>
<?php
	$this->load->view('common/footerNew');
?>
