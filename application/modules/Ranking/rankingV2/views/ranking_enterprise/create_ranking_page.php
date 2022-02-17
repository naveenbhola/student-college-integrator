<?php
	$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_cms_header');
?>
<!--Code Starts form here-->
<div id="ranking-cms-content">
	<h3 class="flLt">Create New Ranking Page</h3>
	<div class="flRt"><input type="button" class="gray-button" value="Ranking Main Page" onclick="window.location.href='/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/index/'"/></div>
	<div class="clearFix"></div>
	<div class="ranking-cate-section">
		<div style="float:left;width:100%;margin-bottom:25px;">
			<div class="ranking-cate-cols">
				<label>Select Stream:</label>
				<div>
					<select name="stream" id="stream" onchange="manageStreamChange();">
						<option value="">Select</option>
						<?php foreach($hierarchy['data'] as $streamData){ ?>
							<option value="<?php echo $streamData['id'];?>"> <?php echo $streamData['name'];?></option>
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
					<select name="popularCourse" id="popularCourse" onchange="managePopularCourseChange();">
						<option value="">Select</option>
						<?php foreach($popularCourses as $course){ ?>
							<option value="<?php echo $course['id'];?>"><?php echo $course['name'];?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Ranking Text (No Special Chars)*:</label>
				<div>
					<input tabindex="1" id="ranking_page_text" type="text" value="" class="rank-field" style="width:70%;" maxlength="99" />
				</div>
			</div>
		</div>

		<div style="display:none;float:left;width:100%;margin-bottom:25px;" id="streamHeirarchySection" class="clearfix">
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Select Substream:</label>
				<div>
					<select name="substream" id="substream" onchange="manageSubstreamChange();">
						<option value="">Select</option>
					</select>
				</div>
			</div>
			<div class="ranking-cate-cols" id="ranking-text-cols">
				<label>Select Base Course:</label>
				<div>
					<select name="baseCourse" id="baseCourse">
						<option value="">Select</option>
						<?php foreach($baseCourses as $course){ ?>
							<option value="<?php echo $course['id'];?>"><?php echo $course['name'];?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Select Credential:</label>
				<div>
					<select id="credentials" name="credentials">
						<option value="">Select</option>
						<?php foreach($courseAttributes['data']['Credential'] as $id =>$value){ ?>
							<option value="<?php echo $id;?>"><?php echo $value;?> </option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div style="display:none;float:left;width:100%;margin-bottom:25px;" id="additionalAttributesSection">
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Education Type:</label>
				<div>
					<select id="educationtype" name="educationtype">
						<option value="">Select</option>
						<?php foreach($courseAttributes['data']['Education Type'] as $id =>$value){ ?>
							<option value="<?php echo $id;?>"><?php echo $value;?> </option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="ranking-cate-cols" id="ranking-text-cols" style="display:block;">
				<label>Delivery Method:</label>
				<div>
					<select id="deliverymethod" name="deliverymethod">
						<option value="">Select</option>
						<?php foreach($courseAttributes['data']['Medium/Delivery Method'] as $id =>$value){ ?>
							<option value="<?php echo $id;?>"><?php echo $value;?> </option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="ranking-cate-cols" id="ranking-text-cols">
				<label>Select Specialization:</label>
				<div>
					<select name="specialization" id="specialization">
						<option value="">Select</option>
					</select>
				</div>
			</div>
		</div>
		
		<div class="ranking-cate-cols" style="margin-top:10px;">
			<label>Select Source*:</label>
			<div>
				<select multiple name="source" id="source">
					<?php foreach($sources as $source){
						$selected = "";
						//if($source['source_id'] $url_params['category_id']){
							//$selected = "selected='selected'";
						//} ?>
						<option <?php echo $selected;?> value="<?php echo $source['source_id'];?>"><?php echo $source['name']." ". $source['year'];?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="ranking-cate-cols" style="margin-top:10px;width:660px;">
			<label>Disclaimer:</label>
			<textarea id="disclaimer" name="disclaimer" class="tinymce-textarea" style="width:660px"></textarea>
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
		        	<label for="name">H1:</label>
		        	<input type="text" id="h1" name="h1">   
		        </li>
		        <li>
		   			<label for="name">BreadCrumb:</label>
		   			<input type="text" id="brd_crmb" name="breadcrumb">
		        </li>
		        <li>
		        	<label for="name">Title:</label>
		        	<input type="text" id="title" name="title">
		        </li>
		        <li>
		        	<label for="name">Description:</label>
		        	<input type="text" id="description" name="description">
		        </li>
		    </ul>
		</div>
	</div>
	
	<div class="ranking-error-cont" id="ranking-error-cont" style="display:none;">
		<div class="floatL" id="ranking-error-value-cont"></div>
	</div>
	<div class="spacer10 clearFix"></div>
	
	<div>
		<?php
		if(count($course_suggestions) > 0){
		?>
			<table cellpadding="0" cellspacing="0" width="100%" border="0" class="cms-ranking-table" id="rank_page_table">
				<tr>
					<th width="80">Rank</th>
					<th>Institute ID</th>
					<th>Name of Institute</th>
					<th>City</th>
					<th>Course</th>
					<th>Fees</th>
					<th>Cutoff</th>
					<th>&nbsp;</th>
				</tr>
				<?php
				if(!empty($course_suggestions)){
					$count = 0;
					foreach($course_suggestions as $page_data){
						$trClassName = "";
						if($count % 2 == 0){
							$trClassName = "alt-rows";
						}
						?>
						<tr class="<?php echo $trClassName;?>" id="tr_<?php echo $count;?>">
							<td>
								<input tabindex="<?php echo $count+1;?>" id="row_<?php echo $count;?>" type="text" value="" class="rank-field" />
							</td>
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
								<a tabindex="-1" href="javascript:void(0)" onclick="deleteDomElement('tr_<?php echo $count;?>');">Delete</a>
							</td>
							<input type="hidden" id="course_alttext_row_<?php echo $count;?>" value="<?php echo $page_data['course_alt_text'];?>" />
							<input type="hidden" id="institute_row_<?php echo $count;?>" value="<?php echo $page_data['institute_id'];?>" />
							<input type="hidden" id="course_row_<?php echo $count;?>" value="<?php echo $page_data['course_id'];?>" />
						</tr>
						<?php
						$count++;
					}
				}
				?>
			</table>
		<?php
		} else {
			if(!empty($url_params['category_id']) && !empty($url_params['subcategory_id'])){
				?>
				<div class="ranking-error-cont" id="no_suggestion_cont">
					<div class="floatL">No institute/course suggestions.</div>
				</div>
				<?php
			}
		}
		?>
	</div>

	<div class="ranking-error-cont" id="ranking-error-cont-bottom" style="display:none;">
		<div class="floatL" id="ranking-error-value-cont-bottom"></div>
	</div>
	<div class="spacer10 clearFix"></div>
	<input type="button" value="Create" class="gray-button" onclick="createNewRankingPage();"/>
	<div class="spacer10 clearFix"></div>
</div>
<!--Code Ends here-->
<?php
	$this->load->view('common/footerNew');
?>

<script type="text/javascript">
	initiateTinYMCE('', false);
	var subscription_details = eval(<?php echo json_encode($specialization_details); ?>);
	var totalEditRows = eval(<?php echo json_encode($count); ?>);
	var maximumCoursesInRankingPage = eval(<?php echo json_encode($maxCoursesInRankingPageAllowed); ?>);
	Ranking.setSpecializationDetails(subscription_details);
	Ranking.setEditRowsCount(totalEditRows);
	Ranking.setMaximumCoursesInRankingPage(maximumCoursesInRankingPage);
	var hierarchyData = <?php echo json_encode($hierarchy['data']); ?>
</script>