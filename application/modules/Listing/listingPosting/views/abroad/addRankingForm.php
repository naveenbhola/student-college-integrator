<script>
      	var categoryDetails = eval(<?php echo json_encode($abroadCategories); ?>);
         var rankIds = new Array();      	
</script>
<?php  
$stateOfForm = $this->input->get("state");
$ldbCourseDropDownHtml = '<option value="" >Select a Desired Course</option>';
foreach($abroadMainLDBCourses as $key => $mainLDBCourse){
    if(!empty($formData['desiredCourse']) && $formData['desiredCourse'] == $mainLDBCourse['SpecializationId'] ){		
		$ldbCourseDropDownHtml .=  '<option selected="selected" value="'.$mainLDBCourse['SpecializationId'].'">'.$mainLDBCourse['CourseName'].'</option>';
    } else {
		$ldbCourseDropDownHtml .=  '<option value="'.$mainLDBCourse['SpecializationId'].'">'.$mainLDBCourse['CourseName'].'</option>';
    }
}
?>
<script>
    var isAddForm  = <?=($stateOfForm == 'add' ? 1 : 0)?>
</script>
  <div class="abroad-cms-rt-box">
         <form action ="/listingPosting/AbroadListingPosting/postFirstSectionOfRankingPage" name = "form_<?= $formName ?>" method="post" enctype="multipart/form-data">
          <?php
	  $breadCrumbText = "Add New Ranking ";
	  $displayData["pageTitle"]  	= "Add a New Ranking";
	  if($stateOfForm != 'add' && $formName == ENT_SA_FORM_EDIT_RANKING)
	  {
		$breadCrumbText = "Edit Ranking ";
		$displayData["pageTitle"]  	= "Edit Ranking";
		$displayData["pageTitle"] 	.= "<label style='color:red;'>".($formData['status']=="draft"?" (Draft state)":" (Published version)")."</label>";
	  }
				$displayData["breadCrumb"] 	= array(array("text" => "All Rankings", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_RANKING ),
									array("text" => $breadCrumbText, "url" => "") );
				
				
				if($formName == ENT_SA_FORM_EDIT_RANKING)
				{
				$displayData["lastUpdatedInfo"] = array("date"     => date("d/m/Y",strtotime($formData['last_modified'])),
									"username" => $formData['username']);
				}
				// load the title section
				$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
		      ?>
            <div class="cms-form-wrapper clear-width">
                
                <div class="clear-width">
                    <h3 class="section-title" ></i>Ranking Details</h3>
                    <div class="cms-form-wrap" style="margin-bottom:0;">
                        <ul>
                        	<li>
                                  <label>Ranking Type* : </label>
                                  <div class="cms-fields" style="margin-top:6px;">
                                   <input <?php if(!empty($formData['type'])){ echo "disabled='disabled'";} ?> type="radio" <?php if(empty($formData['type'])|| $formData['type'] == "university") echo "checked" ?> value="university" name="rankingType"/> University
                                   <input <?php if(!empty($formData['type'])){ echo "disabled='disabled'";} ?> type="radio" <?php  if(!empty($formData['type']) && $formData['type'] == "course") echo "checked" ?> value="course" name="rankingType"/> Course               
                                  </div>
                            </li>
                            <li>
                                <label>Ranking for Country Name : </label>
                                <div class="cms-fields">
                                <select id="country_<?=$formName?>" <?php if($formName == ENT_SA_FORM_EDIT_RANKING) { echo "disabled='disabled'";} ?> name="countryId" caption="country" validationType="select" class="universal-select cms-field">
								<!--<option value="">Select a Country</option>-->
								<?php foreach($abroadCountries as $country)
								{
									$countryId = $country->getId();
									$countryName = $country->getName(); ?>
									<option <?php if(!empty($formData['country_id']) && $formData['country_id'] == $countryId) echo "selected"; ?> value="<?php echo $countryId; ?>"><?php echo $countryName; ?></option>
								<?php } ?>
							    </select>
                                </div>
                             </li>
                             <li class="no-margin">
                             	<label>Desired Course : </label>
                                <div class="cms-fields">
                                
                                        <select id="desiredCourse_<?=$formName?>" <?php if($formName == ENT_SA_FORM_EDIT_RANKING) { echo "disabled='disabled'";} ?> name ="desiredCourse" class="universal-select cms-field" onchange="courseSingleSelect('desiredCourse','<?=$formName?>');">                        
                                              <?=$ldbCourseDropDownHtml?>
                                        </select>
                                        <p style="margin-top:10px;">OR</p>
                                </div> 
                             </li>
                             <li>
                             	<div class="add-more-sec2 clear-width">
                             	<ul>
                             		<li>
                                        <label>Course Type : </label>
