	<div>
	<form class="cms-form" id="form" action="/home/HomePageCMS/saveArticleForHomePage" enctype="multipart/form-data" method="post">
		<ul>
			<li>
				<label>Position*:</label>
				<div class="left-div">
					<select id="position" name="position" type="text" class="client-txt-fld" autocomplete="off">
					  <option value="">--Select--</option>
					  <option value="1">1</option>
					  <option value="2">2</option>
					  <option value="3">3</option>
					  <option value="4">4</option>
					  <option value="5">5</option>
					  <option value="6">6</option>
					  <option value="7">7</option>
					  <option value="8">8</option>
					  <option value="9">9</option>

					</select>
					<div id="position_error" class="errorMsg"></div>
				</div> 
			</li>
			<li>
				<label>Article ID*:</label>
				<div class="left-div">
					<input id="articleId" name="articleId" type="text" class="client-txt-fld" autocomplete="off" value="<?php echo $slotData['article_id']; ?>">
					<div id="articleId_error" class="errorMsg"></div>
				</div> 
			</li>
			
			<li>
				<label>Subscription Date*:</label>
				<input type="text" placeholder="Start date" id="from_date" name="from_date" readonly="" validationtype="html" caption="Start date" class="start-date" autocomplete="off" value="<?php echo $slotData['start_date']; ?>">
					<i onclick="pickStartDate(this);" style="cursor:pointer" id="importantDateStartIcon_1" class="abroad-cms-sprite calendar-icon"></i>
					
				<input type="text" placeholder="End date" id="to_date" name="to_date" readonly="" validationtype="html" caption="End date" class="end-date" autocomplete="off" value="<?php echo $slotData['end_date']; ?>">
					<i onclick="pickEndDate(this);" style="cursor:pointer" id="importantDateEndIcon_1" class="abroad-cms-sprite calendar-icon"></i>
				
				<div id="range_error" class="errorMsg"></div>
			</li>
			<li style="display:none;">
				<div class="left-div">
				 	<input size="40" id="creationDate" name="creationDate" class="target-txt-fld" type="text" autocomplete="off" value="<?php echo $slotData['creationDate']; ?>">
				</div> 
			</li>
		</ul>
		<div class="save">
			<input type="button" class="table-save-btn" onclick="submitBannerFeaturedCollege('article', 'submit');" tabindex="5" value="Save" title="Submit">
		</div>
		<input type="hidden" id="action" name="action" value="<?php echo $action; ?>" />
		<input type="hidden" name="userId" value="<?=$userId?>" />
		<input type="hidden" id="maxLenTitle" value="<?=$maxLenTitle?>" />
		<input type="hidden" id="id" name="idForArticle" value="<?=$slotData['id']?>" />
		<input type="hidden" id="bannerRemoved" name="bannerRemoved" value="0"/>
		<input type="hidden" id="pageType" name="pageType" value="<?php echo $pageType; ?>" />
		<input type="hidden" id="creationDate" name="creationDate"  value="<?php echo $slotData['creationDate']; ?>"/>

	</form>
</div>

<script type="text/javascript">
	var posVal = '<?php echo $slotData['position']; ?>';
	var existingTimePeriods = new Array();
	<?php foreach ($tableData as $dataRow) {
		if(!$dataRow['isDefault'] && $dataRow['banner_id'] != $id) { ?>
			existingTimePeriods.push(new Array('<?php echo $dataRow["start_date"]; ?>', '<?php echo $dataRow["end_date"]; ?>'));
		<?php } 
	} ?>
	
	if(document.all) {
		document.body.onload = updateFormElem;
	} else {
		updateFormElem();
	}
    document.getElementById('position').value = posVal;
</script>
	