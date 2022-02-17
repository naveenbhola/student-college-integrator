<?php $this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_cms_header'); ?>
<div id="ranking-cms-content">
	<h3 class="flLt">Manage Meta Details</h3>
	<div class="ranking-grey-cont" id="ranking-grey-cont">
		<div class="floatL">
			Ranking Page Name: <h5><?php echo $ranking_page['ranking_page_text'];?></h5><br/>
			Current status: <h5><?php echo ucfirst($ranking_page['status']);?></h5><br/>
						
			
		</div>
		<div>
    		<label>Please select Location</label>
    			<select class="score-selectfield" id="locationFilter" onchange="locationFilter(<?php echo $ranking_page['id']; ?>);">
    				<option value = '0' >Select Location</option>
    				<?php 
    				$cityList = $locationFilterData['city'];
    				$stateList = $locationFilterData['state'];
    				foreach($cityList as $city){
    					echo "<option value ='".$city['id']."-city'>".$city['name']."</option>";	
    				}
    				foreach($stateList as $state){
    					if($state['id']==2) continue;
    					echo "<option value ='".$state['id']."-state'>".$state['name']."</option>";	
    				} ?>
    			</select>
    	</div>

		<div class="clearFix spacer10"></div>
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
		<div class="add-new-inst">
			<h5>Location selected(default case) </h5>
		  <ul>
		  	<li>
		  		<a name="institute_add_form"></a>
		  		<label>Page H1 :</label>
		  		<div class="add-field-box">
		  			<textarea rows="3" cols="10" class="universal-select" style="width:500px; height:45px; vertical-align:text-top" minlength="1" caption="Page Title" id="page_heading_field" name="page_title_field"><?php echo $ranking_page_meta_details['h1'];?></textarea>
		  		</div>
		  	</li>
			<li>
				<a name="institute_add_form"></a>
				<label>Page Title:</label>
				<div class="add-field-box">
					<textarea rows="3" cols="10" class="universal-select" style="width:500px; height:45px; vertical-align:text-top" minlength="1" caption="Page Title" id="page_title_field" name="page_title_field"><?php echo $ranking_page_meta_details['ranking_page_title'];?></textarea>
				</div>
			</li>
			<li>
				<a name="institute_add_form"></a>
				<label>Page Description:</label>
				<div class="add-field-box">
					<textarea rows="3" cols="10" class="universal-select" style="width:500px; height:60px; vertical-align:text-top" minlength="1" caption="Page Title" id="page_description_field" name="page_title_field"><?php echo $ranking_page_meta_details['ranking_page_description'];?></textarea>
				</div>
			</li>
			<li>
				<a name="institute_add_form"></a>
				<label>Page Title With Exam:</label>
				<div class="add-field-box">
					<textarea rows="3" cols="10" class="universal-select" style="width:500px; height:45px; vertical-align:text-top" minlength="1" caption="Page Title" id="page_title_field_exam" name="page_title_field"><?php echo $ranking_page_meta_details['ranking_page_title_exam'];?></textarea>
				</div>
			</li>
			<li>
				<a name="institute_add_form"></a>
				<label>Page Description with Exam:</label>
				<div class="add-field-box">
					<textarea rows="3" cols="10" class="universal-select" style="width:500px; height:60px; vertical-align:text-top" minlength="1" caption="Page Title" id="page_description_field_exam" name="page_title_field"><?php echo $ranking_page_meta_details['ranking_page_description_exam'];?></textarea>
				</div>
			</li>
			
			<li>
				<label>Disclaimer:</label>
				<div class="add-field-box">
				<textarea id="disclaimer" class="tinymce-textarea" style="width:660px"><?php echo htmlentities($ranking_page_meta_details['disclaimer']); ?></textarea>
				</div>
		  </ul>
		  <div class="spacer5 clearFix"></div>
	

		  <div class="spacer10 clearFix"></div>
		  <div class="floatL" style="text-align:center;">
			  <?php
			  if( ( !empty($title) || !empty($description) ) && $multiplePassesAllowed == false){
				?>
				<input type="button" class="gray-button" value="Change Meta Details" onclick="alert('Meta details updataion is only one time operation.');"/>
				<?php
			  } else {
				?>
				<input type="button" class="gray-button" value="Change Meta Details" onclick="updateMetaDetails('<?php echo $ranking_page['id']; ?>')"/>
				<?php
			  }
			  ?>
			  <?php
			  if($multiplePassesAllowed == true){
				?>
				<input type="hidden" id="multiple_passes_allowed" name="multiple_passes_allowed" value="true"/>
				<?php
			  } else {
				?>
				<input type="hidden" id="multiple_passes_allowed" name="multiple_passes_allowed" value="false"/>
				<?php
			  }
			  ?>
			  <input type="button" class="gray-button" value="Go back"  onclick="window.location.href='/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/index/'"/>
		  </div>
		  <div class="errorMsg" id="metadetails_error_msg" style="display:block;"></div>
		</div>
	</div>
</div>
<div class="spacer20 clearFix"></div>
<?php
	$this->load->view('common/footerNew');
?>
<script type="text/javascript">
	initiateTinYMCE('', false);
	</script>