<!--                                             <input type="radio"  checked = "checked" name="couresType"/> All Type -->
                                        <div  class="cms-fields" style="margin-top:6px;">
                                         <?php foreach($couresTypes as $couresType) {?>
                                        <label style="width: auto;"><input  <?php if($formName == ENT_SA_FORM_EDIT_RANKING) { echo "disabled='disabled'";} ?> onchange="courseSingleSelect('courseType','<?=$formName?>');" type="radio" value="<?=$couresType['CourseName']?>" <?php if(!empty($formData['couresType']) && (strtoupper(trim($formData['couresType'])) == strtoupper(trim( $couresType['CourseName'])))){ echo " checked ";}?>name="couresType"/><?=$couresType['CourseName']?></label>
                                        <?php }?>               
                                        </div>
                            		</li>
                                    <li>
                                        <label>Parent Category : </label>
                                        <div class="cms-fields">
                                       	<select  <?php if($formName == ENT_SA_FORM_EDIT_RANKING) { echo "disabled='disabled'";} ?> id="parentCat_<?=$formName?>" name="parentCategory" caption="parent category" validationType="select" onchange="courseSingleSelect('parentCategory','<?=$formName?>'); appendChildCategories('<?=$formName?>');" class="universal-select cms-field">                         
								        <option value="">Select a Category</option>
							        	<?php foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){ ?>
									    <option <?php if(!empty($formData['parentCategory']) && $formData['parentCategory'] == $parentCategoryId) echo "selected"; ?> value="<?php echo $parentCategoryId;?>"><?php echo $parentCategoryDetails['name'];?></option>
								        <?php } ?>
							            </select>
							            <div style="display: none" class="errorMsg" id="parentCat_<?=$formName?>_error"></div>
							   </div>
                                    </li>
                                    <li>

                                   <label>Child Category : </label>
									<div class="cms-fields">
									<select  <?php if($formName == ENT_SA_FORM_EDIT_RANKING) { echo "disabled='disabled'";} ?> onchange="courseSingleSelect('childCategory','<?=$formName?>');" id="childCat_<?=$formName?>" name="childCategory" caption="child category" class="universal-select cms-field">                         
									<option value="">Select a Category</option>
									</select>
									<div style="display: none" class="errorMsg" id="childCat_<?=$formName?>_error"></div>
									</div>
                                    </li>
                             	</ul>
                             	</div>
                             </li>
                             <li>
                                <label>Ranking Name* : </label>
                                <div class="cms-fields">
                                    <input  validationType="str" id="rankingName_<?=$formName?>" <?php if(!empty($formData['name'])) { echo "value = \"".htmlspecialchars($formData['name'])."\"";} ?> name="rankingName" caption="Ranking Name" onblur="showErrorMessage(this, '<?=$formName?>');" maxlength="200" minlength="3" required=true class="universal-txt-field cms-text-field" type="text"/>
                                    <div style="display: none" class="errorMsg" id="rankingName_<?=$formName?>_error"></div>
                                </div>
                            </li>
                           </ul>
                    </div>
                </div>
                <?php if(empty($rankingId) || $rankingId < 1) {?>
                    </div>
                	<div class="button-wrap">
                	<a href="javascript:void(0);" class="orange-btn" id="continue_addRankingFrom" onclick = "validateRankingDetails(this,'addRankingForm');">Continue &gt;</a>
                	</div>
                	</div>
                	<div class="clearFix"></div>
                <?php } else { ?>
    
            
                
                <h4 class="ranking-head">Assign Rankings</h4>
                    <div class="clear-width">
                     <table border="1" cellpadding="0" cellspacing="0" id ="ranking-table" class="ranking-table">
                        <tr>
                            <th width="12%">Rank</th>
                            <th width="27%" align="left"><?= $formData['type']?> ID</th>
                            <th width="61%" align="left"><?= $formData['type']?> Name</th>
                        </tr>
			<?php
			  if(empty($formData['study_abroad_rankings']) || count($formData['study_abroad_rankings']) ==0)
			  { for($i = 1; $i < 6; $i++)
			    {
				?>
				<tr><?php //edit-icon?>
				    <td align="center"><strong class="font-14"><?=$i?></strong></td>
				    <td><input type="text" class="universal-txt-field flLt" style="width:120px;"/>
					<input type="hidden" name="listingIds[]" value/>
				    <a href="javascript:void(0);" class="edit-search-box" onclick = "validateAndFetchNameListingId(this);"><i class="abroad-cms-sprite rank-search-icon"></i></a>
				    </td>
				    <td><span class="not-mapped-univ">Not mapped</span></td>
				</tr>
				<?php
			    }
			  }else{
                   $i=1;
                   $TotalRankingCount = Count($formData['study_abroad_rankings']);
                   $maxRank = $formData['study_abroad_rankings'][$TotalRankingCount-1]['rank'];
                   $rankingIds;
                  foreach($formData['study_abroad_rankings'] as $RankingRowData)
                  { ?>
                     <?php  if($RankingRowData['rank'] != $i){
                       for($i;$i<$RankingRowData['rank'];$i++) {?>
                	<tr>
				    <td align="center"><strong class="font-14"><?=$i?></strong></td>
				    <td><input type="text" class="universal-txt-field flLt" style="width:120px;"/>
					<input type="hidden" name="listingIds[]" value/>
				    <a href="javascript:void(0);" class="edit-search-box" onclick = "validateAndFetchNameListingId(this);"><i class="abroad-cms-sprite rank-search-icon"></i></a>
				    </td>
				    <td><span class="not-mapped-univ">Not mapped</span></td>
				    </tr>
                  	<?php }}?>
                  	<?php if(($RankingRowData['rank'] == $i) && empty($RankingRowData['name'])) {?>
                  	<tr>
                  		<td align="center"><strong class="font-14"><?=$RankingRowData['rank']?></strong></td>
                  		 <td><input type="text" class="universal-txt-field flLt" value = "<?php echo $RankingRowData['listing_id'];?>" style="width:120px;"/>
                  		<input type="hidden" name="listingIds[]"/>
                  		<a href="javascript:void(0);" class="edit-search-box" onclick = "validateAndFetchNameListingId(this);"><i class="abroad-cms-sprite rank-search-icon"></i></a>
                  		 </td>
                  		<td><span class="not-mapped-univ">Not Mapped</span></td>
                  		<?php if($i > 5 && $maxRank == $RankingRowData['rank']) {?>  <a  onclick="deleteTableRow('ranking-table');" href="javascript:void(0);" style="color:#333; margin-right:14px;" class="flRt"><i class="abroad-cms-sprite remove-small-icon"></i>Delete</a>
                      <?php }?>
                  	</tr>
                  	<?php }elseif(($RankingRowData['rank'] == $i) && !empty($RankingRowData['name'])) { $rankingIds[] =$RankingRowData['listing_id']; ?>
                  		<tr>
                  		<td align="center"><strong class="font-14"><?=$RankingRowData['rank']?></strong></td>
                  		 <td><input type="text" class="universal-txt-field flLt" disabled="disabled" value = "<?php echo $RankingRowData['listing_id'];?>" style="width:120px;"/>
                  		<input type="hidden" name="listingIds[]" value=<?=$RankingRowData['listing_id']?> />
                  		<a href="javascript:void(0);" class="edit-search-box" onclick = "validateAndFetchNameListingId(this);"><i class="abroad-cms-sprite edit-icon"></i></a>
                  		 </td>
                  		<td><span class=""><?=htmlspecialchars($RankingRowData['name'])?></span>
                  		<?php if($i > 5 && $maxRank == $RankingRowData['rank']) {?>  <a  onclick="deleteTableRow('ranking-table');" href="javascript:void(0);" style="color:#333; margin-right:14px;" class="flRt"><i class="abroad-cms-sprite remove-small-icon"></i>Delete</a>
                      <?php }?>
                  		</td>
                  		</tr>
                  	<?php } ?>
               <?php $i++;}
                   $j = $i == 1 ? $i : $maxRank+1;
                 if($maxRank<5){
                     for($j;$j<=5;$j++) {?>
                	<tr>
				    <td align="center"><strong class="font-14"><?=$j?></strong></td>
				    <td><input type="text" class="universal-txt-field flLt" style="width:120px;"/>
					<input type="hidden" name="listingIds[]" value/>
				    <a href="javascript:void(0);" class="edit-search-box" onclick = "validateAndFetchNameListingId(this);"><i class="abroad-cms-sprite rank-search-icon"></i></a>
				    </td>
				    <td><span class="not-mapped-univ">Not mapped</span></td>
				    </tr>
                  	<?php }

					} 
				 
					?>
			<?php 		
              	}
			  
			?>
                        <tr>
                            <td colspan="3" bgcolor="#ecebeb"><a onclick = "addTableRow('ranking-table');" href="javascript:void(0);" style="margin-left:20px;">[+] Add another row</a></td>
                        </tr>
                     </table>
                    </div>
                    <div style ="display:none" id="deleteButtonHTML">
                     <a  onclick="deleteTableRow('ranking-table');" href="javascript:void(0);" style="color:#333; margin-right:14px;" class="flRt"><i class="abroad-cms-sprite remove-small-icon"></i>Delete</a>
                     </div>
		    
		    <div class="cms-form-wrap">
			<ul>
				<li>
					<label>Ranking Title* : </label>
					<div class="cms-fields">
					    <input  validationType="str" id="rankingTitle_<?=$formName?>" <?php if(!empty($formData['title'])) { echo "value = \"".htmlspecialchars($formData['title'])."\"";} ?> name="rankingTitle" caption="Ranking Title" onblur="showErrorMessage(this, '<?=$formName?>');" maxlength="200" minlength="3" required=true class="universal-txt-field cms-text-field" type="text"/>
					    <div style="display: none" class="errorMsg" id="rankingTitle_<?=$formName?>_error"></div>
					</div>
				</li>
			</ul>
		    </div>
		    
                	<div class="clear-width">
                    <h3 class="section-title" >SEO Details</h3>
                    <div class="cms-form-wrap">
                        <ul>
                            <li>
                                <label>Ranking SEO Title : </label>
                                <div class="cms-fields">
                                    <input name="rankingSeoTitle" class="universal-txt-field cms-text-field" type="text" value="<?=$formData['seo_title']?>"/>
                                </div>
                            </li>
                            <li>
                                <label>Ranking SEO Keywords : </label>
                                <div class="cms-fields">
                                    <textarea name="rankingKeywords" class="cms-textarea"><?=$formData['seo_keywords']?></textarea>
                                </div>
                            </li>
                            <li>
                                <label>Ranking SEO Description : </label>
                                <div class="cms-fields">
                                    <textarea name="rankingDesc" class="cms-textarea"><?=$formData['seo_description']?></textarea>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                	<div class="clear-width">
                    <div class="cms-form-wrap" style="margin:0 0 10px 0; padding-top:8px; border-top:1px solid #ccc;">
                        <ul>
                            <li>
                                <label>User Comments* : </label>
                                <div class="cms-fields">
                                   <textarea id="rankingUserComments_<?=$formName?>" caption="User Comments" validationType ='str' name="rankingUserComments" maxlength="200" minlength="3" required=true class="cms-textarea" style="width:75%;"></textarea>
                                   <div style="display: none" class="errorMsg" id="rankingUserComments_<?=$formName?>_error"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
           </div>
        <div class="button-wrap">
           	<a href="javascript:void(0);" class="gray-btn" onclick="validateRankingDetailsEdit(this,'draft')">Save as Draft</a>
                <a href="javascript:void(0);" class="orange-btn" onclick="validateRankingDetailsEdit(this,'<?=ENT_SA_PRE_LIVE_STATUS?>')">Save & Publish</a>
                <a href="javascript:void(0);" class="cancel-btn" onclick="cancelAction('<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_RANKING?>')">Cancel</a>
            </div>
        <div class="clearFix"></div>
	<?php }?>
	<input type="hidden" id="rankingActionType" 	name="rankingActionType" 	value="<?=$formName?>" />
	<input type="hidden" id="rankingStatus" 	name="rankingStatus" 		value="draft" />
	<input type="hidden" id="rankingId" 		name="rankingId" 		value="<?=$formData['ranking_page_id']?>" />
	<input type="hidden" id="rankingCreatedBy" 	name="rankingCreatedBy" 	value="<?=$formData['last_modified_by']?>" />
	<input type="hidden" id="submit_date" 		name="submit_date" 		value="<?=$formData['created']?>" />
	
       </form> 
 <script >
   <?php if(!empty($rankingIds) && count($rankingIds) > 0) { ?>
	rankIds = <?php echo json_encode($rankingIds);?>;
  <?php } ?>
</script>      
    </div>