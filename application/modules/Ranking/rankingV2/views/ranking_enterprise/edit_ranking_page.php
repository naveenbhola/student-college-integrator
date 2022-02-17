<?php
	$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_cms_header');
?>
<!--Code Starts form here-->
<?php
	$ranking_page_data_current_source = array();
	$ranking_page_data_other_source = array();
	foreach($ranking_page_data as $page_data){
		$rank = $page_data['course_param_details'][$ranking_page_default_source_id];
		if(!empty($rank)) {
			$map[] = $rank;
			$ranking_page_data_current_source[] = $page_data;
		} else {
			$ranking_page_data_other_source[] = $page_data;
		}
	}
	//die;
	asort($map);
	$ranking_page_data_current_source_sorted = array();
	foreach($map as $key => $rank) {
		$ranking_page_data_current_source_sorted[] = $ranking_page_data_current_source[$key];
	}
	$ranking_page_data_source = array_merge($ranking_page_data_current_source_sorted, $ranking_page_data_other_source);
	/*_P($ranking_page_data_source);
	die;*/
?>
<div id="ranking-cms-content">
	<h3 class="flLt">Edit Ranking Page</h3>
	<div class="flRt"><input type="button" class="gray-button" value="Ranking Main Page" onclick="window.location.href='/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/index/'"/></div>
	<?php
	$message = "";
	if(!empty($message_params)){
		$op 	= $message_params['op'];
		$selectedPage = array();
		switch($op){
			case 'institute_add':
				$message = "<h5>Success:</h5><br/>";
				$message .= "- Institute/course has been added to <b>" . $ranking_page['ranking_page_text'] . "</b> ranking page successfully.";
				break;
			case 'institute_delete':
				$message = "<h5>Success:</h5><br/>";
				$message .= "- Institute/course has been successfully deleted from <b>" . $ranking_page['ranking_page_text'] . "</b> ranking page.";
				break;
			case 'ranking_page_updated':
				$message = "<h5>Success:</h5><br/>";
				$message .= "- <b>" . $ranking_page['ranking_page_text'] . "</b> ranking page successfully updated.";
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
	<div class="ranking-grey-cont" id="ranking-grey-cont">
		<div class="floatL">
			Ranking Page Name: <h5><?php echo $ranking_page['ranking_page_text'];?></h5><br/>
			Current status: <h5><?php echo ucfirst($ranking_page['status']);?></h5><br/>
		</div>
		<div class="spacer20 clearFix"></div>
	</div>
	<div class="clearFix"></div>


	
	<div class="ranking-cate-section">
		<div style="float:left;width:100%;margin-bottom:25px;">
			<div class="ranking-cate-cols">
				<label>Select Stream:</label>
				<div>
					<select name="stream" id="stream">
						<?php if($prefillData['type'] == 'stream'){ ?>
							<option value="<?php echo $prefillData['stream']['id'];?>"><?php echo $prefillData['stream']['name'];?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			<div class="ranking-cate-cols" id="ranking-subcat-cols">
				<label>-- OR --</label>	
			</div>
			
			<div class="ranking-cate-cols" id="ranking-specialization-cols" style="display:block;">
				<label>Popular Course:</label>
				<div>
					<select name="popularCourse" id="popularCourse">
					<?php if($prefillData['type'] == 'popularCourse'){ ?>
						<option value="<?php echo $prefillData['popularCourse']['id'];?>"><?php echo $prefillData['popularCourse']['name'];?></option>
					<?php } ?>
						
					</select>
				</div>
			</div>
		
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Ranking Text (No Special Chars)*:</label>
				<div>
					<input tabindex="1" id="ranking_page_text" type="text" value="<?php echo htmlentities($prefillData['rankingPageText']);?>" class="rank-field" style="width:70%;" maxlength="99" />
				</div>
			</div>
		</div>

		<div style="<?php if($prefillData['type']!='stream'){ ?>display:none;<?php } ?>float:left;width:100%;margin-bottom:25px;" id="streamHeirarchySection" class="clearfix">
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Select Substream:</label>
				<div>
					<select name="substream" id="substream">
						<?php if($prefillData['substream']['id']!=0){ ?>
							<option value="<?php echo $prefillData['substream']['id']; ?>"><?php echo $prefillData['substream']['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="ranking-cate-cols" id="ranking-text-cols">
				<label>Select Base Course:</label>
				<div>
					<select name="baseCourse" id="baseCourse">
						<?php if($prefillData['baseCourse']['id']!=0){ ?>
							<option value="<?php echo $prefillData['baseCourse']['id']; ?>"><?php echo $prefillData['baseCourse']['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Select Credential:</label>
				<div>
					<select id="credentials" name="credentials">
						<?php if($prefillData['credential']['id']!=0){ ?>
							<option value="<?php echo $prefillData['credential']['id']; ?>"><?php echo $prefillData['credential']['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div style="float:left;width:100%;margin-bottom:25px;" id="additionalAttributesSection">
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Education Type:</label>
				<div>
					<select id="educationtype" name="educationtype">
						<?php if($prefillData['educationType']['id']!=0){ ?>
							<option value="<?php echo $prefillData['educationType']['id']; ?>"><?php echo $prefillData['educationType']['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Delivery Method:</label>
				<div>
					<select id="deliverymethod" name="deliverymethod">
						<?php if($prefillData['deliveryMethod']['id']!=0){ ?>
							<option value="<?php echo $prefillData['deliveryMethod']['id']; ?>"><?php echo $prefillData['deliveryMethod']['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="ranking-cate-cols" id="ranking-text-cols">
				<label>Select Specialization:</label>
				<div>
					<select name="specialization" id="specialization">
						<?php if($prefillData['specialization']['id']!=0){ ?>
							<option value="<?php echo $prefillData['specialization']['id']; ?>"><?php echo $prefillData['specialization']['name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		
		<div class="ranking-cate-cols" style="margin-top:10px;">
			<label>Select Source:</label>
			<div>
				<select name="source" id="source" onchange="sourceChangeHanlder(this, '<?php echo $ranking_page['id'] ?>');">
					<?php foreach($ranking_page_source_data as $source) {
						$selected = "";
						if($source['source_id'] == $ranking_page_default_source_id) {
							$selected = 'selected';
						}?>
						<option <?php echo $selected;?> value="<?php echo $source['source_id'];?>"><?php echo $source['name']." ".$source['year']; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="ranking-cate-cols" style="margin-top:10px;width:660px;">
			<label>Disclaimer:</label>
			<textarea id="disclaimer" class="tinymce-textarea" style="width:660px"><?php echo htmlentities($ranking_page['disclaimer']); ?></textarea>
		</div>

		<div class="ranking-cate-cols" style="margin-top:10px;">
			<label>Select Default Publisher:</label>
			<div>
				<select name="defaultPublisher" id="defaultPublisher">
					<option value="0">Select Default Publisher</option>
					<?php foreach($publisherList as $publisher) {
						$selected = "";
						if($publisher['publisher_id'] == $ranking_page['default_publisher']) {
							$selected = 'selected';
						}?>
						<option <?php echo $selected;?> value="<?php echo $publisher['publisher_id'];?>"><?php echo $publisher['name'];?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="ranking-cate-cols" style="margin-top:10px;">
			<label>Tuple Type:</label>
			<div>
				<select name="tupleType" id="tupleType">
					<?php if($ranking_page['tuple_type'] == 'institute') {
						$selectedInstitute = 'selected';
					} elseif($ranking_page['tuple_type'] == 'course') {
						$selectedCourse = 'selected';
					} ?>
					<option <?php echo $selectedCourse;?> value="course">Course</option>
					<option <?php echo $selectedInstitute;?> value="institute">Institute</option>
				</select>
			</div>
		</div>
		
		<div class="clearFix"></div>
		<div class="rnk-plchdr">
			<label>PlaceHolder :</label>
		    <div>
		        <label class="sub-lbl">Rank Page name placeholder:</label>
		        <span><?php echo htmlentities('<Nickname>');?></span>
		    </div>
		   	<div>
		        <label class="sub-lbl">Stream placeholder:</label>
		        <span><?php echo htmlentities('<Stream>');?></span>
		   	</div>
		    <div>
		        <label class="sub-lbl">Substream placeholder:</label>
		        <span><?php echo htmlentities('<Substream>');?></span>
		    </div>
		    <div>
	           <label class="sub-lbl">Specialization placeholder:</label>
	           <span><?php echo htmlentities('<Specialization>');?></span>
		    </div>
			<div>
			<label class="sub-lbl">Base Course placeholder:</label>
		        <span><?php echo htmlentities('<Bcourse>');?></span>
		    </div>
		    <div>
		        <label class="sub-lbl">Location placeholder:</label>
		        <span><?php echo htmlentities('<Location>');?></span>
		    </div>
		    <div>
		        <label class="sub-lbl">Exam placeholder:</label>
		        <span><?php echo htmlentities('<Examname>');?></span>
		    </div>
	   </div>
			<div class="rankingMta-Disc">
		    <ul>
		        <li>
		        	<label>H1:</label>
		        	<input type="text" id="h1" name="h1" value='<?php echo htmlentities($metaDetails['h1'])?>'>   
		        </li>
		        <li>
		   			<label>BreadCrumb:</label>
		   			<input type="text" id="brd_crmb" name="breadcrumb" value='<?php echo htmlentities($metaDetails['breadcrumb'])?>'>
		        </li>
		        <li>
		        	<label>Title:</label>
		        	<input type="text" id="title" name="title" value='<?php echo htmlentities($metaDetails['ranking_page_title'])?>'>
		        </li>
		        <li>
		        	<label>Description:</label>
		        	<input type="text" id="description" name="description" value='<?php echo htmlentities($metaDetails['ranking_page_description']) ?>'>
		        </li>
		    </ul>
		</div>
	</div>
	
	<div class="ranking-error-cont" id="ranking-error-cont-bottom" style="display:none;">
		<div class="floatL" id="ranking-error-value-cont-bottom"></div>
	</div>
	
	<div class="spacer10 clearFix"></div>
	<input type="button" value="Save" class="gray-button" disable="disable" onclick="saveRankingPage();"/>
	<div class="spacer10 clearFix"></div>
	
	<div class="spacer10 clearFix"></div>
	
	<div>
		<?php
		if(!empty($ranking_page_data)){
		?>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="cms-ranking-table" id="rank_page_table">
			<tr>
				<th width="6%">Rank</th>
				<th width="10%">Institute ID</th>
				<th width="26%">Name of Institute</th>
				<th width="8%">City</th>
				<th width="13%">Course</th>
				<th width="10%">Fees</th>
				<th width="10%">Cutoff</th>
				<th width="17%">&nbsp;</th>
			</tr>
			<?php
				$count = 0;
				foreach($ranking_page_data_source as $page_data){
					$rank = $page_data['course_param_details'][$ranking_page_default_source_id];
					$trClassName = "";
					if($count % 2 == 0){
						$trClassName = "alt-rows";
					}
					$style = "";
					if(empty($rank)) {
						$style = "style = 'background:#D8D8D8'";
					}
					?>
					<tr class="<?php echo $trClassName;?>" <?php echo $style;?> id="tr_<?php echo $count;?>">
						<td tabindex="-1" id="row_rank_<?php echo $count;?>"><?php echo $rank;?></td>
						<td tabindex="-1"><?php echo $page_data['institute_id'];?></td>
						<td><a tabindex="-1" href="<?php echo $page_data['course_details']['institute_url'];?>"><?php echo $page_data['course_details']['institute_name'];?></a></td>
						<td tabindex="-1"><?php echo $page_data['course_details']['city'];?></td>
						<td><a tabindex="-1" href="<?php echo $page_data['course_details']['url'];?>"><?php echo $page_data['course_alt_text'];?></a></td>
						<td tabindex="-1">
							<?php
								if(!empty($page_data['course_details']['fees_value']) && !empty($page_data['course_details']['fees_currency'])){
									echo $page_data['course_details']['fees_currency'] . " " . $page_data['course_details']['fees_value'];
								}
							?>
						</td>
						<td tabindex="-1">
							<?php
								if(!empty($page_data['course_details']['exams'])){
									foreach($page_data['course_details']['exams'] as $exam){
										echo $exam['name'] . "&nbsp;:&nbsp;" . $exam['marks'] . "<br/>";
									}
								}
							?>
						</td>
						<td>
							<a tabindex="-1" href="#institute_add_form" onclick="editCourseRankingDetails('<?php echo $page_data['course_id'];?>');">Edit</a><br/>
							<a tabindex="-1" href="javascript:void(0)" onclick="removeCourseFromSource('<?php echo $ranking_page['id'];?>', '<?php echo $page_data['course_id'];?>', '<?php echo $ranking_page_default_source_id;?>');">Remove from source</a><br>
							<a tabindex="-1" href="javascript:void(0)" onclick="deleteCourseFromRankingPage('<?php echo $ranking_page['id'];?>', '<?php echo $page_data['course_id'];?>');">Delete from all sources</a>
						</td>
						<input type="hidden" id="row_<?php echo $count;?>" value="<?php echo $rank;?>" />
						<input type="hidden" id="course_alttext_row_<?php echo $count;?>" value="<?php echo $page_data['course_alt_text'];?>" />
						<input type="hidden" id="institute_row_<?php echo $count;?>" value="<?php echo $page_data['institute_id'];?>" />
						<input type="hidden" id="course_row_<?php echo $count;?>" value="<?php echo $page_data['course_id'];?>" />
					</tr>
					<?php
					$count++;
				}
				?>
		</table>
		<?php
		} else {
			echo "<h5>No data available</h5>";
		}
		?>
	</div>
	
	<div class="add-new-inst">
		<h5>Add new institute</h5>
		<ul>
			<li class="institute_editform" >

				<a name="institute_add_form"></a>
				<label>Institute ID:</label>
				<div class="add-field-box">
					<input class="add-txt-field" type="text" value="" id="insti_id_field" name="insti_id_field"/>&nbsp; <input type="button" value="Continue" class="gray-button" onclick="fetchInstituteCourses();"/>
					<br/><span id="loader-cont" style="display:none;"></span>
				</div>
				<form id ="fileUploadForm" method="post" enctype="multipart/form-data">
        			<input type="file" name="files[]" multiple>
        			<input id ="uploadFilebtn"type="submit" value="Upload File" name="submit" onclick="uploadFile();">
   				 </form>
			</li>
			<input type="hidden" id="edit_course_id" value="" />
			<li id="course_dd_li" style="display:none;">
				<label>Course:</label>
				<div class="add-field-box">
					<select class="select-style" id="course_dd_select" onchange="populateRankAltText();">
					</select>
					<div class="ranking-grey-cont" id="ranking-course-details-cont" style="width:80%;">
						<div class="floatL" id="ranking-course-details-value">
						</div>
					</div>
					<div class="clearFix"></div>
				</div>
			</li>
			
			<li id="course_alt_text_li" style="display:none;">
				<label>Short Name:</label>
				<div class="add-field-box">
					<input class="add-txt-field" id="course_alt_text" type="text" />
				</div>
			</li>
			
			<h5 id="param_heading" style="display:none;">Add Ranking Parameters</h5>
			<div id="course_rank_li" style="display:none;">
				<li>
					<label></label>
					<label style="width: 72px; text-align: center; margin-right: 10px;">Rank</label>
					<input class="add-txt-field flLt" name="course_param_rank" id="course_param_rank" style="width:70px; margin-right: 10px;" type="text" />
				</li>

			</div>
			
			<li id="add-insti-error-li">
				<div class="ranking-error-cont" id="add-insti-error-cont"  style="display:none;">
					<div class="floatL" id="add-insti-error-value-cont"></div>
				</div>
				<div class="clearFix"></div>
			</li>
			
			<li id="submit_btn_li" style="display:none;">
				<label>&nbsp;</label>
				<div class="add-field-box">
					<input type="button" value="Submit" class="orange-button" onclick="addInstitute();"/>
				</div>
			</li>
		</ul>
	</div>
	
	
</div>
<!--Code Ends here-->
<?php
	$this->load->view('common/footerNew');
?>

<script type="text/javascript">
	initiateTinYMCE('', false);
	var rankingPageId = '<?php echo $ranking_page['id']; ?>';
	var ranking_page_source_param_data = eval(<?php echo json_encode($ranking_page_source_param_data); ?>);
	var ranking_page_default_source_id = eval(<?php echo json_encode($ranking_page_default_source_id); ?>);
	var category_details = eval(<?php echo json_encode($category_details); ?>);
	var subscription_details = eval(<?php echo json_encode($specialization_details); ?>);
	var ranking_page_details = eval(<?php echo json_encode($ranking_page); ?>);
	var ranking_page_data = eval(<?php echo json_encode($ranking_page_data); ?>);
	var totalEditRows = eval(<?php echo json_encode($count); ?>);
	var maximumCoursesInRankingPage = eval(<?php echo json_encode($maxCoursesInRankingPageAllowed); ?>);
	Ranking.setCategoryDetails(category_details);
	Ranking.setSpecializationDetails(subscription_details);
	Ranking.setRankingPageDetails(ranking_page_details);
	Ranking.setRankingPageData(ranking_page_data);
	Ranking.setEditRowsCount(totalEditRows);
	Ranking.setMaximumCoursesInRankingPage(maximumCoursesInRankingPage);
</script>